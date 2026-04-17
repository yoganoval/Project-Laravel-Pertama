<script setup>
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import { useForm, usePage } from '@inertiajs/vue3'

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
})

const user = usePage().props.auth.user

const form = useForm({
    _method: 'patch',
    name: user.name,
    username: user.username,
    photo: null,
})
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                Profile Information
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Update your account profile information.
            </p>
        </header>

        <form
            @submit.prevent="form.post(route('profile.update'), {
                forceFormData: true
            })"
            class="mt-6 space-y-6"
        >
            <!-- FOTO -->
            <div>
                <InputLabel for="photo" value="Foto Profile" />

                <div class="mt-2 flex items-center gap-4">
                    <img
                        v-if="user.photo"
                        :src="`/storage/${user.photo}`"
                        alt="Foto User"
                        class="w-16 h-16 rounded-full object-cover border border-gray-300 dark:border-gray-600"
                    >

                    <div
                        v-else
                        class="w-16 h-16 rounded-full bg-gray-300 dark:bg-gray-700 flex items-center justify-center text-xl font-bold text-white"
                    >
                        {{ user.name.charAt(0).toUpperCase() }}
                    </div>

                    <input
                        id="photo"
                        type="file"
                        @change="form.photo = $event.target.files[0]"
                        class="block w-full text-sm text-gray-700 dark:text-gray-300
                               file:mr-4 file:py-2 file:px-4
                               file:rounded-lg file:border-0
                               file:text-sm file:font-semibold
                               file:bg-blue-50 file:text-blue-700
                               dark:file:bg-gray-700 dark:file:text-gray-200"
                    />
                </div>

                <InputError class="mt-2" :message="form.errors.photo" />
            </div>

            <!-- NAMA -->
            <div>
                <InputLabel
                    for="name"
                    value="Nama"
                    class="text-gray-700 dark:text-gray-300"
                />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    v-model="form.name"
                    required
                    autofocus
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <!-- USERNAME -->
            <div>
                <InputLabel
                    for="username"
                    value="Username"
                    class="text-gray-700 dark:text-gray-300"
                />

                <TextInput
                    id="username"
                    type="text"
                    class="mt-1 block w-full bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    v-model="form.username"
                    required
                />

                <InputError class="mt-2" :message="form.errors.username" />
            </div>

            <!-- ROLE -->
            <div>
                <InputLabel
                    for="role"
                    value="Role"
                    class="text-gray-700 dark:text-gray-300"
                />

                <TextInput
                    id="role"
                    type="text"
                    class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    :model-value="user.roles?.[0]?.name || '-'"
                    disabled
                />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">
                    Save
                </PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600 dark:text-gray-400"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
