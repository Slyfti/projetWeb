<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesObjetsSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'nom' => 'Éclairage',
                'descriptionCategoriesObjets' => 'Systèmes d\'éclairage du stade',
            ],
            [
                'nom' => 'Sécurité',
                'descriptionCategoriesObjets' => 'Équipements de sécurité et de surveillance',
            ],
            [
                'nom' => 'Affichage',
                'descriptionCategoriesObjets' => 'Écrans et panneaux d\'affichage',
            ],
            [
                'nom' => 'Sonorisation',
                'descriptionCategoriesObjets' => 'Systèmes audio et haut-parleurs',
            ],
            [
                'nom' => 'Contrôle d\'accès',
                'descriptionCategoriesObjets' => 'Portiques et systèmes de contrôle d\'accès',
            ],
            [
                'nom' => 'Climatisation',
                'descriptionCategoriesObjets' => 'Systèmes de ventilation et climatisation',
            ],
        ];

        foreach ($categories as $categorie) {
            DB::table('categoriesObjets')->insert($categorie);
        }
    }
} 