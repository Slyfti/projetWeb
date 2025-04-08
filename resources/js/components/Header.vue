<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import LogoImg from '@/../img/logo.jpeg';
</script>
<template>
    <header class="bg-gray-900 bg-opacity-90 shadow-md p-4">
        <div class="container mx-auto flex justify-between items-center">
            <Link :href="route('home')" class="flex items-center">
                <img :src="LogoImg" alt="Logo Astrosphère" class="h-16 w-16 rounded-full">
            </Link>
            <nav>
                <ul class="flex gap-6 text-white">
                    <li><Link :href="route('evenements.index')" class="hover:text-green-400 transition">Événements</Link></li>
                    <li><Link :href="route('services.index')" class="hover:text-green-400 transition">Services</Link></li>
                    <li><Link :href="route('login')" class="hover:text-green-400 transition">Connexion / Inscription</Link></li>
                </ul>
            </nav>
        </div>
    </header>
</template>
