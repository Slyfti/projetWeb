<script setup lang="ts">
import { ref, watch, computed } from 'vue';
import { useForm, router, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
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
import type { PageProps } from '@/types';

interface ConnexionLog {
    idConnexionsUtilisateurs: number;
    dateConnexion: string;
    pointsGagne: number;
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

const page = usePage<PageProps>();
const isAdmin = computed(() => {
    const auth = page.props.auth as { user: User };
    return auth.user.typeMembre === 'Administratif';
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
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Gestion des Utilisateurs</h2>
            <div class="flex gap-4">
                <div class="relative">
                    <Input
                        v-model="search"
                        placeholder="Rechercher un utilisateur..."
                        class="pr-10"
                        @keyup.enter="handleSearch"
                    />
                    <Button
                        variant="ghost"
                        size="icon"
                        class="absolute right-0 top-0 h-full px-3"
                        @click="handleSearch"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    </Button>
                </div>
                <Button v-if="isAdmin" @click="showUserForm = true">
                    Ajouter un utilisateur
                </Button>
            </div>
        </div>

        <div class="grid gap-4">
            <div v-for="user in filteredUsers" :key="user.id" 
                class="p-4 rounded-lg border">
                <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                    <div class="w-full">
                        <div class="flex items-center gap-4 mb-4">
                            <Avatar class="h-12 w-12">
                                <AvatarImage :src="user.avatar || '/default-avatar.png'" />
                                <AvatarFallback>{{ user.pseudo.charAt(0).toUpperCase() }}</AvatarFallback>
                            </Avatar>
                            <div>
                                <h3 class="font-semibold">{{ user.pseudo }}</h3>
                                <p v-if="isAdmin" class="text-sm text-gray-500">{{ user.email }}</p>
                            </div>
                        </div>
                        <div v-if="isAdmin" class="flex flex-col sm:flex-row gap-8">
                            <div class="w-full sm:w-1/2">
                                <h4 class="font-medium text-gray-500 mb-2">Informations personnelles</h4>
                                <div class="space-y-2 text-sm">
                                    <div>
                                        <span class="font-medium">Nom : </span>
                                        <span>{{ user.nom || 'Non renseigné' }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium">Prénom : </span>
                                        <span>{{ user.prenom || 'Non renseigné' }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium">Date de naissance : </span>
                                        <span>{{ user.dateNaissance ? new Date(user.dateNaissance).toLocaleDateString('fr-FR') : 'Non renseignée' }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium">Sexe : </span>
                                        <span>{{ user.sexe || 'Non renseigné' }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium">Date d'inscription : </span>
                                        <span>{{ new Date(user.created_at).toLocaleDateString('fr-FR') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="w-full sm:w-1/2">
                                <h4 class="font-medium text-gray-500 mb-2">Informations du compte</h4>
                                <div class="space-y-2 text-sm">
                                    <div>
                                        <span class="font-medium">Type de membre : </span>
                                        <span>{{ user.typeMembre }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium">Niveau : </span>
                                        <span>{{ user.niveau }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium">Points : </span>
                                        <span>{{ user.points }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium">Vérifié : </span>
                                        <span :class="user.email_verified_at ? 'text-green-600' : 'text-red-600'">
                                            {{ user.email_verified_at ? 'Oui' : 'Non' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2 items-center whitespace-nowrap">
                        <Button v-if="isAdmin" variant="outline" size="sm" 
                            @click="viewLoginHistory(user)">
                            Historique
                        </Button>
                        <Button v-if="isAdmin" variant="outline" size="sm" 
                            @click="editUser(user)">
                            Modifier
                        </Button>
                        <Button v-if="isAdmin" variant="destructive" size="sm" 
                            @click="deleteUser(user)">
                            Supprimer
                        </Button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Formulaire User -->
        <Dialog v-model:open="showUserForm">
            <DialogContent class="sm:max-w-[500px]">
                <DialogHeader>
                    <DialogTitle>
                        {{ selectedUser ? 'Modifier' : 'Ajouter' }} un utilisateur
                    </DialogTitle>
                </DialogHeader>
                <Form @submit="selectedUser ? updateUser() : createUser()" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Pseudo -->
                        <FormField name="pseudo" class="col-span-2">
                            <FormLabel>Pseudo<InputError :message="form.errors.pseudo" /></FormLabel>
                            
                            <FormControl>
                                <Input v-model="form.pseudo" required placeholder="Pseudo" />
                            </FormControl>
                            
                        </FormField>

                        <!-- Email -->
                        <FormField name="email" class="col-span-2">
                            <FormLabel>Email<InputError :message="form.errors.email" /></FormLabel>
                            <FormControl>
                                <Input type="email" v-model="form.email" required placeholder="email@example.com" />
                            </FormControl>
                        </FormField>

                        <!-- Mot de passe -->
                        <FormField name="password" v-if="!selectedUser" class="col-span-2">
                            <FormLabel>Mot de passe<InputError :message="form.errors.password" /></FormLabel>
                            <FormControl>
                                <Input type="password" v-model="form.password" required placeholder="Mot de passe" />
                            </FormControl>
                        </FormField>

                        <!-- Confirmation mot de passe -->
                        <FormField name="password_confirmation" v-if="!selectedUser" class="col-span-2">
                            <FormLabel>Confirmation du mot de passe<InputError :message="form.errors.password_confirmation" /></FormLabel>
                            <FormControl>
                                <Input type="password" v-model="form.password_confirmation" required placeholder="Confirmation mot de passe" />
                            </FormControl>
                        </FormField>

                        <!-- Nom -->
                        <FormField name="nom">
                            <FormLabel>Nom<InputError :message="form.errors.nom" /></FormLabel>
                            <FormControl>
                                <Input v-model="form.nom" placeholder="Nom" />
                            </FormControl>
                        </FormField>

                        <!-- Prénom -->
                        <FormField name="prenom">
                            <FormLabel>Prénom<InputError :message="form.errors.prenom" /></FormLabel>
                            <FormControl>
                                <Input v-model="form.prenom" placeholder="Prénom" />
                            </FormControl>
                        </FormField>

                        <!-- Date de naissance -->
                        <FormField name="dateNaissance">
                            <FormLabel>Date de naissance<InputError :message="form.errors.dateNaissance" /></FormLabel>
                            <FormControl>
                                <Input type="date" v-model="form.dateNaissance" />
                            </FormControl>
                        </FormField>

                        <!-- Sexe -->
                        <FormField name="sexe">
                            <FormLabel>Sexe<InputError :message="form.errors.sexe" /></FormLabel>
                            <Select v-model="form.sexe" required>
                                <SelectTrigger>
                                    <SelectValue placeholder="Sélectionner un sexe" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="Femme">Femme</SelectItem>
                                        <SelectItem value="Homme">Homme</SelectItem>
                                        <SelectItem value="Autre">Autre</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </FormField>

                        <!-- Type de membre -->
                        <FormField name="typeMembre">
                            <FormLabel>Type de membre<InputError :message="form.errors.typeMembre" /></FormLabel>
                            <Select v-model="form.typeMembre" required>
                                <SelectTrigger>
                                    <SelectValue placeholder="Sélectionner un type" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="Spectateur">Spectateur</SelectItem>
                                        <SelectItem value="Athlète">Athlète</SelectItem>
                                        <SelectItem value="Entraîneur">Entraîneur</SelectItem>
                                        <SelectItem value="Personnel technique">Personnel technique</SelectItem>
                                        <SelectItem value="Sécurité">Sécurité</SelectItem>
                                        <SelectItem value="Administratif">Administratif</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </FormField>

                        <!-- Niveau -->
                        <FormField name="niveau">
                            <FormLabel>Niveau<InputError :message="form.errors.niveau" /></FormLabel>
                            <Select v-model="form.niveau" required>
                                <SelectTrigger>
                                    <SelectValue placeholder="Choisir un niveau" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="Débutant">Débutant</SelectItem>
                                        <SelectItem value="Intermédiaire">Intermédiaire</SelectItem>
                                        <SelectItem value="Avancé">Avancé</SelectItem>
                                        <SelectItem value="Expert">Expert</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </FormField>

                        <!-- Points -->
                        <FormField name="points">
                            <FormLabel>Points<InputError :message="form.errors.points" /></FormLabel>
                            <FormControl>
                                <Input type="number" v-model="form.points" min="0" />
                            </FormControl>
                        </FormField>
                    </div>

                    <div class="mt-4 flex justify-end gap-2">
                        <Button type="button" variant="outline" 
                            @click="resetForm(); showUserForm = false">
                            Annuler
                        </Button>
                        <Button type="submit" :disabled="form.processing">
                            {{ selectedUser ? 'Modifier' : 'Ajouter' }}
                        </Button>
                    </div>
                </Form>
            </DialogContent>
        </Dialog>

        <!-- Modal Historique de Connexion -->
        <Dialog v-model:open="showLoginHistory">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Historique de connexion</DialogTitle>
                </DialogHeader>
                <div class="max-h-96 overflow-y-auto">
                    <div v-for="log in loginHistory" :key="log.idConnexionsUtilisateurs" 
                        class="py-2 border-b last:border-0">
                        <p class="text-sm">
                            {{ new Date(log.dateConnexion).toLocaleString() }}
                        </p>
                        <p class="text-xs text-gray-500">
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