<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

// props dari backend
const props = defineProps({
    totalUsers: Number,
    totalBooks: Number
})

// total user
// const totalUsers = props.users.length
</script>

<template>
    <Head title="Dashboard Admin" />

    <AuthenticatedLayout>
        <!-- HEADER -->
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard Admin
            </h2>
        </template>

        <!-- CONTENT -->
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">

            <!-- CARD GRID -->
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">

            <!-- TOTAL USER -->
            <div class="bg-white p-4 rounded-xl shadow">
                <h3 class="text-gray-500 text-sm">Total User</h3>
                <p class="text-3xl font-bold text-blue-600">
                    {{ props.totalUsers }}
                </p>
            </div>

            <!-- TOTAL BOOK -->
            <div class="bg-white p-4 rounded-xl shadow">
                <h3 class="text-gray-500 text-sm">Total Buku</h3>
                <p class="text-3xl font-bold text-green-600">
                    {{ props.totalBooks }}
                </p>
            </div>

                <!-- CARD 3 -->
                <div class="relative aspect-video overflow-hidden rounded-xl border bg-white flex items-center justify-center">
                    <span class="text-gray-400">Card 3</span>
                </div>

                <!-- <pre>{{ $page.props }}</pre> -->

            </div>

            <!-- BOX UTAMA -->
            <div class="relative min-h-[300px] flex-1 rounded-xl border bg-white p-6">
                <div class="text-gray-900">
                    You're logged in!
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>