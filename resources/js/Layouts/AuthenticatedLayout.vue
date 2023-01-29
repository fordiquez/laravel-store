<script setup>
import { computed, reactive, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Popover, PopoverButton, PopoverGroup, PopoverPanel } from "@headlessui/vue";
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import ResponsiveCategories from "@/Components/ResponsiveCategories.vue";

const showingNavigationDropdown = ref(false);
const open = ref(false)
const showingResponsiveCategories = ref(false)

const categories = reactive([
        {
            "id": 1,
            "title": "Laptops and Computers",
            "slug": "laptops-and-computers",
            "photo": "http://localhost:8000/storage/categories/December2022/laptops-and-computers.jpg",
            "parent_id": null,
            "subcategories": [
                {
                    "id": 2,
                    "title": "Laptops",
                    "slug": "laptops",
                    "photo": "http://localhost:8000/storage/categories/December2022/laptops.jpg",
                    "parent_id": 1,
                    "created_at": "2022-12-05T05:38:22.000000Z",
                    "updated_at": "2022-12-05T05:38:22.000000Z",
                    "deleted_at": null
                },
                {
                    "id": 3,
                    "title": "Computers",
                    "slug": "computers",
                    "photo": "http://localhost:8000/storage/categories/December2022/computers.jpg",
                    "parent_id": 1,
                    "created_at": "2022-12-05T05:38:23.000000Z",
                    "updated_at": "2022-12-05T05:38:23.000000Z",
                    "deleted_at": null
                },
                {
                    "id": 4,
                    "title": "Monitors",
                    "slug": "monitors",
                    "photo": "http://localhost:8000/storage/categories/December2022/monitors.jpg",
                    "parent_id": 1,
                    "created_at": "2022-12-05T05:38:24.000000Z",
                    "updated_at": "2022-12-05T05:38:24.000000Z",
                    "deleted_at": null
                },
                {
                    "id": 5,
                    "title": "Tablets",
                    "slug": "tablets",
                    "photo": "http://localhost:8000/storage/categories/December2022/tablets.jpg",
                    "parent_id": 1,
                    "created_at": "2022-12-05T05:38:26.000000Z",
                    "updated_at": "2022-12-05T05:38:26.000000Z",
                    "deleted_at": null
                },
                {
                    "id": 6,
                    "title": "Computer components",
                    "slug": "computer-components",
                    "photo": "http://localhost:8000/storage/categories/December2022/computer-components.jpg",
                    "parent_id": 1,
                    "created_at": "2022-12-05T05:38:26.000000Z",
                    "updated_at": "2022-12-05T05:38:26.000000Z",
                    "deleted_at": null,
                    "subcategories": [
                        {
                            "id": 7,
                            "title": "SSD",
                            "slug": "ssd",
                            "photo": "http://localhost:8000/storage/categories/December2022/ssd.jpg",
                            "parent_id": 6,
                            "created_at": "2022-12-05T05:38:27.000000Z",
                            "updated_at": "2022-12-05T05:38:27.000000Z",
                            "deleted_at": null
                        },
                        {
                            "id": 8,
                            "title": "Cooling systems",
                            "slug": "cooling-systems",
                            "photo": "http://localhost:8000/storage/categories/December2022/cooling-systems.jpg",
                            "parent_id": 6,
                            "created_at": "2022-12-05T05:38:29.000000Z",
                            "updated_at": "2022-12-05T05:38:29.000000Z",
                            "deleted_at": null
                        },
                        {
                            "id": 9,
                            "title": "Video cards",
                            "slug": "video-cards",
                            "photo": "http://localhost:8000/storage/categories/December2022/video-cards.jpg",
                            "parent_id": 6,
                            "created_at": "2022-12-05T05:38:30.000000Z",
                            "updated_at": "2022-12-05T05:38:30.000000Z",
                            "deleted_at": null
                        },
                        {
                            "id": 10,
                            "title": "RAM",
                            "slug": "ram",
                            "photo": "http://localhost:8000/storage/categories/December2022/ram.jpg",
                            "parent_id": 6,
                            "created_at": "2022-12-05T05:38:31.000000Z",
                            "updated_at": "2022-12-05T05:38:31.000000Z",
                            "deleted_at": null
                        },
                        {
                            "id": 11,
                            "title": "Processors",
                            "slug": "processors",
                            "photo": "http://localhost:8000/storage/categories/December2022/processors.jpg",
                            "parent_id": 6,
                            "created_at": "2022-12-05T05:38:32.000000Z",
                            "updated_at": "2022-12-05T05:38:32.000000Z",
                            "deleted_at": null
                        },
                        {
                            "id": 12,
                            "title": "Motherboards",
                            "slug": "motherboards",
                            "photo": "http://localhost:8000/storage/categories/December2022/motherboards.jpg",
                            "parent_id": 6,
                            "created_at": "2022-12-05T05:38:34.000000Z",
                            "updated_at": "2022-12-05T05:38:34.000000Z",
                            "deleted_at": null
                        },
                        {
                            "id": 13,
                            "title": "Hard drives",
                            "slug": "hard-drives",
                            "photo": "http://localhost:8000/storage/categories/December2022/hard-drives.jpg",
                            "parent_id": 6,
                            "created_at": "2022-12-05T05:38:34.000000Z",
                            "updated_at": "2022-12-05T05:38:34.000000Z",
                            "deleted_at": null
                        },
                        {
                            "id": 14,
                            "title": "Power supply units",
                            "slug": "power-supply-units",
                            "photo": "http://localhost:8000/storage/categories/December2022/power-supply-units.jpg",
                            "parent_id": 6,
                            "created_at": "2022-12-05T05:38:35.000000Z",
                            "updated_at": "2022-12-05T05:38:35.000000Z",
                            "deleted_at": null
                        }
                    ]
                },
                {
                    "id": 15,
                    "title": "Headphones and accessories",
                    "slug": "headphones-and-accessories",
                    "photo": "http://localhost:8000/storage/categories/December2022/headphones-and-accessories.jpg",
                    "parent_id": 1,
                    "created_at": "2022-12-05T05:38:36.000000Z",
                    "updated_at": "2022-12-05T05:38:36.000000Z",
                    "deleted_at": null
                },
                {
                    "id": 16,
                    "title": "Keyboards and mouses",
                    "slug": "keyboards-and-mouses",
                    "photo": "http://localhost:8000/storage/categories/December2022/keyboards-and-mouses.jpg",
                    "parent_id": 1,
                    "created_at": "2022-12-05T05:38:37.000000Z",
                    "updated_at": "2022-12-05T05:38:37.000000Z",
                    "deleted_at": null,
                    "subcategories": [
                        {
                            "id": 17,
                            "title": "Computer mouses",
                            "slug": "computer-mouses",
                            "photo": "http://localhost:8000/storage/categories/December2022/computer-mouses.jpg",
                            "parent_id": 16,
                            "created_at": "2022-12-05T05:38:38.000000Z",
                            "updated_at": "2022-12-05T05:38:38.000000Z",
                            "deleted_at": null
                        },
                        {
                            "id": 18,
                            "title": "Mouse mats",
                            "slug": "mouse-mats",
                            "photo": "http://localhost:8000/storage/categories/December2022/mouse-mats.jpg",
                            "parent_id": 16,
                            "created_at": "2022-12-05T05:38:39.000000Z",
                            "updated_at": "2022-12-05T05:38:39.000000Z",
                            "deleted_at": null
                        },
                        {
                            "id": 19,
                            "title": "Keyboards",
                            "slug": "keyboards",
                            "photo": "http://localhost:8000/storage/categories/December2022/keyboards.jpg",
                            "parent_id": 16,
                            "created_at": "2022-12-05T05:38:40.000000Z",
                            "updated_at": "2022-12-05T05:38:40.000000Z",
                            "deleted_at": null
                        }
                    ]
                },
                {
                    "id": 20,
                    "title": "Accessories for electronics",
                    "slug": "accessories-for-electronics",
                    "photo": "http://localhost:8000/storage/categories/December2022/accessories-for-electronics.jpg",
                    "parent_id": 1,
                    "created_at": "2022-12-05T05:38:40.000000Z",
                    "updated_at": "2022-12-05T05:38:40.000000Z",
                    "deleted_at": null,
                    "subcategories": [
                        {
                            "id": 21,
                            "title": "USB flash drives",
                            "slug": "usb-flash-drives",
                            "photo": "http://localhost:8000/storage/categories/December2022/usb-flash-drives.jpg",
                            "parent_id": 20,
                            "created_at": "2022-12-05T05:38:41.000000Z",
                            "updated_at": "2022-12-05T05:38:41.000000Z",
                            "deleted_at": null
                        },
                        {
                            "id": 22,
                            "title": "Bags, backpacks and laptop cases",
                            "slug": "bags-backpacks-and-laptop-cases",
                            "photo": "http://localhost:8000/storage/categories/December2022/bags-backpacks-and-laptop-cases.jpg",
                            "parent_id": 20,
                            "created_at": "2022-12-05T05:38:42.000000Z",
                            "updated_at": "2022-12-05T05:38:42.000000Z",
                            "deleted_at": null
                        }
                    ]
                }
            ],
            "created_at": "2022-12-05T05:38:21.000000Z",
            "updated_at": "2022-12-05T05:38:21.000000Z"
        },
        {
            "id": 23,
            "title": "Smartphones, TV and Electronics",
            "slug": "smartphones-tv-and-electronics",
            "photo": "http://localhost:8000/storage/categories/December2022/smartphones-tv-and-electronics.jpg",
            "parent_id": null,
            "subcategories": null,
            "created_at": "2022-12-05T05:38:43.000000Z",
            "updated_at": "2022-12-05T05:38:43.000000Z"
        },
        {
            "id": 24,
            "title": "Goods for gamers",
            "slug": "goods-for-gamers",
            "photo": "http://localhost:8000/storage/categories/December2022/goods-for-gamers.jpg",
            "parent_id": null,
            "subcategories": null,
            "created_at": "2022-12-05T05:38:44.000000Z",
            "updated_at": "2022-12-05T05:38:44.000000Z"
        }
    ])

