<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Information\SportEventController;
use App\Http\Controllers\Information\TeamController;
use App\Http\Controllers\Information\TicketController;
use App\Http\Controllers\Information\ServiceController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes pour le module Information (visiteurs)
Route::prefix('information')->group(function () {
    // Routes pour les événements sportifs
    Route::get('/events', [SportEventController::class, 'index'])->name('events.index');
    Route::get('/events/{sportEvent}', [SportEventController::class, 'show'])->name('events.show');
    
    // Routes pour les équipes
    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
    Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show');
    
    // Routes pour les billets
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    
    // Routes pour les services
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
