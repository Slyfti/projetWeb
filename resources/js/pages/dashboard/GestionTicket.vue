<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type PageProps } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import TicketManagement from '@/components/TicketManagement.vue';
import Header from '@/components/Header.vue';
import Footer from '@/components/Footer.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tickets',
        href: '/dashboard/tickets',
    },
];

const { tickets, isAdmin } = usePage().props as unknown as PageProps;
</script>

<template>
    <Head title="Dashboard - Tickets" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                <TicketManagement :tickets="tickets" :is-admin="isAdmin" />
            </div>
        </div>
    </AppLayout>
</template> 