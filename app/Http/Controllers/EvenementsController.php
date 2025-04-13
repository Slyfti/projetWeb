<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\ActionUtilisateur;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EvenementsController extends Controller
{
    public function index(Request $request)
    {
        $query = Evenement::query();

        // Filtre par type d'événement
        if ($request->filled('typeEvents')) {
            $query->where('typeEvents', $request->typeEvents);
        }

        // Filtre par date
        if ($request->filled('date')) {
            $query->whereDate('dateEvenements', $request->date);
        }

        // Filtre par période
        if ($request->filled('periode')) {
            $now = Carbon::now();
            switch ($request->periode) {
                case 'aujourd_hui':
                    $query->whereDate('dateEvenements', $now->toDateString());
                    break;
                case 'cette_semaine':
                    $query->whereBetween('dateEvenements', [
                        $now->startOfWeek()->toDateTimeString(),
                        $now->endOfWeek()->toDateTimeString()
                    ]);
                    break;
                case 'ce_mois':
                    $query->whereBetween('dateEvenements', [
                        $now->startOfMonth()->toDateTimeString(),
                        $now->endOfMonth()->toDateTimeString()
                    ]);
                    break;
                case 'futur':
                    $query->where('dateEvenements', '>=', $now->toDateTimeString());
                    break;
            }
        }

        // Filtre par plage de prix
        if ($request->filled('prix') && is_array($request->prix) && count($request->prix) === 2) {
            $query->whereBetween('prix', $request->prix);
        }

        // Filtre par disponibilité
        if ($request->filled('disponibilite')) {
            if ($request->disponibilite === 'disponible') {
                $query->where('Disponiblilite', '>', 0);
            } elseif ($request->disponibilite === 'complet') {
                $query->where('Disponiblilite', '=', 0);
            }
        }

        // Filtre par équipe
        if ($request->filled('equipe')) {
            $query->where(function($q) use ($request) {
                $q->where('equipeDomicile', 'like', '%' . $request->equipe . '%')
                  ->orWhere('equipeExterieur', 'like', '%' . $request->equipe . '%');
            });
        }

        // Tri
        $sortBy = $request->input('sortBy', 'dateEvenements');
        $sortOrder = $request->input('sortOrder', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Récupération des types d'événements uniques pour le filtre
        $typesEvenements = Evenement::distinct()->pluck('typeEvents')->toArray();

        return Inertia::render('information/evenements/Index', [
            'evenements' => $query->paginate(9),
            'filters' => $request->all(),
            'typesEvenements' => $typesEvenements
        ]);
    }

    public function adminIndex()
    {
        $evenements = Evenement::all();
        return Inertia::render('dashboard/GestionEvenement', [
            'evenements' => $evenements
        ]);
    }

    public function show(Evenement $evenement)
    {
        return Inertia::render('information/evenements/EventDetails', [
            'evenement' => [
                'id' => $evenement->idEvenements,
                'nom' => $evenement->nom,
                'dateEvenements' => $evenement->dateEvenements,
                'lieu' => $evenement->lieu,
                'typeEvents' => $evenement->typeEvents,
                'meteo' => $evenement->meteo,
                'ligue' => $evenement->ligue,
                'descriptionEvenements' => $evenement->descriptionEvenements,
                'consignes_securite' => $evenement->consignes_securite,
                'activites_autour' => $evenement->activites_autour,
                'equipeDomicile' => $evenement->equipeDomicile,
                'equipeExterieur' => $evenement->equipeExterieur,
                'logo_equipe_domicile' => $evenement->logo_equipe_domicile,
                'logo_equipe_exterieur' => $evenement->logo_equipe_exterieur,
                'resultat' => $evenement->resultat,
                'prix' => $evenement->prix,
                'Disponiblilite' => $evenement->Disponiblilite
            ]
        ]);
    }

    private function handleLogoUpload($file, $equipeName, $type)
    {
        if (!$file) return null;

        // Créer le nom du fichier
        $fileName = Str::slug($equipeName) . '_logo' . $file->getClientOriginalExtension();
        
        // Stocker le fichier dans public/images/logos
        $file->move(public_path('images/logos'), $fileName);

        return $fileName;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'dateEvenements' => 'required|date',
            'descriptionEvenements' => 'required|string',
            'typeEvents' => 'required|string|max:100',
            'equipeDomicile' => 'required|string|max:100',
            'equipeExterieur' => 'required|string|max:100',
            'prix' => 'required|numeric|min:0',
            'Disponiblilite' => 'required|integer|min:0',
            'lieu' => 'required|string|max:100',
            'meteo' => 'required|string|max:100',
            'ligue' => 'required|string|max:100',
            'consignes_securite' => 'nullable|string',
            'activites_autour' => 'nullable|string',
            'logo_equipe_domicile' => 'nullable',
            'logo_equipe_exterieur' => 'nullable',
            'resultat' => 'nullable|string|max:50'
        ]);

        // Gérer l'upload des logos si des fichiers sont fournis
        if ($request->hasFile('logo_equipe_domicile')) {
            $validated['logo_equipe_domicile'] = $this->handleLogoUpload(
                $request->file('logo_equipe_domicile'),
                $validated['equipeDomicile'],
                'domicile'
            );
        } else {
            $validated['logo_equipe_domicile'] = 'default_home.png';
        }

        if ($request->hasFile('logo_equipe_exterieur')) {
            $validated['logo_equipe_exterieur'] = $this->handleLogoUpload(
                $request->file('logo_equipe_exterieur'),
                $validated['equipeExterieur'],
                'exterieur'
            );
        } else {
            $validated['logo_equipe_exterieur'] = 'default_away.png';
        }

        // Si la météo n'est pas fournie, mettre une valeur par défaut
        if (empty($validated['meteo'])) {
            $validated['meteo'] = 'Météo non disponible';
        }

        $evenement = Evenement::create($validated);

        // Ajouter des points à l'utilisateur et enregistrer l'action
        $user = Auth::user();
        $pointsGagne = 15; // Points gagnés pour l'ajout d'un événement

        // Mettre à jour les points de l'utilisateur
        $user->points += $pointsGagne;
        $user->save();

        // Enregistrer l'action
        ActionUtilisateur::create([
            'idUtilisateur' => $user->id,
            'typeAction' => 'Ajout',
            'entiteCible' => 'Événement',
            'idCible' => $evenement->idEvenements,
            'pointsGagne' => $pointsGagne
        ]);

        return redirect()->back()->with('success', 'Événement créé avec succès. Vous avez gagné ' . $pointsGagne . ' points !');
    }

    public function update(Request $request, Evenement $evenement)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'dateEvenements' => 'required|date',
            'descriptionEvenements' => 'required|string',
            'typeEvents' => 'required|string|max:100',
            'equipeDomicile' => 'required|string|max:100',
            'equipeExterieur' => 'required|string|max:100',
            'prix' => 'required|numeric|min:0',
            'Disponiblilite' => 'required|integer|min:0',
            'lieu' => 'required|string|max:100',
            'meteo' => 'required|string|max:100',
            'ligue' => 'required|string|max:100',
            'consignes_securite' => 'nullable|string',
            'activites_autour' => 'nullable|string',
            'logo_equipe_domicile' => 'nullable',
            'logo_equipe_exterieur' => 'nullable',
            'resultat' => 'nullable|string|max:50'
        ]);

        // Conserver les logos existants par défaut
        $validated['logo_equipe_domicile'] = $evenement->logo_equipe_domicile;
        $validated['logo_equipe_exterieur'] = $evenement->logo_equipe_exterieur;

        // Gérer l'upload des logos si des fichiers sont fournis
        if ($request->hasFile('logo_equipe_domicile')) {
            $validated['logo_equipe_domicile'] = $this->handleLogoUpload(
                $request->file('logo_equipe_domicile'),
                $validated['equipeDomicile'],
                'domicile'
            );
        }

        if ($request->hasFile('logo_equipe_exterieur')) {
            $validated['logo_equipe_exterieur'] = $this->handleLogoUpload(
                $request->file('logo_equipe_exterieur'),
                $validated['equipeExterieur'],
                'exterieur'
            );
        }

        // Si la météo n'est pas fournie, mettre une valeur par défaut
        if (empty($validated['meteo'])) {
            $validated['meteo'] = 'Météo non disponible';
        }

        $evenement->update($validated);

        // Ajouter des points à l'utilisateur et enregistrer l'action
        $user = Auth::user();
        $pointsGagne = 10; // Points gagnés pour la modification d'un événement

        // Mettre à jour les points de l'utilisateur
        $user->points += $pointsGagne;
        $user->save();

        // Enregistrer l'action
        ActionUtilisateur::create([
            'idUtilisateur' => $user->id,
            'typeAction' => 'Modification',
            'entiteCible' => 'Événement',
            'idCible' => $evenement->idEvenements,
            'pointsGagne' => $pointsGagne
        ]);

        return redirect()->back()->with('success', 'Événement modifié avec succès. Vous avez gagné ' . $pointsGagne . ' points !');
    }

    public function destroy(Evenement $evenement)
    {
        $evenement->delete();

        // Ajouter des points à l'utilisateur et enregistrer l'action
        $user = Auth::user();
        $pointsGagne = 5; // Points gagnés pour la suppression d'un événement

        // Mettre à jour les points de l'utilisateur
        $user->points += $pointsGagne;
        $user->save();

        // Enregistrer l'action
        ActionUtilisateur::create([
            'idUtilisateur' => $user->id,
            'typeAction' => 'Suppression',
            'entiteCible' => 'Événement',
            'idCible' => $evenement->idEvenements,
            'pointsGagne' => $pointsGagne
        ]);

        return redirect()->back()->with('success', 'Événement supprimé avec succès. Vous avez gagné ' . $pointsGagne . ' points !');
    }
} 