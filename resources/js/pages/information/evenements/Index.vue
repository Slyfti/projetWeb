<template>
    <div class="min-h-screen flex flex-col bg-gradient-to-tr from-[#6F4786] to-[#2D0B47]">
        <Header />
        <main class="flex-grow py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Filtres -->
                <div class="mb-6 overflow-hidden rounded-lg border border-[#E4F1F1]/10 bg-gray-900 bg-opacity-90 shadow-md backdrop-blur-sm">
                    <div class="p-6">
                        <h3 class="mb-4 text-lg font-medium text-[#E4F1F1]">Filtres</h3>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                            <!-- Type d'événement -->
                            <div class="space-y-2">
                                <label for="typeEvents" class="text-sm font-medium text-foreground">Type d'événement</label>
                                <Select v-model="filters.typeEvents">
                                    <SelectTrigger>
                                        <SelectValue :placeholder="'Tous les types'" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem value="">Tous les types</SelectItem>
                                            <SelectItem v-for="type in typesEvenements" :key="type" :value="type">
                                                {{ type }}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                            </div>

                            <!-- Date -->
                            <div class="space-y-2">
                                <label for="date" class="text-sm font-medium text-foreground">Date spécifique</label>
                                <Input
                                    type="date"
                                    id="date"
                                    v-model="filters.date"
                                    class="w-full"
                                />
                            </div>

                            <!-- Période -->
                            <div class="space-y-2">
                                <label for="periode" class="text-sm font-medium text-foreground">Période</label>
                                <Select v-model="filters.periode">
                                    <SelectTrigger>
                                        <SelectValue :placeholder="'Toutes les périodes'" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem value="">Toutes les périodes</SelectItem>
                                            <SelectItem value="aujourd_hui">Aujourd'hui</SelectItem>
                                            <SelectItem value="cette_semaine">Cette semaine</SelectItem>
                                            <SelectItem value="ce_mois">Ce mois</SelectItem>
                                            <SelectItem value="futur">Événements à venir</SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                            </div>

                            <!-- Prix -->
                            <div class="space-y-2 col-span-2">
                                <label class="text-sm font-medium text-foreground">Prix (€)</label>
                                <div class="px-2">
                                    <SliderRoot
                                        v-model="filters.prix"
                                        :default-value="[0, 300]"
                                        :min="0"
                                        :max="300"
                                        :step="5"
                                        class="relative flex w-full touch-none select-none items-center"
                                    >
                                        <SliderTrack class="relative h-2 w-full grow overflow-hidden rounded-full bg-secondary">
                                            <SliderRange class="absolute h-full bg-primary" />
                                        </SliderTrack>
                                        <SliderThumb class="block h-5 w-5 rounded-full border-2 border-primary bg-background ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50" />
                                        <SliderThumb class="block h-5 w-5 rounded-full border-2 border-primary bg-background ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50" />
                                    </SliderRoot>
                                    <div class="mt-2 flex justify-between text-sm text-muted-foreground">
                                        <span>{{ filters.prix[0] }}€</span>
                                        <span>{{ filters.prix[1] }}€</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Disponibilité -->
                            <div class="space-y-2">
                                <label for="disponibilite" class="text-sm font-medium text-foreground">Disponibilité</label>
                                <Select v-model="filters.disponibilite">
                                    <SelectTrigger>
                                        <SelectValue :placeholder="'Tous'" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem value="">Tous</SelectItem>
                                            <SelectItem value="disponible">Places disponibles</SelectItem>
                                            <SelectItem value="complet">Complet</SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                            </div>

                            <!-- Recherche équipe -->
                            <div class="space-y-2">
                                <label for="equipe" class="text-sm font-medium text-foreground">Rechercher une équipe</label>
                                <Input
                                    type="text"
                                    id="equipe"
                                    v-model="filters.equipe"
                                    placeholder="Nom de l'équipe"
                                    class="w-full"
                                />
                            </div>

                            <!-- Tri -->
                            <div class="space-y-2">
                                <label for="sortBy" class="text-sm font-medium text-foreground">Trier par</label>
                                <Select v-model="filters.sortBy">
                                    <SelectTrigger>
                                        <SelectValue :placeholder="'Date'" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem value="dateEvenements">Date</SelectItem>
                                            <SelectItem value="prix">Prix</SelectItem>
                                            <SelectItem value="Disponiblilite">Disponibilité</SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                            </div>

                            <!-- Ordre de tri -->
                            <div class="space-y-2">
                                <label for="sortOrder" class="text-sm font-medium text-foreground">Ordre</label>
                                <Select v-model="filters.sortOrder">
                                    <SelectTrigger>
                                        <SelectValue :placeholder="'Croissant'" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem value="asc">Croissant</SelectItem>
                                            <SelectItem value="desc">Décroissant</SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="mt-6 flex justify-end space-x-4">
                            <Button variant="outline" @click="resetFilters">
                                Réinitialiser les filtres
                            </Button>
                            <Button @click="applyFilters">
                                Appliquer les filtres
                            </Button>
                        </div>
                    </div>
                </div>

                <!-- Liste des événements -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <Card
                        v-for="evenement in evenements.data"
                        :key="evenement.idEvenements"
                        class="overflow-hidden bg-gray-900 bg-opacity-90 shadow-md backdrop-blur-sm border-[#E4F1F1]/10"
                    >
                        <CardHeader>
                            <CardTitle class="text-[#E4F1F1]">{{ evenement.nom }}</CardTitle>
                            <CardDescription class="text-[#E4F1F1]/80">{{ formatDate(evenement.dateEvenements) }}</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-2">
                                <div class="inline-flex items-center rounded-md border border-[#E4F1F1]/20 px-2.5 py-0.5 text-xs font-semibold text-[#E4F1F1] transition-colors">
                                    {{ evenement.typeEvents }}
                                </div>
                                <p class="text-sm font-semibold text-[#E4F1F1]">
                                    Prix : {{ evenement.prix }}€
                                </p>
                                <p class="text-sm text-[#E4F1F1]/80">
                                    Places disponibles : {{ evenement.Disponiblilite }}
                                </p>
                                <p class="text-sm text-[#E4F1F1]/80">
                                    {{ evenement.equipeDomicile }} vs {{ evenement.equipeExterieur }}
                                </p>
                            </div>
                        </CardContent>
                        <CardFooter>
                            <Link
                                :href="route('evenements.show', evenement.idEvenements)"
                                class="w-full"
                            >
                                <Button variant="default" class="w-full bg-[#6F4786] hover:bg-[#2D0B47] text-[#E4F1F1]">
                                    Voir les détails
                                </Button>
                            </Link>
                        </CardFooter>
                    </Card>
                </div>

                <!-- Pagination -->
                <div v-if="evenements.last_page > 1" class="mt-6 flex justify-center">
                    <nav class="flex items-center space-x-1">
                        <button 
                            v-if="evenements.current_page > 1"
                            @click="changePage(evenements.current_page - 1)" 
                            class="inline-flex h-10 items-center justify-center rounded-md border border-[#E4F1F1]/10 bg-gray-900 bg-opacity-90 px-4 py-2 text-sm font-medium text-[#E4F1F1] transition-colors hover:bg-[#6F4786] focus:outline-none focus:ring-2 focus:ring-[#E4F1F1]/20 focus:ring-offset-2"
                        >
                            <span class="sr-only">Page précédente</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="m15 18-6-6 6-6"></path></svg>
                        </button>
                        
                        <button 
                            v-for="page in paginationRange" 
                            :key="page" 
                            @click="typeof page === 'number' ? changePage(page) : null"
                            :class="[
                                'inline-flex h-10 w-10 items-center justify-center rounded-md text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-[#E4F1F1]/20 focus:ring-offset-2',
                                page === evenements.current_page 
                                    ? 'bg-[#6F4786] text-[#E4F1F1]' 
                                    : 'border border-[#E4F1F1]/10 bg-gray-900 bg-opacity-90 text-[#E4F1F1] hover:bg-[#6F4786]',
                                typeof page !== 'number' ? 'pointer-events-none' : ''
                            ]"
                        >
                            {{ page }}
                        </button>
                        
                        <button 
                            v-if="evenements.current_page < evenements.last_page"
                            @click="changePage(evenements.current_page + 1)" 
                            class="inline-flex h-10 items-center justify-center rounded-md border border-[#E4F1F1]/10 bg-gray-900 bg-opacity-90 px-4 py-2 text-sm font-medium text-[#E4F1F1] transition-colors hover:bg-[#6F4786] focus:outline-none focus:ring-2 focus:ring-[#E4F1F1]/20 focus:ring-offset-2"
                        >
                            <span class="sr-only">Page suivante</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="m9 18 6-6-6-6"></path></svg>
                        </button>
                    </nav>
                </div>
            </div>
        </main>
        <Footer />
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { format } from 'date-fns';
import { fr } from 'date-fns/locale';
import Header from '@/components/Header.vue';
import Footer from '@/components/Footer.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { SliderRoot, SliderThumb, SliderTrack, SliderRange } from 'radix-vue';

