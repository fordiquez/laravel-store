<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { initDropdowns } from 'flowbite';
import qs from 'qs';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import FiltersDrawer from '@/Components/FiltersDrawer.vue';
import Filters from '@/Components/Filters.vue';
import {useFormat} from "@/composables/format";

const props = defineProps({
    title: String,
    category: Object,
    brands: Array,
    properties: Object,
    prices: Object,
    goods: Object,
});

const {formatMoney} = useFormat()

onMounted(() => {
    initDropdowns();
    if (props.goods.data) {
        const queries = qs.parse(window.location.search.substring(1));
        filters.brands = queries?.brands?.map((brand) => Number.parseInt(brand)) ?? [];
        [rangePrices.min, rangePrices.max] = [props.prices.min, props.prices.max];
        filters.prices = queries?.prices
            ? Object.values(queries.prices).map((value) => Number.parseInt(value))
            : Object.values(rangePrices);
        queries.properties ? filters.properties.push(...queries?.properties) : null;
        sort.value = queries?.sort;
    }
});

const filters = useForm({
    brands: [],
    prices: [0, 100000],
    properties: [],
});

const rangePrices = reactive({
    min: 0,
    max: 100000,
});

const formRoute = computed(() => (props.category ? route('goods.index', props.category) : route('goods.search')));

const brandFilter = (brand) => {
    const brandIndex = filters.brands.indexOf(brand);
    brandIndex === -1 ? filters.brands.push(brand) : filters.brands.splice(brandIndex, 1);
    filters
        .transform((data) => ({
            ...data,
            prices: {
                from: filters.prices[0],
                to: filters.prices[1],
            },
            sort: sort.value,
            search: props.title ?? '',
        }))
        .get(formRoute.value);
};

const priceFilter = () => {
    filters
        .transform((data) => ({
            ...data,
            prices: {
                from: filters.prices[0],
                to: filters.prices[1],
            },
            sort: sort.value,
            search: props.title ?? '',
        }))
        .get(formRoute.value);
};

const propertyFilter = (value) => {
    filters.properties.push(value);
    filters.get(formRoute.value);
};

const clearFilters = () => {
    filters
        .transform(() => ({
            brands: [],
            prices: {
                from: rangePrices.min,
                to: rangePrices.max,
            },
            sort: sort.value,
            search: props.title ?? '',
        }))
        .get(formRoute.value);
};

const sort = ref('rating');

const sortOptions = [
    // {name: 'Best Rating', key: 'rating'},
    { name: 'Newest', key: 'created_at' },
    { name: 'Price: Low to High', key: 'price' },
    { name: 'Price: High to Low', key: '-price' },
];

const goodsSort = (key) => {
    filters
        .transform((data) => ({
            ...data,
            prices: {
                from: filters.prices[0],
                to: filters.prices[1],
            },
            sort: key,
            search: props.title ?? '',
        }))
        .get(formRoute.value);
};
</script>

<template>
    <Head :title="category?.title ?? title" />

    <AuthenticatedLayout :title="category?.title ?? title">
        <section
            v-if="goods?.data"
            class="bg-gray-100 p-4 text-gray-900 dark:bg-gray-900 dark:text-gray-400 sm:p-6 lg:p-8"
        >
            <div class="overflow-hidden bg-white px-4 shadow-sm dark:bg-gray-800 sm:rounded-lg sm:px-6 lg:px-8">
                <div
                    class="flex items-baseline justify-between border-b border-gray-200 pt-5 pb-6 dark:border-gray-600"
                >
                    <div class="flex items-center">
                        <FiltersDrawer
                            v-if="goods.data.length"
                            :brands="brands"
                            :properties="properties"
                            :filters="filters"
                            :range-prices="rangePrices"
                            @brand-filter="brandFilter"
                            @price-filter="priceFilter"
                            @property-filter="propertyFilter"
                        />
                        <h6
                            v-if="goods.data.length"
                            class="text mr-2 hidden font-bold tracking-tight text-gray-900 dark:text-gray-200 sm:block"
                        >
                            {{ goods.meta.total }} good{{ goods.data.length > 1 ? 's' : '' }} found
                        </h6>

                        <div class="hidden lg:block">
                            <button
                                v-if="filters.brands.length || filters.properties.length"
                                @click="clearFilters"
                                class="mx-1 rounded-full bg-purple-700 px-2 text-center text-sm font-medium text-white focus:outline-none focus:ring-4 focus:ring-purple-300 hover:bg-purple-800 dark:bg-purple-600 dark:focus:ring-purple-900 dark:hover:bg-purple-700"
                            >
                                <span>Clear</span>
                            </button>

                            <template v-for="brand in brands">
                                <button
                                    v-if="filters.brands?.includes(brand.id)"
                                    :key="brand.id"
                                    @click.prevent="brandFilter(brand.id)"
                                    class="mx-1 rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800 dark:bg-purple-900 dark:text-purple-300"
                                >
                                    <span class="mr-1">{{ brand.name }}</span>
                                    <font-awesome-icon :icon="['fas', 'xmark']" class="ml-1" />
                                </button>
                            </template>

                            <template v-for="(property, i) in properties" :key="i">
                                <template v-for="value in property.values">
                                    <button
                                        v-if="filters.properties?.includes(value)"
                                        :key="value"
                                        class="mx-1 rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800 dark:bg-purple-900 dark:text-purple-300"
                                    >
                                        <span class="mr-1">{{ value }}</span>
                                        <font-awesome-icon :icon="['fas', 'xmark']" class="ml-1" />
                                    </button>
                                </template>
                            </template>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <button
                            id="dropdownNavbarButton"
                            data-dropdown-toggle="dropdownNavbar"
                            class="flex w-full items-center justify-between py-2 pl-3 pr-4 font-medium text-gray-700 hover:bg-gray-50 dark:text-gray-400 dark:focus:text-white dark:hover:bg-gray-700 dark:hover:text-white md:w-auto md:p-0 md:hover:bg-transparent md:hover:text-blue-700 md:dark:hover:bg-transparent"
                        >
                            Sort
                            <font-awesome-icon :icon="['fas', 'chevron-down']" class="ml-2" />
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
                        <Filters
                            v-if="goods.data.length"
                            :brands="brands"
                            :filters="filters"
                            :properties="properties"
                            :range-prices="rangePrices"
                            @brand-filter="brandFilter"
                            @price-filter="priceFilter"
                            @property-filter="propertyFilter"
                        />

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
                                            <Link :href="route('goods.good.general', good)">
                                                <span aria-hidden="true" class="absolute inset-0" />
                                                {{ good.title }}
                                            </Link>
                                        </h3>
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
                                                    'text-2xl font-medium',
                                                ]"
                                            >
                                                {{ formatMoney(good.price) }}
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
