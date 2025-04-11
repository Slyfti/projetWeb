<script setup lang="ts">
import { ref, watch, computed } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import {
    Form,
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import InputError from '@/components/InputError.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { useToast } from '@/components/ui/toast/use-toast';

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

const props = defineProps<{
    evenements: Evenement[];
}>();

const { toast } = useToast();
const localEvenements = ref<Evenement[]>([]);
const selectedEvenement = ref<Evenement | null>(null);
const showEventForm = ref(false);
const search = ref('');

// Mettre à jour les événements locaux quand les props changent
watch(() => props.evenements, (newEvenements) => {
    localEvenements.value = [...newEvenements];
}, { immediate: true });

const form = useForm({
    nom: '',
    dateEvenements: '',
    descriptionEvenements: '',
    typeEvents: '',
    equipeDomicile: '',
    equipeExterieur: '',
    prix: '',
    Disponiblilite: '',
    lieu: '',
    meteo: '',
    ligue: '',
    consignes_securite: '',
    activites_autour: '',
    logo_equipe_domicile: '',
    logo_equipe_exterieur: '',
    resultat: ''
});

const filteredEvenements = computed(() => {
    if (!search.value) return props.evenements;
    const searchLower = search.value.toLowerCase();
    return props.evenements.filter(evenement => 
        evenement.nom.toLowerCase().includes(searchLower) ||
        evenement.descriptionEvenements.toLowerCase().includes(searchLower) ||
        evenement.typeEvents.toLowerCase().includes(searchLower) ||
        evenement.equipeDomicile.toLowerCase().includes(searchLower) ||
        evenement.equipeExterieur.toLowerCase().includes(searchLower) ||
        evenement.lieu.toLowerCase().includes(searchLower) ||
        evenement.ligue.toLowerCase().includes(searchLower)
    );
});

const handleSearch = () => {
    router.get(route('evenements.index'), { search: search.value }, {
        preserveState: true,
        preserveScroll: true
    });
};

const createEvent = () => {
    form.post(route('evenements.store'), {
        onSuccess: () => {
            showEventForm.value = false;
            form.reset();
            toast({
                title: 'Succès',
                description: 'Événement créé avec succès',
                variant: 'default',
            });
            router.visit(route('evenements.admin'), { preserveScroll: true });
        },
        onError: () => {
            toast({
                title: 'Erreur',
                description: 'Une erreur est survenue lors de la création de l\'événement',
                variant: 'destructive',
            });
        }
    });
};

const deleteEvent = (evenement: Evenement) => {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')) return;

    router.delete(route('evenements.destroy', evenement.idEvenements), {
        onSuccess: () => {
            toast({
                title: 'Succès',
                description: 'Événement supprimé avec succès',
                variant: 'default',
            });
            router.visit(route('evenements.admin'), { preserveScroll: true });
        },
        onError: () => {
            toast({
                title: 'Erreur',
                description: 'Une erreur est survenue lors de la suppression de l\'événement',
                variant: 'destructive',
            });
        }
    });
};

const updateEvent = () => {
    if (!selectedEvenement.value) return;

    form.put(route('evenements.update', selectedEvenement.value.idEvenements), {
        onSuccess: () => {
            showEventForm.value = false;
            form.reset();
            toast({
                title: 'Succès',
                description: 'Événement modifié avec succès',
                variant: 'default',
            });
            router.visit(route('evenements.admin'), { preserveScroll: true });
        },
        onError: () => {
            toast({
                title: 'Erreur',
                description: 'Une erreur est survenue lors de la modification de l\'événement',
                variant: 'destructive',
            });
        }
    });
};

const editEvent = (evenement: Evenement) => {
    selectedEvenement.value = evenement;
    form.nom = evenement.nom;
    form.dateEvenements = evenement.dateEvenements ? new Date(evenement.dateEvenements).toISOString().split('T')[0] : '';
    form.descriptionEvenements = evenement.descriptionEvenements || '';
    form.typeEvents = evenement.typeEvents;
    form.equipeDomicile = evenement.equipeDomicile;
    form.equipeExterieur = evenement.equipeExterieur;
    form.prix = evenement.prix.toString();
    form.Disponiblilite = evenement.Disponiblilite.toString();
    form.lieu = evenement.lieu;
    form.meteo = evenement.meteo;
    form.ligue = evenement.ligue;
    form.consignes_securite = evenement.consignes_securite;
    form.activites_autour = evenement.activites_autour;
    form.logo_equipe_domicile = evenement.logo_equipe_domicile;
    form.logo_equipe_exterieur = evenement.logo_equipe_exterieur;
    form.resultat = evenement.resultat || '';
    showEventForm.value = true;
};

const resetForm = () => {
    selectedEvenement.value = null;
    form.reset();
};

const formatDate = (dateString: string): string => {
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>

<template>
    <div class="p-6 bg-gray-900 bg-opacity-90 shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-white tracking-[0.05em]">Gestion des Événements</h2>
            <div class="flex gap-4">
                <div class="relative">
                    <Input
                        v-model="search"
                        placeholder="Rechercher un événement..."
                        class="pr-10 bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300"
                        @keyup.enter="handleSearch"
                    />
                    <Button
                        variant="ghost"
                        size="icon"
                        class="absolute right-0 top-0 h-full px-3 text-white hover:text-cyan-300"
                        @click="handleSearch"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    </Button>
                </div>
                <Button 
                    @click="showEventForm = true"
                    class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300"
                >
                    Ajouter un événement
                </Button>
            </div>
        </div>

        <div class="grid gap-4">
            <div v-for="evenement in filteredEvenements" :key="evenement.idEvenements" 
                class="p-4 rounded-lg border border-indigo-500/30 bg-indigo-900/30 hover:bg-indigo-800/40 hover:border-indigo-400/50 shadow-md backdrop-blur-sm transition-all duration-300">
                <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                    <div class="w-full">
                        <div class="space-y-2">
                            <div>
                                <h3 class="font-semibold text-white tracking-[0.05em]">{{ evenement.nom }}</h3>
                                <p class="text-sm text-white/80 tracking-[0.05em]">{{ evenement.descriptionEvenements }}</p>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-8">
                                <div class="w-full sm:w-1/2">
                                    <h4 class="font-bold text-white tracking-[0.05em] mb-2">Informations générales</h4>
                                    <div class="space-y-2 text-sm">
                                        <div>
                                            <span class="font-medium text-white/90">Date : </span>
                                            <span class="text-white/80">{{ formatDate(evenement.dateEvenements) }}</span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-white/90">Lieu : </span>
                                            <span class="text-white/80">{{ evenement.lieu }}</span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-white/90">Type : </span>
                                            <span class="text-white/80">{{ evenement.typeEvents }}</span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-white/90">Ligue : </span>
                                            <span class="text-white/80">{{ evenement.ligue }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="w-full sm:w-1/2">
                                    <h4 class="font-bold text-white tracking-[0.05em] mb-2">Détails de l'événement</h4>
                                    <div class="space-y-2 text-sm">
                                        <div>
                                            <span class="font-medium text-white/90">Équipe à domicile : </span>
                                            <span class="text-white/80">{{ evenement.equipeDomicile }}</span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-white/90">Équipe à l'extérieur : </span>
                                            <span class="text-white/80">{{ evenement.equipeExterieur }}</span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-white/90">Prix : </span>
                                            <span class="text-white/80">{{ evenement.prix }}€</span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-white/90">Disponibilité : </span>
                                            <span class="text-white/80">{{ evenement.Disponiblilite }} places</span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-white/90">Météo : </span>
                                            <span class="text-white/80">{{ evenement.meteo }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2 items-center whitespace-nowrap">
                        <Button variant="outline" size="sm" 
                            @click="editEvent(evenement)"
                            class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300">
                            Modifier
                        </Button>
                        <Button variant="destructive" size="sm" 
                            @click="deleteEvent(evenement)"
                            class="bg-red-900/30 hover:bg-red-800/40 border border-red-500/30 hover:border-red-400/50 text-white hover:text-red-300">
                            Supprimer
                        </Button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Formulaire Événement -->
        <Dialog v-model:open="showEventForm">

            <DialogContent class="sm:max-w-[90vw] md:max-w-[600px] max-h-[90vh] overflow-y-auto bg-indigo-900/90 border border-indigo-500/30 text-white">
                <DialogHeader>
                    <DialogTitle class="text-white tracking-[0.05em]">
                        {{ selectedEvenement ? 'Modifier' : 'Ajouter' }} un événement
                    </DialogTitle>
                </DialogHeader>
                <Form @submit="selectedEvenement ? updateEvent() : createEvent()" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nom -->
                        <FormField name="nom" class="col-span-2">
                            <FormLabel>Nom<InputError :message="form.errors.nom" /></FormLabel>
                            <FormControl>
                                <Input v-model="form.nom" required placeholder="Nom de l'événement" />
                            </FormControl>
                        </FormField>

                        <!-- Date -->
                        <FormField name="dateEvenements" class="col-span-2">
                            <FormLabel>Date<InputError :message="form.errors.dateEvenements" /></FormLabel>
                            <FormControl>
                                <Input type="datetime-local" v-model="form.dateEvenements" required />
                            </FormControl>
                        </FormField>

                        <!-- Description -->
                        <FormField name="descriptionEvenements" class="col-span-2">
                            <FormLabel>Description<InputError :message="form.errors.descriptionEvenements" /></FormLabel>
                            <FormControl>
                                <Input v-model="form.descriptionEvenements" required placeholder="Description de l'événement" />
                            </FormControl>
                        </FormField>

                        <!-- Type d'événement -->
                        <FormField name="typeEvents">
                            <FormLabel>Type<InputError :message="form.errors.typeEvents" /></FormLabel>
                            <FormControl>
                                <Input v-model="form.typeEvents" required placeholder="Type d'événement" />
                            </FormControl>
                        </FormField>

                        <!-- Ligue -->
                        <FormField name="ligue">
                            <FormLabel>Ligue<InputError :message="form.errors.ligue" /></FormLabel>
                            <FormControl>
                                <Input v-model="form.ligue" required placeholder="Ligue" />
                            </FormControl>
                        </FormField>

                        <!-- Équipe à domicile -->
                        <FormField name="equipeDomicile">
                            <FormLabel>Équipe à domicile<InputError :message="form.errors.equipeDomicile" /></FormLabel>
                            <FormControl>
                                <Input v-model="form.equipeDomicile" required placeholder="Équipe à domicile" />
                            </FormControl>
                        </FormField>

                        <!-- Équipe à l'extérieur -->
                        <FormField name="equipeExterieur">
                            <FormLabel>Équipe à l'extérieur<InputError :message="form.errors.equipeExterieur" /></FormLabel>
                            <FormControl>
                                <Input v-model="form.equipeExterieur" required placeholder="Équipe à l'extérieur" />
                            </FormControl>
                        </FormField>

                        <!-- Prix -->
                        <FormField name="prix">
                            <FormLabel>Prix (€)<InputError :message="form.errors.prix" /></FormLabel>
                            <FormControl>
                                <Input type="number" v-model="form.prix" min="0" step="0.01" required />
                            </FormControl>
                        </FormField>

                        <!-- Disponibilité -->
                        <FormField name="Disponiblilite">
                            <FormLabel>Disponibilité<InputError :message="form.errors.Disponiblilite" /></FormLabel>
                            <FormControl>
                                <Input type="number" v-model="form.Disponiblilite" min="0" required />
                            </FormControl>
                        </FormField>

                        <!-- Lieu -->
                        <FormField name="lieu" class="col-span-2">
                            <FormLabel>Lieu<InputError :message="form.errors.lieu" /></FormLabel>
                            <FormControl>
                                <Input v-model="form.lieu" required placeholder="Lieu de l'événement" />
                            </FormControl>
                        </FormField>

                        <!-- Météo -->
                        <FormField name="meteo">
                            <FormLabel>Météo<InputError :message="form.errors.meteo" /></FormLabel>
                            <FormControl>
                                <Input v-model="form.meteo" placeholder="Conditions météo" />
                            </FormControl>
                        </FormField>

                        <!-- Consignes de sécurité -->
                        <FormField name="consignes_securite" class="col-span-2">
                            <FormLabel>Consignes de sécurité<InputError :message="form.errors.consignes_securite" /></FormLabel>
                            <FormControl>
                                <Input v-model="form.consignes_securite" placeholder="Consignes de sécurité" />
                            </FormControl>
                        </FormField>

                        <!-- Activités autour -->
                        <FormField name="activites_autour" class="col-span-2">
                            <FormLabel>Activités autour<InputError :message="form.errors.activites_autour" /></FormLabel>
                            <FormControl>
                                <Input v-model="form.activites_autour" placeholder="Activités autour de l'événement" />
                            </FormControl>
                        </FormField>

                        <!-- Logo équipe domicile -->
                        <FormField name="logo_equipe_domicile">
                            <FormLabel>Logo équipe domicile<InputError :message="form.errors.logo_equipe_domicile" /></FormLabel>
                            <FormControl>
                                <Input v-model="form.logo_equipe_domicile" placeholder="URL du logo" />
                            </FormControl>
                        </FormField>

                        <!-- Logo équipe extérieur -->
                        <FormField name="logo_equipe_exterieur">
                            <FormLabel>Logo équipe extérieur<InputError :message="form.errors.logo_equipe_exterieur" /></FormLabel>
                            <FormControl>
                                <Input v-model="form.logo_equipe_exterieur" placeholder="URL du logo" />
                            </FormControl>
                        </FormField>

                        <!-- Résultat -->
                        <FormField name="resultat" class="col-span-2">
                            <FormLabel>Résultat<InputError :message="form.errors.resultat" /></FormLabel>
                            <FormControl>
                                <Input v-model="form.resultat" placeholder="Résultat du match" />
                            </FormControl>
                        </FormField>
                    </div>

                    <div class="mt-4 flex justify-end gap-2">
                        <Button type="button" variant="outline" 
                            @click="resetForm(); showEventForm = false">
                            Annuler
                        </Button>
                        <Button type="submit" :disabled="form.processing">
                            {{ selectedEvenement ? 'Modifier' : 'Ajouter' }}
                        </Button>
                    </div>
                </Form>
            </DialogContent>
        </Dialog>
    </div>
</template>

<style scoped>
:deep(.select-trigger) {
    height: 2.25rem !important;
    padding-top: 0 !important;
    padding-bottom: 0 !important;
    font-size: 0.875rem !important;
}

/* Style pour le formulaire */
:deep(.dialog-content) {
    max-height: 90vh;
    overflow-y: auto;
    padding: 1.5rem;
}

:deep(.form-field) {
    margin-bottom: 1rem;
}

:deep(.form-label) {
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: 0.5rem;
    display: block;
}

:deep(.form-control) {
    width: 100%;
    padding: 0.5rem;
    border-radius: 0.375rem;
    border: 1px solid #e2e8f0;
    background-color: white;
}

:deep(.form-control:focus) {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 1px #3b82f6;
}

:deep(.form-message) {
    font-size: 0.75rem;
    color: #ef4444;
    margin-top: 0.25rem;
}
</style> 