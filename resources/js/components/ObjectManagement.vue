<script setup lang="ts">
import { ref, watch, computed } from 'vue';
import { useForm, router, usePage } from '@inertiajs/vue3';
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
import type { PageProps } from '@/types';

interface ObjetConnecte {
    idObjetsConnectes: number;
    nom: string;
    idCategorie: number;
    descriptionObjetsConnectes: string;
    etat: 'Actif' | 'Inactif' | 'Maintenance';
    mode: 'Automatique' | 'Manuel';
    connectivite: string;
    niveauBatterie: number;
    derniereInteraction: string;
    puissance: number;
    consommationActuelle: number;
    dureeVieEstimee: number;
    dateInstallation: string;
    derniereMaintenance: string;
    idZone: number;
    categorie?: {
        nom: string;
    };
    zone?: {
        nom: string;
    };
}

interface Categorie {
    idCategoriesObjets: number;
    nom: string;
}

interface Zone {
    idZonesStade: number;
    nom: string;
}

const props = defineProps<{
    objets: ObjetConnecte[];
    categories: Categorie[];
    zones: Zone[];
}>();

const { toast } = useToast();
const localObjets = ref<ObjetConnecte[]>([]);
const selectedObjet = ref<ObjetConnecte | null>(null);
const showObjectForm = ref(false);
const search = ref('');
const isAddDialogOpen = ref(false);
const isEditDialogOpen = ref(false);

const page = usePage<PageProps>();
const isAuthorized = computed(() => {
    const auth = page.props.auth as { user: { typeMembre: string } };
    return ['Personnel technique', 'Sécurité', 'Administratif'].includes(auth.user.typeMembre);
});

// Mettre à jour les objets locaux quand les props changent
watch(() => props.objets, (newObjets) => {
    localObjets.value = [...newObjets];
}, { immediate: true });

const form = useForm({
    nom: '',
    idCategorie: '',
    descriptionObjetsConnectes: '',
    etat: 'Actif',
    mode: 'Automatique',
    connectivite: '',
    niveauBatterie: '',
    puissance: '',
    consommationActuelle: '',
    dureeVieEstimee: '',
    dateInstallation: '',
    derniereMaintenance: '',
    derniereInteraction: new Date().toISOString().split('T')[0],
    idZone: ''
});

const filteredObjets = computed(() => {
    if (!search.value) return props.objets;
    const searchLower = search.value.toLowerCase();
    return props.objets.filter(objet => 
        objet.nom.toLowerCase().includes(searchLower) ||
        objet.descriptionObjetsConnectes.toLowerCase().includes(searchLower) ||
        objet.connectivite.toLowerCase().includes(searchLower) ||
        objet.categorie?.nom.toLowerCase().includes(searchLower) ||
        objet.zone?.nom.toLowerCase().includes(searchLower)
    );
});

const handleSearch = () => {
    router.get(route('objets-connectes.index'), { search: search.value }, {
        preserveState: true,
        preserveScroll: true
    });
};

const createObject = () => {
    form.post('/objets-connectes', {
        onSuccess: () => {
            showObjectForm.value = false;
            form.reset();
            router.visit('/dashboard/objets', { preserveScroll: true });
        },
        onError: () => {
            console.error('Erreur lors de la création de l\'objet');
        }
    });
};

const deleteObject = (objet: ObjetConnecte) => {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cet objet ?')) return;

    router.delete(`/objets-connectes/${objet.idObjetsConnectes}`, {
        onSuccess: () => {
            console.log('Objet supprimé avec succès');
            router.visit('/dashboard/objets', { preserveScroll: true });
        },
        onError: () => {
            console.error('Erreur lors de la suppression de l\'objet');
        }
    });
};

const updateObject = () => {
    if (!selectedObjet.value) return;

    form.put(`/objets-connectes/${selectedObjet.value.idObjetsConnectes}`, {
        onSuccess: () => {
            showObjectForm.value = false;
            form.reset();
            router.visit('/dashboard/objets', { preserveScroll: true });
        },
        onError: () => {
            console.error('Erreur lors de la mise à jour de l\'objet');
        }
    });
};

const editObject = (objet: ObjetConnecte) => {
    selectedObjet.value = objet;
    form.nom = objet.nom;
    form.idCategorie = objet.idCategorie.toString();
    form.descriptionObjetsConnectes = objet.descriptionObjetsConnectes || '';
    form.etat = objet.etat;
    form.mode = objet.mode;
    form.connectivite = objet.connectivite || '';
    form.niveauBatterie = objet.niveauBatterie.toString();
    form.puissance = objet.puissance.toString();
    form.consommationActuelle = objet.consommationActuelle.toString();
    form.dureeVieEstimee = objet.dureeVieEstimee.toString();
    form.dateInstallation = objet.dateInstallation ? new Date(objet.dateInstallation).toISOString().split('T')[0] : '';
    form.derniereMaintenance = objet.derniereMaintenance ? new Date(objet.derniereMaintenance).toISOString().split('T')[0] : '';
    form.derniereInteraction = objet.derniereInteraction ? new Date(objet.derniereInteraction).toISOString().split('T')[0] : '';
    form.idZone = objet.idZone.toString();
    showObjectForm.value = true;
};

