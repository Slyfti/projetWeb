<script setup lang="ts">
import { SidebarGroup, SidebarGroupContent, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import ExperienceBar from '@/components/ExperienceBar.vue';
import { usePage } from '@inertiajs/vue3';
import type { SharedData } from '@/types';

interface Props {
    items: NavItem[];
    class?: string;
}

defineProps<Props>();
const page = usePage<SharedData>();
const user = page.props.auth.user;
</script>

<template>
    <SidebarGroup :class="`group-data-[collapsible=icon]:p-0 ${$props.class || ''}`">
        <SidebarGroupContent>
            <!-- Menu des liens -->
            <SidebarMenu>
                <SidebarMenuItem v-for="item in items" :key="item.title">
                    <SidebarMenuButton class="text-neutral-600 hover:text-neutral-800 dark:text-neutral-300 dark:hover:text-neutral-100" as-child>
                        <a :href="item.href" target="_blank" rel="noopener noreferrer">
                            <component :is="item.icon" />
                            <span>{{ item.title }}</span>
                        </a>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>

            <!-- Barre d'expérience -->
            <div class="px-4 py-2 mt-2 border-t border-neutral-700/30">
                <ExperienceBar :user-id="user.id" />
            </div>
        </SidebarGroupContent>
    </SidebarGroup>
</template>
