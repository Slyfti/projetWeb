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
        icon: LayoutGrid,
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
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('home')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems.filter(item => !item.showForTypes || item.showForTypes.includes(page.props.auth.user.typeMembre))" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
