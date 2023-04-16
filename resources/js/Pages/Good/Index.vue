<script setup>
import { Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Tabs from '@/Pages/Good/Tabs.vue';
import { computed, onMounted, reactive, ref } from 'vue';
import '@splidejs/vue-splide/css/sea-green';
import { initTooltips } from 'flowbite';
import { useFormat } from '@/composables/format';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    good: Object,
});

const mainOptions = reactive({
    type: 'fade',
    perMove: 1,
    gap: '1rem',
    pagination: false,
    rewind: true,
    trimSpace: true,
});

const thumbsOptions = reactive({
    type: 'slide',
    fixedWidth: 100,
    fixedHeight: 150,
    gap: 10,
    focus: 'center',
    rewind: true,
    cover: true,
    pagination: false,
    isNavigation: true,
    dragMinThreshold: {
        mouse: 4,
        touch: 10,
    },
});

const main = ref();
const thumbs = ref();
const { formatMoney } = useFormat();

onMounted(() => {
    initTooltips();
    const thumbsSplide = thumbs.value?.splide;
    if (thumbsSplide) {
        main.value?.sync(thumbsSplide);
    }
});

const stars = reactive([0, 1, 2, 3, 4]);

const ratingStars = computed(() =>
    stars.map((star) =>
        props.good.rating > star && props.good.rating < star + 1
            ? ['fas', 'star-half-stroke']
            : props.good.rating > star
            ? ['fas', 'star']
            : ['far', 'star'],
    ),
);

const store = () => router.post(route('cart.store', props.good));
</script>

<template>
    <Head :title="good.title" />

    <AuthenticatedLayout :title="good.title">
        <section v-if="good" class="bg-gray-100 p-4 text-gray-900 dark:bg-gray-900 dark:text-gray-400 sm:p-6 lg:p-8">
            <div class="overflow-hidden bg-white px-4 shadow-sm dark:bg-gray-800 sm:rounded-lg sm:px-6 lg:px-8">
                <tabs :good="good.slug" />

                <div class="container flex flex-wrap overflow-hidden py-4">
                    <div v-if="good.slides.length > 1" class="w-full md:w-1/2">
                        <Splide :options="mainOptions" ref="main">
                            <SplideSlide v-for="slide in good.slides" :key="slide">
                                <div class="flex justify-center">
                                    <img class="rounded-lg" :src="slide" :alt="good.title" :title="good.title" />
                                </div>
                            </SplideSlide>
                        </Splide>

                        <Splide :options="thumbsOptions" ref="thumbs">
                            <SplideSlide v-for="slide in good.slides" :key="slide">
                                <img :src="slide" :alt="good.title" />
                            </SplideSlide>
                        </Splide>
                    </div>
                    <div v-else class="flex w-full justify-center md:w-1/2">
                        <img
                            :alt="good.title"
                            :title="good.title"
                            class="h-auto w-full rounded object-cover object-center"
                            :src="good.preview"
                        />
                    </div>
                    <div class="mt-4 w-full md:w-1/2 lg:mt-0 lg:py-6 lg:pl-10">
                        <h2 class="title-font text-sm tracking-widest text-gray-500">{{ good.brand.name }}</h2>
                        <h1 class="title-font mb-1 text-3xl font-medium text-white">{{ good.title }}</h1>
                        <div class="mb-4 flex">
                            <div class="flex cursor-pointer items-center">
                                <div
                                    id="tooltip-default"
                                    role="tooltip"
                                    class="tooltip invisible absolute z-10 inline-block rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white opacity-0 shadow-sm transition-opacity duration-300 dark:bg-gray-700"
                                >
                                    <span>{{ good.rating }}</span>
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                                <div data-tooltip-target="tooltip-default" class="inline-flex">
                                    <font-awesome-icon
                                        v-for="(star, i) in ratingStars"
                                        :key="i"
                                        :icon="star"
                                        class="text-purple-600 dark:text-purple-400"
                                    />
                                </div>

                                <span class="ml-2">
                                    <span class="text-purple-400">{{ good.reviews_count }}</span>
                                    review{{ good.reviews_count > 1 ? 's' : '' }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="mt-2 flex flex-col">
                                <p
                                    v-if="good.old_price"
                                    class="mt-1 text-sm font-medium text-gray-500 dark:text-gray-300"
                                >
                                    <span class="line-through">{{ formatMoney(good.old_price) }}</span>
                                </p>
                                <p
                                    :class="[
                                        good.old_price
                                            ? 'text-red-600 dark:text-rose-600'
                                            : 'text-gray-900 dark:text-gray-300',
                                        'text-3xl font-medium',
                                    ]"
                                >
                                    {{ formatMoney(good.price) }}
                                </p>
                            </div>
                            <primary-button class="self-end" @click.prevent="store">
                                <font-awesome-icon :icon="['fas', 'cart-plus']" class="mr-2" />
                                <span>Add to cart</span>
                            </primary-button>
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
    </AuthenticatedLayout>
</template>
