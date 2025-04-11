<script setup lang="ts">
import { ref, computed } from 'vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { AreaChart } from '@/components/ui/chart-area';
import RapportTooltip from '@/components/RapportTooltip.vue';

interface ObjetConnecte {
    idObjetsConnectes: number;
    nom: string;
    puissance: number;
    consommationActuelle: number;
    etat: 'Actif' | 'Inactif' | 'Maintenance';
    categorie?: {
        nom: string;
    };
}

interface PointUtilisateur {
    date: string;
    points: number;
}

const props = defineProps<{
    objets: ObjetConnecte[];
    categories: any[];
    zones: any[];
    pointsUtilisateurs: PointUtilisateur[];
}>();

// Calcul de la consommation totale
const consommationTotale = computed(() => {
    return props.objets.reduce((total, objet) => total + objet.consommationActuelle, 0);
});

// Calcul de la puissance totale
const puissanceTotale = computed(() => {
    return props.objets.reduce((total, objet) => total + objet.puissance, 0);
});

// Calcul du nombre d'objets par catégorie
const objetsParCategorie = computed(() => {
    const categories = new Map();
    props.objets.forEach(objet => {
        const categorie = objet.categorie?.nom || 'Non catégorisé';
        categories.set(categorie, (categories.get(categorie) || 0) + 1);
    });
    return Object.fromEntries(categories);
});

// Données pour le graphique de consommation
const chartData = computed(() => {
    return props.pointsUtilisateurs.map(point => ({
        date: new Date(point.date),
        points: point.points
    }));
});

const chartCategories: ("points" | "date")[] = ['points'];
const chartIndex = 'date' as const;

// Options de formatage pour le graphique
const xFormatter = (tick: number | Date, i: number, ticks: (number | Date)[]) => {
    if (tick instanceof Date) {
        return tick.toLocaleDateString('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    return new Date(tick).toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const yFormatter = (tick: number | Date, i: number, ticks: (number | Date)[]) => {
    if (typeof tick === 'number') {
        return tick.toFixed(1) + ' pts';
    }
    return tick.toString();
};
</script>

<template>
    <div class="space-y-6">
        <!-- Cartes de statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <Card class="bg-indigo-900/30 border-indigo-500/30">
                <CardHeader>
                    <CardTitle class="text-white">Consommation Totale</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold text-white">{{ consommationTotale }}W</div>
                </CardContent>
            </Card>

            <Card class="bg-indigo-900/30 border-indigo-500/30">
                <CardHeader>
                    <CardTitle class="text-white">Puissance Totale</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold text-white">{{ puissanceTotale }}W</div>
                </CardContent>
            </Card>

            <Card class="bg-indigo-900/30 border-indigo-500/30">
                <CardHeader>
                    <CardTitle class="text-white">Objets Actifs</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold text-white">
                        {{ objets.filter(o => o.etat === 'Actif').length }}
                    </div>
                </CardContent>
            </Card>

            <Card class="bg-indigo-900/30 border-indigo-500/30">
                <CardHeader>
                    <CardTitle class="text-white">Objets en Maintenance</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold text-white">
                        {{ objets.filter(o => o.etat === 'Maintenance').length }}
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Graphique des points utilisateurs -->
        <Card class="bg-indigo-900/30 border-indigo-500/30">
            <CardHeader>
                <CardTitle class="text-white">Évolution des Points Utilisateurs</CardTitle>
            </CardHeader>
            <CardContent>
                <div class="h-[400px]">
                    <AreaChart
                        :data="chartData"
                        :categories="chartCategories"
                        :index="chartIndex"
                        :xFormatter="xFormatter"
                        :yFormatter="yFormatter"
                        :colors="['rgb(99, 102, 241)']"
                        :custom-tooltip="RapportTooltip"
                        class="w-full h-full"
                    />
                </div>
            </CardContent>
        </Card>

        <!-- Distribution par catégorie -->
        <Card class="bg-indigo-900/30 border-indigo-500/30">
            <CardHeader>
                <CardTitle class="text-white">Distribution par Catégorie</CardTitle>
            </CardHeader>
            <CardContent>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <div v-for="(count, categorie) in objetsParCategorie" :key="categorie" 
                        class="p-4 rounded-lg bg-indigo-800/30 border border-indigo-500/30">
                        <div class="text-lg font-semibold text-white">{{ categorie }}</div>
                        <div class="text-2xl font-bold text-indigo-300">{{ count }}</div>
                    </div>
                </div>
            </CardContent>
        </Card>
    </div>
</template>

<style scoped>
:deep(.chartjs-render-monitor) {
    background-color: transparent !important;
}
</style> 