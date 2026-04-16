<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { onMounted } from 'vue'

const props = defineProps({
    role: Object,
    permissions: Object
})

const form = useForm({
    name: props.role.name,
    permissions: []
})

onMounted(() => {
    form.permissions = props.role.permissions.map(p => p.name)
})

const submit = () => {
    form.put(`/roles/${props.role.id}`)
}
</script>

<template>
    <Head title="Edit Role" />

    <AuthenticatedLayout>

        <!-- HEADER -->
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800">
                Edit Role
            </h2>
        </template>

        <!-- CONTENT -->
        <div class="py-12">
            <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">

                <div class="bg-white p-6 rounded shadow">

                    <!-- NAME -->
                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Nama Role</label>
                        <input v-model="form.name"
                               class="border p-2 w-full rounded" />
                    </div>

                    <!-- PERMISSIONS -->
                    <div v-for="(group, key) in permissions"
                         :key="key"
                         class="mb-4 border p-4 rounded">

                        <b class="uppercase block mb-2 text-gray-700">
                            {{ key }}
                        </b>

                        <div class="grid grid-cols-2 gap-2">
                            <label v-for="perm in group"
                                   :key="perm.id"
                                   class="flex gap-2 items-center">

                                <input type="checkbox"
                                       :value="perm.name"
                                       v-model="form.permissions" />

                                {{ perm.name }}
                            </label>
                        </div>

                    </div>

                    <!-- BUTTON -->
                    <div class="mt-4">
                        <button @click="submit"
                                class="bg-green-600 text-white px-4 py-2 rounded">
                            Update Role
                        </button>
                    </div>

                </div>

            </div>
        </div>

    </AuthenticatedLayout>
</template>