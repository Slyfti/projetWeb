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
    <div class="h-full flex flex-col">
        <main class="flex-grow">
            <AppLayout :breadcrumbs="breadcrumbs">
                <slot />
            </AppLayout>
        </main>
    </div>
</template>
