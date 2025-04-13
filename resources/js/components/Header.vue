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
import { usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const page = usePage();
const isAuthenticated = computed(() => page.props.auth?.user);
const isMenuOpen = ref(false);

const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};
</script>

<style>
.logo-text {
    letter-spacing: 0.05em;
}

.nav-link {
    letter-spacing: 0.05em;
    @apply bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50;
}

.mobile-menu {
    @apply fixed top-0 left-0 w-full h-full bg-gray-900 bg-opacity-95 z-50 transition-all duration-300;
}

.mobile-menu-enter {
    @apply opacity-0;
}

.mobile-menu-enter-active {
    @apply opacity-100;
}

.mobile-menu-leave {
    @apply opacity-100;
}

.mobile-menu-leave-active {
    @apply opacity-0;
}
</style>

<template>
    <header class="bg-gray-900 bg-opacity-90 shadow-md p-4">
        <div class="container mx-auto flex justify-between items-center">
            <Link :href="route('home')" class="flex items-center gap-4">
                <img :src="LogoImg" alt="Logo Astrosphère" class="h-16 w-16 rounded-full">
                <span class="text-white text-2xl font-bold tracking-[0.05em]">Astrosphère</span>
            </Link>

            <!-- Menu Desktop -->
            <NavigationMenu class="hidden md:block">
                <NavigationMenuList class="gap-2">
                    <NavigationMenuItem>
                        <NavigationMenuLink :href="route('evenements.index')" class="tracking-[0.05em] bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300 px-4 py-2 rounded-md transition-all duration-300">
                            Événements
                        </NavigationMenuLink>
                    </NavigationMenuItem>
                    <NavigationMenuItem>
                        <NavigationMenuLink :href="route('services.show')" class="tracking-[0.05em] bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300 px-4 py-2 rounded-md transition-all duration-300">
                            Services
                        </NavigationMenuLink>
                    </NavigationMenuItem>
                    <NavigationMenuItem>
                        <NavigationMenuLink 
                            :href="isAuthenticated ? route('dashboard') : route('login')" 
                            class="tracking-[0.05em] bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300 px-4 py-2 rounded-md transition-all duration-300"
                        >
                            {{ isAuthenticated ? 'Dashboard' : 'Connexion / Inscription' }}
                        </NavigationMenuLink>
                    </NavigationMenuItem>
                </NavigationMenuList>
            </NavigationMenu>

            <!-- Bouton Menu Mobile -->
            <button @click="toggleMenu" class="md:hidden text-white p-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Menu Mobile -->
        <Transition name="mobile-menu">
            <div v-if="isMenuOpen" class="mobile-menu">
                <div class="container mx-auto p-4">
                    <div class="flex justify-end">
                        <button @click="toggleMenu" class="text-white p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <nav class="flex flex-col gap-4 mt-8">
                        <Link :href="route('evenements.index')" class="text-white text-lg tracking-[0.05em] hover:text-cyan-300 transition-all duration-300">
                            Événements
                        </Link>
                        <Link :href="route('services.show')" class="text-white text-lg tracking-[0.05em] hover:text-cyan-300 transition-all duration-300">
                            Services
                        </Link>
                        <Link 
                            :href="isAuthenticated ? route('dashboard') : route('login')" 
                            class="text-white text-lg tracking-[0.05em] hover:text-cyan-300 transition-all duration-300"
                        >
                            {{ isAuthenticated ? 'Dashboard' : 'Connexion / Inscription' }}
                        </Link>
                    </nav>
                </div>
            </div>
        </Transition>
    </header>
</template>
