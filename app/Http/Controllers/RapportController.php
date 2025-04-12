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
        $pointsUtilisateurs = ActionUtilisateur::select(
            DB::raw('DATE(dateAction) as date'),
            DB::raw('SUM(pointsGagne) as points')
        )
        ->groupBy(DB::raw('DATE(dateAction)'))
        ->orderBy(DB::raw('DATE(dateAction)'))
        ->get();

        return Inertia::render('dashboard/GestionRapport', [
            'objets' => $objets,
            'categories' => $categories,
            'zones' => $zones,
            'pointsUtilisateurs' => $pointsUtilisateurs
        ]);
    }
} 