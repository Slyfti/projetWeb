<template>
    <div class="min-h-screen flex flex-col bg-gradient-to-tr from-indigo-900 to-gray-900">
        <Header />
        <main class="flex-grow py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Titre de la page -->
                <div class="mb-8 text-center">
                    <h1 class="text-4xl font-bold text-white tracking-[0.05em] mb-2">Événements</h1>
                    <p class="text-white/80 tracking-[0.05em]">Découvrez tous les événements à venir</p>
                </div>

                <!-- Filtres -->
                <div class="mb-6 overflow-hidden rounded-lg border border-indigo-500/30 bg-gray-900 bg-opacity-90 shadow-md backdrop-blur-sm">
                    <div class="p-4">
                        <div class="flex items-center justify-between cursor-pointer" @click="showFilters = !showFilters">
                            <h3 class="text-lg font-medium text-white tracking-[0.05em]">Filtres</h3>
                            <svg 
                                xmlns="http://www.w3.org/2000/svg" 
                                width="24" 
                                height="24" 
                                viewBox="0 0 24 24" 
                                fill="none" 
                                stroke="currentColor" 
                                stroke-width="2" 
                                stroke-linecap="round" 
                                stroke-linejoin="round"
                                class="h-5 w-5 text-white transition-transform duration-300"
                                :class="{ 'rotate-180': showFilters }"
                            >
                                <path d="m6 9 6 6 6-6"/>
                            </svg>
                        </div>
                        <div 
                            class="overflow-hidden transition-all duration-300 ease-in-out"
                            :style="{ 
                                maxHeight: showFilters ? '2000px' : '0px',
                                opacity: showFilters ? '1' : '0',
                                transform: showFilters ? 'translateY(0)' : 'translateY(-10px)'
                            }"
                        >
                            <div class="mt-4 space-y-4">
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                                    <!-- Type d'événement -->
                                    <div class="space-y-2">
                                        <label for="typeEvents" class="text-sm font-medium text-white tracking-[0.05em]">Type d'événement</label>
                                        <Select v-model="filters.typeEvents">
                                            <SelectTrigger class="w-full bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300">
                                                <SelectValue class="text-white" :placeholder="'Tous les types'" />
                                            </SelectTrigger>
                                            <SelectContent class="bg-indigo-900/90 border-indigo-500/30 text-white">
                                                <SelectGroup>
                                                    <SelectItem value="" class="hover:bg-indigo-800/40 hover:text-cyan-300 text-white">Tous les types</SelectItem>
                                                    <SelectItem v-for="type in typesEvenements" :key="type" :value="type" class="hover:bg-indigo-800/40 hover:text-cyan-300 text-white">
                                                        {{ type }}
                                                    </SelectItem>
                                                </SelectGroup>
                                            </SelectContent>
                                        </Select>
                                    </div>

                                    <!-- Date -->
                                    <div class="space-y-2">
                                        <label for="date" class="text-sm font-medium text-white tracking-[0.05em]">Date spécifique</label>
                                        <Input
                                            type="date"
                                            id="date"
                                            v-model="filters.date"
                                            class="w-full bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300"
                                        />
                                    </div>

                                    <!-- Période -->
                                    <div class="space-y-2">
                                        <label for="periode" class="text-sm font-medium text-white tracking-[0.05em]">Période</label>
                                        <Select v-model="filters.periode">
                                            <SelectTrigger class="w-full bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300">
                                                <SelectValue class="text-white" :placeholder="'Toutes les périodes'" />
                                            </SelectTrigger>
                                            <SelectContent class="bg-indigo-900/90 border-indigo-500/30 text-white">
                                                <SelectGroup>
                                                    <SelectItem value="" class="hover:bg-indigo-800/40 hover:text-cyan-300 text-white">Toutes les périodes</SelectItem>
                                                    <SelectItem value="aujourd_hui" class="hover:bg-indigo-800/40 hover:text-cyan-300 text-white">Aujourd'hui</SelectItem>
                                                    <SelectItem value="cette_semaine" class="hover:bg-indigo-800/40 hover:text-cyan-300 text-white">Cette semaine</SelectItem>
                                                    <SelectItem value="ce_mois" class="hover:bg-indigo-800/40 hover:text-cyan-300 text-white">Ce mois</SelectItem>
                                                    <SelectItem value="futur" class="hover:bg-indigo-800/40 hover:text-cyan-300 text-white">Événements à venir</SelectItem>
                                                </SelectGroup>
                                            </SelectContent>
                                        </Select>
                                    </div>

                                    <!-- Prix -->
                                    <div class="space-y-2 col-span-2">
                                        <label class="text-sm font-medium text-white tracking-[0.05em]">Prix (€)</label>
                                        <div class="px-2">
                                            <SliderRoot
                                                v-model="filters.prix"
                                                :default-value="[0, 300]"
                                                :min="0"
                                                :max="300"
                                                :step="5"
                                                class="relative flex w-full touch-none select-none items-center"
                                            >
                                                <SliderTrack class="relative h-2 w-full grow overflow-hidden rounded-full bg-indigo-900/30">
                                                    <SliderRange class="absolute h-full bg-cyan-300" />
                                                </SliderTrack>
                                                <SliderThumb class="block h-5 w-5 rounded-full border-2 border-indigo-500/30 bg-indigo-900 text-white ring-offset-background transition-colors hover:bg-indigo-800 hover:text-cyan-300 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/30 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50" />
                                                <SliderThumb class="block h-5 w-5 rounded-full border-2 border-indigo-500/30 bg-indigo-900 text-white ring-offset-background transition-colors hover:bg-indigo-800 hover:text-cyan-300 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/30 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50" />
                                            </SliderRoot>
                                            <div class="mt-2 flex justify-between text-sm text-white/80">
                                                <span>{{ filters.prix[0] }}€</span>
                                                <span>{{ filters.prix[1] }}€</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Disponibilité -->
                                    <div class="space-y-2">
                                        <label for="disponibilite" class="text-sm font-medium text-white tracking-[0.05em]">Disponibilité</label>
                                        <Select v-model="filters.disponibilite">
                                            <SelectTrigger class="w-full bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300">
                                                <SelectValue class="text-white" :placeholder="'Tous'" />
                                            </SelectTrigger>
                                            <SelectContent class="bg-indigo-900/90 border-indigo-500/30 text-white">
                                                <SelectGroup>
                                                    <SelectItem value="" class="hover:bg-indigo-800/40 hover:text-cyan-300 text-white">Tous</SelectItem>
                                                    <SelectItem value="disponible" class="hover:bg-indigo-800/40 hover:text-cyan-300 text-white">Places disponibles</SelectItem>
                                                    <SelectItem value="complet" class="hover:bg-indigo-800/40 hover:text-cyan-300 text-white">Complet</SelectItem>
                                                </SelectGroup>
                                            </SelectContent>
                                        </Select>
                                    </div>

                                    <!-- Recherche équipe -->
                                    <div class="space-y-2">
                                        <label for="equipe" class="text-sm font-medium text-white tracking-[0.05em]">Rechercher une équipe</label>
                                        <Input
                                            type="text"
                                            id="equipe"
                                            v-model="filters.equipe"
                                            placeholder="Nom de l'équipe"
                                            class="w-full bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300"
                                        />
                                    </div>

                                    <!-- Tri -->
                                    <div class="space-y-2">
                                        <label for="sortBy" class="text-sm font-medium text-white tracking-[0.05em]">Trier par</label>
                                        <Select v-model="filters.sortBy">
                                            <SelectTrigger class="w-full bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300">
                                                <SelectValue class="text-white" :placeholder="'Date'" />
                                            </SelectTrigger>
                                            <SelectContent class="bg-indigo-900/90 border-indigo-500/30 text-white">
                                                <SelectGroup>
                                                    <SelectItem value="dateEvenements" class="hover:bg-indigo-800/40 hover:text-cyan-300 text-white">Date</SelectItem>
                                                    <SelectItem value="prix" class="hover:bg-indigo-800/40 hover:text-cyan-300 text-white">Prix</SelectItem>
                                                    <SelectItem value="Disponiblilite" class="hover:bg-indigo-800/40 hover:text-cyan-300 text-white">Disponibilité</SelectItem>
                                                </SelectGroup>
                                            </SelectContent>
                                        </Select>
                                    </div>

                                    <!-- Ordre de tri -->
                                    <div class="space-y-2">
                                        <label for="sortOrder" class="text-sm font-medium text-white tracking-[0.05em]">Ordre</label>
                                        <Select v-model="filters.sortOrder">
                                            <SelectTrigger class="w-full bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300">
                                                <SelectValue class="text-white" :placeholder="'Croissant'" />
                                            </SelectTrigger>
                                            <SelectContent class="bg-indigo-900/90 border-indigo-500/30 text-white">
                                                <SelectGroup>
                                                    <SelectItem value="asc" class="hover:bg-indigo-800/40 hover:text-cyan-300 text-white">Croissant</SelectItem>
                                                    <SelectItem value="desc" class="hover:bg-indigo-800/40 hover:text-cyan-300 text-white">Décroissant</SelectItem>
                                                </SelectGroup>
                                            </SelectContent>
                                        </Select>
                                    </div>
                                </div>

                                <!-- Boutons d'action -->
                                <div class="flex justify-end space-x-4">
                                    <Button variant="outline" @click="resetFilters" class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300">
                                        Réinitialiser les filtres
                                    </Button>
                                    <Button @click="applyFilters" class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300">
                                        Appliquer les filtres
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liste des événements -->
                <div class="relative px-12">
                    <div class="flex overflow-x-auto pb-4 space-x-4 snap-x snap-mandatory scrollbar-hide [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
                        <Card
                            v-for="evenement in evenements.data"
                            :key="evenement.idEvenements"
                            class="flex-none w-80 snap-center overflow-hidden bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 shadow-md backdrop-blur-sm transition-all duration-300 flex flex-col"
                        >
                            <div class="relative h-48 w-full">
                                <img 
                                    :src="StadeImg" 
                                    :alt="evenement.nom"
                                    class="w-full h-full object-cover"
                                />
                                <div class="absolute inset-0 bg-gradient-to-t from-indigo-900/90 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-4">
                                    <h3 class="text-white text-lg font-bold tracking-[0.05em]">{{ evenement.nom }}</h3>
                                    <p class="text-white/80 text-sm tracking-[0.05em]">{{ formatDate(evenement.dateEvenements) }}</p>
                                </div>
                            </div>
                            <CardContent class="flex-grow">
                                <div class="space-y-2 pt-4">
                                    <div class="inline-flex items-center rounded-md border border-indigo-500/30 px-2.5 py-0.5 text-xs font-semibold text-white transition-colors hover:bg-indigo-800/40 hover:text-cyan-300">
                                        {{ evenement.typeEvents }}
                                    </div>
                                    <p class="text-sm font-semibold text-white tracking-[0.05em]">
                                        Prix : {{ evenement.prix }}€
                                    </p>
                                    <p class="text-sm text-white/80 tracking-[0.05em]">
                                        Places disponibles : {{ evenement.Disponiblilite }}
                                    </p>
                                    <p class="text-sm text-white/80 tracking-[0.05em]">
                                        {{ evenement.equipeDomicile }} vs {{ evenement.equipeExterieur }}
                                    </p>
                                </div>
                            </CardContent>
                            <CardFooter class="mt-auto">
                                <Link
                                    :href="route('evenements.show', evenement.idEvenements)"
                                    class="w-full"
                                >
                                    <Button variant="default" class="w-full bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300">
                                        Voir les détails
                                    </Button>
                                </Link>
                            </CardFooter>
                        </Card>
                    </div>
                    <!-- Flèches de défilement -->
                    <button 
                        @click="scrollCards('left')"
                        class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-1/2 bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300 p-2 rounded-full shadow-lg transition-all duration-300"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
                            <path d="m15 18-6-6 6-6"/>
                        </svg>
                    </button>
                    <button 
                        @click="scrollCards('right')"
                        class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/2 bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300 p-2 rounded-full shadow-lg transition-all duration-300"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
                            <path d="m9 18 6-6-6-6"/>
                        </svg>
                    </button>
                    <!-- Indicateurs de défilement -->
                    <div class="flex justify-center mt-4 space-x-2">
                        <button 
                            v-for="(_, index) in evenements.data" 
                            :key="index"
                            class="w-2 h-2 rounded-full bg-indigo-500/30 hover:bg-indigo-400/50 transition-colors"
                            :class="{ 'bg-cyan-300': currentCard === index }"
                            @click="scrollToCard(index)"
                        ></button>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="evenements.last_page > 1" class="mt-6 flex justify-center">
                    <nav class="flex items-center space-x-1">
                        <button 
                            v-if="evenements.current_page > 1"
                            @click="changePage(evenements.current_page - 1)" 
                            class="inline-flex h-10 items-center justify-center rounded-md border border-indigo-500/30 bg-indigo-900/30 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-800/40 hover:text-cyan-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:ring-offset-2"
                        >
                            <span class="sr-only">Page précédente</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="m15 18-6-6 6-6"></path></svg>
                        </button>
                        
                        <button 
                            v-for="page in paginationRange" 
                            :key="page" 
                            @click="typeof page === 'number' ? changePage(page) : null"
                            :class="[
                                'inline-flex h-10 w-10 items-center justify-center rounded-md text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:ring-offset-2',
                                page === evenements.current_page 
                                    ? 'bg-indigo-800/40 text-cyan-300' 
                                    : 'border border-indigo-500/30 bg-indigo-900/30 text-white hover:bg-indigo-800/40 hover:text-cyan-300',
                                typeof page !== 'number' ? 'pointer-events-none' : ''
                            ]"
                        >
                            {{ page }}
                        </button>
                        
                        <button 
                            v-if="evenements.current_page < evenements.last_page"
                            @click="changePage(evenements.current_page + 1)" 
                            class="inline-flex h-10 items-center justify-center rounded-md border border-indigo-500/30 bg-indigo-900/30 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-800/40 hover:text-cyan-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:ring-offset-2"
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
import { ref, computed, onMounted } from 'vue';
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
import StadeImg from '@/../img/stade_neon.png';

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
      image?: string;
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

const showFilters = ref(false);
const currentCard = ref(0);

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

const scrollToCard = (index: number) => {
  const container = document.querySelector('.flex.overflow-x-auto');
  const cards = container?.querySelectorAll('.w-80');
  if (container && cards) {
    const card = cards[index] as HTMLElement;
    container.scrollTo({
      left: card.offsetLeft,
      behavior: 'smooth'
    });
    currentCard.value = index;
  }
};

const scrollCards = (direction: 'left' | 'right') => {
    const container = document.querySelector('.flex.overflow-x-auto');
    if (container) {
        const scrollAmount = direction === 'left' ? -320 : 320; // Largeur d'une carte
        container.scrollBy({
            left: scrollAmount,
            behavior: 'smooth'
        });
    }
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

// Observer pour détecter la carte visible
onMounted(() => {
  const container = document.querySelector('.flex.overflow-x-auto');
  const cards = container?.querySelectorAll('.w-80');
  
  if (container && cards) {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const index = Array.from(cards).indexOf(entry.target);
            currentCard.value = index;
          }
        });
      },
      {
        root: container,
        threshold: 0.5
      }
    );

    cards.forEach(card => observer.observe(card));
  }
});
</script>