<script setup lang="ts">
import { useAppearance } from '@/composables/useAppearance';
import { Monitor, Moon, Sun , Rocket } from 'lucide-vue-next';

interface Props {
    class?: string;
}

const { class: containerClass = '' } = defineProps<Props>();

const { appearance, updateAppearance } = useAppearance();

const tabs = [
    { value: 'dark', Icon: Rocket, label: 'Astrosphère' },
] as const;
</script>

<template>
    <div :class="['inline-flex gap-1 rounded-lg bg-neutral-900/50 p-1 backdrop-blur-sm', containerClass]">
        <button
            v-for="{ value, Icon, label } in tabs"
            :key="value"
            @click="updateAppearance(value)"
            :class="[
                'flex items-center rounded-md px-3.5 py-1.5 transition-all duration-300',
                appearance === value
                    ? 'bg-indigo-500/30 shadow-lg shadow-indigo-500/20 text-white backdrop-blur-sm border border-indigo-500/30'
                    : 'text-neutral-400 hover:bg-indigo-500/20 hover:text-white hover:shadow-md dark:hover:border-indigo-500/20 border border-transparent',
            ]"
        >
            <component :is="Icon" class="-ml-1 h-4 w-4" />
            <span class="ml-1.5 text-sm">{{ label }}</span>
        </button>
    </div>
</template>
