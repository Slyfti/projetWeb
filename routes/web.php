<?php

use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\EvenementsController;
use App\Models\User;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ObjetConnecteController;
use App\Http\Controllers\RapportController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\ServiceController as AdminServiceController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

// Routes pour le module Information (visiteurs)
Route::prefix('information')->group(function () {
    
    // Routes pour les événements
    Route::get('/evenements', [EvenementsController::class, 'index'])->name('evenements.index');
    Route::get('/evenements/{evenement}', [EvenementsController::class, 'show'])->name('evenements.show');

    // Routes pour les services
    Route::get('/services', [ServiceController::class, 'getServices'])->name('services.show');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard et gestion des utilisateurs
    Route::get('/dashboard/utilisateurs', [UserController::class, 'index'])->name('utilisateurs');
    Route::get('/dashboard/objets', [ObjetConnecteController::class, 'index'])->name('objets');
    Route::get('/dashboard/evenements', [EvenementsController::class, 'adminIndex'])->name('evenements.admin');
    Route::get('/dashboard/rapports', [RapportController::class, 'index'])->name('rapports');
    Route::get('/dashboard/gestion-bdd', [DatabaseController::class, 'index'])->name('gestion-bdd');
    Route::get('/api/export-database', [DatabaseController::class, 'export'])->name('export-database');
    Route::get('/api/export-sqlite', [DatabaseController::class, 'exportSqlite'])->name('export-sqlite');
    Route::redirect("/dashboard","/dashboard/utilisateurs")->name("dashboard");

    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/{user}/login-history', [UserController::class, 'getLoginHistory'])->name('users.loginHistory');

    // Routes pour la gestion des événements (admin)
    Route::post('/evenements', [EvenementsController::class, 'store'])->name('evenements.store');
    Route::put('/evenements/{evenement}', [EvenementsController::class, 'update'])->name('evenements.update');
    Route::delete('/evenements/{evenement}', [EvenementsController::class, 'destroy'])->name('evenements.destroy');

    // Routes API pour les points d'expérience
    Route::get('/api/users/{user}/points', [UserController::class, 'getPoints'])->name('api.users.points');

    // Routes pour la gestion des services (admin)
    Route::get('/dashboard/services', [AdminServiceController::class, 'index'])->name('dashboard.services');
    Route::get('/api/services', [AdminServiceController::class, 'getServices']);
    Route::post('/api/services', [AdminServiceController::class, 'store']);
    Route::put('/api/services/{service}', [AdminServiceController::class, 'update']);
    Route::delete('/api/services/{service}', [AdminServiceController::class, 'destroy']);
});

Route::middleware(['auth'])->group(function () {
    // Routes pour la gestion des objets connectés
    Route::get('/objets-connectes', [ObjetConnecteController::class, 'index'])->name('objets-connectes.index');
    Route::post('/objets-connectes', [ObjetConnecteController::class, 'store'])->name('objets-connectes.store');
    Route::put('/objets-connectes/{objet}', [ObjetConnecteController::class, 'update'])->name('objets-connectes.update');
    Route::delete('/objets-connectes/{objet}', [ObjetConnecteController::class, 'destroy'])->name('objets-connectes.destroy');

    // Routes pour les tickets
    Route::get('/dashboard/tickets', [\App\Http\Controllers\TicketController::class, 'index'])->name('tickets.index');
    Route::get('/dashboard/tickets/admin', [\App\Http\Controllers\TicketController::class, 'adminIndex'])->name('tickets.admin.index');
    Route::get('/dashboard/tickets/{id}', [\App\Http\Controllers\TicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets', [\App\Http\Controllers\TicketController::class, 'store'])->name('tickets.store');
    Route::put('/tickets/{id}', [\App\Http\Controllers\TicketController::class, 'update'])->name('tickets.update');
    Route::post('/tickets/{id}/cancel', [\App\Http\Controllers\TicketController::class, 'cancel'])->name('tickets.cancel');
    Route::delete('/tickets/{id}', [\App\Http\Controllers\TicketController::class, 'destroy'])->name('tickets.destroy');
});

// Route de test pour l'envoi d'emails
Route::get('/test-email', function() {
    try {
        Mail::raw('Test email from Astrosphère', function($message) {
            $message->to('stade.astrosphere@gmail.com')
                    ->subject('Test Email from Astrosphère');
        });
        return 'Email envoyé avec succès!';
    } catch (\Exception $e) {
        return 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage();
    }
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
