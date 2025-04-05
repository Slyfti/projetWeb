<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import Header from '@/components/Header.vue';
import Footer from '@/components/Footer.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <div class="min-h-screen flex flex-col">
        <Header />
        <main class="flex-grow">
            <AppLayout :breadcrumbs="breadcrumbs">
                <slot />
            </AppLayout>
        </main>
        <Footer />
    </div>
</template>
