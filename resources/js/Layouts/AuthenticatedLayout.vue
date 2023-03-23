<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { useDark, useToggle } from '@vueuse/core';
import { initTooltips } from 'flowbite';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import ResponsiveCategories from '@/Components/ResponsiveCategories.vue';
import Categories from '@/Components/Categories.vue';

defineProps({
    title: String,
});

const { user, categories, breadcrumbs } = reactive(usePage().props);

const isDark = useDark();
const toggleDark = useToggle(isDark);

const showingNavigationDropdown = ref(false);
const showingResponsiveCategories = ref(false);
const search = ref(null);

onMounted(() => initTooltips());

const fullName = computed(() => (user ? `${user.first_name} ${user.last_name}` : null));

const closeResponsiveCategories = () => (showingResponsiveCategories.value = false);

const searchGoods = () => {
    useForm({
        search: search.value,
    }).get(route('index.search'));
};
</script>

<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <header class="border-b border-gray-100 bg-white dark:border-gray-700 dark:bg-gray-800">
            <!-- Primary Navigation Menu -->
            <div class="mx-auto max-w-9xl px-4 sm:px-6 lg:px-8">
                <div class="relative flex h-16 justify-between">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex shrink-0 items-center">
                            <Link :href="route('index.dashboard')">
                                <ApplicationLogo
                                    class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"
                                />
                            </Link>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:flex">
                            <categories :categories="categories" />
                            <button
                                @click="showingResponsiveCategories = true"
                                :class="[
                                    showingResponsiveCategories
                                        ? 'border-indigo-400 text-gray-900 focus:border-indigo-700 dark:border-indigo-600 dark:text-gray-100'
                                        : 'border-transparent text-gray-500 focus:border-gray-300 focus:text-gray-700 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:focus:border-gray-700 dark:focus:text-gray-300 dark:hover:border-gray-700 dark:hover:text-gray-300',
                                    'relative inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none lg:hidden',
                                ]"
                            >
                                <span>Categories</span>
                            </button>
                        </div>
                    </div>

                    <form @submit.prevent="searchGoods" class="hidden items-center md:flex lg:pl-2">
                        <div class="relative self-center lg:w-96">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <font-awesome-icon
                                    icon="fa-solid fa-magnifying-glass"
                                    class="text-gray-500 dark:text-gray-400"
                                />
                            </div>
                            <input
                                v-model="search"
                                type="text"
                                id="goods-search"
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500 sm:text-sm"
                                placeholder="I'm looking for..."
                            />
                        </div>
                        <div class="ml-2">
                            <button
                                class="group relative inline-flex items-center justify-center overflow-hidden rounded-lg bg-gradient-to-br from-purple-600 to-blue-500 p-0.5 font-medium text-gray-900 focus:outline-none focus:ring-4 focus:ring-blue-300 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white dark:focus:ring-blue-800"
                            >
                                <span
                                    class="rounded-md bg-white px-4 py-2 uppercase tracking-wider transition-all duration-150 ease-in group-hover:bg-opacity-0 dark:bg-gray-900"
                                >
                                    Find
                                </span>
                            </button>
                        </div>
                    </form>

                    <div class="ml-auto mr-4 flex items-center sm:ml-0">
                        <!-- Toggle dark mode -->
                        <div
                            id="theme-toggle"
                            role="tooltip"
                            class="tooltip invisible absolute z-10 inline-block rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white opacity-0 shadow-sm transition-opacity duration-300 dark:bg-gray-700"
                        >
                            <span>Toggle {{ isDark ? 'light' : 'dark' }} mode</span>
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                        <button
                            id="theme-toggle"
                            class="rounded-lg p-2.5 text-sm text-gray-500 focus:outline-none focus:ring-4 focus:ring-gray-200 hover:bg-gray-100 dark:text-gray-400 dark:focus:ring-gray-700 dark:hover:bg-gray-700"
                            data-tooltip-target="theme-toggle"
                            type="button"
                            @click="toggleDark()"
                        >
                            <font-awesome-icon icon="fa-solid fa-moon" size="xl" :class="{ hidden: isDark }" />
                            <svg
                                aria-hidden="true"
                                id="theme-toggle-light-icon"
                                :class="[!isDark ? 'hidden' : '', 'h-5 w-5']"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>
                        </button>
                        <!-- Settings Dropdown -->
                        <div v-if="user" class="relative ml-3 hidden sm:flex">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out focus:outline-none hover:text-gray-700 dark:bg-gray-800 dark:hover:text-gray-300"
                                        >
                                            {{ fullName }}
                                            <font-awesome-icon icon="fa-solid fa-angle-down" class="ml-2 -mr-0.5" />
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
                        <div class="hidden sm:flex" v-else>
                            <Link
                                :href="route('register')"
                                class="mx-2 inline-flex items-center justify-center rounded-lg bg-gray-50 px-3 py-2.5 text-sm font-semibold uppercase tracking-wider text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                            >
                                Sign Up
                            </Link>
                            <Link
                                :href="route('login')"
                                class="rounded-lg bg-gradient-to-br from-purple-600 to-blue-500 px-3 py-2.5 text-center text-sm font-medium font-semibold uppercase tracking-wider text-white focus:outline-none focus:ring-4 focus:ring-blue-300 hover:bg-gradient-to-bl dark:focus:ring-blue-800"
                            >
                                Log In
                            </Link>
                        </div>
                        <div v-if="user?.avatar" class="ml-2 hidden h-9 w-9 sm:flex">
                            <img :src="user.avatar" :alt="fullName" :title="fullName" class="rounded-full" />
                        </div>
                    </div>

                    <!-- Hamburger -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button
                            @click="showingNavigationDropdown = !showingNavigationDropdown"
                            class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out focus:bg-gray-100 focus:text-gray-500 focus:outline-none hover:bg-gray-100 hover:text-gray-500 dark:text-gray-500 dark:focus:bg-gray-900 dark:focus:text-gray-400 dark:hover:bg-gray-900 dark:hover:text-gray-400"
                        >
                            <font-awesome-icon
                                :icon="['fa-solid', showingNavigationDropdown ? 'fa-xmark' : 'fa-bars']"
                                size="xl"
                            />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden">
                <div class="space-y-1 pt-2 pb-3">
                    <ResponsiveNavLink :href="route('index.dashboard')" :active="route().current('index.dashboard')">
                        Dashboard
                    </ResponsiveNavLink>
                    <button
                        @click="showingResponsiveCategories = true"
                        :class="[
                            showingResponsiveCategories
                                ? 'border-indigo-400 bg-indigo-50 text-indigo-700 dark:border-indigo-600 dark:bg-indigo-900 dark:text-indigo-300'
                                : 'border-transparent text-gray-600 focus:border-gray-300 focus:bg-gray-50 focus:text-gray-800 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-800 dark:text-gray-400 dark:focus:border-gray-600 dark:focus:bg-gray-700 dark:focus:text-gray-200 dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-gray-200',
                            'block w-full border-l-4 py-2 pl-3 pr-4 text-left text-base font-medium transition duration-150 ease-in-out focus:outline-none',
                        ]"
                    >
                        <span>Categories</span>
                    </button>
                </div>

                <!-- Responsive Settings Options -->
                <div v-if="user" class="border-t border-gray-200 pt-4 pb-1 dark:border-gray-600">
                    <div class="flex items-center">
                        <div v-if="user?.avatar" class="ml-2 h-9 w-9">
                            <img :src="user.avatar" :alt="fullName" :title="fullName" class="rounded-full" />
                        </div>
                        <div class="px-4">
                            <div class="text-base font-medium text-gray-800 dark:text-gray-200">
                                {{ fullName }}
                            </div>
                            <div class="text-sm font-medium text-gray-500">{{ user.email }}</div>
                        </div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <ResponsiveNavLink :href="route('profile.edit')"> Profile</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                            Log Out
                        </ResponsiveNavLink>
                    </div>
                </div>

                <div v-else class="border-t border-gray-200 pt-4 pb-1 dark:border-gray-600">
                    <ResponsiveNavLink :href="route('register')"> Sign Up</ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('login')"> Log In</ResponsiveNavLink>
                </div>
            </div>
        </header>

        <nav aria-label="Breadcrumbs" v-if="breadcrumbs">
            <ol
                role="list"
                class="mx-auto mt-6 flex max-w-2xl items-center space-x-2 px-4 sm:px-6 lg:max-w-9xl lg:px-8"
            >
                <li class="flex items-center">
                    <Link
                        :href="route('index.dashboard')"
                        class="text-gray-700 hover:text-purple-600 dark:text-gray-400 dark:hover:text-white"
                    >
                        <font-awesome-icon icon="fa-solid fa-house-chimney" />
                    </Link>
                </li>
                <li
                    v-for="(breadcrumb, i) in breadcrumbs"
                    :key="breadcrumb.id"
                    :class="{ 'hidden sm:block': i !== breadcrumbs.length - 2 }"
                >
                    <div class="flex items-center">
                        <svg
                            width="16"
                            height="20"
                            viewBox="0 0 16 20"
                            fill="currentColor"
                            class="mr-2 text-gray-400 dark:text-gray-300"
                        >
                            <path d="M5.697 4.34L8.98 16.532h1.327L7.025 4.341H5.697z" />
                        </svg>
                        <Link
                            v-if="
                                i !== breadcrumbs.length - 1 ||
                                route().current('index.good') ||
                                route().current('index.good.properties')
                            "
                            :href="route('index.category', breadcrumb.slug)"
                            class="text-sm font-medium text-gray-700 hover:text-purple-600 dark:text-gray-400 dark:hover:text-white"
                        >
                            {{ breadcrumb.title }}
                        </Link>
                        <p v-else class="text-sm font-medium text-gray-500 dark:text-gray-500">
                            {{ breadcrumb.title }}
                        </p>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Page Heading -->
        <div class="mx-auto max-w-9xl px-4 py-6 sm:px-6 lg:px-8" v-if="title">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">{{ title }}</h1>
        </div>

        <!-- Page Content -->
        <main class="mx-auto max-w-9xl">
            <slot />
        </main>

        <responsive-categories
            v-if="showingResponsiveCategories"
            :showing="showingResponsiveCategories"
            :categories="categories"
            @close="closeResponsiveCategories"
        />
    </div>
</template>
