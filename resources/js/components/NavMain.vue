<script setup lang="ts">
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem, type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';

defineProps<{
    items: NavItem[];
}>();

const page = usePage<SharedData>();
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel class="text-white/80 tracking-[0.05em]">Dashboard</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title">
                <SidebarMenuButton 
                    as-child :is-active="item.href === page.url"
                    :tooltip="item.title"
                    class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300 transition-all duration-300 data-[active=true]:bg-indigo-800/50 data-[active=true]:border-indigo-400/50 data-[active=true]:text-cyan-300"
                >
                    <Link :href="item.href" class="flex items-center gap-2">
                        <component :is="item.icon" class="h-5 w-5 transition-colors duration-300" />
                        <span class="tracking-[0.05em] transition-colors duration-300">{{ item.title }}</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