let hoveredCategoryId = ref(1)

let hoveredCategory = computed(() => categories.find(category => category.id === hoveredCategoryId.value))

const onCategory = (category) => hoveredCategoryId.value = category.id
const closeResponsiveCategories = () => showingResponsiveCategories.value = false
</script>

<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
            <!-- Primary Navigation Menu -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="relative flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <Link :href="route('dashboard')">
                                <ApplicationLogo
                                    class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"
                                />
                            </Link>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                Dashboard
                            </NavLink>

                            <PopoverGroup class="hidden lg:ml-8 lg:block lg:self-stretch">
                                <div class="flex h-full space-x-8">
                                    <Popover class="flex" v-slot="{ open }">
                                        <div class="relative flex">
                                            <PopoverButton
                                                :class="[open ? 'border-indigo-400 dark:border-indigo-600 text-gray-900 dark:text-gray-100 focus:border-indigo-700' :
                                                    'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700 focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700',
                                                    'relative z-10 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out']">
                                                Categories
                                            </PopoverButton>
                                        </div>

                                        <Transition
                                            enter-active-class="transition ease-out duration-200"
                                            enter-from-class="opacity-0"
                                            enter-to-class="opacity-100"
                                            leave-active-class="transition ease-in duration-150"
                                            leave-from-class="opacity-100"
                                            leave-to-class="opacity-0">
                                            <PopoverPanel
                                                class="absolute inset-x-0 top-full text-sm text-gray-500 z-10">
                                                <div class="absolute inset-0 top-1/2 bg-white shadow"
                                                     aria-hidden="true"/>
                                                <div class="relative bg-white dark:bg-gray-800">
                                                    <div class="mx-auto max-w-7xl px-4">
                                                        <div class="py-8 flex justify-between">
                                                            <div class="flex xl:basis-1/5 basis-1/4">
                                                                <ul>
                                                                    <li v-for="category in categories"
                                                                        :key="category.id" class="p-1.5 rounded-md"
                                                                        :class="hoveredCategoryId === category.id ? 'bg-indigo-600 text-white' : 'text-gray-300'"
                                                                        @mouseover="onCategory(category)">
                                                                        <Link href="#"
                                                                              class="flex font-semibold">
                                                                            {{ category.title }}
                                                                        </Link>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div
                                                                class="flex flex-col flex-wrap grow h-[300px]">
                                                                <ul class="flex"
                                                                    v-for="subcategory in hoveredCategory.subcategories"
                                                                    :key="subcategory.id">
                                                                    <li class="flex flex-col">
                                                                        <Link
                                                                            href="#"
                                                                            :id="`${subcategory.title}-heading`"
                                                                            class="text-base font-medium text-gray-300 dark:text-gray-200 hover:text-gray-700 dark:hover:text-gray-100 focus:text-gray-700 dark:focus:text-gray-300">
                                                                            {{ subcategory.title }}
                                                                        </Link>
                                                                        <ul role="list"
                                                                            class="my-2 space-y-2">
                                                                            <li v-for="item in subcategory.subcategories"
                                                                                :key="item.id" class="flex">
                                                                                <a :href="item.slug"
                                                                                   class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-100 hover:border-gray-300 dark:hover:border-gray-700 focus:text-gray-700 dark:focus:text-gray-300">{{
                                                                                        item.title
                                                                                    }}</a>
                                                                            </li>
                                                                        </ul>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="basis-1/5 xl:block hidden">
                                                                <div class="group relative text-base sm:text-sm">
                                                                    <div v-if="hoveredCategory.photo"
                                                                         class="aspect-w-1 aspect-h-1 overflow-hidden rounded-lg bg-gray-100 group-hover:opacity-75">
                                                                        <img :src="hoveredCategory.photo"
                                                                             :alt="hoveredCategory.title"
                                                                             :title="hoveredCategory.title"
                                                                             class="object-cover object-center"/>
                                                                    </div>
                                                                    <Link
                                                                        href="#"
                                                                        class="mt-3 block font-medium text-gray-900">
                                                                            <span class="absolute inset-0 z-10"
                                                                                  aria-hidden="true"/>
                                                                        {{ hoveredCategory.title }}
                                                                    </Link>
                                                                    <p aria-hidden="true" class="mt-1">Shop now</p>
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
                        </div>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <!-- Settings Dropdown -->
                        <div class="ml-3 relative">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="ml-2 -mr-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('profile.edit')"> Profile</DropdownLink>
                                    <DropdownLink :href="route('logout')" method="post" as="button">
                                        Log Out
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </div>

                    <!-- Hamburger -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button
                            @click="showingNavigationDropdown = !showingNavigationDropdown"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out"
                        >
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path
                                    :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                                <path
                                    :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div
                :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                class="sm:hidden"
            >
                <div class="pt-2 pb-3 space-y-1">
                    <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                        Dashboard
                    </ResponsiveNavLink>
                    <button @click="showingResponsiveCategories = true" :class="[ showingResponsiveCategories ?
                                'border-indigo-400 dark:border-indigo-600 text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900' :
                                'border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 focus:text-gray-800 dark:focus:text-gray-200 focus:bg-gray-50 dark:focus:bg-gray-700 focus:border-gray-300 dark:focus:border-gray-600',
                                'block w-full pl-3 pr-4 py-2 border-l-4 text-left text-base font-medium focus:outline-none transition duration-150 ease-in-out']">
                        <span>Categories</span>
                    </button>
                </div>

                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800 dark:text-gray-200">
                            {{ $page.props.auth.user.name }}
                        </div>
                        <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <ResponsiveNavLink :href="route('profile.edit')"> Profile</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                            Log Out
                        </ResponsiveNavLink>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        <header class="bg-white dark:bg-gray-800 shadow" v-if="$slots.header">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <!-- Page Content -->
        <main>
            <slot />
        </main>

        <responsive-categories
            v-if="showingResponsiveCategories"
            :showing="showingResponsiveCategories"
            :categories="categories"
            @close="closeResponsiveCategories" />
    </div>
</template>
