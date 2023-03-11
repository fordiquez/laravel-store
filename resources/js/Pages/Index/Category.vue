<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    category: Object,
});
</script>

<template>
    <Head :title="category.title" />

    <AuthenticatedLayout :category="category">
        <section v-if="category.subcategories" class="bg-gray-100 px-4 text-gray-400 dark:bg-gray-900 sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="flex flex-wrap">
                    <div
                        class="flex w-full flex-col p-4 sm:w-1/2 lg:w-1/3 xl:w-1/4 2xl:w-1/5"
                        v-for="subcategory in category.subcategories"
                        :key="subcategory.id"
                    >
                        <div class="group relative flex flex-col text-base sm:text-sm" :title="subcategory.title">
                            <div
                                v-if="subcategory.thumbnail"
                                class="aspect-w-1 aspect-h-1 mb-4 overflow-hidden rounded-lg group-hover:opacity-75"
                            >
                                <img
                                    :alt="subcategory.title"
                                    :src="subcategory.thumbnail"
                                    class="object-cover object-center"
                                />
                            </div>
                            <Link
                                class="text-center text-base font-medium text-gray-600 hover:text-gray-900 focus:text-gray-900 dark:text-gray-300 dark:hover:text-gray-100 dark:focus:text-gray-100"
                                :href="route('index.category', subcategory)"
                            >
                                <span class="absolute inset-0 z-10" />
                                <h2 class="text-center text-lg font-medium text-gray-800 dark:text-gray-200">
                                    {{ subcategory.title }}
                                </h2>
                            </Link>
                            <p
                                class="mt-1 text-center text-sm text-gray-500 hover:text-gray-900 focus:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:text-gray-100"
                            >
                                Shop now
                            </p>
                        </div>
                        <ul class="my-2 space-y-2" role="list">
                            <li v-for="item in subcategory.subcategories" :key="item.id" class="flex">
                                <Link
                                    class="text-sm text-gray-500 hover:text-gray-900 focus:text-gray-700 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:text-gray-100"
                                    :href="route('index.category', item)"
                                >
                                    {{ item.title }}
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </AuthenticatedLayout>
</template>
