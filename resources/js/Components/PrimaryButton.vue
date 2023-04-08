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
        class="rounded-lg bg-gradient-to-br from-purple-600 to-blue-500 px-5 py-2.5 text-center text-sm font-medium uppercase tracking-wider text-white focus:outline-none focus:ring-4 focus:ring-blue-300 hover:bg-gradient-to-bl dark:focus:ring-blue-800"
    >
        <slot />
    </button>
</template>
