<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

interface PageProps {
    [key: string]: any;
    auth: {
        user: any;
    };
}

const page = usePage<PageProps>();
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="flex min-h-screen flex-col items-center bg-white p-6 text-gray-900 dark:bg-gray-900 dark:text-gray-100 lg:justify-center lg:p-8">
        <header class="mb-6 w-full max-w-[335px] text-sm lg:max-w-4xl">
            <nav class="flex items-center justify-end gap-4">
                <Link
                    v-if="page.props.auth.user"
                    :href="route('dashboard')"
                    class="inline-block rounded-sm border border-gray-300 px-5 py-1.5 text-sm leading-normal hover:border-gray-400 dark:border-gray-700 dark:hover:border-gray-600"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal hover:border-gray-300 dark:hover:border-gray-700"
                    >
                        Connexion
                    </Link>
                    <Link
                        :href="route('register')"
                        class="inline-block rounded-sm border border-gray-300 px-5 py-1.5 text-sm leading-normal hover:border-gray-400 dark:border-gray-700 dark:hover:border-gray-600"
                    >
                        Inscription
                    </Link>
                </template>
            </nav>
        </header>
        <main class="flex w-full max-w-[335px] flex-col gap-8 lg:max-w-4xl">
            <div class="text-center">
                <h1 class="text-4xl font-bold">Bienvenue sur notre plateforme</h1>
                <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
                    Découvrez nos événements et rejoignez la communauté
                </p>
            </div>
            <div class="grid gap-6 lg:grid-cols-2">
                <Link
                    :href="route('evenements.index')"
                    class="flex items-center justify-center rounded-lg bg-indigo-600 px-6 py-4 text-lg font-medium text-white hover:bg-indigo-700"
                >
                    Voir les événements
                </Link>
            </div>
        </main>
    </div>
</template>
