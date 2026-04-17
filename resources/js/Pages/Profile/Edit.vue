<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import DeleteUserForm from './Partials/DeleteUserForm.vue'
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue'
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue'
import { Head } from '@inertiajs/vue3'

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
})
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
                Profile
            </h2>
        </template>

        <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">

                <!-- UPDATE PROFILE -->
                <div class="bg-white dark:bg-gray-800 p-4 shadow rounded-xl sm:p-8 transition-colors duration-300">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <!-- UPDATE PASSWORD -->
                <div class="bg-white dark:bg-gray-800 p-4 shadow rounded-xl sm:p-8 transition-colors duration-300">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <!-- DELETE ACCOUNT -->
                <div class="bg-white dark:bg-gray-800 p-4 shadow rounded-xl sm:p-8 transition-colors duration-300">
                    <DeleteUserForm class="max-w-xl" />
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>