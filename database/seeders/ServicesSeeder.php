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
                'estActif' => true,
            ],
            [
                'nom' => 'Sécurité Générale',
                'idCategorie' => 2, // Sécurité
                'descriptionServices' => 'Service de sécurité et surveillance du stade',
                'estActif' => true,
            ],
            [
                'nom' => 'Nettoyage Tribune Sud',
                'idCategorie' => 3, // Nettoyage
                'descriptionServices' => 'Service de nettoyage et maintenance de la tribune sud',
                'estActif' => true,
            ],
            [
                'nom' => 'Billetterie Principale',
                'idCategorie' => 4, // Billetterie
                'descriptionServices' => 'Service de vente et contrôle des billets',
                'estActif' => true,
            ],
            [
                'nom' => 'Assistance PMR',
                'idCategorie' => 5, // Assistance
                'descriptionServices' => 'Service d\'assistance aux personnes à mobilité réduite',
                'estActif' => true,
            ],
            [
                'nom' => 'Infirmerie Centrale',
                'idCategorie' => 6, // Médical
                'descriptionServices' => 'Service médical et premiers secours',
                'estActif' => true,
            ],
            [
                'nom' => 'Service VIP Lounge',
                'idCategorie' => 7, // VIP
                'descriptionServices' => 'Service premium pour les espaces VIP avec restauration et conciergerie',
                'estActif' => true,
            ],
        ];

        foreach ($services as $service) {
            DB::table('services')->insert($service);
        }
    }
} 