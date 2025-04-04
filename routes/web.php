<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Information\SportEventController;
use App\Http\Controllers\Information\TeamController;
use App\Http\Controllers\Information\TicketController;
use App\Http\Controllers\Information\ServiceController;
use App\Http\Controllers\EvenementsController;
use App\Models\Utilisateur;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    $users = Utilisateur::select('id','email','pseudo')->get(); // Fetch users
    return Inertia::render('Dashboard', [
        'users' => $users, // Pass users to the Dashboard page
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes pour le module Information (visiteurs)
Route::prefix('information')->group(function () {
    // Routes pour les événements
    Route::get('/evenements', [EvenementsController::class, 'index'])->name('evenements.index');
    Route::get('/evenements/{evenement}', [EvenementsController::class, 'show'])->name('evenements.show');
    
    // Routes pour les équipes
    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
    Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show');
    
    // Routes pour les billets
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
