<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class DatabaseController extends Controller
{
    public function index()
    {
        return Inertia::render('dashboard/GestionBDD');
    }

    public function exportSqlite()
    {
        try {
            Log::info('Tentative d\'export de la base de données SQLite');
            
            if (!Auth::check()) {
                Log::error('Utilisateur non authentifié');
                return response()->json(['error' => 'Non authentifié'], 401);
            }

            $user = Auth::user();
            if ($user->typeMembre !== 'Administratif') {
                Log::error('Utilisateur non autorisé: ' . $user->id);
                return response()->json(['error' => 'Non autorisé'], 403);
            }
            
            Log::info('Utilisateur authentifié: ' . $user->id);

            // Chemin de la base de données source
            $sourcePath = config('database.connections.sqlite.database');
            
            if (!file_exists($sourcePath)) {
                Log::error('Fichier de base de données source non trouvé');
                return response()->json(['error' => 'Base de données non trouvée'], 404);
            }

            // Créer un dossier temporaire s'il n'existe pas
            $tempDir = storage_path('app/temp');
            if (!File::exists($tempDir)) {
                File::makeDirectory($tempDir, 0755, true);
            }

            // Créer une copie temporaire
            $tempFile = $tempDir . '/database_' . time() . '.sqlite';
            File::copy($sourcePath, $tempFile);

            // Vérifier que la copie a réussi
            if (!file_exists($tempFile)) {
                Log::error('Échec de la création de la copie temporaire');
                return response()->json(['error' => 'Erreur lors de la copie'], 500);
            }

            return response()->download($tempFile, 'database_backup.sqlite', [
                'Content-Type' => 'application/x-sqlite3',
            ])->deleteFileAfterSend(true);

        } catch (Exception $e) {
            Log::error('Erreur lors de l\'export de la base de données SQLite: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de l\'export: ' . $e->getMessage()], 500);
        }
    }

    public function export()
    {
        try {
            Log::info('Tentative d\'export de la base de données');
            
            if (!Auth::check()) {
                Log::error('Utilisateur non authentifié');
                return response()->json(['error' => 'Non authentifié'], 401);
            }

            $user = Auth::user();
            if ($user->typeMembre !== 'Administratif') {
                Log::error('Utilisateur non autorisé: ' . $user->id);
                return response()->json(['error' => 'Non autorisé'], 403);
            }
            
            Log::info('Utilisateur authentifié: ' . $user->id);
            
            // Récupérer toutes les tables de la base de données
            $tables = DB::select('SELECT name FROM sqlite_master WHERE type="table" AND name NOT LIKE "sqlite_%" AND name NOT LIKE "migrations"');
            
            $output = "Table,Données\n";

            foreach ($tables as $table) {
                $tableName = $table->name;
                try {
                    $data = DB::table($tableName)->get();
                    if ($data->isNotEmpty()) {
                        $headers = array_keys((array) $data->first());
                        $output .= $tableName . "," . implode(',', $headers) . "\n";
                        
                        foreach ($data as $row) {
                            $rowData = (array) $row;
                            // Échapper les virgules dans les valeurs
                            $rowData = array_map(function($value) {
                                if (is_string($value) && str_contains($value, ',')) {
                                    return '"' . str_replace('"', '""', $value) . '"';
                                }
                                return $value;
                            }, $rowData);
                            $output .= $tableName . "," . implode(',', $rowData) . "\n";
                        }
                        $output .= "\n"; // Ligne vide entre les tables
                    }
                } catch (Exception $e) {
                    Log::error("Erreur lors de l'export de la table {$tableName}: " . $e->getMessage());
                    continue;
                }
            }

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="database_export.csv"',
            ];

            Log::info('Export de la base de données terminé avec succès');
            return new Response($output, 200, $headers);
        } catch (Exception $e) {
            Log::error('Erreur lors de l\'export de la base de données: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de l\'export'], 500);
        }
    }
} 