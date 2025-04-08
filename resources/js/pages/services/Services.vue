<script setup lang="ts">
import Header from '@/components/Header.vue';
import Footer from '@/components/Footer.vue';
import { Link } from '@inertiajs/vue3';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';

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
    <div class="min-h-screen flex flex-col bg-gradient-to-tr from-[#6F4786] to-[#2D0B47]">
        <Header />
        <main class="flex-grow container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold text-[#E4F1F1] mb-8">Nos Services</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Service Restauration -->
                <Card v-if="services['Restauration']" class="bg-white/10 backdrop-blur-sm border-none">
                    <CardHeader>
                        <CardTitle class="text-[#E4F1F1]">Restauration</CardTitle>
                        <CardDescription class="text-[#E4F1F1]/80">
                            {{ services['Restauration'][0]?.descriptionServices || 'Découvrez nos différents points de restauration dans le stade' }}
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Link :href="route('restauration.index')">
                            <Button class="w-full bg-[#6F4786] hover:bg-[#5A3A6B] text-white">
                                Voir les options
                            </Button>
                        </Link>
                    </CardContent>
                </Card>

                <!-- Service Billetterie -->
                <Card class="bg-white/10 backdrop-blur-sm border-none">
                    <CardHeader>
                        <CardTitle class="text-[#E4F1F1]">Billetterie</CardTitle>
                        <CardDescription class="text-[#E4F1F1]/80">
                            Réservez vos places pour les prochains événements
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Link :href="route('evenements.index')">
                            <Button class="w-full bg-[#6F4786] hover:bg-[#5A3A6B] text-white">
                                Voir les événements
                            </Button>
                        </Link>
                    </CardContent>
                </Card>

                <!-- Service VIP -->
                <Card v-if="services['VIP']" class="bg-white/10 backdrop-blur-sm border-none">
                    <CardHeader>
                        <CardTitle class="text-[#E4F1F1]">Espace VIP</CardTitle>
                        <CardDescription class="text-[#E4F1F1]/80">
                            {{ services['VIP'][0]?.descriptionServices || 'Profitez d\'une expérience premium dans nos loges VIP' }}
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Link :href="route('vip.index')">
                            <Button class="w-full bg-[#6F4786] hover:bg-[#5A3A6B] text-white">
                                Découvrir
                            </Button>
                        </Link>
                    </CardContent>
                </Card>

                <!-- Service Sécurité -->
                <Card v-if="services['Sécurité']" class="bg-white/10 backdrop-blur-sm border-none">
                    <CardHeader>
                        <CardTitle class="text-[#E4F1F1]">Sécurité</CardTitle>
                        <CardDescription class="text-[#E4F1F1]/80">
                            {{ services['Sécurité'][0]?.descriptionServices || 'Informations sur la sécurité et les consignes du stade' }}
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Link :href="route('securite.index')">
                            <Button class="w-full bg-[#6F4786] hover:bg-[#5A3A6B] text-white">
                                En savoir plus
                            </Button>
                        </Link>
                    </CardContent>
                </Card>

                <!-- Service Médical -->
                <Card v-if="services['Médical']" class="bg-white/10 backdrop-blur-sm border-none">
                    <CardHeader>
                        <CardTitle class="text-[#E4F1F1]">Service Médical</CardTitle>
                        <CardDescription class="text-[#E4F1F1]/80">
                            {{ services['Médical'][0]?.descriptionServices || 'Points d\'accueil médical et informations de santé' }}
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Link :href="route('medical.index')">
                            <Button class="w-full bg-[#6F4786] hover:bg-[#5A3A6B] text-white">
                                Localiser
                            </Button>
                        </Link>
                    </CardContent>
                </Card>

                <!-- Service PMR -->
                <Card v-if="services['Assistance']" class="bg-white/10 backdrop-blur-sm border-none">
                    <CardHeader>
                        <CardTitle class="text-[#E4F1F1]">Accessibilité PMR</CardTitle>
                        <CardDescription class="text-[#E4F1F1]/80">
                            {{ services['Assistance'][0]?.descriptionServices || 'Services et aménagements pour les personnes à mobilité réduite' }}
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Link :href="route('pmr.index')">
                            <Button class="w-full bg-[#6F4786] hover:bg-[#5A3A6B] text-white">
                                Informations
                            </Button>
                        </Link>
                    </CardContent>
                </Card>
            </div>
      </main>
        <Footer />
    </div>
  </template>
  