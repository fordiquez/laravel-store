<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-9xl px-4 sm:px-6 lg:px-8">
                <div class="overflow-hidden rounded-lg bg-white shadow-sm dark:bg-gray-800">
                    <div
                        class="p-6 text-center text-xl font-semibold uppercase tracking-wider text-gray-900 dark:text-gray-100"
                    >
                        Find exactly what you need!
                    </div>
                    <div class="flex flex-wrap">
                        <div
                            class="flex basis-full flex-col p-4 sm:basis-1/2 md:basis-1/3 lg:basis-1/4 xl:basis-1/5 2xl:basis-1/6"
                            v-for="category in usePage().props.categories"
                            :key="category.id"
                        >
                            <div class="group relative flex flex-col text-base sm:text-sm" :title="category.title">
                                <div
                                    v-if="category.thumbnail"
                                    class="aspect-w-1 aspect-h-1 mb-4 overflow-hidden rounded-lg group-hover:opacity-75"
                                >
                                    <img
                                        :alt="category.title"
                                        :src="category.thumbnail"
                                        class="object-cover object-center"
                                    />
                                </div>
                                <Link
                                    class="text-center text-base font-medium text-gray-600 hover:text-gray-900 focus:text-gray-900 dark:text-gray-300 dark:hover:text-gray-100 dark:focus:text-gray-100"
                                    :href="route('index.category', category)"
                                >
                                    <span class="absolute inset-0 z-10" />
                                    <h2 class="text-center text-lg font-medium text-gray-800 dark:text-gray-200">
                                        {{ category.title }}
                                    </h2>
                                </Link>
                                <p
                                    class="mt-1 text-center text-sm text-gray-500 hover:text-gray-900 focus:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:text-gray-100"
                                >
                                    Shop now
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
