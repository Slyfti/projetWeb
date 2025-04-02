<?php

namespace Database\Seeders;

use App\Models\SportEvent;
use Illuminate\Database\Seeder;

class SportEventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'title' => 'Match de Ligue 1: Paris Saint-Germain vs Marseille',
                'description' => 'Classique du football français entre les deux plus grands clubs de France.',
                'start_date' => '2024-04-15 20:00:00',
                'end_date' => '2024-04-15 22:00:00',
                'location' => 'Stade de France',
                'capacity' => 80000,
                'price' => 45.00,
                'status' => 'scheduled',
                'sport_type' => 'Football',
                'competition_type' => 'Ligue 1',
                'additional_info' => json_encode([
                    'broadcast' => 'Canal+',
                    'referee' => 'Clément Turpin'
                ])
            ],
            [
                'title' => 'Finale de la Coupe de France',
                'description' => 'Finale de la prestigieuse Coupe de France de football.',
                'start_date' => '2024-05-25 21:00:00',
                'end_date' => '2024-05-25 23:00:00',
                'location' => 'Stade de France',
                'capacity' => 80000,
                'price' => 60.00,
                'status' => 'scheduled',
                'sport_type' => 'Football',
                'competition_type' => 'Coupe de France',
                'additional_info' => json_encode([
                    'broadcast' => 'France TV',
                    'referee' => 'À déterminer'
                ])
            ],
            [
                'title' => 'Match de Rugby Top 14: Racing 92 vs Stade Français',
                'description' => 'Match de championnat entre deux équipes parisiennes.',
                'start_date' => '2024-04-20 15:00:00',
                'end_date' => '2024-04-20 17:00:00',
                'location' => 'Stade de France',
                'capacity' => 80000,
                'price' => 35.00,
                'status' => 'scheduled',
                'sport_type' => 'Rugby',
                'competition_type' => 'Top 14',
                'additional_info' => json_encode([
                    'broadcast' => 'Canal+',
                    'referee' => 'Romain Poite'
                ])
            ]
        ];

        foreach ($events as $event) {
            SportEvent::create($event);
        }
    }
} 