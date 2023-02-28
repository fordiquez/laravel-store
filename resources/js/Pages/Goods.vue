<script setup>
import {ref} from 'vue'
import {
    Dialog,
    DialogPanel,
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue'
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const sortOptions = [
    {name: 'Most Popular', href: '#', current: true},
    {name: 'Best Rating', href: '#', current: false},
    {name: 'Newest', href: '#', current: false},
    {name: 'Price: Low to High', href: '#', current: false},
    {name: 'Price: High to Low', href: '#', current: false},
]
const subCategories = [
    {name: 'Totes', href: '#'},
    {name: 'Backpacks', href: '#'},
    {name: 'Travel Bags', href: '#'},
    {name: 'Hip Bags', href: '#'},
    {name: 'Laptop Sleeves', href: '#'},
]
const filters = [
    {
        id: 'color',
        name: 'Color',
        options: [
            {value: 'white', label: 'White', checked: false},
            {value: 'beige', label: 'Beige', checked: false},
            {value: 'blue', label: 'Blue', checked: true},
            {value: 'brown', label: 'Brown', checked: false},
            {value: 'green', label: 'Green', checked: false},
            {value: 'purple', label: 'Purple', checked: false},
        ],
    },
    {
        id: 'category',
        name: 'Category',
        options: [
            {value: 'new-arrivals', label: 'New Arrivals', checked: false},
            {value: 'sale', label: 'Sale', checked: false},
            {value: 'travel', label: 'Travel', checked: true},
            {value: 'organization', label: 'Organization', checked: false},
            {value: 'accessories', label: 'Accessories', checked: false},
        ],
    },
    {
        id: 'size',
        name: 'Size',
        options: [
            {value: '2l', label: '2L', checked: false},
            {value: '6l', label: '6L', checked: false},
            {value: '12l', label: '12L', checked: false},
            {value: '18l', label: '18L', checked: false},
            {value: '20l', label: '20L', checked: false},
            {value: '40l', label: '40L', checked: true},
        ],
    },
]

const mobileFiltersOpen = ref(false)

defineProps({
    goods: Array
})
</script>

<template>
    <AuthenticatedLayout>

        <!-- Mobile filter dialog -->
        <TransitionRoot as="template" :show="mobileFiltersOpen">
            <Dialog as="div" class="relative z-40 lg:hidden" @close="mobileFiltersOpen = false">
                <TransitionChild as="template" enter="transition-opacity ease-linear duration-300"
                                 enter-from="opacity-0" enter-to="opacity-100"
                                 leave="transition-opacity ease-linear duration-300" leave-from="opacity-100"
                                 leave-to="opacity-0">
                    <div class="fixed inset-0 bg-black bg-opacity-25"/>
                </TransitionChild>

                <div class="fixed inset-0 z-40 flex">
                    <TransitionChild as="template" enter="transition ease-in-out duration-300 transform"
                                     enter-from="translate-x-full" enter-to="translate-x-0"
                                     leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0"
                                     leave-to="translate-x-full">
                        <DialogPanel
                            class="relative ml-auto flex h-full w-full max-w-xs flex-col overflow-y-auto bg-white py-4 pb-12 shadow-xl">
                            <div class="flex items-center justify-between px-4">
                                <h2 class="text-lg font-medium text-gray-900">Filters</h2>
                                <button type="button"
                                        class="-mr-2 flex h-10 w-10 items-center justify-center rounded-md bg-white p-2 text-gray-400"
                                        @click="mobileFiltersOpen = false">
                                    <span class="sr-only">Close menu</span>
                                    <font-awesome-icon icon="fa-solid fa-xmark" class="h-6 w-6"/>
                                </button>
                            </div>

                            <!-- Filters -->
                            <form class="mt-4 border-t border-gray-200">
                                <h3 class="sr-only">Categories</h3>
                                <ul role="list" class="px-2 py-3 font-medium text-gray-900">
                                    <li v-for="category in subCategories" :key="category.name">
                                        <a :href="category.href" class="block px-2 py-3">{{ category.name }}</a>
                                    </li>
                                </ul>

                                <Disclosure as="div" v-for="section in filters" :key="section.id"
                                            class="border-t border-gray-200 px-4 py-6" v-slot="{ open }">
                                    <h3 class="-mx-2 -my-3 flow-root">
                                        <DisclosureButton
                                            class="flex w-full items-center justify-between bg-white px-2 py-3 text-gray-400 hover:text-gray-500">
                                            <span class="font-medium text-gray-900">{{ section.name }}</span>
                                            <span class="ml-6 flex items-center">
                                                <font-awesome-icon v-if="!open" icon="fa-solid fa-plus"
                                                                   class="h-5 w-5"/>
                                                <font-awesome-icon v-else icon="fa-solid fa-minus" class="h-5 w-5"/>
                                            </span>
                                        </DisclosureButton>
                                    </h3>
                                    <DisclosurePanel class="pt-6">
                                        <div class="space-y-6">
                                            <div v-for="(option, optionIdx) in section.options" :key="option.value"
                                                 class="flex items-center">
                                                <input :id="`filter-mobile-${section.id}-${optionIdx}`"
                                                       :name="`${section.id}[]`" :value="option.value" type="checkbox"
                                                       :checked="option.checked"
                                                       class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"/>
                                                <label :for="`filter-mobile-${section.id}-${optionIdx}`"
                                                       class="ml-3 min-w-0 flex-1 text-gray-500">{{
                                                        option.label
                                                    }}</label>
                                            </div>
                                        </div>
                                    </DisclosurePanel>
                                </Disclosure>
                            </form>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </Dialog>
        </TransitionRoot>

        <main class="mx-auto max-w-9xl px-4 sm:px-6 lg:px-8 bg-white dark:bg-gray-800">
            <div class="flex items-baseline justify-between border-b border-gray-200 dark:border-gray-600 pt-5 pb-6">
                <h1 class="text-4xl font-bold tracking-tight text-gray-900 dark:text-gray-200">New Arrivals</h1>

                <div class="flex items-center">
                    <Menu as="div" class="relative inline-block text-left">
                        <div>
                            <MenuButton
                                class="group inline-flex justify-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-200 dark:hover:text-gray-400">
                                Sort
                                <font-awesome-icon icon="fa-solid fa-chevron-down"
                                                   class="-mr-1 ml-1 h-5 w-5 flex-shrink-0 text-gray-400 group-hover:text-gray-500"/>
                            </MenuButton>
                        </div>

                        <transition enter-active-class="transition ease-out duration-100"
                                    enter-from-class="transform opacity-0 scale-95"
                                    enter-to-class="transform opacity-100 scale-100"
                                    leave-active-class="transition ease-in duration-75"
                                    leave-from-class="transform opacity-100 scale-100"
                                    leave-to-class="transform opacity-0 scale-95">
                            <MenuItems
                                class="absolute right-0 z-10 mt-2 w-40 origin-top-right rounded-md bg-white dark:bg-gray-800 shadow-2xl ring-1 ring-black ring-opacity-5 focus:outline-none">
                                <div class="py-1">
                                    <MenuItem v-for="option in sortOptions" :key="option.name" v-slot="{ active }">
                                        <a :href="option.href"
                                           :class="[option.current ? 'font-medium text-gray-900 dark:text-gray-100' : 'text-gray-500 dark:text-gray-400', active ? 'bg-gray-100 dark:bg-gray-900' : '', 'block px-4 py-2 text-sm']">{{
                                                option.name
                                            }}</a>
                                    </MenuItem>
                                </div>
                            </MenuItems>
                        </transition>
                    </Menu>

                    <button type="button" class="-m-2 ml-4 p-2 text-gray-400 hover:text-gray-500 sm:ml-6 lg:hidden"
                            @click="mobileFiltersOpen = true">
                        <span class="sr-only">Filters</span>
                        <font-awesome-icon icon="fa-solid fa-filter" class="h-5 w-5"/>
                    </button>
                </div>
            </div>

            <section aria-labelledby="products-heading" class="pt-6 pb-24">
                <h2 id="products-heading" class="sr-only">Products</h2>

                <div class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-4">
                    <!-- Filters -->
                    <form class="hidden lg:block">
                        <h3 class="sr-only">Categories</h3>
                        <ul role="list"
                            class="space-y-4 border-b border-gray-200 dark:border-gray-700 pb-6 text-sm font-medium text-gray-900 dark:text-gray-100">
                            <li v-for="category in subCategories" :key="category.name">
                                <a :href="category.href">{{ category.name }}</a>
                            </li>
                        </ul>

                        <Disclosure as="div" v-for="section in filters" :key="section.id"
                                    class="border-b border-gray-200 dark:border-gray-600 py-6" v-slot="{ open }">
                            <h3 class="-my-3 flow-root">
                                <DisclosureButton
                                    class="flex w-full items-center justify-between bg-white dark:bg-gray-800 py-3 text-sm text-gray-400 hover:text-gray-500">
                                    <span class="font-medium text-gray-900 dark:text-gray-200">{{ section.name }}</span>
                                    <span class="ml-6 flex items-center">
                                        <font-awesome-icon v-if="!open" icon="fa-solid fa-plus" class="h-5 w-5"/>
                                        <font-awesome-icon v-else icon="fa-solid fa-minus" class="h-5 w-5"/>
                                    </span>
                                </DisclosureButton>
                            </h3>
                            <DisclosurePanel class="pt-6">
                                <div class="space-y-4">
                                    <div v-for="(option, optionIdx) in section.options" :key="option.value"
                                         class="flex items-center">
                                        <input :id="`filter-${section.id}-${optionIdx}`" :name="`${section.id}[]`"
                                               :value="option.value" type="checkbox" :checked="option.checked"
                                               class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label :for="`filter-${section.id}-${optionIdx}`"
                                               class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ option.label }}</label>
                                    </div>
                                </div>
                            </DisclosurePanel>
                        </Disclosure>
                    </form>

                    <div class="lg:col-span-3">
                        <div v-if="goods.length"
                             class="grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
                            <a v-for="good in goods" :key="good.id" class="group">
                                <div v-if="good.preview"
                                     class="relative h-80 w-full overflow-hidden rounded-lg bg-white dark:bg-gray-800 group-hover:opacity-75 sm:aspect-w-2 sm:aspect-h-1 sm:h-64 lg:aspect-w-1 lg:aspect-h-1">
                                    <img :src="good.preview" :alt="good.title" :title="good.title"
                                         class="h-full w-full object-cover object-center group-hover:opacity-75"/>
                                </div>
                                <h3 class="mt-4 text-sm text-gray-700 dark:text-gray-200">{{ good.title }}</h3>
                                <p class="mt-1 text-lg font-medium text-gray-900 dark:text-gray-100">{{ good.price }} $</p>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </main>

    </AuthenticatedLayout>
</template>
