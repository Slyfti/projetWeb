<template>
    <div class="min-h-screen bg-gray-100">
        <!-- En-tête -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-gray-900">Stade Connecté</h1>
            </div>
        </header>

        <!-- Contenu principal -->
        <main>
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <!-- Section héro -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-6">
                        <div class="text-center">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Bienvenue sur Stade Connecté</h2>
                            <p class="text-gray-600 mb-6">Découvrez tous les événements sportifs et services disponibles dans notre stade.</p>
                            <Link
                                :href="route('events.index')"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Voir les événements
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Section des fonctionnalités -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Événements -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Événements</h3>
                            <p class="text-gray-600 mb-4">Consultez le calendrier des événements sportifs et réservez vos places.</p>
                            <Link
                                :href="route('events.index')"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Voir les événements
                            </Link>
                        </div>
                    </div>

                    <!-- Services -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Services</h3>
                            <p class="text-gray-600 mb-4">Découvrez tous les services disponibles dans le stade.</p>
                            <Link
                                :href="route('services.index')"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Voir les services
                            </Link>
                        </div>
                    </div>

                    <!-- Actualités -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Actualités</h3>
                            <p class="text-gray-600 mb-4">Restez informé des dernières actualités du stade.</p>
                            <Link
                                :href="route('news.index')"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Voir les actualités
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>