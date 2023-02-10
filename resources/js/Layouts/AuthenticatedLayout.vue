<script setup>
import {computed, onMounted, reactive, ref} from 'vue';
import {Link, usePage} from '@inertiajs/vue3';
import { useDark, useToggle } from '@vueuse/core';
import { initTooltips } from 'flowbite';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import ResponsiveCategories from '@/Components/ResponsiveCategories.vue';
import Categories from '@/Components/Categories.vue';

const isDark = useDark();
const toggleDark = useToggle(isDark);

const { user, categories } = reactive(usePage().props)

const showingNavigationDropdown = ref(false);
const showingResponsiveCategories = ref(false);

onMounted(() => {
    initTooltips()
});

const fullname = computed(() => `${user.first_name} ${user.last_name}`)

const closeResponsiveCategories = () => (showingResponsiveCategories.value = false);
</script>

<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <nav class="border-b border-gray-100 bg-white dark:border-gray-700 dark:bg-gray-800">
            <!-- Primary Navigation Menu -->
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="relative flex h-16 justify-between">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex shrink-0 items-center">
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
                            <categories :categories="categories" />
                        </div>
                    </div>

                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <!-- Toggle dark mode -->
                        <div
                            id="theme-toggle"
                            role="tooltip"
                            class="tooltip invisible absolute z-10 inline-block rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white opacity-0 shadow-sm transition-opacity duration-300 dark:bg-gray-700"
                        >
                            <span>Toggle dark mode</span>
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                        <button
                            id="theme-toggle"
                            class="rounded-lg p-2.5 text-sm text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-700"
                            data-tooltip-target="theme-toggle"
                            type="button"
                            @click="toggleDark()"
                        >
                            <svg
                                aria-hidden="true"
                                id="theme-toggle-dark-icon"
                                :class="[isDark ? 'hidden' : '', 'h-5 w-5']"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                            </svg>
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
                        <div class="relative ml-3">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none dark:bg-gray-800 dark:hover:text-gray-300"
                                        >
                                            {{ fullname }}

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
                        <div class="ml-2 h-9 w-9">
                            <img :src="user.avatar" :alt="fullname" :title="fullname" class="rounded-full" />
                        </div>
                    </div>

                    <!-- Hamburger -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button
                            @click="showingNavigationDropdown = !showingNavigationDropdown"
                            class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none dark:text-gray-500 dark:hover:bg-gray-900 dark:hover:text-gray-400 dark:focus:bg-gray-900 dark:focus:text-gray-400"
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
            <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden">
                <div class="space-y-1 pt-2 pb-3">
                    <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                        Dashboard
                    </ResponsiveNavLink>
                    <button
                        @click="showingResponsiveCategories = true"
                        :class="[
                            showingResponsiveCategories
                                ? 'border-indigo-400 bg-indigo-50 text-indigo-700 dark:border-indigo-600 dark:bg-indigo-900 dark:text-indigo-300'
                                : 'border-transparent text-gray-600 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-800 focus:border-gray-300 focus:bg-gray-50 focus:text-gray-800 dark:text-gray-400 dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-gray-200 dark:focus:border-gray-600 dark:focus:bg-gray-700 dark:focus:text-gray-200',
                            'block w-full border-l-4 py-2 pl-3 pr-4 text-left text-base font-medium transition duration-150 ease-in-out focus:outline-none',
                        ]"
                    >
                        <span>Categories</span>
                    </button>
                </div>

                <!-- Responsive Settings Options -->
                <div class="border-t border-gray-200 pt-4 pb-1 dark:border-gray-600">
                    <div class="px-4">
                        <div class="text-base font-medium text-gray-800 dark:text-gray-200">
                            {{ fullname }}
                        </div>
                        <div class="text-sm font-medium text-gray-500">{{ user.email }}</div>
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
        <header class="bg-white shadow dark:bg-gray-800" v-if="$slots.header">
            <div class="mx-auto max-w-7xl py-6 px-4 sm:px-6 lg:px-8">
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
            @close="closeResponsiveCategories"
        />
    </div>
</template>
