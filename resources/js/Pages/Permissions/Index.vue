<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { router } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    permissions: Array
})

// delete
const deletePermission = (id) => {
    if (confirm('Yakin hapus permission ini?')) {
        router.delete(`/permissions/${id}`)
    }
}

// 🔥 grouping permission
const groupedPermissions = computed(() => {
    const groups = {}

    props.permissions.forEach(p => {
        const parts = p.name.split(' ')
        const group = parts[1] || 'lainnya' // contoh: users, books

        if (!groups[group]) {
            groups[group] = []
        }

        groups[group].push(p)
    })

    return groups
})
</script>

<template>
    <AuthenticatedLayout>
        <div class="p-6">

            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">⚙️ List Permission</h1>

                <a 
                    href="/permissions/create" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg"
                >
                    + Tambah Permission
                </a>
            </div>

            <!-- GROUP -->
            <div v-for="(items, group) in groupedPermissions" :key="group" class="mb-6">

                <!-- HEADING -->
                <h2 class="text-lg font-semibold mb-2 capitalize text-gray-700">
                    {{ group }}
                </h2>

                <!-- CARD -->
                <div class="bg-white rounded-xl shadow border overflow-hidden">

                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 text-gray-600">
                            <tr>
                                <th class="p-3 text-left">Permission</th>
                                <th class="p-3 text-center w-32">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr 
                                v-for="p in items" 
                                :key="p.id"
                                class="border-t hover:bg-gray-50"
                            >
                                <td class="p-3">
                                    {{ p.name }}
                                </td>

                                <td class="p-3 text-center">
                                    <button 
                                        @click="deletePermission(p.id)"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs"
                                    >
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>

            </div>

        </div>
    </AuthenticatedLayout>
</template>