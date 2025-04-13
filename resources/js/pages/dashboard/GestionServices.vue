<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type PageProps } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import ServiceManagement from '@/components/ServiceManagement.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Services',
        href: '/dashboard/services',
    },
];

const { services, categories } = usePage().props as unknown as PageProps;
</script>

<template>
    <Head title="Dashboard - Services" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative overflow-hidden rounded-xl border border-indigo-500/30 bg-gray-900 bg-opacity-90 shadow-md backdrop-blur-sm transition-all duration-300">
                <ServiceManagement :services="services" :categories="categories" />
            </div>
        </div>
    </AppLayout>
</template> 