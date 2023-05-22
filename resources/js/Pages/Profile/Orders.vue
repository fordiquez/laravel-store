<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Sidebar from '@/Pages/Profile/Partials/Sidebar.vue';
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue';
import { useFormat } from '@/composables/format';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    orders: Array,
});

const { formatMoney } = useFormat();
</script>

<template>
    <AuthenticatedLayout>
        <div class="flex flex-col justify-between space-x-0 px-4 py-12 sm:px-6 md:flex-row md:space-x-10 lg:px-8">
            <Sidebar />
            <div class="w-full space-y-4">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200 md:leading-[64px]">
                    Orders
                </h2>
                <div v-if="orders.length" class="bg-white p-4 shadow dark:bg-gray-800 sm:rounded-lg sm:p-8">
                    <template v-for="order in orders" :key="order.id">
                        <Disclosure
                            as="div"
                            class="border-b border-gray-200 py-6 dark:border-gray-600"
                            v-slot="{ open }"
                        >
                            <h3 class="-my-3 flow-root">
                                <DisclosureButton
                                    class="group mb-2 flex w-full items-center justify-between px-2 py-2 text-gray-900 focus:rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-4 dark:text-gray-300 dark:focus:ring-offset-gray-800"
                                >
                                    <div
                                        class="text-left font-medium group-hover:text-purple-600 dark:group-hover:text-gray-50"
                                    >
                                        <span class="text-xs">Order # {{ order.id }} – {{ order.created_at }}</span>
                                    </div>
                                    <span
                                        v-if="!open"
                                        class="text-sm group-hover:text-purple-600 dark:group-hover:text-gray-50"
                                    >
                                        {{ formatMoney(order.total_cost) }}
                                    </span>
                                    <div
                                        class="ml-6 flex items-center group-hover:text-purple-600 dark:group-hover:text-gray-50"
                                    >
                                        <font-awesome-icon
                                            :icon="['fa-solid', !open ? 'fa-plus' : 'fa-minus']"
                                            size="lg"
                                        />
                                    </div>
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
                                <DisclosurePanel class="mt-6 p-1">
                                    <div class="relative mt-4 overflow-x-auto">
                                        <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                                            <thead class="text-xs uppercase text-gray-900 dark:text-gray-400">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3">Item</th>
                                                    <th scope="col" class="px-6 py-3 text-center">Price</th>
                                                    <th scope="col" class="px-6 py-3 text-center">Quantity</th>
                                                    <th scope="col" class="px-6 py-3 text-center">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    class="bg-white dark:bg-gray-800"
                                                    v-for="orderItem in order.orderItems"
                                                    :key="orderItem.id"
                                                >
                                                    <th
                                                        scope="row"
                                                        class="whitespace-nowrap py-4 font-medium text-gray-900 dark:text-white"
                                                    >
                                                        <Link
                                                            :href="route('goods.good.general', orderItem.good.slug)"
                                                            :title="orderItem.good.title"
                                                            class="flex items-center space-x-4"
                                                        >
                                                            <img
                                                                class="h-10 w-10 rounded-full"
                                                                :src="orderItem.good.preview"
                                                                :alt="orderItem.good.title"
                                                                :title="orderItem.good.title"
                                                            />
                                                            <span class="text-sm text-gray-900 dark:text-gray-200">
                                                                {{ orderItem.good.title }}
                                                            </span>
                                                        </Link>
                                                    </th>
                                                    <td class="py-4 text-center">
                                                        <p class="whitespace-nowrap text-gray-800 dark:text-gray-400">
                                                            {{ formatMoney(orderItem.good.price) }}
                                                        </p>
                                                    </td>
                                                    <td class="py-4 text-center text-gray-800 dark:text-gray-400">
                                                        <span>{{ orderItem.quantity }}</span>
                                                    </td>
                                                    <td class="py-4 text-center text-gray-800 dark:text-gray-400">
                                                        <span>{{
                                                            formatMoney(orderItem.good.price * orderItem.quantity)
                                                        }}</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr class="py-2 font-semibold text-gray-900 dark:text-white">
                                                    <th scope="row" class="py-1 text-base"></th>
                                                    <td class="py-1 text-right"></td>
                                                    <td class="py-1 text-right">Payment:</td>
                                                    <td class="py-1 text-right">{{ order.payment_method }}</td>
                                                </tr>
                                                <tr class="py-1 font-semibold text-gray-900 dark:text-white">
                                                    <th scope="row" class="py-1 text-base"></th>
                                                    <td class="py-1 text-right"></td>
                                                    <td class="py-1 text-right">Delivery:</td>
                                                    <td class="py-1 text-right">
                                                        {{ order.delivery_method }}
                                                        ({{ formatMoney(order.delivery_cost) }})
                                                    </td>
                                                </tr>
                                                <tr class="py-1 font-semibold text-gray-900 dark:text-white">
                                                    <th scope="row" class="py-1 text-base"></th>
                                                    <td class="py-1 text-right"></td>
                                                    <td class="py-1 text-right">Total:</td>
                                                    <td class="py-1 text-right">{{ formatMoney(order.total_cost) }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </DisclosurePanel>
                            </transition>
                        </Disclosure>
                    </template>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
