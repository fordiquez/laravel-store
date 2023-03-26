<script setup>
import { Link } from '@inertiajs/vue3';
import { reactive } from 'vue';

defineProps({
    good: String,
});

const classes = reactive({
    link: {
        default: 'border-transparent hover:border-purple-300 hover:text-purple-600 dark:hover:text-purple-300',
        active: 'active border-purple-600 text-purple-600 dark:border-purple-400 dark:text-purple-500',
    },
    icon: {
        default: 'text-gray-400 group-hover:text-purple-500 dark:text-gray-500 dark:group-hover:text-purple-300',
        active: 'text-purple-600 dark:text-purple-500',
    },
});

const tabs = reactive([
    {
        title: 'General',
        route: 'index.good',
        icon: ['fas', 'bag-shopping'],
    },
    {
        title: 'Properties',
        route: 'index.good.properties',
        icon: ['fas', 'clipboard-list'],
    },
    {
        title: 'Reviews',
        route: 'index.good.reviews',
        icon: ['fas', 'comments'],
    },
]);
</script>

<template>
    <div class="border-b border-gray-200 dark:border-gray-700">
        <ul class="-mb-px flex flex-wrap text-center text-sm font-medium text-gray-600 dark:text-gray-400">
            <li class="mr-2" v-for="tab in tabs" :key="tab.title">
                <Link
                    :href="route(tab.route, good)"
                    :class="route().current(tab.route) ? classes.link.active : classes.link.default"
                    class="group inline-flex rounded-t-lg border-b-2 p-4"
                >
                    <font-awesome-icon
                        :icon="tab.icon"
                        size="lg"
                        :class="['mr-2', route().current(tab.route) ? classes.icon.active : classes.icon.default]"
                    />
                    <span>{{ tab.title }}</span>
                </Link>
            </li>
        </ul>
    </div>
</template>
