<script setup>
import { computed, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Popover, PopoverButton, PopoverGroup, PopoverPanel } from '@headlessui/vue';

const props = defineProps({
    categories: Array,
});

const popover = ref(false);

let hoveredCategoryId = ref(props.categories[0].id);

let hoveredCategory = computed(() => props.categories.find((category) => category.id === hoveredCategoryId.value));

const onCategory = (category) => (hoveredCategoryId.value = category.id);
</script>

<template>
    <PopoverGroup class="hidden lg:ml-8 lg:block lg:self-stretch">
        <div class="flex h-full space-x-8">
            <Popover v-slot="{ popover }" class="flex">
                <div class="relative flex">
                    <PopoverButton
                        :class="[
                            popover
                                ? 'border-indigo-400 text-gray-900 focus:border-indigo-700 dark:border-indigo-600 dark:text-gray-100'
                                : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 focus:border-gray-300 focus:text-gray-700 dark:text-gray-400 dark:hover:border-gray-700 dark:hover:text-gray-300 dark:focus:border-gray-700 dark:focus:text-gray-300',
                            'relative inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none',
                        ]"
                    >
                        Categories
                    </PopoverButton>
                </div>

                <Transition
                    enter-active-class="transition ease-out duration-200"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="transition ease-in duration-150"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <PopoverPanel class="absolute inset-x-0 top-full z-20 text-sm text-gray-500">
                        <div aria-hidden="true" class="absolute inset-0 top-1/2 bg-white shadow" />
                        <div class="relative top-[1px] bg-white dark:bg-gray-800">
                            <div class="mx-auto max-w-9xl px-4">
                                <div class="flex justify-between py-8">
                                    <div class="flex basis-1/4 xl:basis-1/5">
                                        <ul class="flex flex-col space-y-1.5">
                                            <li
                                                v-for="category in categories"
                                                :key="category.id"
                                                :class="[
                                                    'rounded-md',
                                                    hoveredCategoryId === category.id
                                                        ? 'bg-indigo-600 text-white'
                                                        : 'text-gray-500 dark:text-gray-300',
                                                ]"
                                                @mouseover="onCategory(category)"
                                                @focusin="onCategory(category)"
                                            >
                                                <Link
                                                    class="flex rounded-md p-1.5 font-semibold focus:outline-none focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-700"
                                                    :href="route('index.content', category)"
                                                >
                                                    {{ category.title }}
                                                </Link>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="flex h-[300px] grow flex-col flex-wrap overflow-hidden">
                                        <ul
                                            v-for="subcategory in hoveredCategory.subcategories"
                                            :key="subcategory.id"
                                            class="flex"
                                        >
                                            <li class="flex flex-col">
                                                <Link
                                                    :id="subcategory.slug"
                                                    class="text-base font-medium text-gray-600 hover:text-gray-900 focus:text-gray-700 dark:text-gray-300 dark:hover:text-gray-100 dark:focus:text-gray-100"
                                                    :href="route('index.content', subcategory)"
                                                >
                                                    {{ subcategory.title }}
                                                </Link>
                                                <ul class="my-2 space-y-2" role="list">
                                                    <li
                                                        v-for="item in subcategory.subcategories"
                                                        :key="item.id"
                                                        class="flex"
                                                    >
                                                        <Link
                                                            :href="route('index.content', item)"
                                                            class="text-sm text-gray-500 hover:text-gray-900 focus:text-gray-700 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:text-gray-100"
                                                        >
                                                            {{ item.title }}
                                                        </Link>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="hidden basis-1/5 xl:block">
                                        <div class="group relative text-base sm:text-sm">
                                            <div
                                                v-if="hoveredCategory.thumbnail"
                                                class="aspect-w-1 aspect-h-1 mb-2 overflow-hidden rounded-lg bg-gray-100 group-hover:opacity-75"
                                            >
                                                <img
                                                    :alt="hoveredCategory.title"
                                                    :src="hoveredCategory.thumbnail"
                                                    :title="hoveredCategory.title"
                                                    class="object-cover object-center"
                                                />
                                            </div>
                                            <Link
                                                class="text-base font-medium text-gray-600 hover:text-gray-900 focus:text-gray-900 dark:text-gray-300 dark:hover:text-gray-100 dark:focus:text-gray-100"
                                                :href="route('index.content', hoveredCategory)"
                                            >
                                                <span class="absolute inset-0 z-20" />
                                                {{ hoveredCategory.title }}
                                            </Link>
                                            <p
                                                class="mt-1 text-sm text-gray-500 hover:text-gray-900 focus:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:text-gray-100"
                                            >
                                                Shop now
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </PopoverPanel>
                </Transition>
            </Popover>
        </div>
    </PopoverGroup>
</template>
