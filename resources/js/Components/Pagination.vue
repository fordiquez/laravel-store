<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { range } from 'lodash'
import { parse, stringify } from 'qs'

const props = defineProps<{
    links: any
    meta: any
}>()

const pageLinks = computed(() => {
    const pageLimit = 7
    const parsed = parse(window.location.search.substring(1))

    const pageRange = range(1, props.meta.last_page + 1).map((page) => {
        parsed.page = String(page)

        return {
            label: page,
            url: `${props.meta.path}?${stringify(parsed, { encodeValuesOnly: true })}`,
            active: page === props.meta.current_page
        }
    })

    return pageRange.length > pageLimit ? trimPageRange(pageRange) : pageRange
})

const prevPage = computed(() => {
    const parsed = parse(window.location.search.substring(1))
    parsed.page = String(parsed.page ?? 1)

    if (Number(parsed.page) > 1) {
        parsed.page = String(Number(parsed.page) - 1)

        return `${props.meta.path}?${stringify(parsed, { encodeValuesOnly: true })}`
    }

    return null
})

const nextPage = computed(() => {
    const parsed = parse(window.location.search.substring(1))
    parsed.page = String(parsed.page ?? 1)

    if (Number(parsed.page) < props.meta.last_page) {
        parsed.page = String(Number(parsed.page) + 1)

        return `${props.meta.path}?${stringify(parsed, { encodeValuesOnly: true })}`
    }

    return null
})

const trimPageRange = (pageRange: any[]) => {
    if (props.meta.current_page < 3 || props.meta.current_page > pageRange.length - 2) {
        const beginning = pageRange.slice(0, 3)
        beginning.push({ url: '#' })
        const end = pageRange.slice(pageRange.length - 3)

        return beginning.concat(end)
    }

    const first = pageRange.slice(0, 1)
    first.push({ url: '#' })

    const middle = pageRange.slice(props.meta.current_page - 2, props.meta.current_page + 1)
    middle.push({ url: '#1' })

    const last = pageRange.slice(pageRange.length - 1)

    return first.concat(middle, last)
}
</script>

<template>
    <div class="mt-6 flex items-center justify-between px-4 py-3">
        <div v-if="meta.total > 0" class="flex flex-1 justify-between sm:hidden">
            <Link
                v-if="prevPage"
                :href="prevPage"
                class="mr-3 inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
            >
                <font-awesome-icon :icon="['fas', 'arrow-left-long']" class="mr-2 h-4 w-4" />
                Previous
            </Link>
            <button
                v-else
                class="mr-3 inline-flex cursor-not-allowed items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400"
                disabled
            >
                <font-awesome-icon :icon="['fas', 'arrow-left-long']" class="mr-2 h-4 w-4" />
                Previous
            </button>
            <Link
                v-if="nextPage"
                :href="nextPage"
                class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
            >
                Next
                <font-awesome-icon :icon="['fas', 'arrow-right-long']" class="ml-2 h-4 w-4" />
            </Link>
            <button
                v-else
                class="inline-flex cursor-not-allowed items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400"
                disabled
            >
                Next
                <font-awesome-icon :icon="['fas', 'arrow-right-long']" class="ml-2 h-4 w-4" />
            </button>
        </div>
        <p v-else class="mb-0 text-sm leading-5 text-gray-700 dark:text-gray-400 sm:hidden">
            No results found <span class="font-mono">¯\_(ツ)_/¯</span>
        </p>

        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <p v-if="meta.total > 0" class="text-sm text-gray-700 dark:text-gray-400">
                Showing
                <span class="font-semibold text-gray-900 dark:text-white">{{ meta.from }}</span>
                to
                <span class="font-semibold text-gray-900 dark:text-white">{{ meta.to }}</span>
                of
                <span class="font-semibold text-gray-900 dark:text-white">{{ meta.total }}</span>
                results
            </p>
            <p v-else class="mb-0 text-sm leading-5 text-gray-700 dark:text-gray-400">
                No results found <span class="font-mono">¯\_(ツ)_/¯</span>
            </p>
            <nav v-if="pageLinks.length > 1">
                <ul class="inline-flex items-center -space-x-px">
                    <li>
                        <Link
                            v-if="prevPage"
                            :href="prevPage"
                            class="inline-flex h-10 items-center rounded-l-lg border border-gray-300 bg-white px-3 py-2 leading-tight text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                            aria-label="Previous"
                        >
                            <font-awesome-icon :icon="['fas', 'angle-left']" class="h-4 w-4" />
                        </Link>

                        <button
                            v-else
                            class="inline-flex h-10 cursor-not-allowed items-center rounded-l-lg border border-gray-300 bg-white px-3 py-2 leading-tight text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400"
                            aria-label="Previous"
                            disabled
                        >
                            <font-awesome-icon :icon="['fas', 'angle-left']" class="h-4 w-4" />
                        </button>
                    </li>
                    <li v-for="link in pageLinks" :key="link.url">
                        <Link
                            :href="link.url"
                            class="border px-3 py-2 leading-tight"
                            :class="
                                link.active
                                    ? 'z-10 border-purple-300 bg-purple-100 text-purple-600 hover:bg-purple-200 hover:text-purple-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white'
                                    : ' border-gray-300 bg-white text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white'
                            "
                        >
                            {{ link.label || '...' }}
                        </Link>
                    </li>
                    <li>
                        <Link
                            v-if="nextPage"
                            :href="nextPage"
                            class="inline-flex h-10 items-center rounded-r-lg border border-gray-300 bg-white px-3 py-2 leading-tight text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                            aria-label="Next"
                        >
                            <font-awesome-icon :icon="['fas', 'angle-right']" class="h-4 w-4" />
                        </Link>

                        <button
                            v-else
                            class="inline-flex h-10 cursor-not-allowed items-center rounded-r-lg border border-gray-300 bg-white px-3 py-2 leading-tight text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400"
                            aria-label="Next"
                            disabled
                        >
                            <font-awesome-icon :icon="['fas', 'angle-right']" class="h-4 w-4" />
                        </button>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>
