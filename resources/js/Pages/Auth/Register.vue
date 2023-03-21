<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    password: '',
    password_confirmation: '',
    birth_date: '2001-02-22',
    gender: 'male',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="first_name" value="First Name" />

                <TextInput
                    id="first_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.first_name"
                    required
                    autofocus
                    autocomplete="first_name"
                />

                <InputError class="mt-2" :message="form.errors.first_name" />
            </div>

            <div class="mt-4">
                <InputLabel for="last_name" value="Last Name" />

                <TextInput
                    id="last_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.last_name"
                    required
                    autocomplete="last_name"
                />

                <InputError class="mt-2" :message="form.errors.last_name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="mt-3 flex flex-col justify-between space-y-2 sm:flex-row sm:space-y-0">
                <a
                    :href="route('social', 'github')"
                    class="inline-flex items-center justify-center rounded-lg bg-[#24292F] px-5 py-2.5 text-center text-sm font-medium text-white focus:outline-none focus:ring-4 focus:ring-[#24292F]/50 hover:bg-[#24292F]/90 dark:focus:ring-gray-500 dark:hover:bg-[#050708]/30"
                >
                    <font-awesome-icon icon="fa-brands fa-github" size="lg" class="mr-2 -ml-1" />
                    Sign in with Github
                </a>
                <a
                    :href="route('social', 'google')"
                    class="dark:focus:ring-[#4285F4]/55 inline-flex items-center justify-center rounded-lg bg-[#4285F4] px-5 py-2.5 text-center text-sm font-medium text-white focus:outline-none focus:ring-4 focus:ring-[#4285F4]/50 hover:bg-[#4285F4]/90"
                >
                    <font-awesome-icon icon="fa-brands fa-google" size="lg" class="mr-2 -ml-1" />
                    Sign in with Google
                </a>
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 hover:text-gray-900 dark:text-gray-400 dark:focus:ring-offset-gray-800 dark:hover:text-gray-100"
                >
                    Already registered?
                </Link>

                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
