<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    roles: Array
})

const deleteRole = (id) => {
    if (confirm('Yakin hapus role ini?')) {
        router.delete(`/roles/${id}`)
    }
}
</script>

<template>
<AuthenticatedLayout>
    <div class="p-6">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                Manajemen Role
            </h1>

            <a href="/roles/create"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
                + Tambah Role
            </a>
        </div>

        <!-- TABLE -->
        <div class="bg-white dark:bg-gray-800 shadow rounded border border-gray-200 dark:border-gray-700 overflow-hidden">
            <table class="w-full text-sm text-gray-700 dark:text-gray-200">

                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                    <tr>
                        <th class="p-3 text-left">No</th>
                        <th class="p-3 text-left">Role</th>
                        <th class="p-3 text-left">Permissions</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(role, index) in roles" :key="role.id"
                        class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">

                        <td class="p-3">{{ index + 1 }}</td>

                        <td class="p-3 font-semibold capitalize">
                            {{ role.name }}
                        </td>

                        <td class="p-3">
                            <div class="flex flex-wrap gap-1">
                                <span v-for="p in role.permissions"
                                      :key="p.id"
                                      class="text-xs px-2 py-1 rounded"
                                      :class="{
                                          'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300': p.name.includes('view'),
                                          'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300': p.name.includes('create'),
                                          'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300': p.name.includes('edit'),
                                          'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300': p.name.includes('delete'),
                                      }">
                                    {{ p.name }}
                                </span>
                            </div>
                        </td>

                        <td class="p-3 text-center space-x-2">
                            <a :href="`/roles/${role.id}/edit`"
                               class="text-blue-600 dark:text-blue-400 hover:underline">
                                Edit
                            </a>

                            <button @click="deleteRole(role.id)"
                                    class="text-red-600 dark:text-red-400 hover:underline">
                                Hapus
                            </button>
                        </td>
                    </tr>

                    <tr v-if="roles.length === 0">
                        <td colspan="4" class="text-center p-4 text-gray-500 dark:text-gray-400">
                            Belum ada data role
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>
</AuthenticatedLayout>
</template>