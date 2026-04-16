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
            <h2 class="text-xl font-semibold text-gray-800">
                Dashboard Anggota
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

                <!-- 👋 SAPA USER -->
                <div class="mb-6 text-lg font-semibold">
                    Halo {{ $page.props.auth.user.name }} 👋
                </div>

                <!-- CARD RINGKAS -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

                    <!-- TOTAL PINJAMAN -->
                    <div class="bg-white p-6 rounded shadow">
                        <h3 class="text-gray-500 text-sm">Sedang Dipinjam</h3>
                        <p class="text-3xl font-bold text-blue-600">
                            {{ peminjaman.length }}
                        </p>
                    </div>

                </div>

                <!-- LIST PEMINJAMAN -->
                <div class="bg-white p-6 rounded shadow">

                    <h3 class="text-lg font-semibold mb-4">
                        📖 Buku yang Kamu Pinjam
                    </h3>

                    <!-- JIKA KOSONG -->
                    <div v-if="peminjaman.length === 0" class="text-gray-500">
                        Kamu belum meminjam buku
                    </div>

                    <!-- JIKA ADA -->
                    <div v-else class="space-y-3">

                        <div
                            v-for="item in peminjaman"
                            :key="item.id"
                            class="border p-4 rounded"
                        >
                            <!-- JUDUL -->
                            <div class="font-bold text-lg">
                                {{ item.book?.judul }}
                            </div>

                            <!-- TANGGAL -->
                            <div class="text-sm text-gray-600">
                                📅 Pinjam: {{ formatDate(item.tanggal_pinjam) }}
                            </div>

                            <div class="text-sm text-gray-600">
                                ⏳ Jatuh tempo: {{ formatDate(item.jatuh_tempo) }}
                            </div>

                            <!-- STATUS -->
                            <div class="mt-2">
                                <span
                                    class="px-2 py-1 rounded text-xs font-semibold"
                                    :class="{
                                        'bg-red-100 text-red-600': item.status === 'dipinjam',
                                        'bg-green-100 text-green-600': item.status === 'dikembalikan'
                                    }"
                                >
                                    {{ item.status }}
                                </span>
                            </div>

                            <!-- TERLAMBAT -->
                            <div
                                v-if="isLate(item)"
                                class="text-red-600 text-sm mt-1"
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