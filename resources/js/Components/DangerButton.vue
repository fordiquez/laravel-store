<script setup>
defineProps({
    type: {
        type: String,
        default: 'button'
    },
    disabled: {
        type: Boolean,
        default: false
    }
})
</script>

<template>
    <button
        :type="type"
        :disabled="disabled"
        :class="[
            'inline-flex items-center justify-center rounded-lg border border-transparent bg-red-600 px-4 py-2.5 text-sm font-semibold uppercase tracking-widest text-white',
            disabled
                ? 'cursor-not-allowed opacity-40'
                : 'transition duration-300 ease-in-out hover:bg-red-500 hover:opacity-70 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 active:bg-red-700 dark:focus:ring-offset-gray-800'
        ]"
    >
        <slot />
    </button>
</template>
