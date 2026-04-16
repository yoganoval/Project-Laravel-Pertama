<script setup>
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
<div class="p-6">

    <h1 class="text-xl font-bold mb-4">Tambah Role</h1>

    <!-- NAME -->
    <input v-model="form.name"
           placeholder="Nama Role"
           class="border p-2 w-full mb-4" />

    <!-- PERMISSION -->
    <div v-for="(group, key) in permissions" :key="key" class="mb-4 border p-3 rounded">

        <div class="flex justify-between mb-2">
            <b class="uppercase">{{ key }}</b>

            <button @click="toggleAll(key)" class="text-blue-500 text-sm">
                Toggle Semua
            </button>
        </div>

        <div class="grid grid-cols-2 gap-2">
            <label v-for="perm in group" :key="perm.id" class="flex gap-2">
                <input type="checkbox"
                       :value="perm.name"
                       v-model="form.permissions" />
                {{ perm.name }}
            </label>
        </div>

    </div>

    <button @click="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded">
        Simpan
    </button>

</div>
</template>