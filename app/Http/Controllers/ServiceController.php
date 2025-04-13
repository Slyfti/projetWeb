<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\CategorieService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    private function handleImageUpload($file, $serviceName)
    {
        if (!$file) return null;

        // Créer le nom du fichier
        $fileName = Str::slug($serviceName) . '_' . time() . '.' . $file->getClientOriginalExtension();
        
        // S'assurer que le dossier existe
        $directory = public_path('images/services');
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        // Stocker le fichier dans public/images/services
        $file->move($directory, $fileName);

        return $fileName; // Retourne seulement le nom du fichier
    }


    public function index()
    {
        $services = Service::with('categorie')->get();
        
        // Transformer les images pour avoir des chemins complets
        $services = $services->map(function($service) {
            if ($service->image && !Str::startsWith($service->image, ['http', '/'])) {
                $service->image = '/images/services/' . $service->image;
            }
            return $service;
        });
        
        $categories = CategorieService::all();
        
        return Inertia::render('dashboard/GestionServices', [
            'services' => $services,
            'categories' => $categories
        ]);
    }

    public function getServices()
    {
        $services = Service::with('categorie')->get(); 
        
        // Transformer les images pour avoir des chemins complets
        $services = $services->map(function($service) {
            if ($service->image && !Str::startsWith($service->image, ['http', '/'])) {
                $service->image = '/images/services/' . $service->image;
            }
            return $service;
        });
        
        return Inertia::render('services/Services', [
            'services' => $services,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'idCategorie' => 'required|exists:categoriesServices,idCategoriesServices',
            'descriptionServices' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'estActif' => 'boolean'
        ], [
            'nom.required' => 'Le nom du service est obligatoire',
            'nom.string' => 'Le nom doit être une chaîne de caractères',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères',
            'idCategorie.required' => 'La catégorie est obligatoire',
            'idCategorie.exists' => 'La catégorie sélectionnée n\'existe pas',
            'descriptionServices.required' => 'La description est obligatoire',
            'descriptionServices.string' => 'La description doit être une chaîne de caractères',
            'image.image' => 'Le fichier doit être une image',
            'image.mimes' => 'L\'image doit être au format jpeg, png, jpg ou gif',
            'image.max' => 'L\'image ne peut pas dépasser 2 Mo',
            'estActif.boolean' => 'Le statut doit être un booléen'
        ]);

        // Gérer l'upload de l'image si un fichier est fourni
        if ($request->hasFile('image')) {
            $validated['image'] = $this->handleImageUpload($request->file('image'), $validated['nom']);
        } else {
            $validated['image'] = null; // L'image par défaut sera gérée au niveau de l'affichage
        }

        $service = Service::create($validated);

        return redirect()->route('dashboard.services')->with('success', 'Service créé avec succès');
    }

    public function update(Request $request, Service $service)
    {
        // Règles de validation de base
        $rules = [];
        $messages = [];

        // Si on modifie le nom, la catégorie ou la description
        if ($request->has('nom') || $request->has('idCategorie') || $request->has('descriptionServices')) {
            $rules = [
                'nom' => 'required|string|max:255',
                'idCategorie' => 'required|exists:categoriesServices,idCategoriesServices',
                'descriptionServices' => 'required|string',
            ];
            $messages = [
                'nom.required' => 'Le nom du service est obligatoire',
                'nom.string' => 'Le nom doit être une chaîne de caractères',
                'nom.max' => 'Le nom ne peut pas dépasser 255 caractères',
                'idCategorie.required' => 'La catégorie est obligatoire',
                'idCategorie.exists' => 'La catégorie sélectionnée n\'existe pas',
                'descriptionServices.required' => 'La description est obligatoire',
                'descriptionServices.string' => 'La description doit être une chaîne de caractères',
            ];
        }

        // Si on modifie le statut
        if ($request->has('estActif')) {
            $rules['estActif'] = 'boolean';
            $messages['estActif.boolean'] = 'Le statut doit être un booléen';
        }

        // Si on upload une image, ajouter les règles de validation pour l'image
        if ($request->hasFile('image')) {
            $rules['image'] = 'image|mimes:jpeg,png,jpg,gif|max:2048';
            $messages['image.image'] = 'Le fichier doit être une image';
            $messages['image.mimes'] = 'L\'image doit être au format jpeg, png, jpg ou gif';
            $messages['image.max'] = 'L\'image ne peut pas dépasser 2 Mo';
        }

        $validated = $request->validate($rules, $messages);

        // Si une nouvelle image est uploadée
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe et n'est pas l'image par défaut
            if ($service->image && !Str::contains($service->image, 'stade_neon.png')) {
                $oldImagePath = public_path('images/services/' . basename($service->image));
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $validated['image'] = $this->handleImageUpload($request->file('image'), $validated['nom'] ?? $service->nom);
        } 
        // Si l'image est explicitement définie comme vide (champ image présent mais vide)
        elseif ($request->has('image') && ($request->image === null || $request->image === '')) {
            // Supprimer l'ancienne image si elle existe et n'est pas l'image par défaut
            if ($service->image && !Str::contains($service->image, 'stade_neon.png')) {
                $oldImagePath = public_path('images/services/' . basename($service->image));
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $validated['image'] = null;
        }
        // Si l'image n'est pas fournie dans la requête, garder l'image existante
        elseif (!$request->has('image')) {
            // Ne pas modifier l'image existante
            // Ne pas ajouter la clé 'image' à $validated pour éviter de l'écraser
        }

        $service->update($validated);

        return redirect()->route('dashboard.services')->with('success', 'Service modifié avec succès');
    }

    public function destroy(Service $service)
    {
        // Supprimer l'image si elle existe et n'est pas l'image par défaut
        if ($service->image && !Str::contains($service->image, 'stade_neon.png')) {
            $oldImagePath = public_path('images/services/' . basename($service->image));
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        $service->delete();

        return redirect()->route('dashboard.services')->with('success', 'Service supprimé avec succès');
    }
}