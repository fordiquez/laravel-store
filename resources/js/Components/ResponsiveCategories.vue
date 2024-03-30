<script setup lang="ts">
import { computed, onMounted, onUnmounted, reactive, ref } from 'vue'
import { TransitionRoot, TransitionChild, Dialog, DialogPanel, DialogTitle } from '@headlessui/vue'

const props = defineProps<{
    showing: boolean
    categories: any[]
}>()

const emit = defineEmits(['close'])

const showingSubcategories = ref(false)
const selectedCategory = reactive<any>({})

onMounted(() => document.querySelector('body')?.classList.add('overflow-hidden'))
onUnmounted(() => document.querySelector('body')?.classList.remove('overflow-hidden'))

const selectedCategoryTitle = computed(() => selectedCategory?.title ?? 'Categories of goods')

const closeModal = () => emit('close')

const renderSubcategories = (categoryId: number) => {
    Object.assign(
        selectedCategory,
        props.categories.find((category) => category.id === categoryId)
    )
    showingSubcategories.value = true
}
const rollbackCategories = () => {
    Object.keys(selectedCategory).forEach((key) => delete selectedCategory[key])
    showingSubcategories.value = false
}
</script>

<template>
    <TransitionRoot appear :show="showing" as="template">
        <Dialog as="div" @close="closeModal" class="relative z-10">
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black bg-opacity-25" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <DialogPanel
                            class="w-full transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all dark:bg-gray-800"
                            style="height: calc(100vh - 32px)"
                        >
                            <DialogTitle as="h3" class="flex items-center justify-between text-lg font-medium leading-6 text-gray-100">
                                <span>{{ selectedCategoryTitle }}</span>
                                <button
                                    type="button"
                                    @click="closeModal"
                                    class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none dark:text-gray-500 dark:hover:bg-gray-900 dark:hover:text-gray-400 dark:focus:bg-gray-900 dark:focus:text-gray-400"
                                >
                                    <font-awesome-icon :icon="['fas', 'xmark']" class="h-6 w-6" />
                                </button>
                            </DialogTitle>
                            <div v-if="props.categories.length" class="mt-4">
                                <ul v-if="!showingSubcategories" class="flex flex-col space-y-3">
                                    <li
                                        v-for="category in props.categories"
                                        :key="category.id"
                                        @click="renderSubcategories(category.id)"
                                        class="flex cursor-pointer justify-between p-2 text-gray-600 transition duration-150 ease-in-out hover:bg-gray-50 hover:text-gray-800 focus:bg-gray-50 focus:text-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200 dark:focus:bg-gray-700 dark:focus:text-gray-200"
                                    >
                                        <div class="inline-flex items-center font-medium">
                                            <font-awesome-icon :icon="['fas', 'ellipsis']" class="h-6 w-6" />
                                            <span class="ml-2">{{ category.title }}</span>
                                        </div>
                                        <font-awesome-icon :icon="['fas', 'arrow-right-long']" class="ml-auto h-6 w-6" />
                                    </li>
                                </ul>
                                <div v-else>
                                    <button
                                        @click="rollbackCategories"
                                        class="flex w-full items-center justify-center rounded bg-indigo-600 py-2.5 text-gray-400 shadow-md transition duration-150 ease-in-out hover:bg-indigo-700 hover:shadow-lg focus:bg-indigo-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-indigo-800 active:shadow-lg dark:text-gray-200"
                                    >
                                        <font-awesome-icon :icon="['fas', 'arrow-left-long']" class="h-6 w-6" />
                                        <span class="ml-2 text-base font-semibold uppercase leading-6 tracking-widest">Categories</span>
                                    </button>
                                    <ul v-if="selectedCategory.subcategories" class="mt-4 flex flex-col space-y-3">
                                        <li v-for="subcategory in selectedCategory.subcategories" :key="subcategory.id">
                                            <a
                                                :href="subcategory.slug"
                                                class="text-gray-300 hover:text-gray-700 dark:text-gray-200 dark:hover:text-gray-100"
                                                >{{ subcategory.title }}</a
                                            >
                                            <ul v-if="subcategory.subcategories" class="my-2 space-y-2">
                                                <li v-for="item in subcategory.subcategories" :key="item.id">
                                                    <a
                                                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-100"
                                                        :href="item.slug"
                                                    >
                                                        <span class="">{{ item.title }}</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
