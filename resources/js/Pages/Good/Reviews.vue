<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Tabs from '@/Components/Tabs.vue';
import { reactive, ref } from 'vue';
import Modal from '@/Components/Modal.vue';

defineProps({
    good: Object,
});

const reviewModal = ref(false);

const stars = reactive(['far', 'far', 'far', 'far', 'far']);

const starRating = (number, remove = false) => {
    stars[number - 1] = remove ? 'far' : 'fas';
};
</script>

<template>
    <Head :title="good.title" />

    <AuthenticatedLayout :title="good.title">
        <section v-if="good" class="bg-gray-100 p-4 text-gray-900 dark:bg-gray-900 dark:text-gray-400 sm:p-6 lg:p-8">
            <div class="overflow-hidden bg-white px-4 shadow-sm dark:bg-gray-800 sm:rounded-lg sm:px-6 lg:px-8">
                <tabs :good="good.slug" />

                <div class="container mx-auto py-12">
                    <div class="flex items-center space-x-6 rounded-xl border border-gray-200 p-4 dark:border-gray-700">
                        <h1
                            class="bg-gradient-to-r from-blue-500 to-purple-600 bg-clip-text text-3xl font-bold text-transparent md:text-5xl lg:text-3xl"
                        >
                            Leave your review about this product
                        </h1>
                        <button
                            @click="reviewModal = true"
                            class="group relative inline-flex items-center justify-center overflow-hidden rounded-lg bg-gradient-to-br from-purple-600 to-blue-500 p-0.5 text-sm font-medium uppercase tracking-wider text-gray-900 focus:outline-none focus:ring-4 focus:ring-blue-300 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white dark:focus:ring-blue-800"
                        >
                            <span
                                class="relative rounded-md bg-white px-4 py-2.5 transition-all duration-150 ease-in group-hover:bg-opacity-0 dark:bg-gray-900"
                            >
                                Leave a review
                            </span>
                        </button>
                    </div>

                    <Modal :show="reviewModal" @close="reviewModal = false">
                        <div class="p-6">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Are you sure you want to delete your account?
                            </h2>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Once your account is deleted, all of its resources and data will be permanently deleted.
                                Please enter your password to confirm you would like to permanently delete your account.
                            </p>

                            <div>
                                <font-awesome-icon
                                    :icon="[stars[0], 'star']"
                                    size="2xl"
                                    @mouseenter="starRating(1)"
                                    @mouseleave="starRating(1, true)"
                                    class="text-purple-600 dark:text-purple-400"
                                />
                                <font-awesome-icon
                                    :icon="[stars[1], 'star']"
                                    size="2xl"
                                    @mouseenter="starRating(2)"
                                    @mouseleave="starRating(2, true)"
                                    class="text-purple-600 dark:text-purple-400"
                                />
                                <font-awesome-icon
                                    :icon="[stars[2], 'star']"
                                    @mouseenter="starRating(3)"
                                    @mouseleave="starRating(3, true)"
                                    size="2xl"
                                    class="text-purple-600 dark:text-purple-400"
                                />
                                <font-awesome-icon
                                    :icon="[stars[3], 'star']"
                                    size="2xl"
                                    @mouseenter="starRating(4)"
                                    @mouseleave="starRating(4, true)"
                                    class="text-purple-600 dark:text-purple-400"
                                />
                                <font-awesome-icon
                                    :icon="[stars[4], 'star']"
                                    @mouseenter="starRating(5)"
                                    @mouseleave="starRating(5, true)"
                                    size="2xl"
                                    class="text-purple-600 dark:text-purple-400"
                                />
                            </div>
                        </div>
                    </Modal>

                    <div
                        class="mt-4 flex flex-col rounded-xl border border-gray-200 dark:border-gray-700"
                        v-for="review in good.reviews"
                    >
                        <div class="flex w-full justify-between border-b border-gray-200 p-4 dark:border-gray-700">
                            <div class="flex">
                                <span class="flex items-center">
                                    <font-awesome-icon
                                        :icon="['fas', 'cart-shopping']"
                                        size="lg"
                                        class="text-purple-500"
                                    />
                                    <font-awesome-icon
                                        :icon="['fas', review.is_buyer ? 'circle-check' : 'circle-xmark']"
                                        class="ml-1 text-purple-500"
                                    />
                                </span>
                                <div class="ml-4">
                                    <h6 class="text-lg font-bold dark:text-white">{{ review.username }}</h6>
                                    <span
                                        v-if="review.is_buyer"
                                        class="text-sm font-medium uppercase tracking-wide text-gray-500"
                                        >Buyer</span
                                    >
                                </div>
                            </div>
                            <div class="flex items-center">
                                <span class="text-xs font-medium uppercase tracking-wide text-gray-500">{{
                                    review.created_at
                                }}</span>
                            </div>
                        </div>
                        <div class="flex flex-col p-4">
                            <div class="mb-4">
                                <font-awesome-icon
                                    v-for="(star, i) in Array(5)"
                                    :key="i"
                                    :icon="review.rating >= i + 1 ? ['fas', 'star'] : ['far', 'star']"
                                    class="text-purple-600 dark:text-purple-400"
                                />
                            </div>
                            <div>
                                <blockquote
                                    class="my-4 border-l-4 border-gray-400 bg-gray-100 p-4 dark:border-gray-600 dark:bg-gray-900"
                                >
                                    <p class="font-medium italic leading-relaxed text-gray-900 dark:text-white">
                                        "{{ review.content }}"
                                    </p>
                                </blockquote>
                                <div class="my-4 flex flex-col">
                                    <h6 class="flex items-center text-lg font-bold dark:text-white">
                                        <font-awesome-icon
                                            :icon="['fas', 'check']"
                                            class="text-green-500 dark:text-green-400"
                                        />
                                        <span class="ml-2">Advantages:</span>
                                    </h6>
                                    <blockquote
                                        class="my-4 border-l-4 border-gray-400 bg-gray-100 p-4 dark:border-gray-600 dark:bg-gray-900"
                                    >
                                        <p class="font-medium italic leading-relaxed text-gray-900 dark:text-white">
                                            "{{ review.advantages }}"
                                        </p>
                                    </blockquote>
                                    <p class="text-gray-500 dark:text-gray-400"></p>
                                </div>

                                <div class="my-4 flex flex-col">
                                    <h6 class="text-lg font-bold dark:text-white">
                                        <font-awesome-icon
                                            :icon="['fas', 'xmark']"
                                            class="text-red-600 dark:text-red-500"
                                        />
                                        <span class="ml-2">Disadvantages:</span>
                                    </h6>
                                    <blockquote
                                        class="my-4 border-l-4 border-gray-400 bg-gray-100 p-4 dark:border-gray-600 dark:bg-gray-900"
                                    >
                                        <p class="font-medium italic leading-relaxed text-gray-900 dark:text-white">
                                            "{{ review.disadvantages }}"
                                        </p>
                                    </blockquote>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AuthenticatedLayout>
</template>
