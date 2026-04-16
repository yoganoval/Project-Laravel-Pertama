<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <Head title="Welcome" />

    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white flex flex-col">
        
        <!-- NAVBAR -->
        <header class="flex justify-between items-center px-8 py-5">
            <h1 class="text-2xl font-bold tracking-wide">
                MySystem
            </h1>

            <nav v-if="canLogin" class="flex gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 transition"
                >
                    Dashboard
                </Link>

                <template v-else>
                    <Link
                        :href="route('login')"
                        class="px-4 py-2 rounded-lg border border-white/30 hover:bg-white/10 transition"
                    >
                        Login
                    </Link>

                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 transition"
                    >
                        Register
                    </Link>
                </template>
            </nav>
        </header>

        <!-- HERO SECTION -->
        <main class="flex-1 flex flex-col items-center justify-center text-center px-6">
            
            <h2 class="text-4xl md:text-5xl font-extrabold leading-tight">
                Selamat Datang di <span class="text-blue-500">MySystem</span>
            </h2>

            <p class="mt-6 text-lg text-gray-300 max-w-2xl">
                Sistem informasi modern yang membantu pengelolaan data, 
                meningkatkan efisiensi kerja, dan memberikan pengalaman 
                terbaik bagi pengguna.
            </p>

            <div class="mt-8 flex gap-4">
                <Link
                    v-if="!$page.props.auth.user"
                    :href="route('login')"
                    class="px-6 py-3 bg-blue-600 rounded-lg text-lg hover:bg-blue-700 transition"
                >
                    Mulai Sekarang
                </Link>

                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="px-6 py-3 bg-green-600 rounded-lg text-lg hover:bg-green-700 transition"
                >
                    Masuk Dashboard
                </Link>
            </div>

        </main>

        <!-- FEATURES -->
        <section class="grid md:grid-cols-3 gap-6 px-8 pb-16">
            <div class="bg-white/5 p-6 rounded-xl backdrop-blur">
                <h3 class="text-xl font-semibold mb-2">Cepat</h3>
                <p class="text-gray-400">
                    Sistem dirancang dengan performa tinggi untuk kebutuhan modern.
                </p>
            </div>

            <div class="bg-white/5 p-6 rounded-xl backdrop-blur">
                <h3 class="text-xl font-semibold mb-2">Aman</h3>
                <p class="text-gray-400">
                    Keamanan data menjadi prioritas utama dalam setiap proses.
                </p>
            </div>

            <div class="bg-white/5 p-6 rounded-xl backdrop-blur">
                <h3 class="text-xl font-semibold mb-2">Mudah Digunakan</h3>
                <p class="text-gray-400">
                    Interface sederhana dan intuitif untuk semua pengguna.
                </p>
            </div>
        </section>

        <!-- FOOTER -->
        <footer class="text-center text-gray-500 text-sm pb-6">
            Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
        </footer>

    </div>
</template>
