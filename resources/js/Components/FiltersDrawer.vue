<script setup lang="ts">
import { onMounted, defineProps, defineEmits, ref } from 'vue'
import { Drawer } from 'flowbite'
import Filters from '@/Components/Filters.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'

defineProps<{
    brands: any[]
    properties: any
    filters: any
    rangePrices: any
}>()

const emits = defineEmits(['brandFilter', 'priceFilter', 'propertyFilter'])

const drawer = ref<Drawer | null>(null)
const drawerRef = ref(null)

onMounted(() => (drawer.value = new Drawer(drawerRef.value)))

const show = () => drawer.value?.show()
const hide = () => drawer.value?.hide()

const brandFilter = (brand: any) => {
    emits('brandFilter', brand)
    hide()
}

const priceFilter = () => {
    emits('priceFilter')
    hide()
}

const propertyFilter = (value: any) => {
    emits('propertyFilter', value)
    hide()
}
</script>

<template>
    <secondary-button class="lg:hidden" @click="show">
        <font-awesome-icon :icon="['fas', 'filter']" class="mr-1" />
        FILTERS
    </secondary-button>

    <div
        ref="drawerRef"
        id="filters-drawer"
        class="fixed left-0 top-0 z-40 h-screen w-80 -translate-x-full overflow-y-auto bg-white p-4 transition-transform dark:bg-gray-800 dark:[color-scheme:dark]"
        tabindex="-1"
    >
        <h5 class="mb-4 inline-flex items-center text-lg font-medium text-gray-500 dark:text-gray-400">
            <font-awesome-icon :icon="['fas', 'filter']" class="mr-2" />
            Filters
        </h5>
        <button
            @click="hide"
            type="button"
            class="absolute right-2.5 top-2.5 inline-flex items-center rounded-lg bg-transparent p-2 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white"
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
