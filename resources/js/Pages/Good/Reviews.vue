<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Tabs from '@/Pages/Good/Tabs.vue';
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import Rating from '@/Components/Rating.vue';
import InputLabel from '@/Components/InputLabel.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

defineProps({
    good: Object,
});

const reviewModal = ref(false);

const form = useForm({
    rating: null,
    advantages: '',
    disadvantages: '',
    comment: '',
});

const setRating = (stars) => (form.rating = stars);

const closeReviewModal = () => (reviewModal.value = false);
</script>

<template>
    <Head :title="good.title" />

    <AuthenticatedLayout :title="good.title">
        <section v-if="good" class="bg-gray-100 p-4 text-gray-900 dark:bg-gray-900 dark:text-gray-400 sm:p-6 lg:p-8">
            <div class="overflow-hidden bg-white px-4 shadow-sm dark:bg-gray-800 sm:rounded-lg sm:px-6 lg:px-8">
                <tabs :good="good.slug" />

                <div class="container mx-auto py-12">
                    <div
                        class="flex flex-col items-center space-x-6 rounded-xl border border-gray-200 p-4 dark:border-gray-700 md:flex-row"
                    >
                        <h1
                            class="bg-gradient-to-r from-blue-500 to-purple-600 bg-clip-text text-3xl font-bold text-transparent"
                        >
                            Leave your review about this product
                        </h1>
                        <secondary-button @click="reviewModal = true">Leave a review</secondary-button>
                    </div>

                    <Modal :show="reviewModal" @close="closeReviewModal">
                        <div class="p-6">
                            <div class="flex justify-between text-gray-900 dark:text-white">
                                <h5 class="text-xl font-medium">New review</h5>
                                <font-awesome-icon
                                    :icon="['fas', 'xmark']"
                                    size="xl"
                                    class="cursor-pointer"
                                    @click="closeReviewModal"
                                />
                            </div>

                            <Rating class="mt-4" @rating="setRating" />

                            <div class="mb-4">
                                <InputLabel for="advantages" value="Advantages" class="mb-2" />
                                <input
                                    v-model="form.advantages"
                                    type="text"
                                    id="advantages"
                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500 sm:text-sm"
                                />
                            </div>

                            <div class="mb-4">
                                <InputLabel for="disadvantages" value="Disadvantages" class="mb-2" />
                                <input
                                    v-model="form.disadvantages"
                                    type="text"
                                    id="disadvantages"
                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500 sm:text-sm"
                                />
                            </div>

                            <div class="mb-8">
                                <InputLabel for="comment" value="Your comment" class="mb-2" />
                                <textarea
                                    id="comment"
                                    v-model="form.comment"
                                    rows="4"
                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-purple-500 focus:ring-purple-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-purple-500 dark:focus:ring-purple-500"
                                    placeholder="Write your thoughts here..."
                                ></textarea>
                            </div>

                            <div class="flex space-x-4">
                                <secondary-button class="w-full" @click="closeReviewModal">Cancel</secondary-button>
                                <primary-button class="w-full">Submit review</primary-button>
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
                                    <span class="text-sm font-medium uppercase tracking-wide text-gray-500">{{
                                        review.is_buyer ? 'Buyer' : 'Commentator'
                                    }}</span>
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
