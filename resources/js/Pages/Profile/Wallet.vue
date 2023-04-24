<script setup>
import { computed, nextTick, ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { loadStripe } from '@stripe/stripe-js';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CreditCard from '@/Pages/Profile/Partials/CreditCard.vue';
import Sidebar from '@/Pages/Profile/Partials/Sidebar.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    billingDetails: Object,
    card: Object,
    hasSocials: Boolean,
});

const stripe = ref(null);
const cardElement = ref(null);
const paymentProcessing = ref(false);
const completed = ref(false);
const cardModal = ref(false);
const confirmingCardDeletion = ref(false);
const deleteInput = ref(null);
const confirmInput = ref('');

const form = useForm({
    password: '',
});

const isCardExists = computed(() => !!props.card);

const onCardModal = async () => {
    cardModal.value = true;
    stripe.value = await loadStripe(import.meta.env.VITE_STRIPE_KEY);
    const elements = stripe.value.elements();
    cardElement.value = elements.create('card', {
        hidePostalCode: true,
        classes: {
            base: 'p-3 bg-gray-100 rounded border border-gray-300 outline-none transition-colors duration-200 ease-in-out',
        },
    });
    cardElement.value.mount('#card-element');
    cardElement.value.on('change', (event) => (completed.value = event.complete));
};

const submit = async () => {
    paymentProcessing.value = true;
    await stripe.value
        .createPaymentMethod({
            type: 'card',
            card: cardElement.value,
            billing_details: props.billingDetails,
        })
        .then(({ paymentMethod }) => {
            isCardExists.value
                ? router.put(route('profile.wallet.update'), paymentMethod, {
                      onSuccess: () => (cardModal.value = false),
                  })
                : router.post(route('profile.wallet.store'), paymentMethod, {
                      onSuccess: () => (cardModal.value = false),
                  });
        })
        .catch((error) => {
            console.log(error);
        })
        .finally(() => (paymentProcessing.value = false));
};

const confirmCardDeletion = () => {
    confirmingCardDeletion.value = true;

    nextTick(() => deleteInput.value.focus());
};

const deleteCard = () => {
    if (props.hasSocials) {
        router.delete(route('profile.wallet.delete'), {
            preserveScroll: true,
            onSuccess: () => closeDeletionModal(),
            onError: () => deleteInput.value.focus(),
        });
    } else {
        form.delete(route('profile.wallet.delete'), {
            preserveScroll: true,
            onSuccess: () => closeDeletionModal(),
            onError: () => deleteInput.value.focus(),
            onFinish: () => form.reset(),
        });
    }
};

const closeDeletionModal = () => {
    confirmingCardDeletion.value = false;

    form.reset();
};
</script>

<template>
    <Head title="Wallet" />

    <AuthenticatedLayout>
        <div class="flex flex-col justify-between space-x-0 px-4 py-12 sm:px-6 md:flex-row md:space-x-10 lg:px-8">
            <Sidebar />
            <div class="w-full space-y-4">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200 md:leading-[64px]">
                    Wallet
                </h2>
                <div class="bg-white p-4 shadow dark:bg-gray-800 sm:rounded-lg sm:p-8">
                    <credit-card v-if="card" :labels="card" :isNumberMasked="true" :randomBackgrounds="true" />
                    <div
                        :class="[
                            'flex flex-col space-y-4 lg:flex-row lg:items-center lg:justify-between lg:space-y-0',
                            { 'mt-10': card },
                        ]"
                    >
                        <primary-button v-if="!isCardExists" type="button" @click="onCardModal">
                            <font-awesome-icon :icon="['fas', 'credit-card']" />
                            <span class="ml-2">Add credit card</span>
                        </primary-button>
                        <template v-else>
                            <secondary-button @click="onCardModal">
                                <font-awesome-icon :icon="['fas', 'pen-to-square']" />
                                <span class="ml-2">Update credit card</span>
                            </secondary-button>
                            <danger-button @click="confirmCardDeletion">
                                <font-awesome-icon :icon="['fas', 'trash-can']" />
                                <span class="ml-2">Delete credit card</span>
                            </danger-button>
                        </template>
                    </div>
                    <modal :show="cardModal" @close="cardModal = false">
                        <div class="p-6">
                            <div
                                class="flex items-center justify-between border-b border-gray-200 pb-2 dark:border-gray-700"
                            >
                                <div class="flex flex-col">
                                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                        {{ isCardExists ? 'Update' : 'Add new' }} credit card
                                    </h2>

                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        Fill in all available fields to
                                        {{ isCardExists ? 'update your' : 'add a new' }} credit card
                                        {{ isCardExists ? 'information' : 'for future payments' }}
                                    </p>
                                </div>

                                <button
                                    class="text-purple-900 hover:opacity-70 dark:text-purple-200"
                                    @click="cardModal = false"
                                >
                                    <font-awesome-icon :icon="['fas', 'xmark']" size="xl" />
                                </button>
                            </div>

                            <div class="mt-6">
                                <div class="flex flex-col">
                                    <label
                                        for="card-element"
                                        class="text-lg font-medium text-gray-900 dark:text-gray-100"
                                    >
                                        Credit Card Information
                                    </label>
                                    <div id="card-element"></div>
                                    <primary-button
                                        class="mt-4"
                                        :disabled="paymentProcessing || !completed"
                                        @click="submit"
                                    >
                                        <font-awesome-icon :icon="['fas', 'credit-card']" />
                                        {{ isCardExists ? 'Update' : 'Connect' }} Card
                                    </primary-button>
                                </div>
                            </div>
                        </div>
                    </modal>

                    <modal :show="confirmingCardDeletion" @close="closeDeletionModal">
                        <div class="p-6">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Are you sure you want to delete your credit card?
                            </h2>

                            <template v-if="hasSocials">
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    Please enter <span class="font-bold italic">confirm delete</span> to confirm you
                                    would like to permanently delete your credit card.
                                </p>

                                <div class="mt-6">
                                    <text-input
                                        id="confirm"
                                        ref="deleteInput"
                                        v-model="confirmInput"
                                        type="text"
                                        class="block w-full"
                                        placeholder="Type in confirm delete"
                                        @keyup.enter="deleteCard"
                                    />
                                </div>
                            </template>
                            <template v-else>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    Please enter your password to confirm you would like to permanently delete your
                                    credit card.
                                </p>

                                <div class="mt-6">
                                    <text-input
                                        id="password"
                                        ref="deleteInput"
                                        v-model="form.password"
                                        type="password"
                                        class="block w-full"
                                        placeholder="Password"
                                        @keyup.enter="deleteCard"
                                    />

                                    <input-error :message="form.errors.password" class="mt-2" />
                                </div>
                            </template>

                            <div class="mt-6 flex justify-end">
                                <secondary-button @click="closeDeletionModal">Cancel</secondary-button>

                                <danger-button
                                    type="submit"
                                    class="ml-3"
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing || (hasSocials && confirmInput !== 'confirm delete')"
                                    @click="deleteCard"
                                >
                                    Delete Credit Card
                                </danger-button>
                            </div>
                        </div>
                    </modal>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
