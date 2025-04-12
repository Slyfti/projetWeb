<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Evenement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;

class TicketController extends Controller
{
    /**
     * Affiche la liste des tickets de l'utilisateur connecté
     */
    public function index()
    {
        $user = Auth::user();
        $tickets = Ticket::with('evenement')
            ->where('idUtilisateur', $user->id)
            ->orderBy('dateAchat', 'desc')
            ->get();

        return Inertia::render('dashboard/GestionTicket', [
            'tickets' => $tickets,
            'isAdmin' => $user->typeMembre === 'Administratif'
        ]);
    }

    /**
     * Affiche tous les tickets (pour les administrateurs)
     */
    public function adminIndex()
    {
        $tickets = Ticket::with(['utilisateur', 'evenement'])
            ->orderBy('dateAchat', 'desc')
            ->get();

        return Inertia::render('dashboard/GestionTicket', [
            'tickets' => $tickets,
            'isAdmin' => true
        ]);
    }

    /**
     * Affiche les détails d'un ticket
     */
    public function show($id)
    {
        $ticket = Ticket::with(['utilisateur', 'evenement'])
            ->findOrFail($id);

        // Vérifier si l'utilisateur est autorisé à voir ce ticket
        $user = Auth::user();
        if ($user->typeMembre !== 'Administratif' && $ticket->idUtilisateur !== $user->id) {
            abort(403, 'Non autorisé');
        }

        return Inertia::render('dashboard/TicketDetails', [
            'ticket' => $ticket
        ]);
    }

    /**
     * Crée un nouveau ticket
     */
    public function store(Request $request)
    {
        $request->validate([
            'idEvenements' => 'required|exists:evenements,idEvenements',
            'typePlace' => 'required|string|max:50',
            'numeroPlace' => 'nullable|string|max:20',
            'notes' => 'nullable|string'
        ]);

        $evenement = Evenement::findOrFail($request->idEvenements);
        $user = Auth::user();

        // Générer un numéro de ticket unique
        $numeroTicket = 'TKT-' . Str::random(10);

        $ticket = new Ticket();
        $ticket->idUtilisateur = $user->id;
        $ticket->idEvenements = $request->idEvenements;
        $ticket->numeroTicket = $numeroTicket;
        $ticket->dateAchat = now();
        $ticket->typePlace = $request->typePlace;
        $ticket->numeroPlace = $request->numeroPlace;
        $ticket->prixPaye = $evenement->prix;
        $ticket->notes = $request->notes;
        $ticket->save();

        // Mettre à jour la disponibilité de l'événement
        $evenement->Disponiblilite -= 1;
        $evenement->save();

        return redirect()->route('tickets.index')->with('success', 'Ticket créé avec succès');
    }

    /**
     * Met à jour un ticket
     */
    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $user = Auth::user();

        // Vérifier si l'utilisateur est autorisé à modifier ce ticket
        if ($user->typeMembre !== 'Administratif' && $ticket->idUtilisateur !== $user->id) {
            abort(403, 'Non autorisé');
        }

        $request->validate([
            'statut' => 'required|in:valide,utilise,annule',
            'notes' => 'nullable|string'
        ]);

        $ticket->statut = $request->statut;
        
        if ($request->statut === 'utilise' && !$ticket->dateUtilisation) {
            $ticket->dateUtilisation = now();
        }
        
        $ticket->notes = $request->notes;
        $ticket->save();

        return redirect()->route('tickets.show', $id)->with('success', 'Ticket mis à jour avec succès');
    }

    /**
     * Annule un ticket
     */
    public function cancel($id)
    {
        $ticket = Ticket::findOrFail($id);
        $user = Auth::user();

        // Vérifier si l'utilisateur est autorisé à annuler ce ticket
        if ($user->typeMembre !== 'Administratif' && $ticket->idUtilisateur !== $user->id) {
            abort(403, 'Non autorisé');
        }

        // Vérifier si le ticket peut être annulé
        if ($ticket->statut !== 'valide') {
            return redirect()->back()->with('error', 'Ce ticket ne peut plus être annulé');
        }

        $ticket->statut = 'annule';
        $ticket->save();

        // Rembourser l'utilisateur (à implémenter selon les besoins)
        // ...

        // Mettre à jour la disponibilité de l'événement
        $evenement = $evenement = Evenement::findOrFail($ticket->idEvenements);
        $evenement->Disponiblilite += 1;
        $evenement->save();

        return redirect()->route('tickets.index')->with('success', 'Ticket annulé avec succès');
    }

    /**
     * Supprime un ticket
     */
    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $user = Auth::user();

        // Vérifier si l'utilisateur est autorisé à supprimer ce ticket
        if ($user->typeMembre !== 'Administratif' && $ticket->idUtilisateur !== $user->id) {
            abort(403, 'Non autorisé');
        }

        // Si le ticket est valide, mettre à jour la disponibilité de l'événement
        if ($ticket->statut === 'valide') {
            $evenement = Evenement::findOrFail($ticket->idEvenements);
            $evenement->Disponiblilite += 1;
            $evenement->save();
        }

        // Supprimer le ticket
        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket supprimé avec succès');
    }
} 