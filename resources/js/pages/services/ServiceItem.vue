<template>
    <section
      class="flex flex-col md:flex-row items-center gap-6 p-6 border border-[#6F4786] rounded-2xl bg-white/5"
    >
      <div class="flex-1 text-center md:text-left">
        <h2 class="text-[#40B584] text-xl font-semibold mb-2">{{ title }}</h2>
        <p class="text-sm mb-4">{{ text }}</p>
        <a
          href="#"
          class="inline-block bg-[#2D9CDB] hover:bg-[#C471ED] text-[#E4F1F1] px-4 py-2 rounded-xl transition"
        >
          {{ button }}
        </a>
      </div>
      <div
        class="w-full md:w-40 h-32 bg-gray-700 rounded-lg flex-shrink-0"
      >
        <img
          :src="imageUrl"
          :alt="title"
          class="w-full h-full object-cover rounded-lg"
        />
      </div>
    </section>
  </template>
  
  <script setup>
  defineProps({
    title: String,
    text: String,
    button: String,
    imageUrl: {
      type: String,
      default: 'https://via.placeholder.com/150'
    }
  })
  </script>
  