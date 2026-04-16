<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    username: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Login" />

    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 px-4">
        
        <!-- CARD -->
        <div class="w-full max-w-md bg-white/5 backdrop-blur-lg p-8 rounded-2xl shadow-xl border border-white/10">
            
            <!-- TITLE -->
            <div class="text-center mb-6">
                <h2 class="text-3xl font-bold text-white">Welcome Back</h2>
                <p class="text-gray-400 text-sm mt-2">
                    Silakan login untuk melanjutkan
                </p>
            </div>

            <!-- STATUS -->
            <div v-if="status" class="mb-4 text-sm text-green-400 text-center">
                {{ status }}
            </div>

            <!-- FORM -->
            <form @submit.prevent="submit" class="space-y-5">
                
                <!-- USERNAME -->
                <div>
                    <InputLabel for="username" value="Username" class="text-gray-300" />

                    <TextInput
                        id="username"
                        type="text"
                        class="mt-1 block w-full bg-gray-800 border-gray-700 text-white focus:border-blue-500 focus:ring-blue-500"
                        v-model="form.username"
                        required
                        autofocus
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

                <!-- REMEMBER -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center text-gray-400 text-sm">
                        <Checkbox v-model:checked="form.remember" />
                        <span class="ml-2">Remember me</span>
                    </label>

                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-sm text-blue-400 hover:underline"
                    >
                        Lupa password?
                    </Link>
                </div>

                <!-- BUTTON -->
                <PrimaryButton
                    class="w-full justify-center bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg text-lg transition"
                    :class="{ 'opacity-50': form.processing }"
                    :disabled="form.processing"
                >
                    Login
                </PrimaryButton>

            </form>

            <!-- FOOTER -->
            <div class="mt-6 text-center text-sm text-gray-500">
                © {{ new Date().getFullYear() }} MySystem
            </div>

        </div>

    </div>
</template>
