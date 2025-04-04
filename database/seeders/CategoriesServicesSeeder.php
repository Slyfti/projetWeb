<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesServicesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'nom' => 'Restauration',
                'descriptionCategoriesServices' => 'Services de restauration et buvettes',
            ],
            [
                'nom' => 'Sécurité',
                'descriptionCategoriesServices' => 'Services de sécurité et de surveillance',
            ],
            [
                'nom' => 'Nettoyage',
                'descriptionCategoriesServices' => 'Services de nettoyage et maintenance',
            ],
            [
                'nom' => 'Billetterie',
                'descriptionCategoriesServices' => 'Services de vente et gestion des billets',
            ],
            [
                'nom' => 'Assistance',
                'descriptionCategoriesServices' => 'Services d\'assistance aux spectateurs',
            ],
            [
                'nom' => 'Médical',
                'descriptionCategoriesServices' => 'Services médicaux et premiers secours',
            ],
            [
                'nom' => 'VIP',
                'descriptionCategoriesServices' => 'Services premium pour les espaces VIP',
            ],
        ];

        foreach ($categories as $categorie) {
            DB::table('categoriesServices')->insert($categorie);
        }
    }
} 