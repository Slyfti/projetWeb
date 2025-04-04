<template>
    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- Filtres -->
        <div class="mb-6 overflow-hidden rounded-lg border border-border bg-card shadow-sm">
          <div class="p-6">
            <h3 class="mb-4 text-lg font-medium text-foreground">Filtres</h3>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
              <div class="space-y-2">
                <label for="sport_type" class="text-sm font-medium text-foreground">Type de sport</label>
                <select
                  id="sport_type"
                  v-model="filters.sport_type"
                  class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2"
                >
                  <option value="">Tous les sports</option>
                  <option value="football">Football</option>
                  <option value="basketball">Basketball</option>
                  <option value="tennis">Tennis</option>
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
            v-for="event in events.data"
            :key="event.id"
            class="overflow-hidden"
          >
            <CardHeader>
              <CardTitle>{{ event.title }}</CardTitle>
              <CardDescription>{{ formatDate(event.start_date) }}</CardDescription>
            </CardHeader>
            <CardContent>
              <div class="space-y-2">
                <p class="text-sm text-muted-foreground">{{ event.location }}</p>
                <div class="inline-flex items-center rounded-md border border-input px-2.5 py-0.5 text-xs font-semibold text-foreground transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                  {{ event.sport_type }}
                </div>
                <p class="text-sm font-semibold text-foreground">
                  À partir de {{ event.base_price }}€
                </p>
              </div>
            </CardContent>
            <CardFooter>
              <Link
                :href="route('events.show', event.id)"
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
        <div v-if="events.last_page > 1" class="mt-6 flex justify-center">
          <nav class="flex items-center space-x-1">
            <button 
              v-if="events.current_page > 1"
              @click="changePage(events.current_page - 1)" 
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
                page === events.current_page 
                  ? 'bg-primary text-primary-foreground' 
                  : 'border border-input bg-background text-foreground hover:bg-accent hover:text-accent-foreground',
                typeof page !== 'number' ? 'pointer-events-none' : ''
              ]"
            >
              {{ page }}
            </button>
            
            <button 
              v-if="events.current_page < events.last_page"
              @click="changePage(events.current_page + 1)" 
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

<script setup lang = "ts">
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import AuthLayout from '@/layouts/AppLayout.vue';
import { format } from 'date-fns';
import { fr } from 'date-fns/locale';

// Import shadcn components that you have
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
  events: {
    type: Object,
    required: true
  },
  filters: {
    type: Object,
    required: true
  }
});

const filters = ref(props.filters);

const formatDate = (date) => {
  return format(new Date(date), 'dd MMMM yyyy HH:mm', { locale: fr });
};

const applyFilters = () => {
  // Implémenter la logique de filtrage
};

const changePage = (page) => {
  // Implémenter la logique de changement de page
};

// Calculate pagination range with ellipsis for large page counts
const paginationRange = computed(() => {
  const { current_page, last_page } = props.events;
  
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