<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, reactive } from 'vue';
import { useFormat } from '@/composables/format';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import axios from 'axios';

const props = defineProps({
    cart: Object,
    deliveries: Array,
    payments: Object,
});

const { full_name, email, phone } = reactive(usePage().props.user);

const { formatMoney } = useFormat();

const form = useForm({
    delivery_method: '',
    payment_method: '',
    promo_code_id: null,
});
const promoCode = reactive({
    key: '',
    data: null,
    error: null,
    modal: false,
    loading: false,
});

const deliveryCost = computed(() =>
    form.delivery_method
        ? Number.parseFloat(props.deliveries.find((delivery) => delivery.key === form.delivery_method).value)
        : 0,
);
const totalCost = computed(() => deliveryCost.value + props.cart.total);
const promoCodePercentagePrice = computed(() =>
    promoCode.data ? (totalCost.value * promoCode.data.value) / 100 : null,
);
const totalCostPromoCode = computed(() =>
    promoCode.data
        ? promoCode.data.type === 'fixed'
            ? totalCost.value - promoCode.data.value
            : totalCost.value - promoCodePercentagePrice.value
        : totalCost.value,
);

const itemId = (id) => props.cart.items.findIndex((item) => item.good_id === id);

const verifyPromoCode = () => {
    promoCode.loading = true;
    axios
        .post(route('api.verify-promo-code'), {
            key: promoCode.key,
            total: props.cart.total,
        })
        .then(({ data }) => {
            if (!data.hasOwnProperty('message')) {
                promoCode.error = null;
                promoCode.modal = false;
                promoCode.data = data;
                form.promo_code_id = data.id;
            } else {
                promoCode.error = data.message;
            }
        })
        .catch((error) => (promoCode.error = error.response.data.message))
        .finally(() => (promoCode.loading = false));
};

const confirmOrder = () => {
    form.transform((data) => ({
        ...data,
        items: props.cart.items,
        goods_cost: props.cart.total,
        delivery_cost: deliveryCost.value,
        total_cost: totalCostPromoCode.value,
    })).post(route('checkout.store'));
};
</script>

