<script setup>
import { usePage, Link } from '@inertiajs/vue3';
import { reactive } from 'vue';
import { useDark } from '@vueuse/core';

const { user, badges } = usePage().props;
const isDark = useDark();

const linkClasses = reactive({
    active: 'bg-purple-200 text-purple-900 dark:bg-purple-900 dark:text-purple-100',
    inactive:
        'text-gray-900 hover:bg-purple-200 hover:text-purple-900 dark:text-gray-100 dark:hover:bg-purple-900 dark:hover:text-purple-100',
    default:
        'flex min-h-[48px] items-center space-x-4 rounded-lg p-3 text-sm transition-all focus:outline-none focus:ring-2 focus:ring-purple-400 dark:focus:ring-purple-600',
});

const routes = reactive([
    {
        name: 'profile.orders',
        title: 'Orders',
        icon: 'rectangle-list',
        count: badges.orders,
    },
    {
        name: 'profile.wishlist',
        title: 'Wishlist',
        icon: 'heart',
    },
    {
        name: 'profile.wallet',
        title: 'Wallet',
        icon: 'credit-card',
    },
    {
        name: 'profile.messages',
        title: 'Messages',
        icon: 'comments',
    },
    {
        name: 'profile.reviews',
        title: 'Reviews',
        icon: 'message',
        count: badges.reviews,
    },
]);
</script>

<template>
    <ul class="mb-4 flex w-full flex-col md:mb-0 md:w-80 lg:w-96">
        <li class="border-b border-gray-300 pb-2 dark:border-gray-700">
            <Link
                :href="route('profile.personal-information.edit')"
                :class="[
                    linkClasses.default,
                    route().current('profile.personal-information.edit') ? linkClasses.active : linkClasses.inactive,
                ]"
            >
                <div class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full" :src="user.avatar" :alt="user.full_name" />
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate font-semibold">{{ user.full_name }}</p>
                    <p class="truncate text-purple-900 dark:text-purple-300">{{ user.email }}</p>
                </div>
            </Link>
        </li>
        <li :class="i === 0 ? 'pt-2 pb-1' : 'py-1'" v-for="(item, i) in routes" :key="route.name">
            <Link
                :href="route(item.name)"
                :class="[linkClasses.default, route().current(item.name) ? linkClasses.active : linkClasses.inactive]"
            >
                <div class="flex flex-1 items-center space-x-4">
                    <font-awesome-icon :icon="[isDark ? 'fas' : 'far', item.icon]" size="xl" class="w-6" />
                    <p class="truncate text-sm font-medium">{{ item.title }}</p>
                </div>
                <div
                    v-if="item.count"
                    class="inline-flex min-w-[24px] items-center justify-center rounded-full bg-purple-600 p-0.5 font-semibold text-purple-200"
                >
                    {{ item.count }}
                </div>
            </Link>
        </li>
    </ul>
</template>
