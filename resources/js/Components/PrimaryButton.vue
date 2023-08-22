<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
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
            'rounded-lg bg-gradient-to-br from-purple-600 to-blue-500 px-4 py-2.5 text-center text-sm font-medium uppercase tracking-wider text-white',
            disabled
                ? 'cursor-not-allowed opacity-40'
                : 'transition duration-300 ease-in-out hover:bg-gradient-to-bl focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800',
        ]"
    >
        <slot />
    </button>
</template>
