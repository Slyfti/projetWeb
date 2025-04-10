<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

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

    public function show(Evenement $evenement)
    {
        return Inertia::render('information/evenements/EventDetails', [
            'evenement' => [
                'id' => $evenement->id,
                'titre' => $evenement->nom,
                'date' => $evenement->dateEvenements,
                'lieu' => $evenement->lieu,
                'sport' => $evenement->typeEvents,
                'meteo' => $evenement->meteo,
                'ligue' => $evenement->ligue,
                'description' => $evenement->descriptionEvenements,
                'consignes_securite' => $evenement->consignes_securite,
                'activites_autour' => $evenement->activites_autour,
                'equipe_domicile' => [
                    'nom' => $evenement->equipeDomicile,
                    'logo' => $evenement->logo_equipe_domicile
                ],
                'equipe_exterieur' => [
                    'nom' => $evenement->equipeExterieur,
                    'logo' => $evenement->logo_equipe_exterieur
                ],
                'resultat' => $evenement->resultat,
                'prix' => $evenement->prix,
                'disponibilite' => $evenement->Disponiblilite
            ]
        ]);
    }
} 