<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Tabs from '@/Components/Tabs.vue';

defineProps({
    good: Object,
});
</script>

<template>
    <Head :title="good.title" />

    <AuthenticatedLayout :title="good.title">
        <section v-if="good" class="bg-gray-100 p-4 text-gray-900 dark:bg-gray-900 dark:text-gray-400 sm:p-6 lg:p-8">
            <div class="overflow-hidden bg-white px-4 shadow-sm dark:bg-gray-800 sm:rounded-lg sm:px-6 lg:px-8">
                <tabs :good="good.slug" />

                <div class="container mx-auto pt-12" v-if="good?.properties?.length">
                    <dl class="rounded-lg">
                        <div
                            v-for="(property, i) in good.properties"
                            :key="property.id"
                            :class="[
                                'flex flex-col px-4 py-5 font-medium sm:flex-row sm:px-6',
                                i % 2 === 0 ? 'bg-gray-100 dark:bg-gray-900' : 'bg-white dark:bg-gray-800',
                                { 'rounded-t-lg': i === 0 },
                                { 'rounded-b-lg': i === good.properties.length - 1 },
                            ]"
                        >
                            <dt
                                class="basis-full text-sm text-gray-900 dark:text-gray-200 sm:basis-1/2 md:basis-2/5 lg:basis-1/3"
                            >
                                {{ property.name }}
                            </dt>
                            <dd
                                class="mt-1 basis-full text-sm text-gray-600 dark:text-gray-400 sm:col-span-2 sm:mt-0 sm:basis-1/2 md:basis-3/5 lg:basis-2/3"
                            >
                                {{ property.value }}
                            </dd>
                        </div>
                    </dl>
                </div>

                <p class="font-italic pt-6 pb-12 text-gray-700 dark:text-gray-400">* {{ good.warning_description }}</p>
            </div>
        </section>
    </AuthenticatedLayout>
</template>
