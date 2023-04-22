<script setup>
import {Head, router} from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Sidebar from '@/Pages/Profile/Partials/Sidebar.vue';
import { onMounted, ref } from 'vue';
import { loadStripe } from '@stripe/stripe-js';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    billingDetails: Object,
});

const paymentProcessing = ref(false);
const stripe = ref(null);
const cardElement = ref(null);

onMounted(async () => {
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
});

const completed = ref(false);

const processPayment = async () => {
    paymentProcessing.value = true;
    await stripe.value
        .createPaymentMethod({
            type: 'card',
            card: cardElement.value,
            billing_details: props.billingDetails,
        })
        .then(({ paymentMethod }) => {
            router.post(route('profile.wallet.store'), paymentMethod)
            cardElement.value.clear()
        })
        .catch((error) => {
            console.log(error);
        })
        .finally(() => (paymentProcessing.value = false));
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
                    <div class="flex flex-col">
                        <label for="card-element" class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Credit Card Information
                        </label>
                        <div id="card-element"></div>
                        <primary-button
                            class="mt-4"
                            :disabled="paymentProcessing || !completed"
                            @click="processPayment"
                        >
                            <font-awesome-icon :icon="['fas', 'credit-card']" />
                            Connect Card
                        </primary-button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
