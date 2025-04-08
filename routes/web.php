<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Information\SportEventController;
use App\Http\Controllers\Information\TeamController;
use App\Http\Controllers\Information\TicketController;
use App\Http\Controllers\Information\ServiceController;
use App\Http\Controllers\EvenementsController;
use App\Models\User;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ObjetConnecteController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

// Routes pour le module Information (visiteurs)
Route::prefix('information')->group(function () {
    // Routes pour les services
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/restauration', [ServiceController::class, 'restauration'])->name('restauration.index');
    Route::get('/vip', [ServiceController::class, 'vip'])->name('vip.index');
    Route::get('/securite', [ServiceController::class, 'securite'])->name('securite.index');
    Route::get('/medical', [ServiceController::class, 'medical'])->name('medical.index');
    Route::get('/pmr', [ServiceController::class, 'pmr'])->name('pmr.index');
    
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

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard et gestion des utilisateurs
    Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/{user}/login-history', [UserController::class, 'getLoginHistory'])->name('users.loginHistory');
});

Route::middleware(['auth'])->group(function () {
    // Routes pour la gestion des objets connectés
    Route::get('/objets-connectes', [ObjetConnecteController::class, 'index'])->name('objets-connectes.index');
    Route::post('/objets-connectes', [ObjetConnecteController::class, 'store'])->name('objets-connectes.store');
    Route::put('/objets-connectes/{objet}', [ObjetConnecteController::class, 'update'])->name('objets-connectes.update');
    Route::delete('/objets-connectes/{objet}', [ObjetConnecteController::class, 'destroy'])->name('objets-connectes.destroy');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
