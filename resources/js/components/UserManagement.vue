<script setup lang="ts">
import { ref, watch } from 'vue';
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

const localUsers = ref<User[]>([]);
const selectedUser = ref<User | null>(null);
const showUserForm = ref(false);
const showLoginHistory = ref(false);
const loginHistory = ref<ConnexionLog[]>([]);

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

const createUser = () => {
    form.post('/users', {
        onSuccess: () => {
            showUserForm.value = false;
            form.reset();
            router.visit('/dashboard', { preserveScroll: true });
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
            console.log('Utilisateur supprimé avec succès');
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
            router.visit('/dashboard', { preserveScroll: true });
        },
        onError: () => {
            console.error('Erreur lors de la mise à jour de l\'utilisateur');
        }
    });
};

const viewLoginHistory = (user: User) => {
    router.get(`/users/${user.id}/login-history`, {}, {
        preserveState: true,
        onSuccess: (page: any) => {
            loginHistory.value = page.props.connexions as ConnexionLog[];
            showLoginHistory.value = true;
        },
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
</script>

<template>
    <div class="p-4">
        <div class="mb-4 flex justify-between items-center">
            <h2 class="text-2xl font-bold">Gestion des Utilisateurs</h2>
            <Button @click="resetForm(); showUserForm = true">Ajouter un utilisateur</Button>
        </div>

        <div class="grid gap-4">
            <div v-for="user in localUsers" :key="user.id" 
                class="p-4 rounded-lg border border-gray-200 dark:border-gray-800">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-semibold">{{ user.pseudo }}</h3>
                        <p class="text-sm text-gray-500">{{ user.email }}</p>
                        <p class="text-sm">Niveau: {{ user.niveau }}</p>
                        <p class="text-sm">Points: {{ user.points }}</p>
                    </div>
                    <div class="flex gap-2 items-center">
                        <Button variant="outline" size="sm" 
                            @click="viewLoginHistory(user)">
                            Historique
                        </Button>
                        <Button variant="outline" size="sm" 
                            @click="editUser(user)">
                            Modifier
                        </Button>
                        <Button variant="destructive" size="sm" 
                            @click="deleteUser(user)">
                            Supprimer
                        </Button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Formulaire Utilisateur -->
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