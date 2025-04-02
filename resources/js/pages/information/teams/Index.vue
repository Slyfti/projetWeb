<template>
    <AuthLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Équipes
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Filtres -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Filtres</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <select
                                v-model="filters.sport_type"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">Type de sport</option>
                                <option value="football">Football</option>
                                <option value="basketball">Basketball</option>
                                <option value="tennis">Tennis</option>
                            </select>

                            <select
                                v-model="filters.city"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">Ville</option>
                                <option value="paris">Paris</option>
                                <option value="lyon">Lyon</option>
                                <option value="marseille">Marseille</option>
                            </select>

                            <button
                                @click="applyFilters"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Appliquer les filtres
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Liste des équipes -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div
                        v-for="team in teams.data"
                        :key="team.id"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                    >
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <img
                                    v-if="team.logo"
                                    :src="team.logo"
                                    :alt="team.name"
                                    class="h-12 w-12 rounded-full mr-4"
                                />
                                <h3 class="text-lg font-medium text-gray-900">{{ team.name }}</h3>
                            </div>
                            <div class="space-y-2">
                                <p class="text-sm text-gray-600">{{ team.sport_type }}</p>
                                <p class="text-sm text-gray-600">{{ team.home_city }}</p>
                                <p class="text-sm text-gray-600">Fondée en {{ team.founded_year }}</p>
                            </div>
                            <p class="mt-4 text-sm text-gray-600 line-clamp-3">{{ team.description }}</p>
                            <div class="mt-4">
                                <Link
                                    :href="route('teams.show', team.id)"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    Voir les détails
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="teams.last_page > 1" class="mt-6 flex justify-center">
                    <div class="flex space-x-2">
                        <button
                            v-for="page in teams.last_page"
                            :key="page"
                            @click="changePage(page)"
                            :class="[
                                'px-4 py-2 border rounded-md',
                                page === teams.current_page
                                    ? 'bg-indigo-600 text-white'
                                    : 'bg-white text-gray-700 hover:bg-gray-50'
                            ]"
                        >
                            {{ page }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import AuthLayout from '@/layouts/AuthLayout.vue';

const props = defineProps({
    teams: {
        type: Object,
        required: true
    },
    filters: {
        type: Object,
        required: true
    }
});

const filters = ref(props.filters);

const applyFilters = () => {
    // Implémenter la logique de filtrage
};

const changePage = (page) => {
    // Implémenter la logique de changement de page
};
</script> 