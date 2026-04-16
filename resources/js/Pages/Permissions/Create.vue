<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    actions: [] // ⬅️ ini penting
})

const submit = () => {
    form.post('/permissions')
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="p-6 max-w-lg">

            <h1 class="text-xl font-bold mb-4">Tambah Permission</h1>

            <form @submit.prevent="submit" class="space-y-4">

                <!-- NAMA MODULE -->
                <div>
                    <label class="block mb-1 font-medium">Nama Module</label>
                    <input 
                        v-model="form.name" 
                        type="text"
                        placeholder="Contoh: user"
                        class="border p-2 rounded w-full"
                    />
                </div>

                <!-- PILIH AKSI -->
                <div>
                    <label class="block mb-2 font-medium">Pilih Aksi</label>

                    <div class="grid grid-cols-2 gap-2">
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

                <!-- ERROR -->
                <div v-if="form.errors.name" class="text-red-500 text-sm">
                    {{ form.errors.name }}
                </div>

                <!-- SUBMIT -->
                <button 
                    type="submit"
                    class="bg-green-500 text-white px-4 py-2 rounded"
                    :disabled="form.processing"
                >
                    Simpan
                </button>

            </form>
        </div>
    </AuthenticatedLayout>
</template>