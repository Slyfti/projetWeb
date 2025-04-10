<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type PageProps } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import EventManagement from '@/components/EventManagement.vue';
import Header from '@/components/Header.vue';
import Footer from '@/components/Footer.vue';

interface Evenement {
    idEvenements: number;
    nom: string;
    dateEvenements: string;
    descriptionEvenements: string;
    typeEvents: string;
    equipeDomicile: string;
    equipeExterieur: string;
    prix: number;
    Disponiblilite: number;
    lieu: string;
    meteo: string;
    ligue: string;
    consignes_securite: string;
    activites_autour: string;
    logo_equipe_domicile: string;
    logo_equipe_exterieur: string;
    resultat: string | null;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Gestion des événements',
        href: '/dashboard/evenements',
    },
];

const { evenements } = usePage().props as unknown as { evenements: Evenement[] };
</script>

<template>
    <Head title="Dashboard - Événements" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                <EventManagement :evenements="evenements" />
            </div>
        </div>
    </AppLayout>
</template>
