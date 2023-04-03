<script setup>
import { onMounted, defineProps, defineEmits, ref } from 'vue';
import { Drawer } from 'flowbite';
import Filters from '@/Components/Filters.vue';

const props = defineProps({
    brands: Array,
    properties: Object,
    filters: Object,
    rangePrices: Object,
});

const emits = defineEmits(['brandFilter', 'priceFilter', 'propertyFilter']);

const drawer = ref(null);
const drawerRef = ref(null);

onMounted(() => (drawer.value = new Drawer(drawerRef.value)));

const show = () => drawer.value.show();
const hide = () => drawer.value.hide();

const brandFilter = (brand) => {
    emits('brandFilter', brand);
    hide();
};

const priceFilter = () => {
    emits('priceFilter');
    hide();
};

const propertyFilter = (value) => {
    emits('propertyFilter', value);
    hide();
};
</script>

<template>
    <button
        @click="show"
        class="group relative mr-4 inline-flex items-center justify-center overflow-hidden rounded-lg bg-gradient-to-br from-purple-600 to-blue-500 p-0.5 font-medium text-gray-900 focus:outline-none focus:ring-4 focus:ring-blue-300 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white dark:focus:ring-blue-800 lg:hidden"
    >
        <span
            class="relative rounded-md bg-white px-5 py-2 tracking-wider transition-all duration-150 ease-in group-hover:bg-opacity-0 dark:bg-gray-900"
        >
            <font-awesome-icon :icon="['fas', 'filter']" class="mr-1" />
            FILTERS
        </span>
    </button>

    <div
        ref="drawerRef"
        id="filters-drawer"
        class="fixed top-0 left-0 z-40 h-screen w-80 -translate-x-full overflow-y-auto bg-white p-4 transition-transform dark:bg-gray-800 dark:[color-scheme:dark]"
        tabindex="-1"
        aria-labelledby="drawer-label"
    >
        <h5
            id="drawer-label"
            class="mb-4 inline-flex items-center text-lg font-medium text-gray-500 dark:text-gray-400"
        >
            <font-awesome-icon :icon="['fas', 'filter']" class="mr-2" />
            Filters
        </h5>
        <button
            @click="hide"
            type="button"
            class="absolute top-2.5 right-2.5 inline-flex items-center rounded-lg bg-transparent p-2 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white"
        >
            <font-awesome-icon :icon="['fas', 'xmark']" size="xl" />
        </button>

        <Filters
            class="block lg:hidden"
            :brands="brands"
            :properties="properties"
            :filters="filters"
            :range-prices="rangePrices"
            @brand-filter="brandFilter"
            @price-filter="priceFilter"
            @property-filter="propertyFilter"
        />
    </div>
</template>
