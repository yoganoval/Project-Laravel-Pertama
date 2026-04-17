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

// grouping
const groupedPermissions = computed(() => {
    const groups = {}

    props.permissions.forEach(p => {
        const parts = p.name.split(' ')
        const group = parts[1] || 'lainnya'

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

            <!-- HEADER -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                    ⚙️ List Permission
                </h1>

                <a 
                    href="/permissions/create" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg"
                >
                    + Tambah Permission
                </a>
            </div>

            <!-- GROUP -->
            <div 
                v-for="(items, group) in groupedPermissions" 
                :key="group" 
                class="mb-6"
            >

                <!-- HEADING -->
                <h2 class="text-lg font-semibold mb-2 capitalize text-gray-700 dark:text-gray-300">
                    {{ group }}
                </h2>

                <!-- CARD -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">

                    <table class="w-full text-sm text-gray-700 dark:text-gray-200">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                            <tr>
                                <th class="p-3 text-left">Permission</th>
                                <th class="p-3 text-center w-32">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr 
                                v-for="p in items" 
                                :key="p.id"
                                class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
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