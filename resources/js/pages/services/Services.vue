<script setup lang="ts">
import Header from '@/components/Header.vue';
import Footer from '@/components/Footer.vue';
import ImgService from '@/../img/stade_neon.png';
import { defineProps, ref } from 'vue';

// Définir les props reçues depuis Inertia
interface Service {
    idservices: number;
    nom: string;
    descriptionServices: string;
    image: string | null;
    estActif: boolean;
    categorie: {
        nom: string;
    };
}

defineProps<{ services: Service[] }>();

const imageError = ref<Set<number>>(new Set());

const handleImageError = (serviceId: number) => {
    imageError.value.add(serviceId);
};

// Fonction pour obtenir l'URL de l'image avec gestion d'erreur
const getImageUrl = (image: string | null) => {
    if (!image) return ImgService;
    return image;
};
</script>

<template>
    <div class="min-h-screen flex flex-col bg-gradient-to-tr from-indigo-900 to-gray-900">
        <Header />
        <main class="flex-grow py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <h1 class="text-4xl font-bold text-white tracking-[0.05em] mb-8 text-center">Nos Services</h1>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="service in services" :key="service.idservices" 
                         class="overflow-hidden rounded-lg border border-indigo-500/30 bg-gray-900 bg-opacity-90 shadow-md backdrop-blur-sm">
                        <img 
                            :src="!imageError.has(service.idservices) ? getImageUrl(service.image) : ImgService" 
                            @error="handleImageError(service.idservices)"
                            :alt="service.nom"
                            class="w-full h-48 object-cover" 
                        />
                        <div class="p-6">
                            <div class="flex flex-col gap-2">
                                <h2 class="text-xl font-medium text-white tracking-[0.05em]">{{ service.nom }}</h2>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-indigo-500/30 text-indigo-200 border border-indigo-500/50">
                                        {{ service.categorie.nom }}
                                    </span>
                                </div>
                            </div>
                            <p class="text-white/80 mt-4">
                                {{ service.descriptionServices }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <Footer />
    </div>
</template>
