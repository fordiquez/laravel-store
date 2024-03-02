<script setup lang="ts">
import { ref, computed } from 'vue'
import { Combobox, ComboboxLabel, ComboboxInput, ComboboxButton, ComboboxOptions, ComboboxOption } from '@headlessui/vue'

const props = withDefaults(
    defineProps<{
        options?: any
        optionKey?: string
        optionValue?: string
        optionIcon?: string | null
        modelValue?: any
        label?: string
        error?: string
        maxHeight?: string
        disabled?: boolean
        loading?: boolean
    }>(),
    {
        options: [],
        optionKey: 'id',
        optionValue: 'name',
        optionIcon: null,
        maxHeight: 'max-h-56',
        disabled: false,
        loading: false
    }
)

defineEmits(['update:modelValue'])

const query = ref('')

const filteredOptions = computed(() =>
    query.value === ''
        ? props.options
        : props.options.filter((option: any) =>
              option[props.optionValue].toLowerCase().replace(/\s+/g, '').includes(query.value.toLowerCase().replace(/\s+/g, ''))
          )
)

const selectedModel = computed(() => props.options.find((option: any) => option[props.optionKey] === props.modelValue))
</script>

<template>
    <combobox
        as="div"
        class="relative w-full"
        :model-value="selectedModel"
        :disabled="disabled || loading"
        :by="optionKey"
        v-slot="{ open }"
        @update:model-value="(value) => $emit('update:modelValue', value)"
    >
        <combobox-label
            v-if="label"
            :class="[
                'mb-2 block cursor-pointer select-none text-left text-sm font-medium',
                error ? 'text-red-500' : 'text-gray-900 dark:text-gray-300'
            ]"
        >
            {{ label }}
        </combobox-label>
        <div class="relative">
            <combobox-input
                class="shadow-input relative w-full rounded-lg border-none py-2.5 pr-8 text-left text-sm ring-1 ring-inset focus:outline-none focus:ring-2"
                :class="[
                    error
                        ? 'text-red-500 ring-red-500'
                        : 'text-gray-900 ring-gray-300 focus:ring-indigo-500 dark:text-gray-300 dark:ring-gray-700 dark:focus:ring-indigo-600',
                    disabled ? 'cursor-not-allowed bg-gray-200 dark:bg-gray-800' : 'bg-white dark:bg-gray-900',
                    optionIcon && selectedModel ? 'pl-10' : 'pl-3'
                ]"
                :displayValue="(option: any) => (selectedModel ? (optionValue ? option[optionValue] : option) : null)"
                @change="query = $event.target.value"
            />
            <img
                v-if="optionIcon && selectedModel"
                :src="selectedModel[optionIcon]"
                :alt="selectedModel[optionKey]"
                class="absolute left-3 top-2.5 size-5 rounded-full"
            />
            <font-awesome-icon
                v-if="loading"
                :icon="['fas', 'circle-notch']"
                class="absolute right-2.5 top-3 animate-spin text-gray-700 dark:text-gray-200"
            />
            <combobox-button v-else class="absolute inset-y-0 right-0 flex items-center pr-2">
                <font-awesome-icon :icon="['fas', open ? 'up-long' : 'down-long']" class="size-4 text-gray-600 dark:text-gray-200" />
            </combobox-button>
        </div>
        <transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-out"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
            @after-leave="query = ''"
        >
            <combobox-options
                :class="[
                    'absolute z-50 mt-1 w-full overflow-auto rounded-lg bg-white text-base shadow-lg ring-1 ring-black/5 scrollbar-thin scrollbar-track-indigo-600 scrollbar-thumb-indigo-400 focus:outline-none dark:bg-gray-900 sm:text-sm',
                    maxHeight
                ]"
            >
                <div
                    v-if="!filteredOptions.length && query"
                    class="relative cursor-default select-none px-4 py-2 text-gray-700 dark:text-gray-400"
                >
                    Nothing found.
                </div>

                <combobox-option
                    as="template"
                    v-for="option in filteredOptions"
                    :key="option[optionKey]"
                    :value="option"
                    v-slot="{ selected }"
                >
                    <li
                        :class="[
                            'group relative cursor-pointer select-none py-2 pl-3 text-gray-900 hover:bg-indigo-600 hover:text-white dark:text-gray-200',
                            selected ? 'pr-9' : 'pr-3'
                        ]"
                    >
                        <div class="flex items-center space-x-2">
                            <img
                                v-if="optionIcon"
                                :src="option[optionIcon]"
                                :alt="option[optionKey]"
                                class="size-5 flex-shrink-0 rounded-full"
                            />
                            <span
                                :class="[
                                    selected ||
                                    (modelValue && optionValue ? modelValue[optionValue] === option[optionValue] : modelValue === option)
                                        ? 'font-semibold'
                                        : 'font-normal',
                                    'block truncate text-left capitalize'
                                ]"
                            >
                                {{ optionValue ? option[optionValue] : option }}
                            </span>
                        </div>

                        <span
                            v-if="
                                selected ||
                                (modelValue && optionValue ? modelValue[optionValue] === option[optionValue] : modelValue === option)
                            "
                            class="absolute inset-y-0 right-0 inline-flex items-center pr-3 text-indigo-600 group-hover:text-white"
                        >
                            <font-awesome-icon :icon="['fas', 'check']" class="size-4" />
                        </span>
                    </li>
                </combobox-option>
            </combobox-options>
        </transition>
        <p v-if="error" class="mt-2 text-left text-sm text-red-500">{{ error.charAt(0).toUpperCase() + error.slice(1) }}</p>
    </combobox>
</template>
