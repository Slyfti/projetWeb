<script setup lang="ts">
import { ref, watch, computed } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { type User } from '@/types';
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
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { useToast } from '@/components/ui/toast/use-toast';

interface ConnexionLog {
    idConnexionsUtilisateurs: number;
    dateConnexion: string;
    pointsGagne: number;
}

interface PageProps {
    connexions: ConnexionLog[];
}

interface ApiResponse {
    users: User[];
}

const props = defineProps<{
    users: User[]
}>();

const { toast } = useToast();
const localUsers = ref<User[]>([]);
const selectedUser = ref<User | null>(null);
const showUserForm = ref(false);
const showLoginHistory = ref(false);
const loginHistory = ref<ConnexionLog[]>([]);
const search = ref('');
const isAddDialogOpen = ref(false);
const isEditDialogOpen = ref(false);
const newUser = ref({
    pseudo: '',
    email: '',
    password: '',
    nom: '',
    prenom: '',
    dateNaissance: '',
    sexe: '',
    typeMembre: '',
    niveau: '',
    points: 0
});

// Mettre à jour les utilisateurs locaux quand les props changent
watch(() => props.users, (newUsers) => {
    localUsers.value = [...newUsers];
}, { immediate: true });

const form = useForm({
    pseudo: '',
    email: '',
    password: '',
    password_confirmation: '',
    nom: '',
    prenom: '',
    dateNaissance: '',
    sexe: '',
    typeMembre: 'Spectateur',
    niveau: 'Débutant',
    points: 0
});

const filteredUsers = computed(() => {
    if (!search.value) return localUsers.value;
    const searchLower = search.value.toLowerCase();
    return localUsers.value.filter(user => 
        user.pseudo.toLowerCase().includes(searchLower) ||
        user.email.toLowerCase().includes(searchLower) ||
        user.nom.toLowerCase().includes(searchLower) ||
        user.prenom.toLowerCase().includes(searchLower)
    );
});

const createUser = () => {
    form.post('/users', {
        onSuccess: () => {
            showUserForm.value = false;
            form.reset();
            router.visit('/dashboard/utilisateurs', { preserveScroll: true });
        },
        onError: () => {
            console.error('Erreur lors de la création de l\'utilisateur');
        }
    });
};

const deleteUser = (user: User) => {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) return;

    router.delete(`/users/${user.id}`, {
        onSuccess: () => {
            console.log('User supprimé avec succès');
            router.visit('/dashboard/utilisateurs', { preserveScroll: true });
        },
        onError: () => {
            console.error('Erreur lors de la suppression de l\'utilisateur');
        }
    });
};

const updateUser = () => {
    if (!selectedUser.value) return;

    form.put(`/users/${selectedUser.value.id}`, {
        onSuccess: () => {
            showUserForm.value = false;
            form.reset();
            router.visit('/dashboard/utilisateurs', { preserveScroll: true });
        },
        onError: () => {
            console.error('Erreur lors de la mise à jour de l\'utilisateur');
        }
    });
};

const viewLoginHistory = (user: User) => {
    router.get(`/users/${user.id}/login-history`, {}, {
        preserveState: true,
        onSuccess: (page) => {
            loginHistory.value = page.props.connexions || [];
            showLoginHistory.value = true;
        },
        onError: () => {
            console.error('Erreur lors de la récupération de l\'historique');
            toast({
                title: "Erreur",
                description: "Impossible de récupérer l'historique des connexions",
                variant: "destructive"
            });
        }
    });
};

const editUser = (user: User) => {
    selectedUser.value = user;
    form.pseudo = user.pseudo;
    form.email = user.email;
    form.nom = user.nom || '';
    form.prenom = user.prenom || '';
    if (user.dateNaissance) {
        const date = new Date(user.dateNaissance);
        form.dateNaissance = date.toISOString().split('T')[0];
    } else {
        form.dateNaissance = '';
    }
    form.sexe = ['Homme', 'Femme', 'Autre'].includes(user.sexe || '') ? user.sexe! : '';
    form.typeMembre = user.typeMembre;
    form.niveau = user.niveau;
    form.points = user.points;
    showUserForm.value = true;
};

