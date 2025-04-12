<?php

namespace App\Http\Controllers;

use App\Models\ObjetConnecte;
use App\Models\User;
use App\Models\ActionUtilisateur;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RapportController extends Controller
{
    public function index()
    {
        $objets = ObjetConnecte::with(['categorie', 'zone'])->get();
        $categories = \App\Models\CategorieObjet::all();
        $zones = \App\Models\ZoneStade::all();

        // Récupération des points utilisateurs par jour
        $pointsUtilisateurs = ActionUtilisateur::all()
            ->groupBy(function($action) {
                return Carbon::parse($action->dateAction)->format('Y-m-d');
            })
            ->map(function($group) {
                return [
                    'date' => Carbon::parse($group->first()->dateAction)->format('Y-m-d'),
                    'points' => $group->sum('pointsGagne')
                ];
            })
            ->values()
            ->sortBy('date');

        return Inertia::render('dashboard/GestionRapport', [
            'objets' => $objets,
            'categories' => $categories,
            'zones' => $zones,
            'pointsUtilisateurs' => $pointsUtilisateurs
        ]);
    }
} 