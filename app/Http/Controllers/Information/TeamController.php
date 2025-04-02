<?php

namespace App\Http\Controllers\Information;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $query = Team::query();

        // Filtres
        if ($request->has('sport_type')) {
            $query->where('sport_type', $request->sport_type);
        }

        if ($request->has('city')) {
            $query->where('home_city', $request->city);
        }

        $teams = $query->orderBy('name')->paginate(10);

        return Inertia::render('information/teams/Index', [
            'teams' => $teams,
            'filters' => $request->only(['sport_type', 'city'])
        ]);
    }

    public function show(Team $team)
    {
        return Inertia::render('information/teams/Show', [
            'team' => $team->load('news')
        ]);
    }
} 