<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            NiveauxCompetencesSeeder::class,
            CategoriesObjetsSeeder::class,
            CategoriesServicesSeeder::class,
            ZonesStadeSeeder::class,
            ServicesSeeder::class,
            ObjetsConnectesSeeder::class,
            TeamSeeder::class,
            SportEventSeeder::class,
            EvenementsSeeder::class,
            UtilisateursSeeder::class,
            ActionsUtilisateursSeeder::class,
        ]);
    }
}
