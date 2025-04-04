<template>
    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- Filtres -->
        <div class="mb-6 overflow-hidden rounded-lg border border-border bg-card shadow-sm">
          <div class="p-6">
            <h3 class="mb-4 text-lg font-medium text-foreground">Filtres</h3>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
              <div class="space-y-2">
                <label for="typeEvents" class="text-sm font-medium text-foreground">Type d'événement</label>
                <select
                  id="typeEvents"
                  v-model="filters.typeEvents"
                  class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2"
                >
                  <option value="">Tous les types</option>
                  <option value="Football">Football</option>
                  <option value="Concert">Concert</option>
                </select>
              </div>

              <div class="space-y-2">
                <label for="date" class="text-sm font-medium text-foreground">Date</label>
                <Input
                  type="date"
                  id="date"
                  v-model="filters.date"
                  class="w-full"
                />
              </div>

              <div class="flex items-end">
                <Button @click="applyFilters" class="w-full">
                  Appliquer les filtres
                </Button>
              </div>
            </div>
          </div>
        </div>

        <!-- Liste des événements -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
          <Card
            v-for="evenement in evenements.data"
            :key="evenement.idEvenements"
            class="overflow-hidden"
          >
            <CardHeader>
              <CardTitle>{{ evenement.nom }}</CardTitle>
              <CardDescription>{{ formatDate(evenement.dateEvenements) }}</CardDescription>
            </CardHeader>
            <CardContent>
              <div class="space-y-2">
                <div class="inline-flex items-center rounded-md border border-input px-2.5 py-0.5 text-xs font-semibold text-foreground transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                  {{ evenement.typeEvents }}
                </div>
                <p class="text-sm font-semibold text-foreground">
                  Prix : {{ evenement.prix }}€
                </p>
                <p class="text-sm text-muted-foreground">
                  Places disponibles : {{ evenement.Disponiblilite }}
                </p>
                <p class="text-sm text-muted-foreground">
                  {{ evenement.equipeDomicile }} vs {{ evenement.equipeExterieur }}
                </p>
              </div>
            </CardContent>
            <CardFooter>
              <Link
                :href="route('evenements.show', evenement.idEvenements)"
                class="w-full"
              >
                <Button variant="default" class="w-full">
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
              class="inline-flex h-10 items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium text-foreground ring-offset-background transition-colors hover:bg-accent hover:text-accent-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:pointer-events-none disabled:opacity-50"
            >
              <span class="sr-only">Page précédente</span>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="m15 18-6-6 6-6"></path></svg>
            </button>
            
            <button 
              v-for="page in paginationRange" 
              :key="page" 
              @click="typeof page === 'number' ? changePage(page) : null"
              :class="[
                'inline-flex h-10 w-10 items-center justify-center rounded-md text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2',
                page === evenements.current_page 
                  ? 'bg-primary text-primary-foreground' 
                  : 'border border-input bg-background text-foreground hover:bg-accent hover:text-accent-foreground',
                typeof page !== 'number' ? 'pointer-events-none' : ''
              ]"
            >
              {{ page }}
            </button>
            
            <button 
              v-if="evenements.current_page < evenements.last_page"
              @click="changePage(evenements.current_page + 1)" 
              class="inline-flex h-10 items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium text-foreground ring-offset-background transition-colors hover:bg-accent hover:text-accent-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:pointer-events-none disabled:opacity-50"
            >
              <span class="sr-only">Page suivante</span>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="m9 18 6-6-6-6"></path></svg>
            </button>
          </nav>
        </div>
      </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import AuthLayout from '@/layouts/AppLayout.vue';
import { format } from 'date-fns';
import { fr } from 'date-fns/locale';

import { 
  Card, 
  CardHeader, 
  CardTitle, 
  CardDescription, 
  CardContent, 
  CardFooter 
} from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';

const props = defineProps({
  evenements: {
    type: Object,
    required: true
  },
  filters: {
    type: Object,
    required: true
  }
});

const filters = ref(props.filters);

const formatDate = (date: string) => {
  return format(new Date(date), 'dd MMMM yyyy HH:mm', { locale: fr });
};

const applyFilters = () => {
  // Implémenter la logique de filtrage
};

const changePage = (page: number) => {
  // Implémenter la logique de changement de page
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