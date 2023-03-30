<script setup>
import Modal from "@/Components/Modal.vue";

defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    count: Number,
    total: Number,
    items: Array,
})

defineEmits(['close'])
</script>

<template>
    <Modal :show="show" @close="$emit('close')">
        <div class="">
            <div
                class="modal__header flex items-center justify-between h-14 px-4 md:px-6 border-b border-slate-100">
                <h3 class="text-2xl sm:text-3xl text-slate-900">Cart</h3>
                <button @click="$emit('close')">
                    <font-awesome-icon :icon="['fas', 'xmark']" size="xl" />
                </button>
            </div>
            <div class="modal__content max-h-[80vh] p-4 md:p-6 overflow-y-auto">
                <div v-if="items.length">
                    <div class="flex justify-end mb-4">
                        <button @click="$emit('bulk-delete')"
                                class="text-white uppercase bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">
                            <span>Clear all</span>
                        </button>
                    </div>
                    <ul class="cart-list md:mb-6">
                        <li class="cart-list__item mt-6 pt-6 border-t border-slate-100"
                            :class="i === 0 ? 'mt-0 pt-0 border-none' : null" v-for="(item, i) in items"
                            :key="item.id">
                            <div class="cart-product">
                                <div class="cart-product__body relative flex">
                            <span
                                class="promo-label text-xs leading-6 px-2 bg-red-600 top-0 left-0 absolute">−20%</span>
                                    <a class="flex shrink-0 items-center justify-center mr-4 w-32 h-32">
                                        <img v-if="item.preview" :src="item.preview"
                                             class="h-full w-full object-cover object-center rounded-lg" loading="lazy"
                                             :alt="item.title" :title="item.title">
                                    </a>
                                    <div class="cart-product__main grow">
                                        <a class="block mb-2" href="" :title="item.title">
                                            <span class="text-sm text-gray-500">{{ item.title }}</span>
                                        </a>
                                    </div>
                                    <div>
                                        <button @click="$emit('remove', item)">
                                            <font-awesome-icon :icon="['fas', 'trash-can']" />
                                        </button>
                                    </div>
                                </div>
                                <div
                                    class="cart-product__footer flex row flex-wrap justify-between md:justify-end py-4 md:py-0 md:pl-28">
                                    <div class="cart-product__counter">
                                        <div class="flex items-center">
                                            <font-awesome-icon :icon="['fas', 'minus']" @click="$emit('update', item, item.quantity - 1)" />
                                            <input type="number" v-model="item.quantity" @input="$emit('update', item, item.quantity)"
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm text-center font-medium rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-14 p-2.5">
                                            <font-awesome-icon :icon="['fas', 'minus']" class="ml-2 text-indigo-600 cursor-pointer" @click="$emit('update', item, item.quantity + 1)" />
                                        </div>
                                    </div>
                                    <div
                                        class="cart-product__coast flex flex-col justify-center text-right ml-auto md:ml-0 md:w-1/4">
                                        <p class="text-sm leading-4 line-through text-gray-500">{{ item.oldPrice }}&nbsp;₴</p>
                                        <p class="text-xl text-red-600 whitespace-nowrap">{{ item.price }}&nbsp;<small>₴</small></p>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="cart-footer sticky -bottom-4 md:-mb-4 flex flex-wrap items-center py-4 bg-inherit bg-white">
                        <button @click="$emit('close-cart')"
                                class="relative inline-flex items-center justify-center p-0.5 mb-2 mr-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white focus:ring-4 focus:outline-none focus:ring-blue-300">
                        <span
                            class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white rounded-md group-hover:bg-opacity-0">Continue shopping</span>
                        </button>
                        <div
                            class="flex flex-col md:flex-row items-center w-full md:w-auto p-4 md:p-6 md:ml-auto rounded bg-purple-50 border border-slate-300">
                            <div class="cart-receipt__sum flex flex-row items-center justify-between w-full md:w-auto mb-4 md:mr-6 md:mb-0">
                                <p class="cart-receipt__sum-label text-xl md:hidden">Total</p>
                                <div class="cart-receipt__sum-price ml-auto text-2xl md:text-4xl"><span>{{ total }}</span>&nbsp;<span
                                    class="cart-receipt__sum-currency text-base md:text-lg">₴</span>
                                </div>
                            </div>
                            <button @click="$emit('checkout')"
                                    class="text-white uppercase bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                <span>Proceed to checkout</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div v-else class="cart-dummy flex flex-col justify-center items-center">
                    <img loading="lazy" alt="Cart" title="Cart" class="cart-dummy__illustration w-full mb-12 max-w-xs"
                         src="@/assets/modal-cart-dummy.svg">
                    <h4 class="cart-dummy__heading md:text-2xl text-xl mb-4">Cart is empty</h4>
                    <p class="cart-dummy__caption text-sm text-gray-500">But it's never too late to fix it :)</p>
                </div>
            </div>
        </div>
    </Modal>
</template>