<template>
    <Head title="Checkout" />

    <AuthenticatedLayout>
        <div class="px-4 py-12 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-extrabold dark:text-white">Checkout</h1>
            <div class="flex flex-col justify-between lg:flex-row lg:space-x-10">
                <div class="mt-10 flex flex-col space-y-6 lg:basis-3/4">
                    <div class="flex flex-col">
                        <div class="flex space-x-4">
                            <span
                                class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-purple-500 font-semibold text-purple-200 dark:bg-purple-700 dark:text-purple-200"
                            >
                                1
                            </span>
                            <p class="text-gray-700 dark:text-gray-400">Your contact details</p>
                        </div>
                        <p class="mt-4 pl-10 text-sm text-gray-500 dark:text-gray-300">
                            {{ `${full_name}, ${email}` }}{{ phone ? `, ${phone}` : null }}
                        </p>
                    </div>
                    <div class="flex flex-col">
                        <div class="flex space-x-4">
                            <span
                                class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-purple-500 font-semibold text-purple-200 dark:bg-purple-700 dark:text-purple-200"
                            >
                                2
                            </span>
                            <p class="text-gray-700 dark:text-gray-400">Good items</p>
                        </div>

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
                                    <tr class="bg-white dark:bg-gray-800" v-for="good in cart.goods" :key="good.id">
                                        <th
                                            scope="row"
                                            class="whitespace-nowrap px-6 py-4 font-medium text-gray-900 dark:text-white"
                                        >
                                            <Link
                                                :href="route('goods.good.general', good.slug)"
                                                :title="good.title"
                                                class="flex items-center space-x-4"
                                            >
                                                <img
                                                    class="h-10 w-10 rounded-full"
                                                    :src="good.preview"
                                                    :alt="good.title"
                                                    :title="good.title"
                                                />
                                                <span class="text-sm text-gray-900 dark:text-gray-200">
                                                    {{ good.title }}
                                                </span>
                                            </Link>
                                        </th>
                                        <td class="px-6 py-4 text-center">
                                            <p
                                                class="whitespace-nowrap font-medium"
                                                :class="
                                                    good.old_price
                                                        ? 'text-red-600 dark:text-red-500'
                                                        : 'text-gray-800 dark:text-gray-400'
                                                "
                                            >
                                                {{ formatMoney(good.price) }}
                                            </p>
                                            <p
                                                v-if="good.old_price"
                                                class="text-xs leading-4 text-gray-400 line-through"
                                            >
                                                {{ formatMoney(good.old_price) }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-4 text-center text-gray-800 dark:text-gray-400">
                                            <span>{{ cart.items[itemId(good.id)].quantity }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center text-gray-800 dark:text-gray-400">
                                            <span>{{
                                                formatMoney(good.price * cart.items[itemId(good.id)].quantity)
                                            }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <div class="flex space-x-4">
                            <span
                                class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-purple-500 font-semibold text-purple-200 dark:bg-purple-700 dark:text-purple-200"
                            >
                                3
                            </span>
                            <p class="text-gray-700 dark:text-gray-400">Delivery method</p>
                        </div>
                        <div
                            class="mt-4 flex items-center justify-between px-10"
                            v-for="delivery in deliveries"
                            :key="delivery.id"
                        >
                            <div class="flex items-center space-x-4">
                                <input
                                    type="radio"
                                    class="h-5 w-5 cursor-pointer border-gray-300 bg-gray-100 text-purple-600 focus:ring-2 focus:ring-purple-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-purple-600"
                                    v-model="form.delivery_method"
                                    :id="delivery.key"
                                    :value="delivery.key"
                                />
                                <label
                                    :for="delivery.key"
                                    class="cursor-pointer text-sm font-medium text-gray-900 dark:text-gray-300"
                                >
                                    {{ delivery.name }}
                                </label>
                            </div>
                            <p id="helper-radio-text" class="font-normal text-gray-500 dark:text-gray-300">
                                {{ formatMoney(delivery.value) }}
                            </p>
                        </div>
                        <InputError class="ml-10 mt-4" :message="form.errors.delivery_method" />
                    </div>
                    <div class="flex flex-col">
                        <div class="flex space-x-4">
                            <span
                                class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-purple-500 font-semibold text-purple-200 dark:bg-purple-700 dark:text-purple-200"
                            >
                                4
                            </span>
                            <p class="text-gray-700 dark:text-gray-400">Payment method</p>
                        </div>
                        <div
                            class="mt-4 flex items-center px-10"
                            v-for="(payment, index) in Object.entries(payments)"
                            :key="index"
                        >
                            <div class="flex items-center space-x-4">
                                <input
                                    type="radio"
                                    class="h-5 w-5 cursor-pointer border-gray-300 bg-gray-100 text-purple-600 focus:ring-2 focus:ring-purple-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-purple-600"
                                    v-model="form.payment_method"
                                    :id="payment[0]"
                                    :value="payment[0]"
                                />
                                <label
                                    :for="payment[0]"
                                    class="cursor-pointer text-sm font-medium text-gray-900 dark:text-gray-300"
                                >
                                    {{ payment[1] }}
                                </label>
                            </div>
                        </div>
                        <InputError class="ml-10 mt-4" :message="form.errors.payment_method" />
                    </div>
                </div>
                <div
                    class="mt-10 flex max-h-80 w-full flex-col rounded-lg border border-gray-200 bg-white p-4 shadow dark:border-gray-700 dark:bg-gray-800 lg:max-w-xs"
                >
                    <h5 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Total</h5>
                    <div class="mt-4 flex justify-between text-sm">
                        <p class="text-gray-900 dark:text-gray-300">{{ cart.count }} goods for the amount</p>
                        <p class="text-gray-900 dark:text-gray-300">{{ formatMoney(cart.total) }}</p>
                    </div>
                    <div class="mt-2 flex justify-between text-sm">
                        <p class="text-gray-900 dark:text-gray-300">Delivery cost</p>
                        <p class="text-gray-900 dark:text-gray-300">{{ formatMoney(deliveryCost) }}</p>
                    </div>
                    <div v-if="promoCode.data" class="mt-2 flex justify-between text-sm">
                        <p class="text-gray-900 dark:text-gray-300">Applied promo code</p>
                        <p class="text-red-600 dark:text-red-500">
                            -{{
                                formatMoney(
                                    promoCode.data.type === 'fixed' ? promoCode.data.value : promoCodePercentagePrice,
                                )
                            }}
                        </p>
                    </div>
                    <div class="mt-2 flex justify-between border-y border-gray-200 py-2 dark:border-gray-700">
                        <p class="text-gray-900 dark:text-gray-300">Total cost</p>
                        <p class="text-gray-900 dark:text-gray-300">{{ formatMoney(totalCostPromoCode) }}</p>
                    </div>
                    <secondary-button class="mt-8 w-full lg:mt-auto" @click="promoCode.modal = true">
                        Apply promo code
                    </secondary-button>
                    <primary-button class="mt-4 w-full" @click.prevent="confirmOrder">Confirm order</primary-button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

    <Modal :show="promoCode.modal" @close="promoCode.modal = false">
        <form class="p-6" @submit.prevent="verifyPromoCode">
            <div class="flex justify-between text-gray-900 dark:text-white">
                <h5 class="text-xl font-medium">Apply promo code</h5>
                <font-awesome-icon
                    :icon="['fas', 'xmark']"
                    size="xl"
                    class="cursor-pointer"
                    @click="promoCode.modal = false"
                />
            </div>

            <div class="my-4">
                <TextInput
                    id="promo-code"
                    type="text"
                    class="mt-1 block w-full"
                    placeholder="Enter your promo code"
                    v-model="promoCode.key"
                    required
                />
                <InputError class="mt-2" :message="promoCode.error" />
            </div>

            <div class="flex space-x-4">
                <secondary-button class="w-full" @click="promoCode.modal = false">Cancel</secondary-button>
                <primary-button class="w-full" :disabled="promoCode.loading">Apply</primary-button>
            </div>
        </form>
    </Modal>
</template>
