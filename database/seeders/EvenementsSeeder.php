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
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Concert - Festival d\'été',
                'dateEvenements' => Carbon::now()->addMonths(2)->setTime(19, 30),
                'descriptionEvenements' => 'Grand concert en plein air',
                'typeEvents' => 'Concert',
                'equipeDomicile' => 'Artiste Principal',
                'equipeExterieur' => 'Première Partie',
                'prix' => 85.00,
                'Disponiblilite' => 50000,
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
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($evenements as $evenement) {
            DB::table('evenements')->insert($evenement);
        }
    }
} 