<template>
    <div class="min-h-screen flex flex-col bg-gradient-to-tr from-indigo-900 to-gray-900">
        <Header />
        <main class="flex-grow py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden rounded-lg border border-indigo-500/30 bg-gray-900 bg-opacity-90 shadow-md backdrop-blur-sm">
                    <div class="p-6">
                        <h1 class="text-4xl font-bold text-white tracking-[0.05em] mb-8 text-center">{{ title }}</h1>
                        <p class="text-white/80 mb-8">{{ text }}</p>
                        <div class="flex justify-center">
                            <Button class="bg-indigo-600 hover:bg-indigo-700 text-white transition-colors">
                                {{ buttonText }}
                            </Button>
                        </div>
                    </div>
                </div>
                <div class="mt-8">
                    <img :src="image" :alt="title" class="w-full rounded-lg shadow-lg" />
                </div>
            </div>
        </main>
        <Footer />
    </div>
</template>
  
<script setup>
defineProps({
  title: String,
  text: String,
  buttonText: String,
  image: {
    type: String,
    default: 'https://via.placeholder.com/150'
  }
})
</script>
  