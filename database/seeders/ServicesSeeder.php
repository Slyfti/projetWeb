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
                'image' => '/images/services/restauration.jpg',
                'estActif' => true,
            ],
            [
                'nom' => 'Sécurité Générale',
                'idCategorie' => 2, // Sécurité
                'descriptionServices' => 'Service de sécurité et surveillance du stade',
                'image' => '/images/services/securite.jpg',
                'estActif' => true,
            ],
            [
                'nom' => 'Nettoyage Tribune Sud',
                'idCategorie' => 3, // Nettoyage
                'descriptionServices' => 'Service de nettoyage et maintenance de la tribune sud',
                'image' => '/images/services/nettoyage.jpg',
                'estActif' => true,
            ],
            [
                'nom' => 'Billetterie Principale',
                'idCategorie' => 4, // Billetterie
                'descriptionServices' => 'Service de vente et contrôle des billets',
                'image' => '/images/services/billetterie.jpg',
                'estActif' => true,
            ],
            [
                'nom' => 'Assistance PMR',
                'idCategorie' => 5, // Assistance
                'descriptionServices' => 'Service d\'assistance aux personnes à mobilité réduite',
                'image' => '/images/services/pmr.jpg',
                'estActif' => true,
            ],
            [
                'nom' => 'Infirmerie Centrale',
                'idCategorie' => 6, // Médical
                'descriptionServices' => 'Service médical et premiers secours',
                'image' => '/images/services/medical.jpg',
                'estActif' => true,
            ],
            [
                'nom' => 'Service VIP Lounge',
                'idCategorie' => 7, // VIP
                'descriptionServices' => 'Service premium pour les espaces VIP avec restauration et conciergerie',
                'image' => '/images/services/vip.jpg',
                'estActif' => true,
            ],
        ];

        foreach ($services as $service) {
            DB::table('services')->insert($service);
        }
    }
} 