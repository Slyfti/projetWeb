<script setup lang="ts">
import Header from '@/components/Header.vue';
import Footer from '@/components/Footer.vue';
import { Link } from '@inertiajs/vue3';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';

defineProps<{
    services: {
        [key: string]: Array<{
            nom: string;
            descriptionServices: string;
            categorie: {
                nom: string;
            };
        }>;
    };
}>();
</script>

<template>
    <div class="min-h-screen flex flex-col bg-gradient-to-tr from-indigo-900 to-gray-900">
        <Header />
        <main class="flex-grow py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <h1 class="text-4xl font-bold text-white tracking-[0.05em] mb-8 text-center">Nos Services</h1>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Service Restauration -->
                    <div v-if="services['Restauration']" class="overflow-hidden rounded-lg border border-indigo-500/30 bg-gray-900 bg-opacity-90 shadow-md backdrop-blur-sm">
                        <div class="p-6">
                            <h2 class="text-xl font-medium text-white tracking-[0.05em] mb-2">Restauration</h2>
                            <p class="text-white/80">
                                {{ services['Restauration'][0]?.descriptionServices || 'Découvrez nos différents points de restauration dans le stade' }}
                            </p>
                        </div>
                    </div>

                    <!-- Service Billetterie -->
                    <div class="overflow-hidden rounded-lg border border-indigo-500/30 bg-gray-900 bg-opacity-90 shadow-md backdrop-blur-sm">
                        <div class="p-6">
                            <h2 class="text-xl font-medium text-white tracking-[0.05em] mb-2">Billetterie</h2>
                            <p class="text-white/80">
                                Réservez vos places pour les prochains événements
                            </p>
                        </div>
                    </div>

                    <!-- Service VIP -->
                    <div v-if="services['VIP']" class="overflow-hidden rounded-lg border border-indigo-500/30 bg-gray-900 bg-opacity-90 shadow-md backdrop-blur-sm">
                        <div class="p-6">
                            <h2 class="text-xl font-medium text-white tracking-[0.05em] mb-2">Espace VIP</h2>
                            <p class="text-white/80">
                                {{ services['VIP'][0]?.descriptionServices || 'Profitez d\'une expérience premium dans nos loges VIP' }}
                            </p>
                        </div>
                    </div>

                    <!-- Service Sécurité -->
                    <div v-if="services['Sécurité']" class="overflow-hidden rounded-lg border border-indigo-500/30 bg-gray-900 bg-opacity-90 shadow-md backdrop-blur-sm">
                        <div class="p-6">
                            <h2 class="text-xl font-medium text-white tracking-[0.05em] mb-2">Sécurité</h2>
                            <p class="text-white/80">
                                {{ services['Sécurité'][0]?.descriptionServices || 'Informations sur la sécurité et les consignes du stade' }}
                            </p>
                        </div>
                    </div>

                    <!-- Service Médical -->
                    <div v-if="services['Médical']" class="overflow-hidden rounded-lg border border-indigo-500/30 bg-gray-900 bg-opacity-90 shadow-md backdrop-blur-sm">
                        <div class="p-6">
                            <h2 class="text-xl font-medium text-white tracking-[0.05em] mb-2">Service Médical</h2>
                            <p class="text-white/80">
                                {{ services['Médical'][0]?.descriptionServices || 'Points d\'accueil médical et informations de santé' }}
                            </p>
                        </div>
                    </div>

                    <!-- Service PMR -->
                    <div v-if="services['Assistance']" class="overflow-hidden rounded-lg border border-indigo-500/30 bg-gray-900 bg-opacity-90 shadow-md backdrop-blur-sm">
                        <div class="p-6">
                            <h2 class="text-xl font-medium text-white tracking-[0.05em] mb-2">Accessibilité PMR</h2>
                            <p class="text-white/80">
                                {{ services['Assistance'][0]?.descriptionServices || 'Services et aménagements pour les personnes à mobilité réduite' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <Footer />
    </div>
</template>
  