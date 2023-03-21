<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 hover:bg-gray-700 active:bg-gray-900 dark:bg-gray-200 dark:text-gray-800 dark:focus:bg-white dark:focus:ring-offset-gray-800 dark:hover:bg-white dark:active:bg-gray-300"
    >
        <slot />
    </button>
</template>
