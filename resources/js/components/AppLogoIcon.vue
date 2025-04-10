<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import LogoImg from '@/../img/logo_notext.png';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <img :src="LogoImg" alt="Logo Astrosphère" :class="className" v-bind="$attrs" class="h-32 w-32 object-contain">
</template>
