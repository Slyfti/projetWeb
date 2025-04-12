<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem, type SharedData, type User } from '@/types';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Paramètres du profil',   
        href: '/settings/profile',
    },
];

const page = usePage<SharedData>();
const user = page.props.auth.user as User;

const form = useForm({
    nom: user.nom,
    prenom: user.prenom,
    dateNaissance: user.dateNaissance ? new Date(user.dateNaissance).toISOString().split('T')[0] : '',
    sexe: user.sexe,
    typeMembre: user.typeMembre,
    niveau: user.niveau,
    email: user.email,
});

const points = ref(user.points || 0);

const niveaux = [
    { value: 'Débutant', points: 0 },
    { value: 'Intermédiaire', points: 100 },
    { value: 'Avancé', points: 300 },
    { value: 'Expert', points: 600 }
];

const estNiveauAccessible = (niveauPoints: number) => {
    return points.value >= niveauPoints;
};

const submit = () => {
    form.patch(route('profile.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Paramètres du profil" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Informations du profil" description="Mettez à jour votre nom et votre adresse email" />

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid gap-2">
                        <Label for="nom">Nom</Label>
                        <Input id="nom" class="mt-1 block w-full" v-model="form.nom" required autocomplete="nom" placeholder="Nom" />
                        <InputError class="mt-2" :message="form.errors.nom" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="prenom">Prénom</Label>
                        <Input id="prenom" class="mt-1 block w-full" v-model="form.prenom" autocomplete="prenom" placeholder="Prénom" />
                        <InputError class="mt-2" :message="form.errors.prenom" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="dateNaissance">Date de naissance</Label>
                        <Input id="dateNaissance" type="date" class="mt-1 block w-full" v-model="form.dateNaissance" />
                        <InputError class="mt-2" :message="form.errors.dateNaissance" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="sexe">Sexe</Label>
                        <Select v-model="form.sexe">
                            <SelectTrigger>
                                <SelectValue placeholder="Sélectionnez un sexe" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="Homme">Homme</SelectItem>
                                <SelectItem value="Femme">Femme</SelectItem>
                                <SelectItem value="Autre">Autre</SelectItem>
                            </SelectContent>
                        </Select>
                        <InputError class="mt-2" :message="form.errors.sexe" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="typeMembre">Type de membre</Label>
                        <Select v-model="form.typeMembre" disabled>
                            <SelectTrigger>
                                <SelectValue placeholder="Sélectionnez un type de membre" />
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
                        <InputError class="mt-2" :message="form.errors.typeMembre" />
                        <p class="text-sm text-muted-foreground">
                            Le type de membre ne peut pas être modifié
                        </p>
                    </div>

                    <div class="grid gap-2">
                        <Label for="niveau">Niveau</Label>
                        <Select v-model="form.niveau" required>
                            <SelectTrigger>
                                <SelectValue placeholder="Sélectionnez un niveau" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem 
                                    v-for="niveau in niveaux" 
                                    :key="niveau.value"
                                    :value="niveau.value"
                                    :disabled="!estNiveauAccessible(niveau.points)"
                                    :class="{ 'opacity-50 cursor-not-allowed': !estNiveauAccessible(niveau.points) }"
                                >
                                    {{ niveau.value }} ({{ niveau.points }} points)
                                </SelectItem>
                            </SelectContent>
                        </Select>
                        <InputError class="mt-2" :message="form.errors.niveau" />
                        <p class="text-sm text-muted-foreground">
                            Points actuels : {{ points }}
                        </p>
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">Adresse email</Label>
                        <Input
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                            autocomplete="username"
                            placeholder="Adresse email"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div v-if="mustVerifyEmail && !user.email_verified_at">
                        <p class="-mt-4 text-sm text-muted-foreground">
                            Votre adresse email n'est pas vérifiée.
                            <Link
                                :href="route('verification.send')"
                                method="post"
                                as="button"
                                class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:!decoration-current dark:decoration-neutral-500"
                            >
                                Cliquez ici pour renvoyer l'email de vérification.
                            </Link>
                        </p>

                        <div v-if="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
                            Un nouveau lien de vérification a été envoyé à votre adresse email.
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="form.processing">Enregistrer</Button>

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">Enregistré.</p>
                        </Transition>
                    </div>
                </form>
            </div>

            <DeleteUser />
        </SettingsLayout>
    </AppLayout>
</template>