interface Props {
  evenements: {
    data: Array<{
      idEvenements: number;
      nom: string;
      dateEvenements: string;
      typeEvents: string;
      prix: number;
      Disponiblilite: number;
      equipeDomicile: string;
      equipeExterieur: string;
    }>;
    current_page: number;
    last_page: number;
  };
  filters: {
    typeEvents?: string;
    date?: string;
    prix?: [number, number];
    disponibilite?: string;
    equipe?: string;
    periode?: string;
    sortBy?: string;
    sortOrder?: string;
  };
  typesEvenements: string[];
}

const props = defineProps<Props>();

const filters = ref({
  typeEvents: props.filters.typeEvents || '',
  date: props.filters.date || '',
  prix: props.filters.prix || [0, 300],
  disponibilite: props.filters.disponibilite || '',
  equipe: props.filters.equipe || '',
  periode: props.filters.periode || '',
  sortBy: props.filters.sortBy || 'dateEvenements',
  sortOrder: props.filters.sortOrder || 'asc'
});

const formatDate = (date: string) => {
  return format(new Date(date), 'dd MMMM yyyy HH:mm', { locale: fr });
};

const applyFilters = () => {
  router.get(route('evenements.index'), {
    ...filters.value,
    page: 1
  }, {
    preserveState: true,
    preserveScroll: true,
    replace: true
  });
};

const resetFilters = () => {
  filters.value = {
    typeEvents: '',
    date: '',
    prix: [0, 300],
    disponibilite: '',
    equipe: '',
    periode: '',
    sortBy: 'dateEvenements',
    sortOrder: 'asc'
  };
  applyFilters();
};

const changePage = (page: number) => {
  router.get(route('evenements.index'), {
    ...filters.value,
    page
  }, {
    preserveState: true,
    preserveScroll: true,
    replace: true
  });
};

const paginationRange = computed(() => {
  const { current_page, last_page } = props.evenements;
  
  if (last_page <= 7) {
    return Array.from({ length: last_page }, (_, i) => i + 1);
  }
  
  if (current_page <= 3) {
    return [1, 2, 3, 4, 5, '...', last_page];
  }
  
  if (current_page >= last_page - 2) {
    return [1, '...', last_page - 4, last_page - 3, last_page - 2, last_page - 1, last_page];
  }
  
  return [1, '...', current_page - 1, current_page, current_page + 1, '...', last_page];
});
</script>