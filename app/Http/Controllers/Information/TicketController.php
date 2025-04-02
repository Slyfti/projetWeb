<?php

namespace App\Http\Controllers\Information;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with('sportEvent');

        // Filtres
        if ($request->has('event_id')) {
            $query->where('sport_event_id', $request->event_id);
        }

        if ($request->has('ticket_type')) {
            $query->where('ticket_type', $request->ticket_type);
        }

        $tickets = $query->orderBy('price')->paginate(10);

        return Inertia::render('information/Tickets/Index', [
            'tickets' => $tickets,
            'filters' => $request->only(['event_id', 'ticket_type'])
        ]);
    }

    public function show(Ticket $ticket)
    {
        return Inertia::render('information/Tickets/Show', [
            'ticket' => $ticket->load('sportEvent')
        ]);
    }
} 