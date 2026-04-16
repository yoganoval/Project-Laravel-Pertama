<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    username: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register" />

    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 px-4">
        
        <!-- CARD -->
        <div class="w-full max-w-md bg-white/5 backdrop-blur-lg p-8 rounded-2xl shadow-xl border border-white/10">
            
            <!-- TITLE -->
            <div class="text-center mb-6">
                <h2 class="text-3xl font-bold text-white">Create Account</h2>
                <p class="text-gray-400 text-sm mt-2">
                    Daftar untuk mulai menggunakan sistem
                </p>
            </div>

            <!-- FORM -->
            <form @submit.prevent="submit" class="space-y-5">

                <!-- NAME -->
                <div>
                    <InputLabel for="name" value="Nama Lengkap" class="text-gray-300" />

                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full bg-gray-800 border-gray-700 text-white focus:border-blue-500 focus:ring-blue-500"
                        v-model="form.name"
                        required
                        autofocus
                    />

                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <!-- USERNAME -->
                <div>
                    <InputLabel for="username" value="Username" class="text-gray-300" />

                    <TextInput
                        id="username"
                        type="text"
                        class="mt-1 block w-full bg-gray-800 border-gray-700 text-white focus:border-blue-500 focus:ring-blue-500"
                        v-model="form.username"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.username" />
                </div>

                <!-- PASSWORD -->
                <div>
                    <InputLabel for="password" value="Password" class="text-gray-300" />

                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full bg-gray-800 border-gray-700 text-white focus:border-blue-500 focus:ring-blue-500"
                        v-model="form.password"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <!-- CONFIRM PASSWORD -->
                <div>
                    <InputLabel
                        for="password_confirmation"
                        value="Konfirmasi Password"
                        class="text-gray-300"
                    />

                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="mt-1 block w-full bg-gray-800 border-gray-700 text-white focus:border-blue-500 focus:ring-blue-500"
                        v-model="form.password_confirmation"
                        required
                    />

                    <InputError
                        class="mt-2"
                        :message="form.errors.password_confirmation"
                    />
                </div>

                <!-- BUTTON -->
                <PrimaryButton
                    class="w-full justify-center bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg text-lg transition"
                    :class="{ 'opacity-50': form.processing }"
                    :disabled="form.processing"
                >
                    Register
                </PrimaryButton>

            </form>

            <!-- FOOTER -->
            <div class="mt-6 text-center text-sm text-gray-400">
                Sudah punya akun?
                <Link
                    :href="route('login')"
                    class="text-blue-400 hover:underline"
                >
                    Login di sini
                </Link>
            </div>

        </div>

    </div>
</template>
