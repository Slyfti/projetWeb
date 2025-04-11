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
        title: 'Utilisateurs',
        href: '/dashboard/utilisateurs',
    },
];

const { users, objets, categories, zones } = usePage().props as unknown as PageProps;
const activeTab = ref('users');
</script>

<template>
    <Head title="Dashboard - Utilisateurs" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                <UserManagement :users="users" />
            </div>
        </div>
    </AppLayout>
</template>
