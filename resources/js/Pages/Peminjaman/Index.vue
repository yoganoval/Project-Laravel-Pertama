<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { router, usePage } from '@inertiajs/vue3'

const props = defineProps({
    peminjaman: Array
})

const page = usePage()

const formatRupiah = (angka) => {
    return new Intl.NumberFormat('id-ID').format(angka || 0)
}

const formatDate = (date) => {
    if (!date) return '-'
    return new Date(date).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    })
}

const formatDateInput = (date) => {
    if (!date) return ''
    return new Date(date).toISOString().split('T')[0]
}

const bayarDenda = (id) => {
    if (confirm('Bayar denda sekarang?')) {
        router.post(`/peminjaman/${id}/bayar-denda`, {}, {
            preserveScroll: true,
            onSuccess: () => router.reload()
        })
    }
}

const kembalikan = (id) => {
    if (confirm('Kembalikan buku ini?')) {
        router.put(`/peminjaman/${id}/kembali`)
    }
}

const hapus = (id) => {
    if (confirm('Hapus data ini?')) {
        router.delete(`/peminjaman/${id}`)
    }
}

const updateJatuhTempo = (e, item) => {
    router.put(`/peminjaman/${item.id}`, {
        jatuh_tempo: e.target.value
    })
}
</script>

<template>
<AuthenticatedLayout>

<div class="p-6">

    <!-- 🔔 NOTIF -->
    <div v-if="$page.props.flash.success"
        class="mb-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 p-3 rounded">
        {{ $page.props.flash.success }}
    </div>

    <div v-if="$page.props.flash.error"
        class="mb-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 p-3 rounded">
        {{ $page.props.flash.error }}
    </div>

    <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100">
        📖 Data Peminjaman
    </h1>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">

        <table class="w-full text-sm text-gray-700 dark:text-gray-200">
            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                <tr>
                    <th class="p-3">No</th>
                    <th class="p-3 text-left">User</th>
                    <th class="p-3 text-left">Buku</th>
                    <th class="p-3">Tanggal Pinjam</th>
                    <th class="p-3">Jatuh Tempo</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Denda</th>
                    <th class="p-3">Status Denda</th>
                    <th class="p-3">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <tr
                    v-for="(item, i) in peminjaman"
                    :key="item.id"
                    :class="[
                        item.is_terlambat ? 'bg-red-50 dark:bg-red-900/30' : '',
                        'border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition'
                    ]"
                >
                    <td class="p-3 text-center">{{ i + 1 }}</td>

                    <td class="p-3">{{ item.user?.name }}</td>
                    <td class="p-3">{{ item.book?.judul }}</td>

                    <td class="p-3 text-center">
                        {{ formatDate(item.tanggal_pinjam) }}
                    </td>

                    <!-- INPUT DATE -->
                    <td class="p-3 text-center">
                        <input
                            type="date"
                            :value="formatDateInput(item.jatuh_tempo)"
                            @change="updateJatuhTempo($event, item)"
                            :disabled="item.status === 'dikembalikan'"
                            class="border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded px-2 py-1 text-sm"
                        />
                    </td>

                    <!-- STATUS -->
                    <td class="p-3 text-center">
                        <span v-if="item.status === 'dipinjam' && item.is_terlambat"
                              class="bg-red-500 text-white px-2 py-1 rounded">
                            Terlambat
                        </span>

                        <span v-else-if="item.status === 'dipinjam'"
                              class="bg-yellow-500 text-white px-2 py-1 rounded">
                            Dipinjam
                        </span>

                        <span v-else
                              class="bg-green-500 text-white px-2 py-1 rounded">
                            Dikembalikan
                        </span>
                    </td>

                    <!-- DENDA -->
                    <td class="p-3 text-center">
                        <span v-if="item.denda > 0" class="text-red-600 dark:text-red-400 font-semibold">
                            Rp {{ formatRupiah(item.denda) }}
                        </span>
                        <span v-else>-</span>
                    </td>

                    <!-- STATUS DENDA -->
                    <td class="p-3 text-center">
                        <span v-if="item.status_denda === 'belum_bayar'"
                            class="bg-red-500 text-white px-2 py-1 rounded">
                            Belum Bayar
                        </span>

                        <span v-else-if="item.denda > 0"
                            class="bg-green-500 text-white px-2 py-1 rounded">
                            Lunas
                        </span>

                        <span v-else>-</span>
                    </td>

                    <!-- AKSI -->
                    <td class="p-3 text-center space-x-1">

                        <button
                            v-if="item.is_terlambat && item.status_denda !== 'lunas'"
                            @click="bayarDenda(item.id)"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs"
                        >
                            Bayar (Rp {{ formatRupiah(item.denda) }})
                        </button>

                        <button
                            v-else-if="item.status === 'dipinjam'"
                            @click="kembalikan(item.id)"
                            class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs"
                        >
                            Kembalikan
                        </button>

                        <button
                            @click="hapus(item.id)"
                            class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs"
                        >
                            Hapus
                        </button>

                    </td>

                </tr>
            </tbody>
        </table>

    </div>

</div>

</AuthenticatedLayout>
</template>