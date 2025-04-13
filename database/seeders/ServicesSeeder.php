<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicesSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'nom' => 'Restauration Tribune Nord',
                'idCategorie' => 1, // Restauration
                'descriptionServices' => 'Service de restauration rapide et boissons dans la tribune nord',
                'image' => '/images/services/restauration.png',
                'estActif' => true,
            ],
            [
                'nom' => 'Sécurité générale',
                'idCategorie' => 2, // Sécurité
                'descriptionServices' => 'Service de sécurité et surveillance du stade',
                'image' => '/images/services/securite.png',
                'estActif' => true,
            ],
            [
                'nom' => 'Billetterie principale',
                'idCategorie' => 4, // Billetterie
                'descriptionServices' => 'Service de vente et contrôle des billets',
                'image' => '/images/services/billeterie.png',
                'estActif' => true,
            ],
            [
                'nom' => 'Infirmerie centrale',
                'idCategorie' => 6, // Médical
                'descriptionServices' => 'Service médical et premiers secours',
                'image' => '/images/services/infirmerie.png',
                'estActif' => true,
            ],
            [
                'nom' => 'Service VIP',
                'idCategorie' => 7, // VIP
                'descriptionServices' => 'Service premium pour les espaces VIP avec restauration et conciergerie',
                'image' => '/images/services/vip.png',
                'estActif' => true,
            ],
            [
                'nom' => 'Infirmerie mobile',
                'idCategorie' => 6, // Médical
                'descriptionServices' => 'Soins médicaux rapides et efficaces',
                'image' => '/images/services/ambulance.png',
                'estActif' => true,
            ],
        ];

        foreach ($services as $service) {
            DB::table('services')->insert($service);
        }
    }
} 