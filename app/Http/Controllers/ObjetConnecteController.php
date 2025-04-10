<?php

namespace App\Http\Controllers;

use App\Models\ObjetConnecte;
use App\Models\CategorieObjet;
use App\Models\ZoneStade;
use App\Models\ActionUtilisateur;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class ObjetConnecteController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $query = ObjetConnecte::with(['categorie', 'zone']);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('descriptionObjetsConnectes', 'like', "%{$search}%")
                  ->orWhere('connectivite', 'like', "%{$search}%")
                  ->orWhereHas('categorie', function($q) use ($search) {
                      $q->where('nomCategorie', 'like', "%{$search}%");
                  })
                  ->orWhereHas('zone', function($q) use ($search) {
                      $q->where('nomZone', 'like', "%{$search}%");
                  });
            });
        }
        
        $objets = $query->get();
        $categories = CategorieObjet::all();
        $zones = ZoneStade::all();

        return Inertia::render('dashboard/GestionObjet', [
            'objets' => $objets,
            'categories' => $categories,
            'zones' => $zones,
            'search' => $search
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'idCategorie' => 'required|exists:categoriesObjets,idCategoriesObjets',
            'descriptionObjetsConnectes' => 'required|string',
            'etat' => 'required|in:Actif,Inactif,Maintenance',
            'mode' => 'required|in:Automatique,Manuel',
            'connectivite' => 'required|string|max:255',
            'niveauBatterie' => 'required|numeric|min:0|max:100',
            'puissance' => 'required|numeric|min:0',
            'consommationActuelle' => 'required|numeric|min:0',
            'dureeVieEstimee' => 'required|numeric|min:0',
            'dateInstallation' => 'required|date',
            'derniereMaintenance' => 'required|date',
            'idZone' => 'required|exists:zonesStade,idZonesStade',
        ]);

        $validated['derniereInteraction'] = now();

        $objet = ObjetConnecte::create($validated);

        // Ajouter des points à l'utilisateur et enregistrer l'action
        $user = Auth::user();
        $pointsGagne = 10; // Points gagnés pour l'ajout d'un objet

        // Mettre à jour les points de l'utilisateur
        $user->points += $pointsGagne;
        $user->save();

        // Enregistrer l'action
        ActionUtilisateur::create([
            'idUtilisateur' => $user->id,
            'typeAction' => 'Ajout',
            'entiteCible' => 'Objet',
            'idCible' => $objet->idObjetsConnectes,
            'pointsGagne' => $pointsGagne
        ]);

        return redirect()->back()->with('success', 'Objet connecté créé avec succès. Vous avez gagné ' . $pointsGagne . ' points !');
    }

    public function update(Request $request, ObjetConnecte $objet)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'idCategorie' => 'required|exists:categoriesObjets,idCategoriesObjets',
            'descriptionObjetsConnectes' => 'required|string',
            'etat' => 'required|in:Actif,Inactif,Maintenance',
            'mode' => 'required|in:Automatique,Manuel',
            'connectivite' => 'required|string|max:255',
            'niveauBatterie' => 'required|numeric|min:0|max:100',
            'puissance' => 'required|numeric|min:0',
            'consommationActuelle' => 'required|numeric|min:0',
            'dureeVieEstimee' => 'required|numeric|min:0',
            'dateInstallation' => 'required|date',
            'derniereMaintenance' => 'required|date',
            'idZone' => 'required|exists:zonesStade,idZonesStade',
        ]);

        $objet->update($validated);

        // Ajouter des points à l'utilisateur et enregistrer l'action
        $user = Auth::user();
        $pointsGagne = 5; // Points gagnés pour la mise à jour d'un objet

        // Mettre à jour les points de l'utilisateur
        $user->points += $pointsGagne;
        $user->save();

        // Enregistrer l'action
        ActionUtilisateur::create([
            'idUtilisateur' => $user->id,
            'typeAction' => 'Modification',
            'entiteCible' => 'Objet',
            'idCible' => $objet->idObjetsConnectes,
            'pointsGagne' => $pointsGagne
        ]);

        return redirect()->back()->with('success', 'Objet connecté mis à jour avec succès. Vous avez gagné ' . $pointsGagne . ' points !');
    }

    public function destroy(ObjetConnecte $objet)
    {
        // Ajouter des points à l'utilisateur et enregistrer l'action avant la suppression
        $user = Auth::user();
        $pointsGagne = 3; // Points gagnés pour la suppression d'un objet

        // Mettre à jour les points de l'utilisateur
        $user->points += $pointsGagne;
        $user->save();

        // Enregistrer l'action
        ActionUtilisateur::create([
            'idUtilisateur' => $user->id,
            'typeAction' => 'Suppression',
            'entiteCible' => 'Objet',
            'idCible' => $objet->idObjetsConnectes,
            'pointsGagne' => $pointsGagne
        ]);

        $objet->delete();

        return redirect()->back()->with('success', 'Objet connecté supprimé avec succès. Vous avez gagné ' . $pointsGagne . ' points !');
    }
} 