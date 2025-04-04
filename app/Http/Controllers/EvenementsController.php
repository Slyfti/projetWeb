<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EvenementsController extends Controller
{
    public function index(Request $request)
    {
        $query = Evenement::query();

        // Appliquer les filtres
        if ($request->filled('typeEvents')) {
            $query->where('typeEvents', $request->typeEvents);
        }

        if ($request->filled('date')) {
            $query->whereDate('dateEvenements', $request->date);
        }

        $evenements = $query->paginate(9)
            ->withQueryString();

        return Inertia::render('information/evenements/Index', [
            'evenements' => $evenements,
            'filters' => $request->only(['typeEvents', 'date'])
        ]);
    }

    public function show(Evenement $evenement)
    {
        return Inertia::render('information/evenements/Show', [
            'evenement' => $evenement
        ]);
    }
} 