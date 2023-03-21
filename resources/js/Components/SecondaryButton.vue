<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 hover:bg-gray-50 dark:border-gray-500 dark:bg-gray-800 dark:text-gray-300 dark:focus:ring-offset-gray-800 dark:hover:bg-gray-700"
    >
        <slot />
    </button>
</template>
