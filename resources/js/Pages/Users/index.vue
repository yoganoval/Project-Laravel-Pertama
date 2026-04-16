<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'

// props
const props = defineProps({
    users: Array,
    roles: Array
})

// state
const form = ref({
    id: null,
    name: '',
    username: '',
    password: '',
    role: '',
    photo: null,
    preview: null
})

const isEdit = ref(false)
const errors = ref({})

// handle file
const handleFile = (e) => {
    const file = e.target.files[0]
    form.value.photo = file

    if (file) {
        form.value.preview = URL.createObjectURL(file)
    }
}

// reset form
const resetForm = () => {
    form.value = {
        id: null,
        name: '',
        username: '',
        password: '',
        role: '',
        photo: null,
        preview: null
    }
    isEdit.value = false
    errors.value = {}
}

// submit (pakai FormData 🔥)
const submit = () => {
    if (!form.value.name || !form.value.username) {
        errors.value = {
            name: !form.value.name ? 'Nama wajib diisi' : '',
            username: !form.value.username ? 'username wajib diisi' : ''
        }
        return
    }

    const data = new FormData()
    data.append('name', form.value.name)
    data.append('username', form.value.username)
    data.append('password', form.value.password || '')
    data.append('role', form.value.role)

    if (form.value.photo) {
        data.append('photo', form.value.photo)
    }

    if (isEdit.value) {
        data.append('_method', 'PUT')

        router.post(`/users/${form.value.id}`, data, {
            forceFormData: true,
            onSuccess: () => resetForm()
        })
    } else {
        router.post('/users', data, {
            forceFormData: true,
            onSuccess: () => resetForm()
        })
    }
}

// edit
const editUser = (user) => {
    form.value = {
        id: user.id,
        name: user.name,
        username: user.username,
        password: '',
        role: user.roles?.[0]?.name || '',
        photo: null,
        preview: user.photo ? `/storage/${user.photo}` : null
    }
    isEdit.value = true
}

// delete
const deleteUser = (id) => {
    if (confirm('Yakin hapus user ini?')) {
        router.delete(`/users/${id}`)
    }
}
</script>

<template>
    <Head title="Manajemen User" />

    <AuthenticatedLayout>
        <!-- HEADER -->
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Manajemen User
            </h2>
        </template>

        <!-- CONTENT -->
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

                <!-- FORM -->
                <div class="bg-white p-4 rounded shadow mb-6">
                    <div class="grid grid-cols-2 gap-4">

                        <div>
                            <input v-model="form.name" placeholder="Nama" class="border p-2 w-full">
                            <small class="text-red-500">{{ errors.name }}</small>
                        </div>

                        <div>
                            <input v-model="form.username" placeholder="Username" class="border p-2 w-full">
                            <small class="text-red-500">{{ errors.username }}</small>
                        </div>

                        <div>
                            <input v-model="form.password" type="password" placeholder="Password" class="border p-2 w-full">
                        </div>

                        <div>
                            <select v-model="form.role" class="border p-2 w-full">
                                <option value="">-- Pilih Role --</option>
                                <option v-for="role in roles" :key="role.id" :value="role.name">
                                    {{ role.name }}
                                </option>
                            </select>
                        </div>

                        <!-- UPLOAD FOTO -->
                        <div class="col-span-2">
                            <input type="file" @change="handleFile" class="border p-2 w-full">
                        </div>

                    </div>

                    <!-- PREVIEW FOTO -->
                    <div v-if="form.preview" class="mt-4">
                        <img :src="form.preview" class="w-20 h-20 rounded-full object-cover">
                    </div>

                    <div class="mt-4">
                        <button @click="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded mr-2">
                            {{ isEdit ? 'Update' : 'Tambah' }}
                        </button>

                        <button v-if="isEdit" @click="resetForm"
                            class="bg-gray-500 text-white px-4 py-2 rounded">
                            Batal
                        </button>
                    </div>
                </div>

                <!-- TABLE -->
                <div class="bg-white rounded shadow overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3 text-left">No</th>
                                <th class="p-3 text-left">Foto</th>
                                <th class="p-3 text-left">Nama</th>
                                <th class="p-3 text-left">Username</th>
                                <th class="p-3 text-left">Role</th>
                                <th class="p-3 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(user, index) in users" :key="user.id" class="border-t">
                                <td class="p-3">{{ index + 1 }}</td>

                                <!-- FOTO -->
                                <td class="p-3">
                                    <img v-if="user.photo"
                                        :src="`/storage/${user.photo}`"
                                        class="w-10 h-10 rounded-full object-cover">
                                    <span v-else>-</span>
                                </td>

                                <td class="p-3">{{ user.name }}</td>
                                <td class="p-3">{{ user.username }}</td>
                                <td class="p-3">
                                    {{ user.roles?.[0]?.name || '-' }}
                                </td>

                                <td class="p-3">
                                    <button @click="editUser(user)"
                                        class="bg-yellow-500 text-white px-2 py-1 rounded mr-2">
                                        Edit
                                    </button>
                                    <button @click="deleteUser(user.id)"
                                        class="bg-red-600 text-white px-2 py-1 rounded">
                                        Hapus
                                    </button>
                                </td>
                            </tr>

                            <tr v-if="users.length === 0">
                                <td colspan="6" class="text-center p-4 text-gray-500">
                                    Data kosong
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>