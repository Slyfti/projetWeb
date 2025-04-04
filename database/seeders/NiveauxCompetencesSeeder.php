<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NiveauxCompetencesSeeder extends Seeder
{
    public function run(): void
    {
        $niveaux = [
            [
                'niveau' => 'Débutant',
                'pointsRequis' => 0,
                'descriptionNiveauxCompetences' => 'Niveau débutant - Apprentissage des bases',
            ],
            [
                'niveau' => 'Intermédiaire',
                'pointsRequis' => 100,
                'descriptionNiveauxCompetences' => 'Niveau intermédiaire - Maîtrise des concepts fondamentaux',
            ],
            [
                'niveau' => 'Avancé',
                'pointsRequis' => 250,
                'descriptionNiveauxCompetences' => 'Niveau avancé - Expertise confirmée',
            ],
            [
                'niveau' => 'Expert',
                'pointsRequis' => 500,
                'descriptionNiveauxCompetences' => 'Niveau expert - Maîtrise complète',
            ],
        ];

        foreach ($niveaux as $niveau) {
            DB::table('niveauxCompetences')->insert($niveau);
        }
    }
} 