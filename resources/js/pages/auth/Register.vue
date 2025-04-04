<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';

const form = useForm({
    name: '',
    prenom:'',
    pseudo:'',
    dateNaissance:'',
    sexe:'',
    typeMembre:'',
    photo:'',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthBase title="Créer un compte" description="Entrez vos coordonnées ci-dessous pour créer votre compte">
        <Head title="Inscription" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="name">Nom</Label>
                    <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name" v-model="form.name" placeholder="Nom" />
                    <InputError :message="form.errors.name" />
                </div>

                <!--rajouter-->
                <div class="grid gap-2">
                    <Label for="prenom">Prénom</Label>
                    <Input id="prenom" type="text" required autofocus :tabindex="1" autocomplete="prenom" v-model="form.prenom" placeholder="Prénom" />
                    <InputError :message="form.errors.prenom" />
                </div>

                <!--rajouter-->
                <div class="grid gap-2">
                    <Label for="pseudo">Pseudo</Label>
                    <Input id="pseudo" type="text" required autofocus :tabindex="1" autocomplete="pseudo" v-model="form.pseudo" placeholder="Pseudo" />
                    <InputError :message="form.errors.pseudo" />
                </div>

                <!--rajouter-->
                <div class="grid gap-2">
                    <Label for="dateNaissance">Date de naissance</Label>
                    <Input id="dateNaissance" type="date" required autofocus :tabindex="1" autocomplete="dateNaissance" v-model="form.dateNaissance" />
                    <InputError :message="form.errors.dateNaissance" />
                </div>

                <!--rajouter-->
                <div class="grid gap-2">
                    <Label for="sexe">Sexe</Label>
                    <RadioGroup default-value="femme" v-model="form.sexe">
                        <div class="flex items-center space-x-2">
                            <RadioGroupItem id="femme" value="femme" />
                            <Label for="option-one">Femme</Label>
                        </div>
                        <div class="flex items-center space-x-2">
                            <RadioGroupItem id="homme" value="homme" />
                            <Label for="homme">Homme</Label>
                        </div>
                        <div class="flex items-center space-x-2">
                            <RadioGroupItem id="autre" value="autre" />
                            <Label for="autre">Autre</Label>
                        </div>
                    </RadioGroup>
                </div>

                <!--rajouter-->
                <div class="grid gap-2">
                    <Label for="typeMembre">Type membre</Label>
                    <Select v-model="form.typeMembre">
                        <SelectTrigger>
                        <SelectValue placeholder="Selectionner un type de membre" />
                        </SelectTrigger>
                        <SelectContent>
                        <SelectGroup>
                            <SelectItem value="Spectateur">Spectateur</SelectItem>
                            <SelectItem value="Athlete">Athlète</SelectItem>
                            <SelectItem value="Entraineur">Entraîneur</SelectItem>
                            <SelectItem value="PersonnelTechnique">Personnel technique</SelectItem>
                            <SelectItem value="Sécurite">Sécurité</SelectItem>
                            <SelectItem value="Administratif">Administratif</SelectItem>
                        </SelectGroup>
                        </SelectContent>
                    </Select>
                </div>

                <!--rajouter-->
                <div class="grid gap-2">
                    <div className="grid w-full max-w-sm items-center gap-1.5">
                        <Label htmlFor="photo">Photo de profil</Label>
                        <Input id="photo" type="file" v-model="form.photo"/>
                    </div>
                    <InputError :message="form.errors.photo" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Addresse mail</Label>
                    <Input id="email" type="email" required :tabindex="2" autocomplete="email" v-model="form.email" placeholder="mail@example.com" />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Mot de passe</Label>
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="3"
                        autocomplete="new-password"
                        v-model="form.password"
                        placeholder="Mot de passe"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirmation du mot de passe</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        v-model="form.password_confirmation"
                        placeholder="confirmation mot de passe"
                    />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Créer un compte
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                Vous avez déjà un compte ?
                <TextLink :href="route('login')" class="underline underline-offset-4" :tabindex="6">Se connecter</TextLink>
            </div>
        </form>
    </AuthBase>
</template>
