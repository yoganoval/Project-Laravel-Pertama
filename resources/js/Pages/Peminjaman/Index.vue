<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { router, usePage } from '@inertiajs/vue3'

// props
const props = defineProps({
    peminjaman: Array
})

const page = usePage()

// 🔥 BAYAR DENDA
const bayarDenda = (id) => {
    if (confirm('Bayar denda sekarang?')) {
        router.post(`/peminjaman/${id}/bayar-denda`, {}, {
            preserveScroll: true,
            onSuccess: () => {
                router.reload() // 🔥 ini kunci biar update kelihatan
            }
        })
    }
}

// FORMAT TANGGAL
const formatDate = (date) => {
    if (!date) return '-'
    return new Date(date).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    })
}

// FORMAT INPUT DATE
const formatDateInput = (date) => {
    if (!date) return ''
    return new Date(date).toISOString().split('T')[0]
}

// KEMBALIKAN
const kembalikan = (id) => {
    if (confirm('Kembalikan buku ini?')) {
        router.put(`/peminjaman/${id}/kembali`)
    }
}

// HAPUS
const hapus = (id) => {
    if (confirm('Hapus data ini?')) {
        router.delete(`/peminjaman/${id}`)
    }
}

// UPDATE JATUH TEMPO
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
    <div v-if="$page.props.flash.success" class="mb-4 bg-green-100 text-green-700 p-3 rounded">
        {{ $page.props.flash.success }}
    </div>

    <div v-if="$page.props.flash.error" class="mb-4 bg-red-100 text-red-700 p-3 rounded">
        {{ $page.props.flash.error }}
    </div>

    <h1 class="text-2xl font-bold mb-6">📖 Data Peminjaman</h1>

    <div class="bg-white rounded-xl shadow border overflow-hidden">

        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    <th class="p-3">No</th>
                    <th class="p-3 text-left">User</th>
                    <th class="p-3 text-left">Buku</th>
                    <th class="p-3">Tanggal Pinjam</th>
                    <th class="p-3">Jatuh Tempo</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Denda</th>
                    <th class="p-3">Status Denda</th> <!-- 🔥 -->
                    <th class="p-3">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <tr
                    v-for="(item, i) in peminjaman"
                    :key="item.id"
                    :class="item.is_terlambat ? 'bg-red-50' : ''"
                    class="border-t hover:bg-gray-50"
                >
                    <td class="p-3 text-center">{{ i + 1 }}</td>

                    <td class="p-3">
                        {{ item.user?.name }}
                    </td>

                    <td class="p-3">
                        {{ item.book?.judul }}
                    </td>

                    <!-- TANGGAL PINJAM -->
                    <td class="p-3 text-center">
                        {{ formatDate(item.tanggal_pinjam) }}
                    </td>

                    <!-- JATUH TEMPO -->
                    <td class="p-3 text-center">
                        <input
                            type="date"
                            :value="formatDateInput(item.jatuh_tempo)"
                            @change="updateJatuhTempo($event, item)"
                            :disabled="item.status === 'dikembalikan'"
                            class="border rounded px-2 py-1 text-sm"
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

                    <!-- 💰 DENDA -->
                    <td class="p-3 text-center">

                        <!-- kalau belum jatuh tempo -->
                        <span v-if="new Date() < new Date(item.jatuh_tempo)">
                            -
                        </span>

                        <!-- kalau sudah jatuh tempo -->
                        <template v-else>
                            <!-- sudah dikembalikan -->
                            <span v-if="item.status === 'dikembalikan' && item.denda > 0">
                                Rp {{ new Intl.NumberFormat('id-ID').format(item.denda) }}
                            </span>

                            <!-- masih dipinjam & terlambat -->
                            <span v-else-if="item.status === 'dipinjam' && item.is_terlambat">
                                Rp {{
                                    new Intl.NumberFormat('id-ID').format(
                                        Math.max(
                                            0,
                                            Math.floor(
                                                (new Date() - new Date(item.jatuh_tempo)) / (1000 * 60 * 60 * 24)
                                            )
                                        ) * 1000
                                    )
                                }}
                            </span>

                            <span v-else>-</span>
                        </template>

                    </td>

                    <!-- 💳 STATUS DENDA -->
                    <td class="p-3 text-center">

                        <!-- kalau belum jatuh tempo -->
                        <span v-if="new Date() < new Date(item.jatuh_tempo)">
                            -
                        </span>

                        <!-- kalau sudah jatuh tempo -->
                        <template v-else>
                            <span v-if="item.status_denda === 'belum_bayar'"
                                class="bg-red-500 text-white px-2 py-1 rounded">
                                Belum Bayar
                            </span>

                            <span v-else-if="item.denda > 0"
                                class="bg-green-500 text-white px-2 py-1 rounded">
                                Lunas
                            </span>

                            <span v-else>-</span>
                        </template>

                    </td>

                    <!-- ⚙️ AKSI -->
                    <td class="p-3 text-center space-x-1">

                        <!-- KEMBALIKAN -->
                        <button
                            v-if="item.status === 'dipinjam'"
                            @click="kembalikan(item.id)"
                            class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs"
                        >
                            Kembalikan
                        </button>

                        <!-- BAYAR DENDA -->
                        <button
                            v-if="item.status_denda === 'belum_bayar' && new Date() >= new Date(item.jatuh_tempo)"
                            @click="bayarDenda(item.id)"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs"
                        >
                            Bayar
                        </button>

                        <!-- HAPUS -->
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