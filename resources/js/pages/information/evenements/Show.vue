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
                    <p class="mt-1 text-foreground">{{ formatDate(evenement.dateEvenements) }}</p>
                  </div>
                  <div>
                    <p class="text-sm font-medium text-muted-foreground">Type d'événement</p>
                    <p class="mt-1 text-foreground">{{ evenement.typeEvents }}</p>
                  </div>
                  <div>
                    <p class="text-sm font-medium text-muted-foreground">Équipes</p>
                    <p class="mt-1 text-foreground">{{ evenement.equipeDomicile }} vs {{ evenement.equipeExterieur }}</p>
                  </div>
                  <div>
                    <p class="text-sm font-medium text-muted-foreground">Places disponibles</p>
                    <p class="mt-1 text-foreground">{{ evenement.Disponiblilite }}</p>
                  </div>
                  <div>
                    <p class="text-sm font-medium text-muted-foreground">Prix</p>
                    <p class="mt-1 text-foreground">{{ evenement.prix }}€</p>
                  </div>
                </div>
              </div>

              <div>
                <h3 class="mb-4 text-lg font-medium text-foreground">Description</h3>
                <p class="text-muted-foreground">{{ evenement.descriptionEvenements }}</p>
              </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4">
              <Link :href="route('evenements.index')">
                <Button variant="outline">Retour à la liste</Button>
              </Link>
              <Button>Réserver</Button>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
</template>

<script setup lang="ts">
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

const props = defineProps({
  evenement: {
    type: Object,
    required: true
  }
});

const formatDate = (date: string) => {
  return format(new Date(date), 'dd MMMM yyyy HH:mm', { locale: fr });
};
</script> 