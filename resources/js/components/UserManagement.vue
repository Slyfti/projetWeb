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
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

interface ConnexionLog {
    idConnexionsUtilisateurs: number;
    dateConnexion: string;
    pointsGagne: number;
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
    nom: '',
    prenom: '',
    dateNaissance: '',
    sexe: '',
    typeMembre: 'Spectateur',
    niveau: 'Débutant',
});

const createUser = () => {
    // Créer une copie des données du formulaire
    const newUser = {
        id: Date.now(), // ID temporaire
        pseudo: form.pseudo,
        email: form.email,
        nom: form.nom,
        prenom: form.prenom,
        niveau: form.niveau,
        points: 0,
        typeMembre: form.typeMembre
    };

    // Mise à jour optimiste
    localUsers.value = [...localUsers.value, newUser];

    form.post('/users', {
        preserveScroll: true,
        preserveState: true,
        only: ['users'],
        onSuccess: () => {
            showUserForm.value = false;
            form.reset();
        },
        onError: () => {
            // En cas d'erreur, on retire l'utilisateur ajouté
            localUsers.value = localUsers.value.filter(u => u.id !== newUser.id);
        }
    });
};

const deleteUser = (user: User) => {
    if (confirm(`Êtes-vous sûr de vouloir supprimer ${user.pseudo} ?`)) {
        // Optimistic update
        localUsers.value = localUsers.value.filter(u => u.id !== user.id);

        router.delete(`/users/${user.id}`, {
            preserveScroll: true,
            preserveState: true,
            only: ['users'],
            onError: () => {
                // En cas d'erreur, on remet l'utilisateur dans la liste
                localUsers.value = [...props.users];
            }
        });
    }
};

const updateUser = () => {
    if (!selectedUser.value) return;
    
    form.put(`/users/${selectedUser.value.id}`, {
        preserveScroll: true,
        preserveState: true,
        only: ['users'],
        onSuccess: () => {
            showUserForm.value = false;
            form.reset();
        },
    });
};

const updateAccessLevel = (user: User, niveau: string) => {
    // Optimistic update
    const index = localUsers.value.findIndex(u => u.id === user.id);
    if (index !== -1) {
        localUsers.value[index] = { ...user, niveau };
    }

    router.put(`/users/${user.id}/access-level`, { niveau }, {
        preserveScroll: true,
        preserveState: true,
        only: ['users'],
        onSuccess: () => {
            // La mise à jour est déjà faite grâce au watch sur props.users
        },
        onError: () => {
            // En cas d'erreur, on revient à l'état précédent
            const index = localUsers.value.findIndex(u => u.id === user.id);
            if (index !== -1) {
                localUsers.value[index] = user;
            }
        }
    });
};

const updateUserType = (user: User, typeMembre: string) => {
    // Optimistic update
    const index = localUsers.value.findIndex(u => u.id === user.id);
    if (index !== -1) {
        localUsers.value[index] = { ...user, typeMembre };
    }

    router.put(`/users/${user.id}/type`, { typeMembre }, {
        preserveScroll: true,
        preserveState: true,
        only: ['users'],
        onSuccess: () => {
            // La mise à jour est déjà faite grâce au watch sur props.users
        },
        onError: () => {
            // En cas d'erreur, on revient à l'état précédent
            const index = localUsers.value.findIndex(u => u.id === user.id);
            if (index !== -1) {
                localUsers.value[index] = user;
            }
        }
    });
};

const viewLoginHistory = (user: User) => {
    router.get(`/users/${user.id}/login-history`, {}, {
        preserveState: true,
        onSuccess: (page) => {
            loginHistory.value = page.props.connexions;
            showLoginHistory.value = true;
        },
    });
};
</script>

