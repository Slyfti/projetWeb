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
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip';

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
    lieu: 'Stade Astrophere, 123 Avenue des Champions, 75000 Paris',
    meteo: '',
    ligue: '',
    consignes_securite: 'Interdiction d\'apporter des objets dangereux. Les sacs seront fouillés à l\'entrée.\n\nObjets interdits\n\n    Bouteilles en verre\n    Objets contondants\n    Banderoles et drapeaux sur manche\n    Pétards et artifices\n\nRecommandations\n\n    Arrivez 30 minutes avant le début\n    Conservez votre billet sur vous\n    Respectez les zones assignées\n    En cas d\'urgence, contactez un steward',
    activites_autour: 'Boutique officielle, restaurants, bars, zone de restauration rapide.',
    logo_equipe_domicile: 'logos/default_home.png',
    logo_equipe_exterieur: 'logos/default_away.png',
    resultat: ''
});

// Liste des sports disponibles
const sports = [
    'Football',
    'Basketball',
    'Rugby',
    'Tennis',
    'Handball',
    'Volleyball',
    'Athlétisme',
    'Natation'
];

// Liste des ligues par sport
const liguesParSport = {
    'Football': [
        'Ligue 1',
        'Ligue 2',
        'Ligue des Champions',
        'Ligue Europa',
        'Coupe de France',
        'Coupe de la Ligue'
    ],
    'Basketball': [
        'LNB Pro A',
        'LNB Pro B',
        'EuroLeague',
        'Coupe de France'
    ],
    'Rugby': [
        'Top 14',
        'Pro D2',
        'Champions Cup',
        'Challenge Cup',
        'Tournoi des Six Nations'
    ],
    'Tennis': [
        'ATP Tour',
        'WTA Tour',
        'Grand Chelem',
        'Masters 1000'
    ],
    'Handball': [
        'Lidl Starligue',
        'ProLigue',
        'Ligue des Champions',
        'Coupe de France'
    ],
    'Volleyball': [
        'Ligue A',
        'Ligue B',
        'Coupe de France',
        'Champions League'
    ],
    'Athlétisme': [
        'Championnats du Monde',
        'Championnats d\'Europe',
        'Diamond League',
        'Championnats de France'
    ],
    'Natation': [
        'Championnats du Monde',
        'Championnats d\'Europe',
        'Championnats de France',
        'Coupe du Monde'
    ]
};

