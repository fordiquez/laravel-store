<script setup>
import { Link, router } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import { useFormat } from '@/composables/format';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    count: Number,
    total: Number,
    items: Array,
    goods: Array,
});

defineEmits(['close']);

const { formatMoney } = useFormat();

const itemId = (id) => props.items.findIndex((item) => item.good_id === id);

const update = (good, quantity) =>
    router.patch(route('cart.update', good), {
        quantity,
    });

const remove = (good) => router.delete(route('cart.delete', good));

const bulkDelete = () => router.delete(route('cart.bulk-delete'));
</script>

<template>
    <Modal :show="show" @close="$emit('close')">
        <div
            class="flex items-center justify-between border-b border-gray-200 px-4 py-3 text-gray-800 dark:border-gray-700 dark:text-gray-200 md:px-6"
        >
            <h3 class="text-2xl sm:text-3xl">Cart</h3>
            <button class="hover:opacity-70" @click="$emit('close')">
                <font-awesome-icon :icon="['fas', 'xmark']" size="xl" />
            </button>
        </div>
        <div
            class="max-h-[80vh] overflow-y-auto p-4 scrollbar-thin scrollbar-track-purple-300 scrollbar-thumb-purple-600 md:p-6"
        >
            <template v-if="items.length">
                <div class="mb-4 flex flex-col justify-between space-y-4 sm:flex-row sm:space-y-0">
                    <secondary-button @click="$emit('close')">
                        <font-awesome-icon :icon="['fas', 'cart-plus']" class="mr-2" />
                        Continue shopping
                    </secondary-button>
                    <danger-button @click="bulkDelete">
                        <font-awesome-icon :icon="['fas', 'trash-can']" class="mr-2" />
                        Clear all
                    </danger-button>
                </div>
                <ul class="md:mb-6">
                    <li
                        :class="['border-t border-gray-200 py-6 dark:border-gray-700', { 'border-none': i === 0 }]"
                        v-for="(good, i) in goods"
                        :key="good.id"
                    >
                        <div class="relative flex">
                            <span
                                v-if="good.old_price"
                                class="absolute left-0 top-0 bg-red-600 px-2 text-xs font-medium leading-6 text-gray-100"
                            >
                                {{ Number((good.price * 100) / good.old_price - 100).toFixed(0) }}%
                            </span>
                            <Link
                                :href="route('goods.good.general', good.slug)"
                                class="mr-4 flex h-32 w-32 shrink-0 items-center justify-center"
                            >
                                <img
                                    v-if="good.preview"
                                    :src="good.preview"
                                    :alt="good.title"
                                    :title="good.title"
                                    class="h-full w-full rounded-lg object-cover object-center"
                                    loading="lazy"
                                />
                            </Link>
                            <div class="grow">
                                <Link :href="route('goods.good.general', good.slug)" class="mb-2" :title="good.title">
                                    <span class="text-sm text-gray-900 dark:text-gray-200">{{ good.title }}</span>
                                </Link>
                            </div>
                            <font-awesome-icon
                                :icon="['fas', 'trash-can']"
                                class="cursor-pointer text-purple-900 hover:opacity-70 dark:text-purple-200"
                                @click.prevent="remove(good)"
                            />
                        </div>
                        <div class="row flex flex-wrap justify-between py-4 md:justify-end md:py-0 md:pl-28">
                            <div class="flex items-center">
                                <button
                                    :class="[
                                        'mr-3',
                                        items[itemId(good.id)].quantity > 1
                                            ? 'cursor-pointer text-purple-600'
                                            : 'cursor-not-allowed text-gray-300 dark:text-gray-500',
                                    ]"
                                    :disabled="items[itemId(good.id)].quantity <= 1"
                                    @click.prevent="update(good, items[itemId(good.id)].quantity - 1)"
                                >
                                    <font-awesome-icon :icon="['fas', 'minus']" />
                                </button>
                                <input
                                    type="number"
                                    class="w-14 rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-center text-sm text-gray-900 focus:border-purple-500 focus:ring-purple-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-purple-500 dark:focus:ring-purple-500"
                                    v-model="items[itemId(good.id)].quantity"
                                    @input="update(good, items[itemId(good.id)].quantity)"
                                />
                                <font-awesome-icon
                                    :icon="['fas', 'plus']"
                                    class="ml-3 cursor-pointer text-purple-600"
                                    @click.prevent="update(good, items[itemId(good.id)].quantity + 1)"
                                />
                            </div>
                            <div class="ml-auto flex flex-col justify-center text-right md:ml-0 md:w-1/4">
                                <p v-if="good.old_price" class="text-sm leading-4 text-gray-400 line-through">
                                    {{ formatMoney(good.old_price) }}
                                </p>
                                <p class="whitespace-nowrap text-xl font-medium text-red-600">
                                    {{ formatMoney(good.price) }}
                                </p>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="sticky -bottom-4 flex flex-wrap items-center py-4 md:-mb-4">
                    <div
                        class="flex w-full flex-col items-center rounded border border-slate-300 bg-purple-50 p-4 dark:border-slate-500 dark:bg-gray-700 md:ml-auto md:w-auto md:flex-row md:p-6"
                    >
                        <div class="mb-4 flex w-full flex-row items-center justify-between md:mb-0 md:mr-6 md:w-auto">
                            <p class="text-xl text-gray-900 dark:text-gray-200 md:hidden">Total</p>
                            <div class="ml-auto text-2xl text-gray-900 dark:text-gray-200 md:text-4xl">
                                <span>{{ formatMoney(total) }}</span>
                            </div>
                        </div>
                        <primary-button class="self-end" type="button" @click="router.get(route('checkout.index'))">
                            <font-awesome-icon :icon="['fas', 'credit-card']" class="mr-2" />
                            <span>Proceed to checkout</span>
                        </primary-button>
                    </div>
                </div>
            </template>
            <div v-else class="flex flex-col items-center justify-center">
                <img
                    loading="lazy"
                    alt="Cart"
                    title="Cart"
                    class="mb-12 w-full max-w-xs"
                    src="@/assets/modal-cart-dummy.svg"
                />
                <h4
                    class="mb-4 text-xl font-medium uppercase tracking-wide text-gray-900 dark:text-gray-200 md:text-2xl"
                >
                    Cart is empty
                </h4>
                <p class="text-sm text-gray-800 dark:text-gray-400">But it's never too late to fix it :)</p>
            </div>
        </div>
    </Modal>
</template>
