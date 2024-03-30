<script setup lang="ts">
import { reactive } from 'vue'
import { Link, useForm, usePage } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import { PageProps } from '@/types'
// import { VueTelInput } from 'vue-tel-input'
// import 'vue-tel-input/vue-tel-input.css'

defineProps<{
    mustVerifyEmail?: Boolean
    status?: String
}>()

const { user } = reactive<PageProps>(usePage().props)

const form = useForm({
    first_name: user.first_name,
    last_name: user.last_name,
    email: user.email,
    phone: user.phone,
    birth_date: user.birth_date,
    gender: user.gender
})
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Profile Information</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update your account's profile information and email address.</p>
        </header>

        <form @submit.prevent="form.patch(route('profile.personal-information.update'))" class="mt-6 space-y-6">
            <div>
                <input-label for="first_name" value="First Name" />

                <text-input
                    id="first_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.first_name"
                    required
                    autofocus
                    autocomplete="first_name"
                />

                <input-error class="mt-2" :message="form.errors.first_name" />
            </div>

            <div>
                <input-label for="last_name" value="Last Name" />

                <text-input
                    id="last_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.last_name"
                    required
                    autocomplete="last_name"
                />

                <input-error class="mt-2" :message="form.errors.last_name" />
            </div>

            <div>
                <input-label for="email" value="Email" />

                <text-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autocomplete="email" />

                <input-error class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 text-sm text-gray-800 dark:text-gray-200">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div v-show="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600 dark:text-green-400">
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div>
                <input-label for="phone" value="Phone Number" />

                <!--                <vue-tel-input-->
                <!--                    v-model="form.phone"-->
                <!--                    mode="auto"-->
                <!--                    style-classes="mt-1 !border-none ring-1 ring-gray-300 dark:ring-gray-700 !rounded-lg dark:bg-gray-900 dark:text-gray-300"-->
                <!--                    :dropdown-options="{-->
                <!--                        disabled: false,-->
                <!--                        showDialCodeInList: true,-->
                <!--                        showDialCodeInSelection: false,-->
                <!--                        showFlags: true,-->
                <!--                        showSearchBox: false,-->
                <!--                        tabindex: 0-->
                <!--                    }"-->
                <!--                    :input-options="{-->
                <!--                        placeholder: 'Phone number',-->
                <!--                        styleClasses: '!rounded-lg h-[42px] dark:bg-gray-900 dark:text-gray-300',-->
                <!--                        showDialCode: true-->
                <!--                    }"-->
                <!--                    auto-format-->
                <!--                    auto-default-country-->
                <!--                    valid-characters-only-->
                <!--                />-->

                <input-error class="mt-2" :message="form.errors.phone" />
            </div>

            <div class="flex items-center gap-4">
                <primary-button :disabled="form.processing">Save</primary-button>

                <transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
                </transition>
            </div>
        </form>
    </section>
</template>

<style>
.vue-tel-input:focus-within {
    @apply border-none shadow-none ring-2 ring-indigo-500;
}

.vti__dropdown:hover,
.vti__dropdown.open {
    @apply rounded-lg bg-gray-700;
}

.vti__dropdown-list {
    @apply border border-gray-600 scrollbar-thin scrollbar-track-purple-300 scrollbar-thumb-purple-600 dark:bg-gray-900 dark:text-gray-200;
}

.vti__dropdown-item.highlighted {
    @apply dark:bg-gray-800;
}
</style>
