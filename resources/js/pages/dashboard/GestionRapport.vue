<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type PageProps } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import RapportManagement from '@/components/RapportManagement.vue';
import Header from '@/components/Header.vue';
import Footer from '@/components/Footer.vue';
import { ref } from 'vue';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Rapports',
        href: '/dashboard/rapports',
    },
];

const { objets, categories, zones, pointsUtilisateurs } = usePage().props as unknown as PageProps;
</script>

<template>
    <Head title="Dashboard - Rapports" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                <RapportManagement 
                    :objets="objets" 
                    :categories="categories" 
                    :zones="zones" 
                    :pointsUtilisateurs="pointsUtilisateurs" 
                />
            </div>
        </div>
    </AppLayout>
</template> 