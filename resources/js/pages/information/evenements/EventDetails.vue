<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import Header from '@/components/Header.vue';
import Footer from '@/components/Footer.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';

defineProps<{
    evenement: {
        id: number;
        titre: string;
        date: string;
        lieu: string;
        sport: string;
        meteo?: string;
        equipe_domicile: {
            nom: string;
        };
        equipe_exterieur: {
            nom: string;
        };
    };
}>();
</script>

<template>
    <div class="min-h-screen flex flex-col bg-gradient-to-tr from-[#6F4786] to-[#2D0B47]">
        <Header />
        <main class="flex-grow container mx-auto px-4 py-8">
            <div class="mb-6">
                <Link 
                    :href="route('evenements.index')" 
                    class="text-[#E4F1F1] hover:text-[#40B584] transition flex items-center gap-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Retour aux événements
                </Link>
            </div>

            <Card class="bg-white/10 backdrop-blur-sm border-none">
                <CardHeader>
                    <CardTitle class="text-3xl font-bold text-[#E4F1F1]">
                        {{ evenement.titre }}
                    </CardTitle>
                    <CardDescription class="text-[#E4F1F1]/80 text-lg">
                        {{ new Date(evenement.date).toLocaleDateString('fr-FR', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        }) }}
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="space-y-6 text-[#E4F1F1]">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ evenement.lieu }}</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ evenement.sport }}</span>
                        </div>

                        <div v-if="evenement.meteo" class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M5.5 16a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 16h-8z" />
                            </svg>
                            <span>{{ evenement.meteo }}</span>
                        </div>

                        <div class="mt-8">
                            <h3 class="text-xl font-semibold mb-4">Équipes</h3>
                            <div class="flex items-center justify-center gap-4 text-center">
                                <div class="flex-1">
                                    <div class="text-lg font-medium">{{ evenement.equipe_domicile.nom }}</div>
                                    <div class="text-sm text-[#E4F1F1]/60">Domicile</div>
                                </div>
                                <div class="text-2xl font-bold">VS</div>
                                <div class="flex-1">
                                    <div class="text-lg font-medium">{{ evenement.equipe_exterieur.nom }}</div>
                                    <div class="text-sm text-[#E4F1F1]/60">Extérieur</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </main>
        <Footer />
    </div>
</template>
