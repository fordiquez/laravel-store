<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <button
        :type="type"
        :disabled="disabled"
        :class="[
            'group relative inline-flex items-center justify-center rounded-lg bg-gradient-to-br from-purple-600 to-blue-500 p-0.5 font-medium text-gray-900 dark:text-white',
            disabled
                ? 'cursor-not-allowed opacity-40'
                : 'focus:outline-none focus:ring-4 focus:ring-blue-300 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:focus:ring-blue-800',
        ]"
    >
        <span
            :class="[
                'relative w-full rounded-md bg-white px-4 py-2.5 text-sm uppercase tracking-wider',
                disabled
                    ? 'dark:bg-gray-900'
                    : 'transition duration-300 ease-in-out group-hover:bg-opacity-0 dark:bg-gray-900',
            ]"
        >
            <slot />
        </span>
    </button>
</template>
