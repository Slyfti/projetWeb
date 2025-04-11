<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, Calendar, Users, Ticket, Globe } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import type { SharedData } from '@/types';

const page = usePage<SharedData>();

const mainNavItems: NavItem[] = [
    {
        title: 'Utilisateurs',
        href: '/dashboard/utilisateurs',
        icon: Users,
    },
    {
        title: 'Objets',
        href: '/dashboard/objets',
        icon: Globe,
    },
    {
        title: 'Événements',
        href: '/dashboard/evenements',
        icon: Calendar,
        showForTypes: ['Personnel technique', 'Sécurité', 'Administratif']
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Dépot GitHub',
        href: 'https://github.com/Slyfti/projetWeb',
        icon: Folder,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset" class="bg-gray-900 bg-opacity-90 shadow-md border-r border-indigo-500/30">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300 transition-all duration-300">
                        <Link :href="route('home')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>


        <SidebarContent>
            <NavMain  class="bg-gray-900 bg-opacity-90" :items="mainNavItems.filter(item => !item.showForTypes || item.showForTypes.includes(page.props.auth.user.typeMembre))" />
        </SidebarContent>

        <SidebarFooter class="bg-gray-900 bg-opacity-90 border-t border-indigo-500/30">
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
