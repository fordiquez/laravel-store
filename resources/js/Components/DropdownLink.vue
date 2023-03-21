<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <Link
        class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 transition duration-150 ease-in-out focus:bg-gray-100 focus:outline-none hover:bg-gray-100 dark:text-gray-300 dark:focus:bg-gray-800 dark:hover:bg-gray-800"
    >
        <slot />
    </Link>
</template>
