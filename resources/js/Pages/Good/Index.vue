<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Tabs from '@/Components/Tabs.vue';

defineProps({
    good: Object,
});
</script>

<template>
    <Head :title="good.title" />

    <AuthenticatedLayout :title="good.title">
        <section v-if="good" class="bg-gray-100 p-4 text-gray-900 dark:bg-gray-900 dark:text-gray-400 sm:p-6 lg:p-8">
            <div class="overflow-hidden bg-white px-4 shadow-sm dark:bg-gray-800 sm:rounded-lg sm:px-6 lg:px-8">
                <tabs :good="good.slug" />

                <section class="body-font overflow-hidden">
                    <div class="container mx-auto py-12">
                        <div class="flex flex-wrap">
                            <img
                                alt="ecommerce"
                                class="h-64 w-full rounded object-cover object-center lg:h-auto lg:w-1/2"
                                :src="good.preview"
                            />
                            <div class="mt-4 w-full lg:mt-0 lg:w-1/2 lg:py-6 lg:pl-10">
                                <h2 class="title-font text-sm tracking-widest text-gray-500">{{ good.brand.name }}</h2>
                                <h1 class="title-font mb-1 text-3xl font-medium text-white">{{ good.title }}</h1>
                                <div class="mb-4 flex">
                                    <span class="flex items-center">
                                        <font-awesome-icon
                                            :icon="['fas', 'star']"
                                            class="text-purple-600 dark:text-purple-400"
                                        />
                                        <font-awesome-icon
                                            :icon="['fas', 'star']"
                                            class="text-purple-600 dark:text-purple-400"
                                        />
                                        <font-awesome-icon
                                            :icon="['fas', 'star']"
                                            class="text-purple-600 dark:text-purple-400"
                                        />
                                        <font-awesome-icon
                                            :icon="['fas', 'star-half-stroke']"
                                            class="text-purple-600 dark:text-purple-400"
                                        />
                                        <font-awesome-icon
                                            :icon="['far', 'star']"
                                            class="text-purple-600 dark:text-purple-400"
                                        />

                                        <span class="ml-3">4 Reviews</span>
                                    </span>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="mt-2 flex flex-col">
                                        <p
                                            v-if="good.old_price"
                                            class="mt-1 text-sm font-medium text-gray-500 dark:text-gray-300"
                                        >
                                            <span class="line-through">{{ good.old_price }}</span>
                                            <span class="ml-1">₴</span>
                                        </p>
                                        <p
                                            :class="[
                                                good.old_price
                                                    ? 'text-red-600 dark:text-rose-600'
                                                    : 'text-gray-900 dark:text-gray-300',
                                                'text-3xl font-medium',
                                            ]"
                                        >
                                            {{ good.price }} ₴
                                        </p>
                                    </div>
                                    <button
                                        type="button"
                                        class="self-end rounded-lg bg-gradient-to-br from-purple-600 to-blue-500 px-5 py-2.5 text-center text-sm font-medium uppercase tracking-wider text-white focus:outline-none focus:ring-4 focus:ring-blue-300 hover:bg-gradient-to-bl dark:focus:ring-blue-800"
                                    >
                                        <font-awesome-icon :icon="['fas', 'cart-plus']" class="mr-2" />
                                        <span>Add to cart</span>
                                    </button>
                                </div>
                                <div class="mt-4 flex">
                                    <div
                                        class="flex items-center justify-center rounded border bg-purple-100 py-2 px-2 dark:border-purple-600 dark:bg-purple-900"
                                    >
                                        <span
                                            class="font-[Inter] text-xs uppercase tracking-wider text-purple-600 dark:text-purple-400"
                                            >{{ good.status }}</span
                                        >
                                    </div>
                                </div>
                                <p class="mt-4 leading-relaxed">{{ good.description }}</p>
                                <div class="mt-4 mb-5 flex items-center pb-5">
                                    <div class="flex">
                                        <span class="mr-3">Color</span>
                                        <button
                                            class="h-6 w-6 rounded-full border-2 border-gray-800 bg-fuchsia-600 focus:outline-none"
                                        ></button>
                                        <button
                                            class="ml-1 h-6 w-6 rounded-full border-2 border-gray-800 bg-gray-700 focus:outline-none"
                                        ></button>
                                        <button
                                            class="ml-1 h-6 w-6 rounded-full border-2 border-gray-800 bg-indigo-500 focus:outline-none"
                                        ></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </section>
    </AuthenticatedLayout>
</template>
