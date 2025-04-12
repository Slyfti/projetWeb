<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ActionsUtilisateursSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['Consultation', 'Modification', 'Ajout', 'Suppression'];
        $entites = ['Objet', 'Service', 'Utilisateur', 'Événement'];
        
        // Générer des actions pour les 7 derniers jours
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            // Générer entre 5 et 15 actions par jour
            $nombreActions = rand(5, 15);
            
            for ($j = 0; $j < $nombreActions; $j++) {
                $type = $types[array_rand($types)];
                $entite = $entites[array_rand($entites)];
                
                // Points variables selon le type d'action
                $points = match($type) {
                    'Consultation' => 0.5,
                    'Modification' => 1.0,
                    'Ajout' => 2.0,
                    'Suppression' => 1.5,
                    default => 0.5
                };
                
                // Ajouter quelques heures aléatoires à la date
                $dateAction = $date->copy()->addHours(rand(0, 23))->addMinutes(rand(0, 59));
                
                DB::table('actionsUtilisateurs')->insert([
                    'idUtilisateur' => rand(1, 4), // Nous avons 4 utilisateurs
                    'typeAction' => $type,
                    'entiteCible' => $entite,
                    'idCible' => rand(1, 5), // Réduire aussi le nombre d'IDs cibles pour éviter les erreurs
                    'dateAction' => $dateAction,
                    'pointsGagne' => $points
                ]);
            }
        }
    }
} 