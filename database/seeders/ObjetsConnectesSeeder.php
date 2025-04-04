<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ObjetsConnectesSeeder extends Seeder
{
    public function run(): void
    {
        $objets = [
            [
                'nom' => 'Projecteur LED Nord',
                'idCategorie' => 1, // Éclairage
                'descriptionObjetsConnectes' => 'Projecteur LED haute puissance tribune nord',
                'etat' => 'Actif',
                'mode' => 'Automatique',
                'connectivite' => 'WiFi',
                'niveauBatterie' => 100,
                'derniereInteraction' => Carbon::now(),
                'puissance' => 1000,
                'consommationActuelle' => 800,
                'dureeVieEstimee' => 50000,
                'dateInstallation' => Carbon::now()->subMonths(6),
                'derniereMaintenance' => Carbon::now()->subMonth(),
                'idZone' => 1, // Tribune Nord
            ],
            [
                'nom' => 'Caméra Surveillance Est',
                'idCategorie' => 2, // Sécurité
                'descriptionObjetsConnectes' => 'Caméra de surveillance HD tribune est',
                'etat' => 'Actif',
                'mode' => 'Automatique',
                'connectivite' => 'Ethernet',
                'niveauBatterie' => 95,
                'derniereInteraction' => Carbon::now(),
                'puissance' => 50,
                'consommationActuelle' => 45,
                'dureeVieEstimee' => 40000,
                'dateInstallation' => Carbon::now()->subMonths(3),
                'derniereMaintenance' => Carbon::now()->subWeeks(2),
                'idZone' => 3, // Tribune Est
            ],
            [
                'nom' => 'Écran Géant Sud',
                'idCategorie' => 3, // Affichage
                'descriptionObjetsConnectes' => 'Écran LED géant tribune sud',
                'etat' => 'Actif',
                'mode' => 'Manuel',
                'connectivite' => 'Ethernet',
                'niveauBatterie' => 100,
                'derniereInteraction' => Carbon::now(),
                'puissance' => 5000,
                'consommationActuelle' => 4500,
                'dureeVieEstimee' => 35000,
                'dateInstallation' => Carbon::now()->subYears(1),
                'derniereMaintenance' => Carbon::now()->subMonths(2),
                'idZone' => 2, // Tribune Sud
            ],
            [
                'nom' => 'Portique Accès VIP',
                'idCategorie' => 5, // Contrôle d'accès
                'descriptionObjetsConnectes' => 'Portique de contrôle d\'accès zone VIP',
                'etat' => 'Actif',
                'mode' => 'Automatique',
                'connectivite' => 'WiFi',
                'niveauBatterie' => 90,
                'derniereInteraction' => Carbon::now(),
                'puissance' => 200,
                'consommationActuelle' => 180,
                'dureeVieEstimee' => 45000,
                'dateInstallation' => Carbon::now()->subMonths(8),
                'derniereMaintenance' => Carbon::now()->subWeeks(3),
                'idZone' => 6, // Zone VIP
            ],
        ];

        foreach ($objets as $objet) {
            DB::table('objetsConnectes')->insert($objet);
        }
    }
} 