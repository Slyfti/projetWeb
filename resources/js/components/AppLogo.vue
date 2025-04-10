<script setup lang="ts">
import LogoImg from '@/../img/logo_notext.png';
</script>

<template>
    <div class="flex aspect-square size-8 items-center justify-center rounded-md bg-sidebar-primary text-sidebar-primary-foreground">
        <img :src="LogoImg" alt="Logo Astrosphère" class="size-8 rounded-md">
    </div>
    <div class="ml-1 grid flex-1 text-left text-sm">
        <span class="mb-0.5 truncate font-semibold leading-none">Astrosphère</span>
    </div>
</template>
