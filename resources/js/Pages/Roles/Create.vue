<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    permissions: Object
})

const form = useForm({
    name: '',
    permissions: []
})

const submit = () => {
    form.post('/roles')
}

const toggleAll = (group) => {
    const perms = props.permissions[group].map(p => p.name)

    const allSelected = perms.every(p => form.permissions.includes(p))

    if (allSelected) {
        form.permissions = form.permissions.filter(p => !perms.includes(p))
    } else {
        form.permissions = [...new Set([...form.permissions, ...perms])]
    }
}
</script>

<template>
<AuthenticatedLayout>

<div class="p-6 max-w-5xl">

    <!-- TITLE -->
    <h1 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">
        Tambah Role
    </h1>

    <!-- CARD -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow border border-gray-200 dark:border-gray-700">

        <!-- NAME -->
        <div class="mb-4">
            <label class="block mb-1 font-medium text-gray-700 dark:text-gray-300">
                Nama Role
            </label>

            <input 
                v-model="form.name"
                placeholder="Nama Role"
                class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white p-2 rounded"
            />

            <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">
                {{ form.errors.name }}
            </p>
        </div>

        <!-- PERMISSION -->
        <div 
            v-for="(group, key) in permissions" 
            :key="key" 
            class="mb-4 border border-gray-200 dark:border-gray-700 p-4 rounded-lg bg-gray-50 dark:bg-gray-900"
        >

            <!-- HEADER GROUP -->
            <div class="flex justify-between mb-2 items-center">
                <b class="uppercase text-gray-700 dark:text-gray-300">
                    {{ key }}
                </b>

                <button 
                    type="button"
                    @click="toggleAll(key)" 
                    class="text-blue-600 dark:text-blue-400 text-sm hover:underline"
                >
                    Toggle Semua
                </button>
            </div>

            <!-- LIST PERMISSION -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-2 text-gray-700 dark:text-gray-200">
                <label 
                    v-for="perm in group" 
                    :key="perm.id" 
                    class="flex gap-2 items-center"
                >
                    <input 
                        type="checkbox"
                        :value="perm.name"
                        v-model="form.permissions"
                    />
                    {{ perm.name }}
                </label>
            </div>

        </div>

        <!-- BUTTON -->
        <div class="mt-4">
            <button 
                @click="submit"
                :disabled="form.processing"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded"
            >
                {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
            </button>
        </div>

    </div>

</div>

</AuthenticatedLayout>
</template>