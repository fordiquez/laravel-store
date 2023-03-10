<script setup>
import { defineProps, defineEmits } from 'vue';
import Filter from '@/Components/Filter.vue';
import Slider from '@vueform/slider';

const props = defineProps({
    brands: Array,
    form: Object,
    rangePrices: Object,
});

defineEmits(['brandFilter', 'priceFilter']);

const sliderFormat = (value) => `${Math.round(value)} ₴`;
</script>

<template>
    <div :class="$attrs.class ?? 'hidden lg:block'">
        <Filter title="Brands" classes="space-y-4">
            <div v-for="brand in brands" :key="brand.id" class="group flex items-center">
                <input
                    type="checkbox"
                    :id="`filters-brands-${brand.id}`"
                    :name="`brands[]`"
                    :value="brand.id"
                    :checked="form.brands?.includes(brand.id)"
                    @change="$emit('brandFilter', brand.id)"
                    class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-purple-600 focus:ring-2 focus:ring-purple-500 group-hover:cursor-pointer dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-purple-600"
                />
                <label
                    :for="`filters-brands-${brand.id}`"
                    class="ml-2 text-sm font-medium text-gray-900 group-hover:cursor-pointer group-hover:text-purple-600 dark:text-gray-300 dark:group-hover:text-gray-50"
                >
                    {{ brand.name }}
                </label>
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
                    <label for="filters-price-to" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
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
                    @click="$emit('priceFilter')"
                    class="group relative inline-flex items-center justify-center self-end overflow-hidden rounded-lg bg-gradient-to-br from-purple-600 to-blue-500 p-0.5 text-sm font-medium text-gray-900 hover:text-white focus:outline-none focus:ring-4 focus:ring-blue-300 group-hover:from-purple-600 group-hover:to-blue-500 dark:text-white dark:focus:ring-blue-800"
                >
                    <span
                        class="relative rounded-md bg-white px-3 py-2.5 uppercase tracking-wider transition-all duration-75 ease-in group-hover:bg-opacity-0 dark:bg-gray-900"
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
    </div>
</template>
