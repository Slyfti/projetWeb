<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EvenementsSeeder extends Seeder
{
    public function run(): void
    {
        $evenements = [
            [
                'nom' => 'Match de Ligue 1 - Journée 28',
                'dateEvenements' => Carbon::now()->addDays(10)->setTime(20, 45),
                'descriptionEvenements' => 'Match de championnat de Ligue 1',
                'typeEvents' => 'Football',
                'equipeDomicile' => 'Paris Saint-Germain',
                'equipeExterieur' => 'Olympique de Marseille',
                'prix' => 65.00,
                'Disponiblilite' => 45000,
                'lieu' => 'Parc des Princes',
                'meteo' => 'Ensoleillé, 22°C',
                'ligue' => 'Ligue 1',
                'consignes_securite' => 'Interdiction d\'apporter des objets dangereux. Les sacs seront fouillés à l\'entrée.',
                'activites_autour' => 'Boutique officielle, restaurants, bars, zone de restauration rapide.',
                'logo_equipe_domicile' => 'Paris_Saint-Germain_Logo.png',
                'logo_equipe_exterieur' => 'Logo_Olympique_de_Marseille.png',
                'resultat' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Coupe de France - Quart de finale',
                'dateEvenements' => Carbon::now()->addDays(20)->setTime(21, 00),
                'descriptionEvenements' => 'Match de coupe de France',
                'typeEvents' => 'Football',
                'equipeDomicile' => 'Paris Saint-Germain',
                'equipeExterieur' => 'OGC Nice',
                'prix' => 45.00,
                'Disponiblilite' => 40000,
                'lieu' => 'Parc des Princes',
                'meteo' => 'Pluvieux, 15°C',
                'ligue' => 'Coupe de France',
                'consignes_securite' => 'Interdiction d\'apporter des objets dangereux. Les sacs seront fouillés à l\'entrée.',
                'activites_autour' => 'Boutique officielle, restaurants, bars, zone de restauration rapide.',
                'logo_equipe_domicile' => 'Paris_Saint-Germain_Logo.png',
                'logo_equipe_exterieur' => 'Logo_OGC_Nice.png',
                'resultat' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Match Rocket League',
                'dateEvenements' => Carbon::now()->addMonths(2)->setTime(19, 30),
                'descriptionEvenements' => 'Match d\'exhibition',
                'typeEvents' => 'Football',
                'equipeDomicile' => 'KC',
                'equipeExterieur' => 'M8',
                'prix' => 85.00,
                'Disponiblilite' => 50000,
                'lieu' => 'Stade de France',
                'meteo' => 'Ensoleillé, 28°C',
                'ligue' => 'Festival',
                'consignes_securite' => 'Interdiction d\'apporter des objets dangereux. Les sacs seront fouillés à l\'entrée.',
                'activites_autour' => 'Boutique officielle, restaurants, bars, zone de restauration rapide.',
                'logo_equipe_domicile' => 'kc.png',
                'logo_equipe_exterieur' => 'm8.png',
                'resultat' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Match de Champions League',
                'dateEvenements' => Carbon::now()->addDays(30)->setTime(21, 00),
                'descriptionEvenements' => 'Match de phase de groupes de Champions League',
                'typeEvents' => 'Football',
                'equipeDomicile' => 'Paris Saint-Germain',
                'equipeExterieur' => 'Manchester City',
                'prix' => 95.00,
                'Disponiblilite' => 35000,
                'lieu' => 'Parc des Princes',
                'meteo' => 'Nuageux, 18°C',
                'ligue' => 'Champions League',
                'consignes_securite' => 'Interdiction d\'apporter des objets dangereux. Les sacs seront fouillés à l\'entrée.',
                'activites_autour' => 'Boutique officielle, restaurants, bars, zone de restauration rapide.',
                'logo_equipe_domicile' => 'Paris_Saint-Germain_Logo.png',
                'logo_equipe_exterieur' => 'Manchester_City_Logo.png',
                'resultat' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($evenements as $evenement) {
            DB::table('evenements')->insert($evenement);
        }
    }
} 