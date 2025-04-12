<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import axios from 'axios';

interface Props {
    userId: number;
}

const props = defineProps<Props>();
const points = ref(0);
const loading = ref(true);
const error = ref<string | null>(null);

const progress = computed(() => {
    return Math.min(Math.max((points.value / 600) * 100, 0), 100);
});

const ticks = computed(() => {
    return [100, 300, 600];
});

const fetchPoints = async () => {
    try {
        loading.value = true;
        error.value = null;
        const response = await axios.get(`/api/users/${props.userId}/points`);
        points.value = response.data.points;
    } catch (e) {
        error.value = "Erreur lors de la récupération des points";
        console.error(e);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchPoints();
});
</script>

<template>
    <div class="w-full">
        <div v-if="loading" class="h-2 w-full rounded-full bg-gray-200/20 dark:bg-gray-700/20 animate-pulse"></div>
        <div v-else-if="error" class="text-red-500 text-xs">{{ error }}</div>
        <div v-else class="relative h-2 w-full rounded-full bg-gray-200/20 dark:bg-gray-700/20 backdrop-blur-sm">
            <!-- Effet de brillance -->
            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-cyan-500/20 rounded-full"></div>
            
            <!-- Barre de progression -->
            <div
                class="absolute h-full rounded-full bg-gradient-to-r from-blue-500 to-cyan-500 transition-all duration-300 shadow-[0_0_10px_rgba(6,182,212,0.5)]"
                :style="{ width: `${progress}%` }"
            ></div>
            
            <!-- Marqueurs -->
            <div
                v-for="tick in ticks"
                :key="tick"
                class="absolute h-full w-[1px] bg-blue-400/30 dark:bg-cyan-400/30"
                :style="{ left: `${(tick / 600) * 100}%` }"
            ></div>
            
            <!-- Points actuels -->
            <div class="absolute inset-0 flex items-center justify-center text-[10px] font-medium text-blue-100 dark:text-cyan-100">
                {{ points }}/600
            </div>
        </div>
    </div>
</template> 