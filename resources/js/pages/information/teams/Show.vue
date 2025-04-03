<template>
    <AuthLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ team.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <!-- En-tête de l'équipe -->
                        <div class="flex items-center mb-8">
                            <img
                                v-if="team.logo"
                                :src="team.logo"
                                :alt="team.name"
                                class="h-24 w-24 rounded-full mr-6"
                            />
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ team.name }}</h3>
                                <div class="flex items-center space-x-4">
                                    <span class="text-sm text-gray-600">{{ team.sport_type }}</span>
                                    <span class="text-sm text-gray-600">{{ team.home_city }}</span>
                                    <span class="text-sm text-gray-600">Fondée en {{ team.founded_year }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">À propos</h3>
                            <p class="text-gray-600">{{ team.description }}</p>
                        </div>

                        <!-- Liens externes -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Liens externes</h3>
                            <div class="flex space-x-4">
                                <a
                                    v-if="team.website"
                                    :href="team.website"
                                    target="_blank"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    Site web
                                </a>
                                <a
                                    v-if="team.social_media?.facebook"
                                    :href="team.social_media.facebook"
                                    target="_blank"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    Facebook
                                </a>
                                <a
                                    v-if="team.social_media?.twitter"
                                    :href="team.social_media.twitter"
                                    target="_blank"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    Twitter
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>

<script setup lang="js">
import { Link } from '@inertiajs/vue3';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { format } from 'date-fns';
import { fr } from 'date-fns/locale';

const props = defineProps({
    team: {
        type: Object,
        required: true
    }
});

const formatDate = (date) => {
    return format(new Date(date), 'dd MMMM yyyy', { locale: fr });
};
</script> 