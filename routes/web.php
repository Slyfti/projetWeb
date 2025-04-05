<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Information\SportEventController;
use App\Http\Controllers\Information\TeamController;
use App\Http\Controllers\Information\TicketController;
use App\Http\Controllers\Information\ServiceController;
use App\Http\Controllers\EvenementsController;
use App\Models\Utilisateur;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

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

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard et gestion des utilisateurs
    Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::put('/users/{user}/access-level', [UserController::class, 'updateAccessLevel'])->name('users.updateAccessLevel');
    Route::put('/users/{user}/type', [UserController::class, 'updateUserType'])->name('users.updateType');
    Route::get('/users/{user}/login-history', [UserController::class, 'getLoginHistory'])->name('users.loginHistory');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
