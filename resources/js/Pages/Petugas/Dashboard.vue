<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

// props dari backend
const props = defineProps({
    users: Array
})


</script>

<template>
    <Head title="Dashboard Petugas" />

    <AuthenticatedLayout>
        <!-- HEADER -->
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard Petugas
            </h2>
        </template>

        <!-- CONTENT -->
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

                <!-- CARD -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    
                    <!-- CARD TOTAL USER -->
                    <!-- <div class="bg-white p-6 rounded shadow">
                        <h3 class="text-gray-500 text-sm">Total User</h3>
                        <p class="text-3xl font-bold text-blue-600">
                            {{ totalUsers }}
                        </p>
                    </div> -->

                </div>

                <!-- BOX UTAMA -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        You're logged in!
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>