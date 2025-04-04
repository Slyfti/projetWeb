<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UtilisateursSeeder extends Seeder
{
    public function run(): void
    {
        $utilisateurs = [
            [
                'pseudo' => 'admin_stade',
                'email' => 'admin@stade.fr',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('admin123'),
                'nom' => 'Dubois',
                'prenom' => 'Jean',
                'dateNaissance' => '1985-05-15',
                'age' => 38,
                'sexe' => 'Homme',
                'typeMembre' => 'Administratif',
                'niveau' => 'Expert',
                'points' => 1000,
                'photo' => null,
                'dateInscription' => Carbon::now()->subYears(2),
                'derniereConnexion' => Carbon::now(),
                'estActif' => true,
                'estVerifie' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pseudo' => 'tech_martin',
                'email' => 'martin@stade.fr',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('tech123'),
                'nom' => 'Martin',
                'prenom' => 'Sophie',
                'dateNaissance' => '1990-08-22',
                'age' => 33,
                'sexe' => 'Femme',
                'typeMembre' => 'Personnel technique',
                'niveau' => 'Avancé',
                'points' => 750,
                'photo' => null,
                'dateInscription' => Carbon::now()->subMonths(8),
                'derniereConnexion' => Carbon::now()->subDays(1),
                'estActif' => true,
                'estVerifie' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pseudo' => 'coach_pierre',
                'email' => 'pierre@stade.fr',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('coach123'),
                'nom' => 'Petit',
                'prenom' => 'Pierre',
                'dateNaissance' => '1982-03-10',
                'age' => 41,
                'sexe' => 'Homme',
                'typeMembre' => 'Entraîneur',
                'niveau' => 'Expert',
                'points' => 1200,
                'photo' => null,
                'dateInscription' => Carbon::now()->subYears(1),
                'derniereConnexion' => Carbon::now()->subHours(12),
                'estActif' => true,
                'estVerifie' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pseudo' => 'secu_marie',
                'email' => 'marie@stade.fr',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('secu123'),
                'nom' => 'Durand',
                'prenom' => 'Marie',
                'dateNaissance' => '1988-11-30',
                'age' => 35,
                'sexe' => 'Femme',
                'typeMembre' => 'Sécurité',
                'niveau' => 'Intermédiaire',
                'points' => 500,
                'photo' => null,
                'dateInscription' => Carbon::now()->subMonths(3),
                'derniereConnexion' => Carbon::now()->subDays(2),
                'estActif' => true,
                'estVerifie' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($utilisateurs as $utilisateur) {
            DB::table('utilisateurs')->insert($utilisateur);
        }
    }
} 