<template>
    <div class="p-4">
        <div class="mb-4 flex justify-between items-center">
            <h2 class="text-2xl font-bold">Gestion des Utilisateurs</h2>
            <Button @click="showUserForm = true">Ajouter un utilisateur</Button>
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
                        <Select 
                            :model-value="user.typeMembre"
                            @update:model-value="(value) => updateUserType(user, value as string)">
                            <SelectTrigger class="h-9 w-[180px]">
                                <SelectValue placeholder="Type de membre" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="Spectateur">Spectateur</SelectItem>
                                <SelectItem value="Athlète">Athlète</SelectItem>
                                <SelectItem value="Entraîneur">Entraîneur</SelectItem>
                                <SelectItem value="Personnel technique">Personnel technique</SelectItem>
                                <SelectItem value="Sécurité">Sécurité</SelectItem>
                                <SelectItem value="Administratif">Administratif</SelectItem>
                            </SelectContent>
                        </Select>
                        <Select 
                            :model-value="user.niveau"
                            @update:model-value="(value) => updateAccessLevel(user, value as string)">
                            <SelectTrigger class="h-9 w-[140px]">
                                <SelectValue placeholder="Niveau d'accès" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="Débutant">Débutant</SelectItem>
                                <SelectItem value="Intermédiaire">Intermédiaire</SelectItem>
                                <SelectItem value="Avancé">Avancé</SelectItem>
                                <SelectItem value="Expert">Expert</SelectItem>
                            </SelectContent>
                        </Select>
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
                            <FormLabel>Pseudo</FormLabel>
                            <FormControl>
                                <Input v-model="form.pseudo" required />
                            </FormControl>
                            <FormMessage />
                        </FormField>

                        <!-- Email -->
                        <FormField name="email" class="col-span-2">
                            <FormLabel>Email</FormLabel>
                            <FormControl>
                                <Input type="email" v-model="form.email" required />
                            </FormControl>
                            <FormMessage />
                        </FormField>

                        <!-- Mot de passe -->
                        <FormField name="password" v-if="!selectedUser" class="col-span-2">
                            <FormLabel>Mot de passe</FormLabel>
                            <FormControl>
                                <Input type="password" v-model="form.password" required />
                            </FormControl>
                            <FormMessage />
                        </FormField>

                        <!-- Nom -->
                        <FormField name="nom">
                            <FormLabel>Nom</FormLabel>
                            <FormControl>
                                <Input v-model="form.nom" />
                            </FormControl>
                            <FormMessage />
                        </FormField>

                        <!-- Prénom -->
                        <FormField name="prenom">
                            <FormLabel>Prénom</FormLabel>
                            <FormControl>
                                <Input v-model="form.prenom" />
                            </FormControl>
                            <FormMessage />
                        </FormField>

                        <!-- Date de naissance -->
                        <FormField name="dateNaissance">
                            <FormLabel>Date de naissance</FormLabel>
                            <FormControl>
                                <Input type="date" v-model="form.dateNaissance" />
                            </FormControl>
                            <FormMessage />
                        </FormField>

                        <!-- Sexe -->
                        <FormField name="sexe">
                            <FormLabel>Sexe</FormLabel>
                            <Select v-model="form.sexe">
                                <SelectTrigger>
                                    <SelectValue placeholder="Choisir" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="Homme">Homme</SelectItem>
                                    <SelectItem value="Femme">Femme</SelectItem>
                                    <SelectItem value="Autre">Autre</SelectItem>
                                </SelectContent>
                            </Select>
                            <FormMessage />
                        </FormField>

                        <!-- Type de membre -->
                        <FormField name="typeMembre">
                            <FormLabel>Type de membre</FormLabel>
                            <Select v-model="form.typeMembre" required>
                                <SelectTrigger>
                                    <SelectValue placeholder="Sélectionner un type" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="Spectateur">Spectateur</SelectItem>
                                    <SelectItem value="Athlète">Athlète</SelectItem>
                                    <SelectItem value="Entraîneur">Entraîneur</SelectItem>
                                    <SelectItem value="Personnel technique">Personnel technique</SelectItem>
                                    <SelectItem value="Sécurité">Sécurité</SelectItem>
                                    <SelectItem value="Administratif">Administratif</SelectItem>
                                </SelectContent>
                            </Select>
                            <FormMessage />
                        </FormField>

                        <!-- Niveau -->
                        <FormField name="niveau">
                            <FormLabel>Niveau</FormLabel>
                            <Select v-model="form.niveau" required>
                                <SelectTrigger>
                                    <SelectValue placeholder="Choisir un niveau" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="Débutant">Débutant</SelectItem>
                                    <SelectItem value="Intermédiaire">Intermédiaire</SelectItem>
                                    <SelectItem value="Avancé">Avancé</SelectItem>
                                    <SelectItem value="Expert">Expert</SelectItem>
                                </SelectContent>
                            </Select>
                            <FormMessage />
                        </FormField>
                    </div>

                    <div class="mt-4 flex justify-end gap-2">
                        <Button type="button" variant="outline" 
                            @click="showUserForm = false">
                            Annuler
                        </Button>
                        <Button type="submit">
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