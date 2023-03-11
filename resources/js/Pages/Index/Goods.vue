<script setup>
import { onMounted, reactive, ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { initDropdowns } from 'flowbite';
import Slider from '@vueform/slider';
import qs from 'qs';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import Filter from '@/Components/Filter.vue';

const props = defineProps({
    category: Object,
    goods: Object,
    brands: Array,
    filters: Object,
});

onMounted(() => {
    initDropdowns();
    if (props.goods.data) {
        const queries = qs.parse(window.location.search.substring(1));
        form.brands = queries?.brands?.map((brand) => Number.parseInt(brand)) ?? [];
        [rangePrices.min, rangePrices.max] = [props.filters.prices.min, props.filters.prices.max];
        form.prices = queries?.prices
            ? Object.values(queries.prices).map((value) => Number.parseInt(value))
            : Object.values(rangePrices);
        sort.value = queries?.sort;
    }
});

const form = useForm({
    brands: [],
    prices: [0, 100000],
});

const rangePrices = reactive({
    min: 0,
    max: 100000,
});

const sliderFormat = (value) => `${Math.round(value)} ₴`;

const brandFilter = (brand) => {
    const brandIndex = form.brands.indexOf(brand);
    brandIndex === -1 ? form.brands.push(brand) : form.brands.splice(brandIndex, 1);
    form.get(route('index.goods', props.category));
};

const priceFilter = () => {
    form.transform((data) => ({
        ...data,
        prices: {
            from: data.prices[0],
            to: data.prices[1],
        },
    })).get(route('index.goods', props.category));
};

const sort = ref('rating');

const goodsSort = (key) => {
    const data = form.data();
    useForm({
        ...data,
        prices: {
            from: data.prices[0],
            to: data.prices[1],
        },
        sort: key,
    }).get(route('index.goods', props.category));
};

const clearFilters = () => {
    form.transform(() => ({
        brands: [],
        prices: {
            from: rangePrices.min,
            to: rangePrices.max,
        },
    })).get(route('index.goods', props.category));
};

const sortOptions = [
    // {name: 'Best Rating', key: 'rating'},
    { name: 'Newest', key: 'created_at' },
    { name: 'Price: Low to High', key: 'price' },
    { name: 'Price: High to Low', key: '-price' },
];
</script>

<template>
    <Head :title="category.title" />

    <AuthenticatedLayout :category="category">
        <section v-if="goods?.data" class="bg-gray-100 px-4 text-gray-400 dark:bg-gray-900 sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white px-4 shadow-sm dark:bg-gray-800 sm:rounded-lg sm:px-6 lg:px-8">
                <div
                    class="flex items-baseline justify-between border-b border-gray-200 pt-5 pb-6 dark:border-gray-600"
                >
                    <div class="flex items-center">
                        <h6 class="text mr-2 font-bold tracking-tight text-gray-900 dark:text-gray-200">
                            {{ goods.data.length }} good{{ goods.data.length > 1 ? 's' : '' }} found
                        </h6>

                        <button
                            v-if="form.brands.length"
                            @click="clearFilters"
                            class="mx-1 rounded-full bg-purple-700 px-2 text-center text-sm font-medium text-white hover:bg-purple-800 focus:outline-none focus:ring-4 focus:ring-purple-300 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900"
                        >
                            <span>Clear</span>
                        </button>

                        <template v-for="brand in brands">
                            <button
                                v-if="form.brands?.includes(brand.id)"
                                :key="brand.id"
                                @click.prevent="brandFilter(brand.id)"
                                class="mx-1 rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800 dark:bg-purple-900 dark:text-purple-300"
                            >
                                <span class="mr-1">{{ brand.name }}</span>
                                <font-awesome-icon icon="fa-solid fa-xmark" class="ml-1" />
                            </button>
                        </template>
                    </div>

                    <div class="flex items-center">
                        <button
                            id="dropdownNavbarButton"
                            data-dropdown-toggle="dropdownNavbar"
                            class="flex w-full items-center justify-between border-b border-gray-100 py-2 pl-3 pr-4 font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:text-white md:w-auto md:border-0 md:p-0 md:hover:bg-transparent md:hover:text-blue-700 md:dark:hover:bg-transparent"
                        >
                            Sort
                            <font-awesome-icon icon="fa-solid fa-chevron-down" class="ml-2" />
                        </button>
                        <!-- Sort menu -->
                        <div
                            id="dropdownNavbar"
                            class="z-10 hidden w-44 divide-y divide-gray-100 rounded-lg bg-white shadow dark:divide-gray-600 dark:bg-gray-700"
                        >
                            <ul
                                class="py-2 text-sm text-gray-700 dark:text-gray-400"
                                aria-labelledby="dropdownNavbarButton"
                            >
                                <li
                                    v-for="option in sortOptions"
                                    :key="option.name"
                                    :class="[
                                        'hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white',
                                        { 'bg-gray-100 dark:bg-gray-600 dark:text-white': option.key === sort },
                                    ]"
                                >
                                    <button @click.prevent="goodsSort(option.key)" class="flex px-4 py-2 text-sm">
                                        <span>{{ option.name }}</span>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="pt-6 pb-24">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-4">
                        <form class="hidden lg:block">
                            <Filter title="Brands" classes="space-y-4">
                                <div v-for="brand in brands" :key="brand.id" class="group flex items-center">
                                    <input
                                        type="checkbox"
                                        :id="`filters-brands-${brand.id}`"
                                        :name="`brands[]`"
                                        :value="brand.id"
                                        :checked="form.brands?.includes(brand.id)"
                                        @change="brandFilter(brand.id)"
                                        class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-purple-600 focus:ring-2 focus:ring-purple-500 group-hover:cursor-pointer dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-purple-600"
                                    />
                                    <label
                                        :for="`filters-brands-${brand.id}`"
                                        class="ml-2 text-sm font-medium text-gray-900 group-hover:cursor-pointer group-hover:text-purple-600 dark:text-gray-300 dark:group-hover:text-gray-50"
                                        >{{ brand.name }}</label
                                    >
                                </div>
                            </Filter>

                            <Filter title="Prices">
                                <div class="flex items-center justify-between space-x-3">
                                    <div class="basis-1/3">
                                        <label
                                            for="filters-price-from"
                                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"
                                        >
                                            From
                                        </label>

                                        <input
                                            type="number"
                                            id="filters-price-from"
                                            v-model="form.prices[0]"
                                            placeholder="Min price"
                                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                        />
                                    </div>

                                    <div class="basis-1/3">
                                        <label
                                            for="filters-price-to"
                                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"
                                        >
                                            To
                                        </label>

                                        <input
                                            type="number"
                                            id="filters-price-to"
                                            v-model="form.prices[1]"
                                            placeholder="Max price"
                                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                        />
                                    </div>

                                    <button
                                        type="button"
                                        :disabled="form.processing"
                                        @click="priceFilter"
                                        class="group relative inline-flex items-center justify-center self-end overflow-hidden rounded-lg bg-gradient-to-br from-purple-600 to-blue-500 p-0.5 text-sm font-medium text-gray-900 hover:text-white focus:outline-none focus:ring-4 focus:ring-blue-300 group-hover:from-purple-600 group-hover:to-blue-500 dark:text-white dark:focus:ring-blue-800"
                                    >
                                        <span
                                            class="relative rounded-md bg-white px-3 py-2.5 transition-all duration-75 ease-in group-hover:bg-opacity-0 dark:bg-gray-900"
                                        >
                                            Ok
                                        </span>
                                    </button>
                                </div>

                                <Slider
                                    v-model="form.prices"
                                    :min="rangePrices.min"
                                    :max="rangePrices.max"
                                    :format="sliderFormat"
                                    show-tooltip="drag"
                                    class="mt-12 px-2"
                                />
                            </Filter>
                        </form>

                        <div class="lg:col-span-3">
                            <div class="flex flex-wrap">
                                <div
                                    v-for="good in goods.data"
                                    :key="good.id"
                                    class="group relative flex w-full flex-col px-4 pb-4 sm:w-1/2 lg:w-1/3 xl:w-1/4 2xl:w-1/5"
                                >
                                    <div
                                        class="min-h-80 aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80"
                                    >
                                        <img
                                            :src="good.preview"
                                            :alt="good.title"
                                            class="h-full w-full object-cover object-center lg:h-full lg:w-full"
                                        />
                                    </div>
                                    <div class="mt-4 flex flex-col">
                                        <h3 class="text-gray-700 dark:text-gray-200">
                                            <Link :href="route('index.good', good)">
                                                <span aria-hidden="true" class="absolute inset-0" />
                                                {{ good.title }}
                                            </Link>
                                        </h3>
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
                                                    'text-xl font-medium',
                                                ]"
                                            >
                                                {{ good.price }} ₴
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <pagination :links="goods.links" :meta="goods.meta" />
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AuthenticatedLayout>
</template>

<style>
@import url('@vueform/slider/themes/default.css');
</style>
