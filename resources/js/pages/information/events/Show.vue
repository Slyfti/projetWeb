<template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <Card class="overflow-hidden">
          <CardContent class="p-6">
            <!-- Informations principales -->
            <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2">
              <div>
                <h3 class="mb-4 text-lg font-medium text-foreground">Détails de l'événement</h3>
                <div class="space-y-4">
                  <div>
                    <p class="text-sm font-medium text-muted-foreground">Date et heure</p>
                    <p class="mt-1 text-foreground">{{ formatDate(event.start_date) }}</p>
                  </div>
                  <div>
                    <p class="text-sm font-medium text-muted-foreground">Lieu</p>
                    <p class="mt-1 text-foreground">{{ event.location }}</p>
                  </div>
                  <div>
                    <p class="text-sm font-medium text-muted-foreground">Type de sport</p>
                    <p class="mt-1 text-foreground">{{ event.sport_type }}</p>
                  </div>
                  <div>
                    <p class="text-sm font-medium text-muted-foreground">Capacité maximale</p>
                    <p class="mt-1 text-foreground">{{ event.max_spectators }} spectateurs</p>
                  </div>
                </div>
              </div>

              <div>
                <h3 class="mb-4 text-lg font-medium text-foreground">Description</h3>
                <p class="text-muted-foreground">{{ event.description }}</p>
              </div>
            </div>

            <!-- Billets disponibles -->
            <div class="mb-8">
              <h3 class="mb-4 text-lg font-medium text-foreground">Billets disponibles</h3>
              <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <Card
                  v-for="ticket in event.tickets"
                  :key="ticket.id"
                  class="border border-border"
                >
                  <CardHeader>
                    <CardTitle class="text-foreground">{{ ticket.ticket_type }}</CardTitle>
                    <CardDescription class="text-2xl font-bold text-primary">
                      {{ ticket.price }}€
                    </CardDescription>
                  </CardHeader>
                  <CardContent>
                    <p class="mb-2 text-sm text-muted-foreground">
                      Places disponibles : {{ ticket.quantity_available }}
                    </p>
                    <ul class="mb-4 text-sm text-muted-foreground">
                      <li 
                        v-for="(benefit, index) in ticket.benefits" 
                        :key="index" 
                        class="mb-1 flex items-start"
                      >
                        <span class="mr-2 text-primary">•</span>
                        <span>{{ benefit }}</span>
                      </li>
                    </ul>
                  </CardContent>
                  <CardFooter>
                    <Button class="w-full">
                      Réserver
                    </Button>
                  </CardFooter>
                </Card>
              </div>
            </div>

    
          </CardContent>
        </Card>
      </div>
    </div>
</template>

<script setup lang="js">
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

const props = defineProps({
  event: {
    type: Object,
    required: true
  }
});

const formatDate = (date) => {
  return format(new Date(date), 'dd MMMM yyyy HH:mm', { locale: fr });
};
</script>