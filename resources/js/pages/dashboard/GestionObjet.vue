<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type PageProps } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import UserManagement from '@/components/UserManagement.vue';
import ObjectManagement from '@/components/ObjectManagement.vue';
import Header from '@/components/Header.vue';
import Footer from '@/components/Footer.vue';
import { ref } from 'vue';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Gestion des objets',
        href: '/dashboard/objets',
    },
];

const { users, objets, categories, zones } = usePage().props as unknown as PageProps;
const activeTab = ref('users');
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative overflow-hidden rounded-xl border border-indigo-500/30 bg-gray-900 bg-opacity-90 shadow-md backdrop-blur-sm transition-all duration-300">
                <ObjectManagement :objets="objets" :categories="categories" :zones="zones" />
            </div>
        </div>
    </AppLayout>
</template>
