<script setup lang="ts">
import { computed, defineProps } from 'vue'
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'

defineProps<{
    title: string
    classes: string
}>()

const defaultClass = computed(() => 'mt-6 p-1 max-h-96 dark:[color-scheme:dark]')
</script>

<template>
    <Disclosure as="div" class="border-b border-gray-200 py-6 dark:border-gray-600" v-slot="{ open }" default-open>
        <h3 class="-my-3 flow-root">
            <DisclosureButton
                class="group mb-2 flex w-full items-center justify-between px-2 py-2 text-gray-900 focus:rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-4 dark:text-gray-300 dark:focus:ring-offset-gray-800"
            >
                <span class="text-left font-medium group-hover:text-purple-600 dark:group-hover:text-gray-50">
                    {{ title }}
                </span>
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
            <DisclosurePanel :class="classes ? [classes, defaultClass] : defaultClass">
                <slot />
            </DisclosurePanel>
        </transition>
    </Disclosure>
</template>
