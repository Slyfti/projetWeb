<script setup lang="ts">
import Header from '@/components/Header.vue';
import Footer from '@/components/Footer.vue';
import AstrosphereImg from '@/../img/Astrosphere.jpeg';
</script>

<template>
    <div class="min-h-screen flex flex-col bg-gradient-to-tr from-indigo-900 to-gray-900">
        <Header />
        <main class="flex-grow flex flex-col items-center justify-center text-center p-6">
            <img :src="AstrosphereImg" alt="Stade Astrosphère" class="w-4/5 max-w-xl rounded-lg shadow-lg mb-6">
            <p class="text-lg text-[#E4F1F1] max-w-2xl">
                Bienvenue dans l'univers d'Astrosphère, le stade connecté du futur où innovation et spectacle
                s'entrelacent pour une expérience inédite. Plongez dans une ambiance immersive et découvrez nos événements exclusifs.
            </p>
        </main>
        <Footer />
    </div>
</template>

