<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

const props = defineProps({
    peminjaman: Array
})

// format tanggal
const formatDate = (date) => {
    if (!date) return '-'
    return new Date(date).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    })
}

// cek terlambat
const isLate = (item) => {
    return item.status === 'dipinjam' && new Date() > new Date(item.jatuh_tempo)
}
</script>

<template>
    <Head title="Dashboard Anggota" />

    <AuthenticatedLayout>
        <!-- HEADER -->
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                Dashboard Anggota
            </h2>
        </template>

        <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

                <!-- SAPA USER -->
                <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow p-6 transition-colors duration-300">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                        Halo {{ $page.props.auth.user.name }} 👋
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Selamat datang kembali di dashboard anggota
                    </p>
                </div>

                <!-- CARD -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow transition-colors duration-300">
                        <h3 class="text-sm text-gray-500 dark:text-gray-400">
                            Sedang Dipinjam
                        </h3>
                        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-2">
                            {{ peminjaman.length }}
                        </p>
                    </div>
                </div>

                <!-- LIST PEMINJAMAN -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow transition-colors duration-300">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">
                        📖 Buku yang Kamu Pinjam
                    </h3>

                    <!-- KOSONG -->
                    <div
                        v-if="peminjaman.length === 0"
                        class="text-gray-500 dark:text-gray-400"
                    >
                        Kamu belum meminjam buku
                    </div>

                    <!-- ADA DATA -->
                    <div v-else class="space-y-4">
                        <div
                            v-for="item in peminjaman"
                            :key="item.id"
                            class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 bg-gray-50 dark:bg-gray-900 transition-colors duration-300"
                        >
                            <!-- JUDUL -->
                            <div class="font-bold text-lg text-gray-800 dark:text-white">
                                {{ item.book?.judul }}
                            </div>

                            <!-- TANGGAL -->
                            <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                📅 Pinjam: {{ formatDate(item.tanggal_pinjam) }}
                            </div>

                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                ⏳ Jatuh tempo: {{ formatDate(item.jatuh_tempo) }}
                            </div>

                            <!-- STATUS -->
                            <div class="mt-3">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold"
                                    :class="{
                                        'bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300': item.status === 'dipinjam',
                                        'bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300': item.status === 'dikembalikan'
                                    }"
                                >
                                    {{ item.status }}
                                </span>
                            </div>

                            <!-- TERLAMBAT -->
                            <div
                                v-if="isLate(item)"
                                class="mt-3 text-sm font-medium text-red-600 dark:text-red-400"
                            >
                                ⚠️ Kamu terlambat mengembalikan buku
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>