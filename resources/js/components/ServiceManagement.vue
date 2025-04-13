<template>
    <div class="p-6 bg-gray-900 bg-opacity-90 shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-white tracking-[0.05em]">Gestion des Services</h2>
            <div class="flex gap-4">
                <div class="relative">
                    <Input
                        v-model="search"
                        placeholder="Rechercher un service..."
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
                    @click="showServiceForm = true"
                    class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300"
                >
                    Ajouter un service
                </Button>
            </div>
        </div>

        <div class="grid gap-4">
            <div v-for="service in filteredServices" :key="service.idservices" 
                class="p-4 rounded-lg border border-indigo-500/30 bg-indigo-900/30 hover:bg-indigo-800/40 hover:border-indigo-400/50 shadow-md backdrop-blur-sm transition-all duration-300">
                <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                    <div class="w-full">
                        <div class="flex items-center gap-4">
                            <img :src="service.image || '/img/stade_neon.png'" :alt="service.nom" class="h-12 w-12 rounded-full object-cover">
                            <div>
                                <h3 class="font-semibold text-white tracking-[0.05em]">{{ service.nom }}</h3>
                                <p class="text-sm text-white/80 tracking-[0.05em]">{{ service.categorie.nom }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-sm text-white/80">{{ service.descriptionServices }}</p>
                            <span :class="service.estActif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" 
                                class="mt-2 inline-block px-2 py-1 text-xs font-semibold rounded-full">
                                {{ service.estActif ? 'Actif' : 'Inactif' }}
                            </span>
                        </div>
                    </div>
                    <div class="flex gap-2 items-center whitespace-nowrap">
                        <Button variant="outline" size="sm" 
                            @click="editService(service)"
                            class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300">
                            Modifier
                        </Button>
                        <Button variant="destructive" size="sm" 
                            @click="deleteService(service)"
                            class="bg-red-900/30 hover:bg-red-800/40 border border-red-500/30 hover:border-red-400/50 text-white hover:text-red-300">
                            Supprimer
                        </Button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Formulaire Service -->
        <Dialog v-model:open="showServiceForm">
            <DialogContent class="sm:max-w-[500px] bg-indigo-900/90 border border-indigo-500/30 text-white">
                <DialogHeader>
                    <DialogTitle class="text-white tracking-[0.05em]">
                        {{ selectedService ? 'Modifier' : 'Ajouter' }} un service
                    </DialogTitle>
                </DialogHeader>
                <Form @submit="selectedService ? updateService() : createService()" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Nom -->
                        <FormField name="nom" class="col-span-2">
                            <FormLabel class="text-white/90">Nom<InputError :message="form.errors.nom" /></FormLabel>
                            <FormControl>
                                <Input v-model="form.nom" required placeholder="Nom du service" 
                                    class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300" />
                            </FormControl>
                        </FormField>

                        <!-- Catégorie -->
                        <FormField name="idCategorie" class="col-span-2">
                            <FormLabel class="text-white/90">Catégorie<InputError :message="form.errors.idCategorie" /></FormLabel>
                            <Select v-model="form.idCategorie" required>
                                <SelectTrigger class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300">
                                    <SelectValue placeholder="Sélectionner une catégorie" />
                                </SelectTrigger>
                                <SelectContent class="bg-indigo-900/90 border-indigo-500/30 text-white">
                                    <SelectGroup>
                                        <SelectItem v-for="categorie in categories" 
                                            :key="categorie.idCategoriesServices" 
                                            :value="categorie.idCategoriesServices"
                                            class="hover:bg-indigo-800/40 hover:text-cyan-300 text-white">
                                            {{ categorie.nom }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </FormField>

                        <!-- Description -->
                        <FormField name="descriptionServices" class="col-span-2">
                            <FormLabel class="text-white/90">Description<InputError :message="form.errors.descriptionServices" /></FormLabel>
                            <FormControl>
                                <textarea v-model="form.descriptionServices" rows="3" required
                                    class="w-full rounded-md bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300"
                                    placeholder="Description du service"></textarea>
                            </FormControl>
                        </FormField>

                        <!-- Image -->
                        <FormField name="image">
                            <FormLabel>Image<InputError :message="form.errors.image" /></FormLabel>
                            <FormControl>
                                <div class="flex flex-col gap-2">
                                    <div class="flex items-center gap-4">
                                        <img 
                                            :src="getImageUrl(form.image)" 
                                            alt="Image du service"
                                            class="w-16 h-16 object-contain border border-indigo-500/30 rounded-lg"
                                        />
                                        <div class="flex-1">
                                            <Input 
                                                type="file" 
                                                accept="image/*"
                                                @change="handleFileChange"
                                                class="bg-indigo-900/30 border border-indigo-500/30 text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-500/50 file:text-white hover:file:bg-indigo-400/50"
                                            />
                                            <div v-if="form.image" class="text-sm text-white/80 mt-2">
                                                {{ form.image.name }}
                                            </div>
                                            <div v-else class="text-sm text-white/80 mt-2">
                                                Image par défaut
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </FormControl>
                        </FormField>

                        <!-- Statut -->
                        <FormField name="estActif" class="col-span-2">
                            <FormLabel class="text-white/90">Statut<InputError :message="form.errors.estActif" /></FormLabel>
                            <FormControl>
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" v-model="form.estActif" 
                                        class="h-4 w-4 rounded border-indigo-500/30 bg-indigo-900/30 text-indigo-600 focus:ring-indigo-500" />
                                    <label class="text-sm text-white/80">Service actif</label>
                                </div>
                            </FormControl>
                        </FormField>
                    </div>

                    <div class="mt-4 flex justify-end gap-2">
                        <Button type="button" variant="outline" 
                            @click="resetForm(); showServiceForm = false"
                            class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300">
                            Annuler
                        </Button>
                        <Button type="submit" :disabled="form.processing"
                            class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300">
                            {{ selectedService ? 'Modifier' : 'Ajouter' }}
                        </Button>
                    </div>
                </Form>
            </DialogContent>
        </Dialog>
    </div>
</template>

<script setup lang="ts">
import { ref, watch, computed } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import {
    Form,
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from '@/components/ui/form';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { useToast } from '@/components/ui/toast/use-toast';
import InputError from '@/components/InputError.vue';

interface Service {
    idservices: number;
    nom: string;
    idCategorie: number;
    descriptionServices: string;
    image: string | null;
    estActif: boolean;
    categorie: {
        nom: string;
    };
}

interface Categorie {
    idCategoriesServices: number;
    nom: string;
}

const props = defineProps<{
    services: Service[];
    categories: Categorie[];
}>();

const { toast } = useToast();
const localServices = ref<Service[]>(props.services);
const selectedService = ref<Service | null>(null);
const showServiceForm = ref(false);
const search = ref('');

// Mettre à jour les services locaux quand les props changent
watch(() => props.services, (newServices) => {
    localServices.value = [...newServices];
}, { immediate: true });

const form = useForm({
    nom: '',
    idCategorie: '',
    descriptionServices: '',
    image: null as File | null,
    estActif: true as boolean
});

const filteredServices = computed(() => {
    if (!search.value) return localServices.value;
    const searchLower = search.value.toLowerCase();
    return localServices.value.filter(service => 
        service.nom.toLowerCase().includes(searchLower) ||
        service.categorie.nom.toLowerCase().includes(searchLower)
    );
});

const createService = () => {
    const formData = new FormData();
    Object.keys(form).forEach(key => {
        const value = form[key as keyof typeof form];
        if (value !== null) {
            if (typeof value === 'boolean') {
                formData.append(key, value.toString());
            } else if (value instanceof File) {
                formData.append(key, value);
            } else {
                formData.append(key, String(value));
            }
        }
    });

    form.post('/api/services', {
        onSuccess: () => {
            showServiceForm.value = false;
            form.reset();
            router.visit('/dashboard/services', { preserveScroll: true });
        },
        onError: () => {
            toast({
                title: "Erreur",
                description: "Une erreur est survenue lors de la création du service",
                variant: "destructive"
            });
        }
    });
};

const deleteService = (service: Service) => {
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce service ?')) return;

    router.delete(`/api/services/${service.idservices}`, {
        onSuccess: () => {
            toast({
                title: "Succès",
                description: "Le service a été supprimé avec succès",
            });
            router.visit('/dashboard/services', { preserveScroll: true });
        },
        onError: () => {
            toast({
                title: "Erreur",
                description: "Une erreur est survenue lors de la suppression du service",
                variant: "destructive"
            });
        }
    });
};

const updateService = () => {
    if (!selectedService.value) return;

    const formData = new FormData();
    
    // Ajouter les champs de base
    formData.append('nom', form.nom);
    formData.append('idCategorie', String(form.idCategorie));
    formData.append('descriptionServices', form.descriptionServices);
    formData.append('estActif', String(form.estActif));
    
    // Gestion spéciale pour l'image
    if (form.image instanceof File) {
        // Si une nouvelle image est sélectionnée, l'ajouter au formulaire
        formData.append('image', form.image);
    } else if (form.image === null) {
        // Si l'image est explicitement définie à null, garder null (utiliser l'image par défaut)
        formData.append('image', '');
    }
    // Si form.image est une chaîne, c'est une image existante - ne pas l'ajouter au formData
    // pour que le contrôleur sache qu'il faut garder l'image existante

    form.post(`/api/services/${selectedService.value.idservices}?_method=PUT`, {
        onSuccess: () => {
            showServiceForm.value = false;
            form.reset();
            router.visit('/dashboard/services', { preserveScroll: true });
        },
        onError: () => {
            toast({
                title: "Erreur",
                description: "Une erreur est survenue lors de la mise à jour du service",
                variant: "destructive"
            });
        }
    });
};

const editService = (service: Service) => {
    selectedService.value = service;
    form.nom = service.nom;
    form.idCategorie = String(service.idCategorie);
    form.descriptionServices = service.descriptionServices;
    form.estActif = service.estActif;
    
    // Stocker l'information de l'image existante
    form.image = service.image;
    
    showServiceForm.value = true;
};

const resetForm = () => {
    selectedService.value = null;
    form.reset();
    form.estActif = true;
};

const handleSearch = () => {
    router.get(route('dashboard.services'), { search: search.value }, {
        preserveState: true,
        preserveScroll: true
    });
};

const handleFileChange = (event: Event) => {
    const input = event.target as HTMLInputElement;
    if (input.files && input.files[0]) {
        form.image = input.files[0];
    } else {
        form.image = null;
    }
};

// Fonction pour obtenir l'URL de l'image
const getImageUrl = (image: File | null | string) => {
    if (image === null) {
        return '/img/stade_neon.png'; // Image par défaut
    }
    
    if (typeof image === 'string') {
        // Si c'est une chaîne, c'est une URL d'image existante
        // Vérifions si c'est un chemin complet ou relatif
        if (image.startsWith('http') || image.startsWith('/')) {
            return image;
        } else {
            return `/images/services/${image}`;
        }
    }
    
    // Si c'est un objet File, créer un URL temporaire
    return URL.createObjectURL(image);
};
</script>