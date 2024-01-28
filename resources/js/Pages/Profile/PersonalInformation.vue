<script setup>
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue'
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue'
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue'
import Sidebar from '@/Pages/Profile/Partials/Sidebar.vue'
import UserAddresses from '@/Pages/Profile/Partials/UserAddressesForm.vue'

defineProps({
    mustVerifyEmail: Boolean,
    status: String,
    addresses: Array,
    countries: Array
})
</script>

<template>
    <Head title="Personal Information" />

    <AuthenticatedLayout>
        <div class="flex flex-col justify-between space-x-0 px-4 py-12 sm:px-6 md:flex-row md:space-x-10 lg:px-8">
            <Sidebar />
            <div class="w-full space-y-4">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200 md:leading-[64px]">Personal Information</h2>
                <div class="bg-white p-4 shadow dark:bg-gray-800 sm:rounded-lg sm:p-8">
                    <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" class="max-w-xl" />
                </div>

                <div class="bg-white p-4 shadow dark:bg-gray-800 sm:rounded-lg sm:p-8">
                    <UserAddresses class="max-w-xl lg:max-w-full" :addresses="addresses" :countries="countries" />
                </div>

                <div class="bg-white p-4 shadow dark:bg-gray-800 sm:rounded-lg sm:p-8">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div class="bg-white p-4 shadow dark:bg-gray-800 sm:rounded-lg sm:p-8">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