// Liste des ligues disponibles en fonction du sport sélectionné
const liguesDisponibles = computed(() => {
    return liguesParSport[form.typeEvents] || [];
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
    
    // Utiliser FormData pour pouvoir envoyer des fichiers
    const formData = new FormData();
    
    // Ajouter les champs de base
    formData.append('nom', form.nom);
    formData.append('dateEvenements', form.dateEvenements);
    formData.append('descriptionEvenements', form.descriptionEvenements);
    formData.append('typeEvents', form.typeEvents);
    formData.append('equipeDomicile', form.equipeDomicile);
    formData.append('equipeExterieur', form.equipeExterieur);
    formData.append('prix', form.prix);
    formData.append('Disponiblilite', form.Disponiblilite);
    formData.append('lieu', form.lieu);
    formData.append('meteo', form.meteo);
    formData.append('ligue', form.ligue);
    formData.append('consignes_securite', form.consignes_securite);
    formData.append('activites_autour', form.activites_autour);
    formData.append('resultat', form.resultat || '');
    
    // Gestion spéciale pour les images
    if (form.logo_equipe_domicile instanceof File) {
        formData.append('logo_equipe_domicile', form.logo_equipe_domicile);
    } 
    
    if (form.logo_equipe_exterieur instanceof File) {
        formData.append('logo_equipe_exterieur', form.logo_equipe_exterieur);
    }

    // Utiliser post avec _method=PUT pour permettre l'envoi de fichiers via la route PUT standard
    form.post(route('evenements.update', selectedEvenement.value.idEvenements) + '?_method=PUT', {
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
    form.dateEvenements = evenement.dateEvenements ? new Date(evenement.dateEvenements).toISOString().slice(0, 16) : '';
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

// Fonction pour générer une météo aléatoire basée sur la date
const genererMeteo = () => {
    if (!form.dateEvenements) return;

    const date = new Date(form.dateEvenements);
    const mois = date.getMonth();
    const heure = date.getHours();

    // Conditions météo basées sur la saison et l'heure
    const conditions = [
        'Ensoleillé',
        'Nuageux',
        'Pluvieux',
        'Orageux',
        'Brouillard'
    ];

    // Températures basées sur la saison
    let temperature;
    if (mois >= 2 && mois <= 4) { // Printemps
        temperature = Math.floor(Math.random() * 10) + 10; // 10-20°C
    } else if (mois >= 5 && mois <= 7) { // Été
        temperature = Math.floor(Math.random() * 15) + 20; // 20-35°C
    } else if (mois >= 8 && mois <= 10) { // Automne
        temperature = Math.floor(Math.random() * 10) + 10; // 10-20°C
    } else { // Hiver
        temperature = Math.floor(Math.random() * 10) + 0; // 0-10°C
    }

    // Sélection aléatoire de la condition météo
    const condition = conditions[Math.floor(Math.random() * conditions.length)];
    
    form.meteo = `${condition}, ${temperature}°C`;
};

// Fonction pour gérer le changement de fichier
const handleFileChange = (event: Event, field: 'logo_equipe_domicile' | 'logo_equipe_exterieur') => {
    const input = event.target as HTMLInputElement;
    if (input.files && input.files[0]) {
        form[field] = input.files[0];
    } else {
        form[field] = field === 'logo_equipe_domicile' ? 'logos/default_home.png' : 'logos/default_away.png';
    }
};

// Fonction pour obtenir l'URL de l'image
const getImageUrl = (image: string | File) => {
    if (image instanceof File) {
        return URL.createObjectURL(image);
    }
    return `/images/${image}`;
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
                                <textarea 
                                    v-model="form.descriptionEvenements" 
                                    class="min-h-[100px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                    required 
                                    placeholder="Description détaillée de l'événement"
                                />
                            </FormControl>
                        </FormField>

                        <!-- Type d'événement -->
                        <FormField name="typeEvents">
                            <FormLabel>Type<InputError :message="form.errors.typeEvents" /></FormLabel>
                            <FormControl>
                                <Select v-model="form.typeEvents" required>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Sélectionner un sport" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem v-for="sport in sports" :key="sport" :value="sport">
                                                {{ sport }}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                            </FormControl>
                        </FormField>

                        <!-- Ligue -->
                        <FormField name="ligue">
                            <FormLabel>Ligue<InputError :message="form.errors.ligue" /></FormLabel>
                            <FormControl>
                                <TooltipProvider>
                                    <Tooltip>
                                        <TooltipTrigger asChild>
                                            <div>
                                                <Select v-model="form.ligue" required :disabled="!form.typeEvents">
                                                    <SelectTrigger>
                                                        <SelectValue placeholder="Sélectionner une ligue" />
                                                    </SelectTrigger>
                                                    <SelectContent>
                                                        <SelectGroup>
                                                            <SelectItem v-for="ligue in liguesDisponibles" :key="ligue" :value="ligue">
                                                                {{ ligue }}
                                                            </SelectItem>
                                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                            </div>
                        </TooltipTrigger>
                        <TooltipContent>
                            <p>Veuillez d'abord sélectionner un sport</p>
                        </TooltipContent>
                    </Tooltip>
                </TooltipProvider>
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
                <Input v-model="form.lieu" required disabled class="bg-indigo-900/30 border border-indigo-500/30 text-white cursor-not-allowed" />
            </FormControl>
        </FormField>

        <!-- Météo -->
        <FormField name="meteo">
            <FormLabel>Météo<InputError :message="form.errors.meteo" /></FormLabel>
            <FormControl>
                <div class="flex gap-2">
                    <TooltipProvider>
                        <Tooltip>
                            <TooltipTrigger asChild>
                                <div>
                                    <Input 
                                        v-model="form.meteo" 
                                        placeholder="Conditions météo" 
                                        readonly 
                                        class="bg-indigo-900/30 border border-indigo-500/30 text-white cursor-not-allowed" 
                                    />
                                </div>
                            </TooltipTrigger>
                            <TooltipContent>
                                <p>La météo sera générée automatiquement en fonction de la date sélectionnée</p>
                            </TooltipContent>
                        </Tooltip>
                    </TooltipProvider>
                    <TooltipProvider>
                        <Tooltip>
                            <TooltipTrigger asChild>
                                <div>
                                    <Button 
                                        type="button" 
                                        variant="outline" 
                                        size="icon"
                                        :disabled="!form.dateEvenements"
                                        class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300"
                                        @click="genererMeteo"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-refresh-cw"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
                                    </Button>
                                </div>
                            </TooltipTrigger>
                            <TooltipContent>
                                <p>Veuillez d'abord sélectionner une date</p>
                            </TooltipContent>
                        </Tooltip>
                    </TooltipProvider>
                </div>
            </FormControl>
        </FormField>

        <!-- Consignes de sécurité -->
        <FormField name="consignes_securite" class="col-span-2">
            <FormLabel>Consignes de sécurité<InputError :message="form.errors.consignes_securite" /></FormLabel>
            <FormControl>
                <textarea 
                    v-model="form.consignes_securite" 
                    class="min-h-[100px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    placeholder="Interdiction d'apporter des objets dangereux. Les sacs seront fouillés à l'entrée."
                />
            </FormControl>
        </FormField>

        <!-- Activités autour -->
        <FormField name="activites_autour" class="col-span-2">
            <FormLabel>Activités autour<InputError :message="form.errors.activites_autour" /></FormLabel>
            <FormControl>
                <textarea 
                    v-model="form.activites_autour" 
                    class="min-h-[100px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    placeholder="Activités autour de l'événement"
                />
            </FormControl>
        </FormField>

        <!-- Logo équipe domicile -->
        <FormField name="logo_equipe_domicile">
            <FormLabel>Logo équipe domicile<InputError :message="form.errors.logo_equipe_domicile" /></FormLabel>
            <FormControl>
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-4">
                        <img 
                            :src="getImageUrl(form.logo_equipe_domicile)" 
                            alt="Logo équipe domicile"
                            class="w-16 h-16 object-contain border border-indigo-500/30 rounded-lg"
                        />
                        <div class="flex-1">
                            <Input 
                                type="file" 
                                accept="image/*"
                                @change="(e) => handleFileChange(e, 'logo_equipe_domicile')"
                                class="bg-indigo-900/30 border border-indigo-500/30 text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-500/50 file:text-white hover:file:bg-indigo-400/50"
                            />
                            <div v-if="form.logo_equipe_domicile && form.logo_equipe_domicile !== 'logos/default_home.png'" class="text-sm text-white/80 mt-2">
                                {{ form.logo_equipe_domicile.name }}
                            </div>
                            <div v-else class="text-sm text-white/80 mt-2">
                                Logo domicile par défaut
                            </div>
                        </div>
                    </div>
                </div>
            </FormControl>
        </FormField>

        <!-- Logo équipe extérieur -->
        <FormField name="logo_equipe_exterieur">
            <FormLabel>Logo équipe extérieur<InputError :message="form.errors.logo_equipe_exterieur" /></FormLabel>
            <FormControl>
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-4">
                        <img 
                            :src="getImageUrl(form.logo_equipe_exterieur)" 
                            alt="Logo équipe extérieur"
                            class="w-16 h-16 object-contain border border-indigo-500/30 rounded-lg"
                        />
                        <div class="flex-1">
                            <Input 
                                type="file" 
                                accept="image/*"
                                @change="(e) => handleFileChange(e, 'logo_equipe_exterieur')"
                                class="bg-indigo-900/30 border border-indigo-500/30 text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-500/50 file:text-white hover:file:bg-indigo-400/50"
                            />
                            <div v-if="form.logo_equipe_exterieur && form.logo_equipe_exterieur !== 'logos/default_away.png'" class="text-sm text-white/80 mt-2">
                                {{ form.logo_equipe_exterieur.name }}
                            </div>
                            <div v-else class="text-sm text-white/80 mt-2">
                                Logo extérieur par défaut
                            </div>
                        </div>
                    </div>
                </div>
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