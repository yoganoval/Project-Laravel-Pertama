<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    actions: []
})

const submit = () => {
    form.post('/permissions')
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="p-6 max-w-lg">

            <!-- TITLE -->
            <h1 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">
                Tambah Permission
            </h1>

            <!-- CARD -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow border border-gray-200 dark:border-gray-700">

                <form @submit.prevent="submit" class="space-y-4">

                    <!-- NAMA MODULE -->
                    <div>
                        <label class="block mb-1 font-medium text-gray-700 dark:text-gray-300">
                            Nama Module
                        </label>

                        <input 
                            v-model="form.name" 
                            type="text"
                            placeholder="Contoh: user"
                            class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white p-2 rounded"
                        />

                        <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <!-- PILIH AKSI -->
                    <div>
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                            Pilih Aksi
                        </label>

                        <div class="grid grid-cols-2 gap-2 text-gray-700 dark:text-gray-200">

                            <label class="flex items-center gap-2">
                                <input type="checkbox" value="view" v-model="form.actions">
                                View
                            </label>

                            <label class="flex items-center gap-2">
                                <input type="checkbox" value="create" v-model="form.actions">
                                Create
                            </label>

                            <label class="flex items-center gap-2">
                                <input type="checkbox" value="edit" v-model="form.actions">
                                Edit
                            </label>

                            <label class="flex items-center gap-2">
                                <input type="checkbox" value="delete" v-model="form.actions">
                                Delete
                            </label>

                        </div>
                    </div>

                    <!-- SUBMIT -->
                    <div class="pt-2">
                        <button 
                            type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded"
                            :disabled="form.processing"
                        >
                            {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </AuthenticatedLayout>
</template>