const resetForm = () => {
    selectedObjet.value = null;
    form.reset();
    form.etat = 'Actif';
    form.mode = 'Automatique';
    form.niveauBatterie = '';
    form.derniereInteraction = new Date().toISOString().split('T')[0];
};

const formatDate = (dateString: string): string => {
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
};
</script>

<template>
    <div class="p-6 bg-gray-900 bg-opacity-90 shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-white tracking-[0.05em]">Gestion des Objets Connectés</h2>
            <div class="flex gap-4">
                <div class="relative">
                    <Input
                        v-model="search"
                        placeholder="Rechercher un objet..."
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
                <Button v-if="isAuthorized"
                    @click="showObjectForm = true"
                    class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300"
                >
                    Ajouter un objet
                </Button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            <div v-for="objet in filteredObjets" :key="objet.idObjetsConnectes" 
                class="relative p-4 rounded-lg border border-indigo-500/30 bg-indigo-900/30 hover:bg-indigo-800/40 hover:border-indigo-400/50 shadow-md backdrop-blur-sm transition-all duration-300">
                <!-- Icône en haut à droite -->
                <div class="absolute top-2 right-2">
                    <div class="w-10 h-10 rounded-full bg-indigo-800/50 flex items-center justify-center">
                        <svg v-if="objet.categorie?.nom.toLowerCase().includes('caméra')" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-camera text-white"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                        <svg v-else-if="objet.categorie?.nom.toLowerCase().includes('capteur')" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-activity text-white"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                        <svg v-else-if="objet.categorie?.nom.toLowerCase().includes('éclairage')" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lightbulb text-white"><line x1="9" y1="18" x2="15" y2="18"/><line x1="10" y1="22" x2="14" y2="22"/><path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1 .23 2.23 1.5 3.5A4.61 4.61 0 0 1 8.91 14"/></svg>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-device text-white"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12" y2="18"/></svg>
                    </div>
                </div>

                <!-- En-tête de la carte -->
                <div class="mb-4">
                    <h3 class="font-semibold text-white tracking-[0.05em] text-lg">{{ objet.nom }}</h3>
                    <p class="text-sm text-white/80 tracking-[0.05em] line-clamp-2">{{ objet.descriptionObjetsConnectes }}</p>
                </div>

                <!-- État et Mode -->
                <div class="flex gap-2 mb-4">
                    <Badge :class="{
                        'bg-green-500/20 text-green-400': objet.etat === 'Actif',
                        'bg-red-500/20 text-red-400': objet.etat === 'Inactif',
                        'bg-yellow-500/20 text-yellow-400': objet.etat === 'Maintenance'
                    }">
                        {{ objet.etat }}
                    </Badge>
                    <Badge class="bg-indigo-500/20 text-indigo-400">
                        {{ objet.mode }}
                    </Badge>
                </div>

                <!-- Informations principales -->
                <div class="space-y-2 text-sm">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-battery text-white/70"><rect x="2" y="7" width="16" height="10" rx="2" ry="2"/><line x1="22" y1="11" x2="22" y2="11"/></svg>
                        <span class="text-white/70">{{ objet.niveauBatterie }}%</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap text-white/70"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                        <span class="text-white/70">{{ objet.puissance }}W</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin text-white/70"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span class="text-white/70">{{ objet.zone?.nom || 'Zone non définie' }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="absolute bottom-4 right-4 flex gap-2">
                    <Button v-if="isAuthorized" variant="outline" size="sm" 
                        @click="editObject(objet)"
                        class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                    </Button>
                    <Button v-if="isAuthorized" variant="destructive" size="sm" 
                        @click="deleteObject(objet)"
                        class="bg-red-900/30 hover:bg-red-800/40 border border-red-500/30 hover:border-red-400/50 text-white hover:text-red-300">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                    </Button>
                </div>
            </div>
        </div>

        <!-- Modal Formulaire Objet -->
        <Dialog v-model:open="showObjectForm">
            <DialogContent class="sm:max-w-[500px] bg-indigo-900/90 border border-indigo-500/30 text-white">
                <DialogHeader>
                    <DialogTitle class="text-white tracking-[0.05em]">
                        {{ selectedObjet ? 'Modifier' : 'Ajouter' }} un objet connecté
                    </DialogTitle>
                </DialogHeader>
                <Form @submit="selectedObjet ? updateObject() : createObject()" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Nom -->
                        <FormField name="nom" class="col-span-2">
                            <FormLabel>Nom<InputError :message="form.errors.nom" /></FormLabel>
                            <FormControl>
                                <Input v-model="form.nom" required placeholder="Nom de l'objet" />
                            </FormControl>
                        </FormField>

                        <!-- Catégorie -->
                        <FormField name="idCategorie" class="col-span-2">
                            <FormLabel>Catégorie<InputError :message="form.errors.idCategorie" /></FormLabel>
                            <Select v-model="form.idCategorie" required>
                                <SelectTrigger>
                                    <SelectValue placeholder="Sélectionner une catégorie" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="categorie in categories" 
                                            :key="categorie.idCategoriesObjets" 
                                            :value="categorie.idCategoriesObjets.toString()">
                                            {{ categorie.nom }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </FormField>

                        <!-- Description -->
                        <FormField name="descriptionObjetsConnectes" class="col-span-2">
                            <FormLabel>Description<InputError :message="form.errors.descriptionObjetsConnectes" /></FormLabel>
                            <FormControl>
                                <Input v-model="form.descriptionObjetsConnectes" required placeholder="Description de l'objet" />
                            </FormControl>
                        </FormField>

                        <!-- État -->
                        <FormField name="etat">
                            <FormLabel>État<InputError :message="form.errors.etat" /></FormLabel>
                            <Select v-model="form.etat" required>
                                <SelectTrigger>
                                    <SelectValue placeholder="Sélectionner un état" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="Actif">Actif</SelectItem>
                                        <SelectItem value="Inactif">Inactif</SelectItem>
                                        <SelectItem value="Maintenance">Maintenance</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </FormField>

                        <!-- Mode -->
                        <FormField name="mode">
                            <FormLabel>Mode<InputError :message="form.errors.mode" /></FormLabel>
                            <Select v-model="form.mode" required>
                                <SelectTrigger>
                                    <SelectValue placeholder="Sélectionner un mode" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="Automatique">Automatique</SelectItem>
                                        <SelectItem value="Manuel">Manuel</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </FormField>

                        <!-- Connectivité -->
                        <FormField name="connectivite" class="col-span-2">
                            <FormLabel>Connectivité<InputError :message="form.errors.connectivite" /></FormLabel>
                            <FormControl>
                                <Input v-model="form.connectivite" required placeholder="Type de connectivité" />
                            </FormControl>
                        </FormField>

                        <!-- Niveau de batterie -->
                        <FormField name="niveauBatterie">
                            <FormLabel>Niveau de batterie (%)<InputError :message="form.errors.niveauBatterie" /></FormLabel>
                            <FormControl>
                                <Input type="number" v-model="form.niveauBatterie" min="0" max="100" />
                            </FormControl>
                        </FormField>

                        <!-- Puissance -->
                        <FormField name="puissance">
                            <FormLabel>Puissance (W)<InputError :message="form.errors.puissance" /></FormLabel>
                            <FormControl>
                                <Input type="number" v-model="form.puissance" min="0" />
                            </FormControl>
                        </FormField>

                        <!-- Consommation actuelle -->
                        <FormField name="consommationActuelle">
                            <FormLabel>Consommation actuelle (W)<InputError :message="form.errors.consommationActuelle" /></FormLabel>
                            <FormControl>
                                <Input type="number" v-model="form.consommationActuelle" min="0" />
                            </FormControl>
                        </FormField>

                        <!-- Durée de vie estimée -->
                        <FormField name="dureeVieEstimee">
                            <FormLabel>Durée de vie estimée (h)<InputError :message="form.errors.dureeVieEstimee" /></FormLabel>
                            <FormControl>
                                <Input type="number" v-model="form.dureeVieEstimee" min="0" />
                            </FormControl>
                        </FormField>

                        <!-- Date d'installation -->
                        <FormField name="dateInstallation">
                            <FormLabel>Date d'installation<InputError :message="form.errors.dateInstallation" /></FormLabel>
                            <FormControl>
                                <Input type="date" v-model="form.dateInstallation" />
                            </FormControl>
                        </FormField>

                        <!-- Dernière maintenance -->
                        <FormField name="derniereMaintenance">
                            <FormLabel>Dernière maintenance<InputError :message="form.errors.derniereMaintenance" /></FormLabel>
                            <FormControl>
                                <Input type="date" v-model="form.derniereMaintenance" />
                            </FormControl>
                        </FormField>

                        <!-- Dernière interaction -->
                        <FormField name="derniereInteraction">
                            <FormLabel>Dernière interaction<InputError :message="form.errors.derniereInteraction" /></FormLabel>
                            <FormControl>
                                <Input type="date" v-model="form.derniereInteraction" />
                            </FormControl>
                        </FormField>

                        <!-- Zone -->
                        <FormField name="idZone" class="col-span-2">
                            <FormLabel>Zone<InputError :message="form.errors.idZone" /></FormLabel>
                            <Select v-model="form.idZone" required>
                                <SelectTrigger>
                                    <SelectValue placeholder="Sélectionner une zone" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="zone in zones" 
                                            :key="zone.idZonesStade" 
                                            :value="zone.idZonesStade.toString()">
                                            {{ zone.nom }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </FormField>
                    </div>

                    <div class="mt-4 flex justify-end gap-2">
                        <Button type="button" variant="outline" 
                            @click="resetForm(); showObjectForm = false">
                            Annuler
                        </Button>
                        <Button type="submit" :disabled="form.processing">
                            {{ selectedObjet ? 'Modifier' : 'Ajouter' }}
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
}
</style> 