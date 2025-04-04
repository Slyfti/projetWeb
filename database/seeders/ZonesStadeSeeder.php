<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ZonesStadeSeeder extends Seeder
{
    public function run(): void
    {
        $zones = [
            [
                'nom' => 'Tribune Nord',
                'descriptionZonesStade' => 'Tribune principale avec vue directe sur le terrain',
                'capacite' => 15000,
            ],
            [
                'nom' => 'Tribune Sud',
                'descriptionZonesStade' => 'Tribune opposée avec accès aux espaces VIP',
                'capacite' => 12000,
            ],
            [
                'nom' => 'Tribune Est',
                'descriptionZonesStade' => 'Tribune latérale avec accès aux buvettes',
                'capacite' => 8000,
            ],
            [
                'nom' => 'Tribune Ouest',
                'descriptionZonesStade' => 'Tribune latérale avec espaces familiaux',
                'capacite' => 8000,
            ],
            [
                'nom' => 'Pelouse',
                'descriptionZonesStade' => 'Terrain de jeu principal',
                'capacite' => 0,
            ],
            [
                'nom' => 'Zone VIP',
                'descriptionZonesStade' => 'Espace privilégié avec services premium',
                'capacite' => 1000,
            ],
            [
                'nom' => 'Vestiaires',
                'descriptionZonesStade' => 'Zone réservée aux équipes et au staff',
                'capacite' => 100,
            ],
        ];

        foreach ($zones as $zone) {
            DB::table('zonesStade')->insert($zone);
        }
    }
} 