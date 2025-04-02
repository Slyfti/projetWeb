<?php

namespace App\Http\Controllers\Information;

use App\Http\Controllers\Controller;
use App\Models\SportEvent;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SportEventController extends Controller
{
    public function index(Request $request)
    {
        $query = SportEvent::query();

        // Filtres
        if ($request->has('sport_type')) {
            $query->where('sport_type', $request->sport_type);
        }

        if ($request->has('date')) {
            $query->whereDate('start_date', $request->date);
        }

        $events = $query->orderBy('start_date', 'asc')->paginate(10);

        return Inertia::render('information/events/Index', [
            'events' => $events,
            'filters' => $request->only(['sport_type', 'date'])
        ]);
    }

    public function show(SportEvent $sportEvent)
    {
        return Inertia::render('information/events/Show', [
            'event' => $sportEvent->load(['tickets'])
        ]);
    }
} 