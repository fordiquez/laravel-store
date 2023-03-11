<script setup>
import { defineProps } from 'vue';
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue';

defineProps({
    title: String,
    classes: String,
});
</script>

<template>
    <Disclosure as="div" class="border-b border-gray-200 py-6 dark:border-gray-600" v-slot="{ open }" default-open>
        <h3 class="-my-3 flow-root">
            <DisclosureButton
                class="group flex w-full items-center justify-between pb-3 text-gray-900 dark:text-gray-300"
            >
                <span class="font-medium group-hover:text-purple-600 dark:group-hover:text-gray-50">{{ title }}</span>
                <span class="ml-6 flex items-center group-hover:text-purple-600 dark:group-hover:text-gray-50">
                    <font-awesome-icon :icon="['fa-solid', !open ? 'fa-plus' : 'fa-minus']" size="lg" />
                </span>
            </DisclosureButton>
        </h3>
        <transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-out"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
            <DisclosurePanel :class="classes ? [classes, 'pt-6'] : 'pt-6'">
                <slot />
            </DisclosurePanel>
        </transition>
    </Disclosure>
</template>