const resetForm = () => {
    selectedUser.value = null;
    form.reset();
    form.typeMembre = 'Spectateur';
    form.niveau = 'Débutant';
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

const handleSearch = () => {
    router.get(route('utilisateurs'), { search: search.value }, {
        preserveState: true,
        preserveScroll: true
    });
};
</script>

<template>
    <div class="p-6 bg-gray-900 bg-opacity-90 shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-white tracking-[0.05em]">Gestion des Utilisateurs</h2>
            <div class="flex gap-4">
                <div class="relative">
                    <Input
                        v-model="search"
                        placeholder="Rechercher un utilisateur..."
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
                    @click="showUserForm = true"
                    class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300"
                >
                    Ajouter un utilisateur
                </Button>
            </div>
        </div>

        <div class="grid gap-4">
            <div v-for="user in filteredUsers" :key="user.id" 
                class="p-4 rounded-lg border border-indigo-500/30 bg-indigo-900/30 hover:bg-indigo-800/40 hover:border-indigo-400/50 shadow-md backdrop-blur-sm transition-all duration-300">
                <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                    <div class="w-full">
                        <div class="space-y-2">
                            <div>
                                <h3 class="font-semibold text-white tracking-[0.05em]">{{ user.pseudo }}</h3>
                                <p class="text-sm text-white/80 tracking-[0.05em]">{{ user.email }}</p>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-8">
                                <div class="w-full sm:w-1/2">
                                    <h4 class="font-bold text-white tracking-[0.05em] mb-2">Informations personnelles</h4>
                                    <div class="space-y-2 text-sm">
                                        <div>
                                            <span class="font-medium text-white/90">Nom : </span>
                                            <span class="text-white/80">{{ user.nom || 'Non renseigné' }}</span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-white/90">Prénom : </span>
                                            <span class="text-white/80">{{ user.prenom || 'Non renseigné' }}</span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-white/90">Date de naissance : </span>
                                            <span class="text-white/80">{{ user.dateNaissance ? new Date(user.dateNaissance).toLocaleDateString('fr-FR') : 'Non renseignée' }}</span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-white/90">Sexe : </span>
                                            <span class="text-white/80">{{ user.sexe || 'Non renseigné' }}</span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-white/90">Date d'inscription : </span>
                                            <span class="text-white/80">{{ new Date(user.created_at).toLocaleDateString('fr-FR') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="w-full sm:w-1/2">
                                    <h4 class="font-bold text-white tracking-[0.05em] mb-2">Informations du compte</h4>
                                    <div class="space-y-2 text-sm">
                                        <div>
                                            <span class="font-medium text-white/90">Type de membre : </span>
                                            <span class="text-white/80">{{ user.typeMembre }}</span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-white/90">Niveau : </span>
                                            <span class="text-white/80">{{ user.niveau }}</span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-white/90">Points : </span>
                                            <span class="text-white/80">{{ user.points }}</span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-white/90">Vérifié : </span>
                                            <span :class="user.email_verified_at ? 'text-cyan-300' : 'text-red-400'">
                                                {{ user.email_verified_at ? 'Oui' : 'Non' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2 items-center whitespace-nowrap">
                        <Button variant="outline" size="sm" 
                            @click="viewLoginHistory(user)"
                            class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300">
                            Historique
                        </Button>
                        <Button variant="outline" size="sm" 
                            @click="editUser(user)"
                            class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300">
                            Modifier
                        </Button>
                        <Button variant="destructive" size="sm" 
                            @click="deleteUser(user)"
                            class="bg-red-900/30 hover:bg-red-800/40 border border-red-500/30 hover:border-red-400/50 text-white hover:text-red-300">
                            Supprimer
                        </Button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Formulaire User -->
        <Dialog v-model:open="showUserForm">
            <DialogContent class="sm:max-w-[500px] bg-indigo-900/90 border border-indigo-500/30 text-white">
                <DialogHeader>
                    <DialogTitle class="text-white tracking-[0.05em]">
                        {{ selectedUser ? 'Modifier' : 'Ajouter' }} un utilisateur
                    </DialogTitle>
                </DialogHeader>
                <Form @submit="selectedUser ? updateUser() : createUser()" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Pseudo -->
                        <FormField name="pseudo" class="col-span-2">
                            <FormLabel class="text-white/90">Pseudo<InputError :message="form.errors.pseudo" /></FormLabel>
                            <FormControl>
                                <Input v-model="form.pseudo" required placeholder="Pseudo" 
                                    class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300" />
                            </FormControl>
                        </FormField>

                        <!-- Email -->
                        <FormField name="email" class="col-span-2">
                            <FormLabel class="text-white/90">Email<InputError :message="form.errors.email" /></FormLabel>
                            <FormControl>
                                <Input type="email" v-model="form.email" required placeholder="email@example.com" 
                                    class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300" />
                            </FormControl>
                        </FormField>

                        <!-- Mot de passe -->
                        <FormField name="password" v-if="!selectedUser" class="col-span-2">
                            <FormLabel class="text-white/90">Mot de passe<InputError :message="form.errors.password" /></FormLabel>
                            <FormControl>
                                <Input type="password" v-model="form.password" required placeholder="Mot de passe" 
                                    class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300" />
                            </FormControl>
                        </FormField>

                        <!-- Confirmation mot de passe -->
                        <FormField name="password_confirmation" v-if="!selectedUser" class="col-span-2">
                            <FormLabel class="text-white/90">Confirmation du mot de passe<InputError :message="form.errors.password_confirmation" /></FormLabel>
                            <FormControl>
                                <Input type="password" v-model="form.password_confirmation" required placeholder="Confirmation mot de passe" 
                                    class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300" />
                            </FormControl>
                        </FormField>

                        <!-- Nom -->
                        <FormField name="nom">
                            <FormLabel class="text-white/90">Nom<InputError :message="form.errors.nom" /></FormLabel>
                            <FormControl>
                                <Input v-model="form.nom" placeholder="Nom" 
                                    class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300" />
                            </FormControl>
                        </FormField>

                        <!-- Prénom -->
                        <FormField name="prenom">
                            <FormLabel class="text-white/90">Prénom<InputError :message="form.errors.prenom" /></FormLabel>
                            <FormControl>
                                <Input v-model="form.prenom" placeholder="Prénom" 
                                    class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300" />
                            </FormControl>
                        </FormField>

                        <!-- Date de naissance -->
                        <FormField name="dateNaissance">
                            <FormLabel class="text-white/90">Date de naissance<InputError :message="form.errors.dateNaissance" /></FormLabel>
                            <FormControl>
                                <Input type="date" v-model="form.dateNaissance" 
                                    class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300" />
                            </FormControl>
                        </FormField>

                        <!-- Sexe -->
                        <FormField name="sexe">
                            <FormLabel class="text-white/90">Sexe<InputError :message="form.errors.sexe" /></FormLabel>
                            <Select v-model="form.sexe" required>
                                <SelectTrigger class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300">
                                    <SelectValue placeholder="Sélectionner un sexe" />
                                </SelectTrigger>
                                <SelectContent class="bg-indigo-900/90 border-indigo-500/30 text-white">
                                    <SelectGroup>
                                        <SelectItem value="Femme" class="hover:bg-indigo-800/40 hover:text-cyan-300 text-white">Femme</SelectItem>
                                        <SelectItem value="Homme" class="hover:bg-indigo-800/40 hover:text-cyan-300 text-white">Homme</SelectItem>
                                        <SelectItem value="Autre" class="hover:bg-indigo-800/40 hover:text-cyan-300 text-white">Autre</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </FormField>

                        <!-- Type de membre -->
                        <FormField name="typeMembre">
                            <FormLabel class="text-white/90">Type de membre<InputError :message="form.errors.typeMembre" /></FormLabel>
                            <Select v-model="form.typeMembre" required>
                                <SelectTrigger class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300">
                                    <SelectValue placeholder="Sélectionner un type" />
                                </SelectTrigger>
                                <SelectContent class="bg-indigo-900/90 border-indigo-500/30 text-white">
                                    <SelectGroup>
                                        <SelectItem value="Spectateur" class="hover:bg-indigo-800/40 hover:text-cyan-300 text-white">Spectateur</SelectItem>
                                        <SelectItem value="Athlète" class="hover:bg-indigo-800/40 hover:text-cyan-300 text-white">Athlète</SelectItem>
                                        <SelectItem value="Entraîneur" class="hover:bg-indigo-800/40 hover:text-cyan-300 text-white">Entraîneur</SelectItem>
                                        <SelectItem value="Personnel technique" class="hover:bg-indigo-800/40 hover:text-cyan-300 text-white">Personnel technique</SelectItem>
                                        <SelectItem value="Sécurité" class="hover:bg-indigo-800/40 hover:text-cyan-300 text-white">Sécurité</SelectItem>
                                        <SelectItem value="Administratif" class="hover:bg-indigo-800/40 hover:text-cyan-300 text-white">Administratif</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </FormField>

                        <!-- Niveau -->
                        <FormField name="niveau">
                            <FormLabel class="text-white/90">Niveau<InputError :message="form.errors.niveau" /></FormLabel>
                            <Select v-model="form.niveau" required>
                                <SelectTrigger class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300">
                                    <SelectValue placeholder="Choisir un niveau" />
                                </SelectTrigger>
                                <SelectContent class="bg-indigo-900/90 border-indigo-500/30 text-white">
                                    <SelectGroup>
                                        <SelectItem value="Débutant" class="hover:bg-indigo-800/40 hover:text-cyan-300 text-white">Débutant</SelectItem>
                                        <SelectItem value="Intermédiaire" class="hover:bg-indigo-800/40 hover:text-cyan-300 text-white">Intermédiaire</SelectItem>
                                        <SelectItem value="Avancé" class="hover:bg-indigo-800/40 hover:text-cyan-300 text-white">Avancé</SelectItem>
                                        <SelectItem value="Expert" class="hover:bg-indigo-800/40 hover:text-cyan-300 text-white">Expert</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </FormField>

                        <!-- Points -->
                        <FormField name="points">
                            <FormLabel class="text-white/90">Points<InputError :message="form.errors.points" /></FormLabel>
                            <FormControl>
                                <Input type="number" v-model="form.points" min="0" 
                                    class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300" />
                            </FormControl>
                        </FormField>
                    </div>

                    <div class="mt-4 flex justify-end gap-2">
                        <Button type="button" variant="outline" 
                            @click="resetForm(); showUserForm = false"
                            class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300">
                            Annuler
                        </Button>
                        <Button type="submit" :disabled="form.processing"
                            class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300">
                            {{ selectedUser ? 'Modifier' : 'Ajouter' }}
                        </Button>
                    </div>
                </Form>
            </DialogContent>
        </Dialog>

        <!-- Modal Historique de Connexion -->
        <Dialog v-model:open="showLoginHistory">
            <DialogContent class="bg-indigo-900/90 border border-indigo-500/30 text-white">
                <DialogHeader>
                    <DialogTitle class="text-white tracking-[0.05em]">Historique de connexion</DialogTitle>
                </DialogHeader>
                <div class="max-h-96 overflow-y-auto">
                    <div v-for="log in loginHistory" :key="log.idConnexionsUtilisateurs" 
                        class="py-2 border-b border-indigo-500/30 last:border-0">
                        <p class="text-sm text-white/90">
                            {{ new Date(log.dateConnexion).toLocaleString() }}
                        </p>
                        <p class="text-xs text-white/80">
                            Points gagnés: {{ log.pointsGagne }}
                        </p>
                    </div>
                </div>
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