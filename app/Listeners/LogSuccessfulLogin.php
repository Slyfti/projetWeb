<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\ConnexionUtilisateur;
use App\Models\Utilisateur;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        if (!($event->user instanceof Utilisateur)) {
            return;
        }

        $user = Utilisateur::find($event->user->id);
        if (!$user) {
            return;
        }
        
        // Ajouter des points à l'utilisateur (par exemple, 10 points par connexion)
        $pointsGagnes = 10;
        $user->points = ($user->points ?? 0) + $pointsGagnes;
        $user->save();

        // Enregistrer la connexion
        ConnexionUtilisateur::create([
            'idUtilisateur' => $user->id,
            'dateConnexion' => now(),
            'pointsGagne' => $pointsGagnes
        ]);
    }
}
