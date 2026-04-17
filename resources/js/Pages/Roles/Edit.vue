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
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                Edit Role
            </h2>
        </template>

        <!-- CONTENT -->
        <div class="py-12">
            <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">

                <!-- CARD -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow border border-gray-200 dark:border-gray-700">

                    <!-- NAME -->
                    <div class="mb-4">
                        <label class="block mb-1 font-medium text-gray-700 dark:text-gray-300">
                            Nama Role
                        </label>

                        <input v-model="form.name"
                               class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white p-2 rounded" />
                    </div>

                    <!-- PERMISSIONS -->
                    <div v-for="(group, key) in permissions"
                         :key="key"
                         class="mb-4 border border-gray-200 dark:border-gray-700 p-4 rounded-lg bg-gray-50 dark:bg-gray-900">

                        <b class="uppercase block mb-2 text-gray-700 dark:text-gray-300">
                            {{ key }}
                        </b>

                        <div class="grid grid-cols-2 gap-2 text-gray-700 dark:text-gray-200">
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
                                :disabled="form.processing"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                            {{ form.processing ? 'Updating...' : 'Update Role' }}
                        </button>
                    </div>

                </div>

            </div>
        </div>

    </AuthenticatedLayout>
</template>