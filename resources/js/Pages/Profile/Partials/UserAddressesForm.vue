<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import { initTooltips } from 'flowbite'
import axios from 'axios'
import DangerButton from '@/Components/DangerButton.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import Modal from '@/Components/Modal.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import { Address, City, Country, State } from '@/types'
import Autocomplete from '@/Components/Autocomplete.vue'

const props = defineProps<{
    addresses: Address[]
    countries: Country[]
}>()

onMounted(() => initTooltips())

const form = useForm<{
    id?: number
    country_id: number | null
    state_id: number | null
    city_id: number | null
    street: string
    house: string
    flat: string | null
    postal_code: string | null
}>({
    country_id: null,
    state_id: null,
    city_id: null,
    street: '',
    house: '',
    flat: '',
    postal_code: ''
})

const defaultAddress = ref(props.addresses?.find((address: Address) => address.is_main)?.id || null)
const addressModal = ref(false)
const addressUpdateModal = ref(false)
const locations = useForm<{
    countries: Country[]
    states: State[]
    cities: City[]
    loading: 'states' | 'cities' | 'none'
}>({
    countries: props.countries,
    states: [],
    cities: [],
    loading: 'none'
})

const onCountrySelected = async (country: Country) => {
    if (country.id !== form.country_id) {
        form.country_id = country.id
        form.reset('state_id', 'city_id')
        locations.loading = 'states'
        locations.states.splice(0, locations.states.length)
        axios
            .get(route('api.locations.states', country.iso2.toLowerCase()))
            .then(({ data }) => locations.states.push(...data))
            .finally(() => (locations.loading = 'none'))
    }
}

const onStateSelected = async (state: State) => {
    if (state.id !== form.state_id) {
        form.state_id = state.id
        form.reset('city_id')
        locations.loading = 'cities'
        locations.cities.splice(0, locations.cities.length)
        axios
            .get(route('api.locations.cities', state.id))
            .then(({ data }) => locations.cities.push(...data))
            .finally(() => (locations.loading = 'none'))
    }
}

const onCitySelected = (selectedCity: City) => (form.city_id = selectedCity.id)

const submit = () =>
    !addressUpdateModal.value
        ? form.post(route('profile.address.store'), {
              onSuccess: () => closeAddressModal(),
              preserveScroll: true
          })
        : form.put(route('profile.address.update', form.id), {
              onSuccess: () => closeAddressModal(),
              preserveScroll: true
          })

const closeAddressModal = () => {
    form.reset()
    form.clearErrors()
    addressModal.value = false
}

const onEditAddress = (address: Address) => {
    console.log(address)
    form.country_id = address.country.id
    onCountrySelected(address.country).then(() => {
        form.state_id = address.state.id
        onStateSelected(address.state).then(() => {
            form.city_id = address.city.id
            onCitySelected(address.city)
        })
    })
    form.id = address.id
    form.street = address.street
    form.house = address.house
    form.flat = address.flat
    form.postal_code = address.postal_code
    addressModal.value = true
    addressUpdateModal.value = true
}

const onChangeDefaultAddress = (event: any) => {
    defaultAddress.value = Number.parseInt(event.target.value) || null
    router.patch(route('profile.address.patch', { id: defaultAddress.value }))
}
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
            <label for="addresses" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Default delivery address </label>
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
                    v-html="`${address.country.name}, ${address.state.name}, ${address.city.name}, ${address.street}, ${address.house}`"
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
                    <form @submit.prevent="submit" class="mt-6 flex flex-col">
                        <div class="flex w-full flex-col space-y-4 lg:flex-row lg:space-x-4 lg:space-y-0">
                            <div class="basis-1/3">
                                <autocomplete
                                    :options="locations.countries"
                                    :model-value="form.country_id"
                                    :error="form.errors.country_id"
                                    option-icon="flag"
                                    label="Country"
                                    @update:model-value="onCountrySelected"
                                />

                                <InputError class="mt-2" :message="form.errors.country_id" />
                            </div>
                            <div class="basis-1/3">
                                <autocomplete
                                    :options="locations.states"
                                    :model-value="form.state_id"
                                    :error="form.errors.state_id"
                                    :disabled="!form.country_id"
                                    :loading="locations.loading === 'states'"
                                    label="State"
                                    @update:model-value="onStateSelected"
                                />
                            </div>
                            <div class="basis-1/3">
                                <autocomplete
                                    :options="locations.cities"
                                    :model-value="form.city_id"
                                    :error="form.errors.city_id"
                                    :disabled="!form.state_id"
                                    :loading="locations.loading === 'cities'"
                                    label="City"
                                    @update:model-value="onCitySelected"
                                />
                            </div>
                        </div>
                        <div class="mt-6 flex w-full flex-col space-y-4 lg:flex-row lg:space-x-4 lg:space-y-0">
                            <div class="basis-2/5">
                                <input-label for="street" value="Street" />

                                <text-input
                                    id="street"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.street"
                                    required
                                    autocomplete="street"
                                />

                                <input-error class="mt-2" :message="form.errors.street" />
                            </div>
                            <div class="basis-1/5">
                                <input-label for="house" value="House" />

                                <text-input
                                    id="house"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.house"
                                    required
                                    autocomplete="house"
                                />

                                <input-error class="mt-2" :message="form.errors.house" />
                            </div>
                            <div class="basis-1/5">
                                <input-label for="flat" value="Flat" />

                                <text-input id="flat" type="text" class="mt-1 block w-full" v-model="form.flat" autocomplete="flat" />

                                <input-error class="mt-2" :message="form.errors.flat" />
                            </div>
                            <div class="basis-1/5">
                                <input-label for="postal_code" value="Postal Code" />

                                <text-input
                                    id="postal_code"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.postal_code"
                                    autocomplete="postal_code"
                                />

                                <input-error class="mt-2" :message="form.errors.postal_code" />
                            </div>
                        </div>
                        <div class="mt-20 flex items-center justify-between">
                            <danger-button :disabled="form.processing" @click="closeAddressModal">Cancel</danger-button>
                            <primary-button :disabled="form.processing">Save</primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </modal>
    </section>
</template>
