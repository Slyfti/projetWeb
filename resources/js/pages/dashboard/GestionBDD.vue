<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Base de données',
        href: '/dashboard/gestion-bdd',
    },
];

const exportDatabase = () => {
    // Redirection directe vers l'URL d'export
    window.location.href = '/api/export-database';
};

const exportSqlite = () => {
    window.location.href = '/api/export-sqlite';
};
</script>

<template>
    <Head title="Dashboard - Base de données" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative overflow-hidden rounded-xl border border-indigo-500/30 bg-gray-900 bg-opacity-90 shadow-md backdrop-blur-sm transition-all duration-300">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-white tracking-[0.05em]">Gestion de la Base de Données</h2>
                    </div>

                    <div class="grid gap-4">
                        <div class="rounded-lg border border-indigo-500/30 bg-indigo-900/30 p-4">
                            <div class="flex flex-col gap-4">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-lg font-semibold text-white">Export de la Base de Données</h2>
                                    <div class="flex gap-4">
                                        <Button 
                                            @click="exportDatabase"
                                            class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300"
                                        >
                                            Exporter en CSV
                                        </Button>
                                        <Button 
                                            @click="exportSqlite"
                                            class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300"
                                        >
                                            Exporter en SQLite
                                        </Button>
                                    </div>
                                </div>
                                <p class="text-sm text-white/80">
                                    Exportez l'ensemble de vos données dans un fichier CSV pour analyse ou en SQLite pour sauvegarde complète.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 