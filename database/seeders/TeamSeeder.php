<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $teams = [
            [
                'name' => 'Paris Saint-Germain',
                'sport_type' => 'Football',
                'home_city' => 'Paris',
                'founding_year' => 1970,
                'description' => 'Club de football français fondé en 1970, basé à Paris.',
                'logo' => 'logos/psg.png',
                'website' => 'https://www.psg.fr',
                'social_media' => json_encode([
                    'twitter' => 'https://twitter.com/PSG_inside',
                    'facebook' => 'https://facebook.com/PSG',
                    'instagram' => 'https://instagram.com/psg'
                ]),
                'additional_info' => json_encode([
                    'stadium' => 'Parc des Princes',
                    'capacity' => 48000
                ])
            ],
            [
                'name' => 'Racing 92',
                'sport_type' => 'Rugby',
                'home_city' => 'Paris',
                'founding_year' => 1882,
                'description' => 'Club de rugby français basé à Paris, fondé en 1882.',
                'logo' => 'logos/racing92.png',
                'website' => 'https://www.racing92.fr',
                'social_media' => json_encode([
                    'twitter' => 'https://twitter.com/racing92',
                    'facebook' => 'https://facebook.com/racing92',
                    'instagram' => 'https://instagram.com/racing92'
                ]),
                'additional_info' => json_encode([
                    'stadium' => 'Paris La Défense Arena',
                    'capacity' => 32000
                ])
            ],
            [
                'name' => 'Stade Français Paris',
                'sport_type' => 'Rugby',
                'home_city' => 'Paris',
                'founding_year' => 1883,
                'description' => 'Club de rugby français basé à Paris, fondé en 1883.',
                'logo' => 'logos/stade-francais.png',
                'website' => 'https://www.stade.fr',
                'social_media' => json_encode([
                    'twitter' => 'https://twitter.com/stade_francais',
                    'facebook' => 'https://facebook.com/stadefrancais',
                    'instagram' => 'https://instagram.com/stadefrancais'
                ]),
                'additional_info' => json_encode([
                    'stadium' => 'Stade Jean-Bouin',
                    'capacity' => 20000
                ])
            ]
        ];

        foreach ($teams as $team) {
            Team::create($team);
        }
    }
} 