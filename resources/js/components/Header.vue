<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import LogoImg from '@/../img/logo_multi.png';
import {
    NavigationMenu,
    NavigationMenuContent,
    NavigationMenuItem,
    NavigationMenuLink,
    NavigationMenuList,
    NavigationMenuTrigger,
    navigationMenuTriggerStyle,
} from '@/components/ui/navigation-menu';
</script>

<style>
.logo-text {
    letter-spacing: 0.05em;
}

.nav-link {
    letter-spacing: 0.05em;
    @apply bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50;
}
</style>

<template>
    <header class="bg-gray-900 bg-opacity-90 shadow-md p-4">
        <div class="container mx-auto flex justify-between items-center">
            <Link :href="route('home')" class="flex items-center gap-4">
                <img :src="LogoImg" alt="Logo Astrosphère" class="h-16 w-16 rounded-full">
                <span class="text-white text-2xl font-bold tracking-[0.05em]">Astrosphère</span>
            </Link>
            <NavigationMenu>
                <NavigationMenuList class="gap-2">
                    <NavigationMenuItem>
                        <NavigationMenuLink :href="route('evenements.index')" class="tracking-[0.05em] bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300 px-4 py-2 rounded-md transition-all duration-300">
                            Événements
                        </NavigationMenuLink>
                    </NavigationMenuItem>
                    <NavigationMenuItem>
                        <NavigationMenuLink :href="route('services.index')" class="tracking-[0.05em] bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300 px-4 py-2 rounded-md transition-all duration-300">
                            Services
                        </NavigationMenuLink>
                    </NavigationMenuItem>
                    <NavigationMenuItem>
                        <NavigationMenuLink :href="route('login')" class="tracking-[0.05em] bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300 px-4 py-2 rounded-md transition-all duration-300">
                            Connexion / Inscription
                        </NavigationMenuLink>
                    </NavigationMenuItem>
                </NavigationMenuList>
            </NavigationMenu>
        </div>
    </header>
</template>
