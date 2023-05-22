<script setup>
import { onMounted, reactive, ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import { initTooltips } from 'flowbite';
import Multiselect from 'vue-multiselect';
import axios from 'axios';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import 'vue-multiselect/dist/vue-multiselect.css';

const props = defineProps({
    addresses: Array,
    countries: Array,
});

onMounted(() => initTooltips());

const form = useForm({
    country_id: null,
    state_id: null,
    city_id: null,
    street: '',
    house: '',
    flat: null,
    postal_code: null,
});

const defaultAddress = ref(props.addresses?.find((item) => item.is_main)?.id || null);
const addressModal = ref(false);
const addressUpdateModal = ref(false);
const country = ref();
const state = ref();
const city = ref();
const states = reactive([]);
const cities = reactive([]);
const loading = reactive([]);

const isLoading = (field) => !!loading.find((item) => field === item);

const onCountrySelected = async (selectedCountry) => {
    form.country_id = selectedCountry.id;
    loading.push('state');
    await axios
        .get(route('api.locations.states', selectedCountry.iso2.toLowerCase()))
        .then(({ data }) => {
            states.splice(0, states.length, ...data);
            state.value = city.value = null;
            form.reset('state_id', 'city_id');
        })
        .catch((error) => {
            console.log(error);
        })
        .finally(() => loading.pop());
};

const onStateSelected = async (selectedState) => {
    form.state_id = selectedState.id;
    loading.push('city');
    await axios
        .get(route('api.locations.cities', selectedState.id))
        .then(({ data }) => {
            cities.splice(0, cities.length, ...data);
            city.value = null;
            form.reset('city_id');
        })
        .catch((error) => {
            console.log(error);
        })
        .finally(() => loading.pop());
};

const onCitySelected = (selectedCity) => (form.city_id = selectedCity.id);

const submit = () =>
    !addressUpdateModal.value
        ? form.post(route('profile.address.store'), {
              onSuccess: () => closeAddressModal(),
          })
        : form.put(route('profile.address.update', form), {
              onSuccess: () => closeAddressModal(),
          });

const closeAddressModal = () => {
    form.reset();
    form.clearErrors();
    addressModal.value = false;
    country.value = state.value = city.value = null;
};

const onEditAddress = (address) => {
    country.value = address.country;
    onCountrySelected(address.country).then(() => {
        state.value = address.state;
        onStateSelected(address.state).then(() => {
            city.value = address.city;
            onCitySelected(address.city);
        });
    });
    form.id = address.id;
    form.street = address.street;
    form.house = address.house;
    form.flat = address.flat;
    form.postal_code = address.postal_code;
    addressModal.value = true;
    addressUpdateModal.value = true;
};

const onChangeDefaultAddress = (event) => {
    defaultAddress.value = Number.parseInt(event.target.value) || null;
    router.patch(route('profile.address.patch', defaultAddress.value));
};
</script>

<template>
    <section>
        <header class="flex flex-col justify-between space-y-4 sm:flex-row sm:items-center sm:space-y-0">
            <div>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Delivery Addresses</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage your saved delivery addresses</p>
            </div>
            <secondary-button class="w-full sm:w-auto" @click="addressModal = true">Add address</secondary-button>
        </header>

        <div v-if="addresses.length" class="mt-4 lg:max-w-lg">
            <label for="addresses" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                Default delivery address
            </label>
            <select
                @change="onChangeDefaultAddress"
                id="addresses"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-purple-500 focus:ring-purple-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-purple-500 dark:focus:ring-purple-500"
            >
                <option disabled :selected="!defaultAddress">Choose the address</option>
                <option
                    v-for="address in addresses"
                    :key="address.id"
                    :value="address.id"
                    :selected="address.id === defaultAddress"
                    v-html="
                        `${address.country.name}, ${address.state.name}, ${address.city.name}, ${address.street}, ${address.house}`
                    "
                />
            </select>
        </div>

        <div
            v-if="addresses.length"
            class="relative mx-auto mt-6 max-w-full overflow-x-auto shadow-md sm:rounded-lg md:max-w-md lg:max-w-xl xl:max-w-full"
        >
            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="p-4">#</th>
                        <th scope="col" class="p-4">Country</th>
                        <th scope="col" class="p-4">State</th>
                        <th scope="col" class="p-4">City</th>
                        <th scope="col" class="p-4">Street</th>
                        <th scope="col" class="p-4">House</th>
                        <th scope="col" class="p-4">Flat</th>
                        <th scope="col" class="p-4">Postal Code</th>
                        <th scope="col" class="p-4"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(address, i) in addresses" :key="address.id" class="bg-white dark:bg-gray-800">
                        <th scope="row" class="whitespace-nowrap p-4 font-bold text-gray-900 dark:text-white">
                            {{ i + 1 }}
                        </th>
                        <td class="p-4">{{ address.country.name }}</td>
                        <td class="p-4">{{ address.state.name }}</td>
                        <td class="p-4">{{ address.city.name }}</td>
                        <td class="p-4">{{ address.street }}</td>
                        <td class="p-4">{{ address.house }}</td>
                        <td class="p-4">{{ address.flat ?? '–' }}</td>
                        <td class="p-4">{{ address.postal_code ?? '–' }}</td>
                        <td class="flex items-center justify-end p-4">
                            <button
                                :data-tooltip-target="`edit-${address.id}-tooltip`"
                                class="text-purple-900 transition-all duration-300 hover:opacity-70 dark:text-purple-200"
                                @click.prevent="onEditAddress(address)"
                            >
                                <font-awesome-icon :icon="['fas', 'pen-to-square']" />
                            </button>
                            <div
                                :id="`edit-${address.id}-tooltip`"
                                role="tooltip"
                                class="tooltip invisible absolute z-10 inline-block rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white opacity-0 shadow-sm transition-opacity duration-300 dark:bg-gray-700"
                            >
                                Edit address
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                            <button
                                :data-tooltip-target="`delete-${address.id}-tooltip`"
                                class="ml-2 text-purple-900 transition-all duration-300 hover:opacity-70 dark:text-purple-200"
                                @click.prevent="router.delete(route('profile.address.destroy', address.id))"
                            >
                                <font-awesome-icon :icon="['fas', 'trash-can']" />
                            </button>
                            <div
                                :id="`delete-${address.id}-tooltip`"
                                role="tooltip"
                                class="tooltip invisible absolute z-10 inline-block rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white opacity-0 shadow-sm transition-opacity duration-300 dark:bg-gray-700"
                            >
                                Delete address
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <modal :show="addressModal" max-width="5xl" @close="closeAddressModal">
            <div class="p-6">
                <div class="flex items-center justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                    <div class="flex flex-col">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">New Delivery Address</h2>

                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Fill in all available fields to add a new delivery address
                        </p>
                    </div>

                    <button class="text-purple-900 hover:opacity-70 dark:text-purple-200" @click="closeAddressModal">
                        <font-awesome-icon :icon="['fas', 'xmark']" size="xl" />
                    </button>
                </div>

                <div class="mt-6">
                    <form @submit.prevent="submit" class="mt-6 flex flex-col space-y-6">
                        <div class="flex w-full flex-col space-y-4 lg:flex-row lg:space-x-4 lg:space-y-0">
                            <div class="basis-1/3">
                                <InputLabel for="country" value="Country" />

                                <multiselect
                                    id="country"
                                    v-model="country"
                                    :options="countries"
                                    :close-on-select="true"
                                    :clear-on-select="false"
                                    @select="onCountrySelected"
                                    class="mt-1"
                                    placeholder="Select the country"
                                    label="name"
                                    track-by="id"
                                />

                                <InputError class="mt-2" :message="form.errors.country_id" />
                            </div>
                            <div class="basis-1/3">
                                <InputLabel for="state" value="State" />

                                <multiselect
                                    id="state"
                                    v-model="state"
                                    :options="states"
                                    :close-on-select="true"
                                    :clear-on-select="false"
                                    :disabled="!form.country_id || isLoading('state')"
                                    :loading="isLoading('state')"
                                    @select="onStateSelected"
                                    class="mt-1"
                                    placeholder="Select the state"
                                    label="name"
                                    track-by="id"
                                />

                                <InputError class="mt-2" :message="form.errors.state_id" />
                            </div>
                            <div class="basis-1/3">
                                <InputLabel for="city" value="City" />

                                <multiselect
                                    id="city"
                                    v-model="city"
                                    :options="cities"
                                    :disabled="!form.country_id || !form.state_id || isLoading('city')"
                                    :loading="isLoading('city')"
                                    @select="onCitySelected"
                                    :close-on-select="true"
                                    :clear-on-select="false"
                                    class="mt-1"
                                    placeholder="Select the city"
                                    label="name"
                                    track-by="id"
                                />

                                <InputError class="mt-2" :message="form.errors.city_id" />
                            </div>
                        </div>
                        <div class="flex w-full flex-col space-y-4 lg:flex-row lg:space-x-4 lg:space-y-0">
                            <div class="basis-2/5">
                                <InputLabel for="street" value="Street" />

                                <TextInput
                                    id="street"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.street"
                                    required
                                    autocomplete="street"
                                />

                                <InputError class="mt-2" :message="form.errors.street" />
                            </div>
                            <div class="basis-1/5">
                                <InputLabel for="house" value="House" />

                                <TextInput
                                    id="house"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.house"
                                    required
                                    autocomplete="house"
                                />

                                <InputError class="mt-2" :message="form.errors.house" />
                            </div>
                            <div class="basis-1/5">
                                <InputLabel for="flat" value="Flat" />

                                <TextInput
                                    id="flat"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.flat"
                                    autocomplete="flat"
                                />

                                <InputError class="mt-2" :message="form.errors.flat" />
                            </div>
                            <div class="basis-1/5">
                                <InputLabel for="postal_code" value="Postal Code" />

                                <TextInput
                                    id="postal_code"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.postal_code"
                                    autocomplete="postal_code"
                                />

                                <InputError class="mt-2" :message="form.errors.postal_code" />
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <danger-button :disabled="form.processing" @click="closeAddressModal">Cancel</danger-button>
                            <primary-button :disabled="form.processing">Save</primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </modal>
    </section>
</template>

<style>
.multiselect__tags {
    @apply border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300;
}

.multiselect__input {
    @apply border-gray-300 text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:!text-gray-100 dark:focus:border-indigo-600 dark:focus:ring-indigo-600;
}

.multiselect__single {
    @apply dark:bg-transparent;
}

.multiselect__content {
    @apply dark:bg-gray-900 dark:text-gray-300;
}

.multiselect__option--selected {
    @apply dark:bg-gray-700 dark:text-gray-300;
}

.multiselect__content-wrapper {
    @apply relative border-gray-300 scrollbar-thin scrollbar-track-purple-300 scrollbar-thumb-purple-600 dark:border-gray-700;
}
</style>
