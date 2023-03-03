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
            <div class="mx-auto max-w-9xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                    <div
                        class="p-6 text-center text-xl font-semibold uppercase tracking-wider text-gray-900 dark:text-gray-100"
                    >
                        Find exactly what you need!
                    </div>
                    <div class="flex flex-wrap">
                        <div
                            class="flex flex-col p-4 sm:w-1/2 lg:w-1/3 xl:w-1/4"
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
                                    :href="route('index.content', category)"
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
                <section class="body-font bg-gray-900 text-gray-400">
                    <div class="container mx-auto px-5 py-24">
                        <div class="-m-4 flex flex-wrap">
                            <div class="w-full p-4 md:w-1/2 lg:w-1/4">
                                <a class="relative block h-48 overflow-hidden rounded" href="#">
                                    <img
                                        alt="ecommerce"
                                        class="block h-full w-full object-cover object-center"
                                        src="https://dummyimage.com/420x260"
                                    />
                                </a>
                                <div class="mt-4">
                                    <h3 class="title-font mb-1 text-xs tracking-widest text-gray-500">CATEGORY</h3>
                                    <h2 class="title-font text-lg font-medium text-white">The Catalyzer</h2>
                                    <p class="mt-1">$16.00</p>
                                </div>
                            </div>
                            <div class="w-full p-4 md:w-1/2 lg:w-1/4">
                                <a class="relative block h-48 overflow-hidden rounded">
                                    <img
                                        alt="ecommerce"
                                        class="block h-full w-full object-cover object-center"
                                        src="https://dummyimage.com/421x261"
                                    />
                                </a>
                                <div class="mt-4">
                                    <h3 class="title-font mb-1 text-xs tracking-widest text-gray-500">CATEGORY</h3>
                                    <h2 class="title-font text-lg font-medium text-white">Shooting Stars</h2>
                                    <p class="mt-1">$21.15</p>
                                </div>
                            </div>
                            <div class="w-full p-4 md:w-1/2 lg:w-1/4">
                                <a class="relative block h-48 overflow-hidden rounded">
                                    <img
                                        alt="ecommerce"
                                        class="block h-full w-full object-cover object-center"
                                        src="https://dummyimage.com/422x262"
                                    />
                                </a>
                                <div class="mt-4">
                                    <h3 class="title-font mb-1 text-xs tracking-widest text-gray-500">CATEGORY</h3>
                                    <h2 class="title-font text-lg font-medium text-white">Neptune</h2>
                                    <p class="mt-1">$12.00</p>
                                </div>
                            </div>
                            <div class="w-full p-4 md:w-1/2 lg:w-1/4">
                                <a class="relative block h-48 overflow-hidden rounded">
                                    <img
                                        alt="ecommerce"
                                        class="block h-full w-full object-cover object-center"
                                        src="https://dummyimage.com/423x263"
                                    />
                                </a>
                                <div class="mt-4">
                                    <h3 class="title-font mb-1 text-xs tracking-widest text-gray-500">CATEGORY</h3>
                                    <h2 class="title-font text-lg font-medium text-white">The 400 Blows</h2>
                                    <p class="mt-1">$18.40</p>
                                </div>
                            </div>
                            <div class="w-full p-4 md:w-1/2 lg:w-1/4">
                                <a class="relative block h-48 overflow-hidden rounded">
                                    <img
                                        alt="ecommerce"
                                        class="block h-full w-full object-cover object-center"
                                        src="https://dummyimage.com/424x264"
                                    />
                                </a>
                                <div class="mt-4">
                                    <h3 class="title-font mb-1 text-xs tracking-widest text-gray-500">CATEGORY</h3>
                                    <h2 class="title-font text-lg font-medium text-white">The Catalyzer</h2>
                                    <p class="mt-1">$16.00</p>
                                </div>
                            </div>
                            <div class="w-full p-4 md:w-1/2 lg:w-1/4">
                                <a class="relative block h-48 overflow-hidden rounded">
                                    <img
                                        alt="ecommerce"
                                        class="block h-full w-full object-cover object-center"
                                        src="https://dummyimage.com/425x265"
                                    />
                                </a>
                                <div class="mt-4">
                                    <h3 class="title-font mb-1 text-xs tracking-widest text-gray-500">CATEGORY</h3>
                                    <h2 class="title-font text-lg font-medium text-white">Shooting Stars</h2>
                                    <p class="mt-1">$21.15</p>
                                </div>
                            </div>
                            <div class="w-full p-4 md:w-1/2 lg:w-1/4">
                                <a class="relative block h-48 overflow-hidden rounded">
                                    <img
                                        alt="ecommerce"
                                        class="block h-full w-full object-cover object-center"
                                        src="https://dummyimage.com/427x267"
                                    />
                                </a>
                                <div class="mt-4">
                                    <h3 class="title-font mb-1 text-xs tracking-widest text-gray-500">CATEGORY</h3>
                                    <h2 class="title-font text-lg font-medium text-white">Neptune</h2>
                                    <p class="mt-1">$12.00</p>
                                </div>
                            </div>
                            <div class="w-full p-4 md:w-1/2 lg:w-1/4">
                                <a class="relative block h-48 overflow-hidden rounded">
                                    <img
                                        alt="ecommerce"
                                        class="block h-full w-full object-cover object-center"
                                        src="https://dummyimage.com/428x268"
                                    />
                                </a>
                                <div class="mt-4">
                                    <h3 class="title-font mb-1 text-xs tracking-widest text-gray-500">CATEGORY</h3>
                                    <h2 class="title-font text-lg font-medium text-white">The 400 Blows</h2>
                                    <p class="mt-1">$18.40</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
