<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'
import Chart from 'chart.js/auto'

const props = defineProps({
    totalUsers: Number,
    totalBooks: Number,
    totalPinjam: Number,
    totalDenda: Number,
    pinjamBulanIni: Number,
    chartPinjam: Array,
    peminjaman: Array
})

const chartRef = ref(null)

onMounted(() => {
    new Chart(chartRef.value, {
        type: 'line',
        data: {
            labels: props.chartPinjam.map(i => i.tanggal),
            datasets: [{
                label: 'Peminjaman',
                data: props.chartPinjam.map(i => i.total),
                borderWidth: 2,
            }]
        },
        options: {
            plugins: {
                legend: {
                    labels: {
                        color: document.documentElement.classList.contains('dark') ? '#fff' : '#000'
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: document.documentElement.classList.contains('dark') ? '#ccc' : '#333'
                    }
                },
                y: {
                    ticks: {
                        color: document.documentElement.classList.contains('dark') ? '#ccc' : '#333'
                    }
                }
            }
        }
    })
})
</script>

<template>
    <Head title="Dashboard Admin" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                Dashboard Admin
            </h2>
        </template>

        <div class="p-4 space-y-6">

            <!-- 🔹 CARD -->
            <div class="grid md:grid-cols-4 gap-4">

                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total User</p>
                    <p class="text-2xl font-bold text-blue-600">{{ totalUsers }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Buku</p>
                    <p class="text-2xl font-bold text-green-600">{{ totalBooks }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Peminjaman</p>
                    <p class="text-2xl font-bold text-purple-600">{{ totalPinjam }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Denda</p>
                    <p class="text-2xl font-bold text-red-600">Rp {{ totalDenda }}</p>
                </div>

            </div>

            <!-- 🔹 INFO -->
            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
                <p class="text-gray-500 dark:text-gray-400">Peminjaman Bulan Ini</p>
                <p class="text-xl font-bold text-gray-800 dark:text-gray-100">
                    {{ pinjamBulanIni }}
                </p>
            </div>

            <!-- 🔹 CHART -->
            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
                <h3 class="mb-4 font-semibold text-gray-800 dark:text-gray-100">
                    Grafik Peminjaman (7 Hari)
                </h3>
                <canvas ref="chartRef"></canvas>
            </div>

            <!-- 🔹 TABLE -->
            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
                <h3 class="mb-4 font-semibold text-gray-800 dark:text-gray-100">
                    Peminjaman Terbaru
                </h3>

                <table class="w-full text-sm text-gray-700 dark:text-gray-200">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700 text-left">
                            <th>User</th>
                            <th>Buku</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="p in peminjaman" :key="p.id" class="border-b border-gray-100 dark:border-gray-700">
                            <td>{{ p.user.name }}</td>
                            <td>{{ p.book?.judul }}</td>
                            <td>
                                <span
                                    class="px-2 py-1 rounded text-white text-xs"
                                    :class="p.status === 'dipinjam' ? 'bg-yellow-500' : 'bg-green-500'"
                                >
                                    {{ p.status }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </AuthenticatedLayout>
</template>