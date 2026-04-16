<script setup>
import { ref, computed } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'

import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'

const page = usePage()

// sidebar toggle
const open = ref(false)

// permissions (pakai Set biar cepat)
const permissionSet = computed(() => new Set(page.props.auth.user?.permissions || []))

const hasPermission = (perm) => permissionSet.value.has(perm)

// active menu (lebih stabil)
const isActive = (prefix) => route().current().startsWith(prefix)
</script>

<template>
<div class="flex min-h-screen bg-gray-100">

    <!-- SIDEBAR -->
    <aside
        :class="[
            'fixed z-20 inset-y-0 left-0 w-64 bg-gray-900 text-white transform transition-transform duration-300 ease-in-out',
            open ? 'translate-x-0' : '-translate-x-full',
            'md:translate-x-0 md:static'
        ]"
    >
        <!-- HEADER -->
        <div class="flex items-center justify-center h-16 bg-gray-800 font-bold">
            📚 Perpustakaan
        </div>

        <nav class="mt-4 px-4 space-y-2">

            <!-- DASHBOARD -->
            <Link
                @click="open = false"
                :href="route('dashboard')"
                :class="[
                    'block px-4 py-2 rounded',
                    isActive('dashboard') ? 'bg-gray-700' : 'hover:bg-gray-700'
                ]"
            >
                🏠 Dashboard
            </Link>

            <!-- MASTER DATA -->
            <div class="text-gray-400 text-xs uppercase px-4 mt-4">
                Master Data
            </div>

            <!-- USERS -->
            <Link
                v-if="hasPermission('view users')"
                @click="open = false"
                :href="route('users.index')"
                :class="[
                    'block px-4 py-2 rounded',
                    isActive('users') ? 'bg-gray-700' : 'hover:bg-gray-700'
                ]"
            >
                👤 Users
            </Link>

            <!-- BOOKS -->
            <Link
                v-if="hasPermission('view books')"
                @click="open = false"
                :href="route('books.index')"
                :class="[
                    'block px-4 py-2 rounded',
                    isActive('books') ? 'bg-gray-700' : 'hover:bg-gray-700'
                ]"
            >
                📚 Data Buku
            </Link>

            <!-- TRANSAKSI -->
            <div class="text-gray-400 text-xs uppercase px-4 mt-4">
                Transaksi
            </div>

            <!-- PEMINJAMAN -->
            <Link
                v-if="hasPermission('view peminjaman')"
                @click="open = false"
                :href="route('peminjaman.index')"
                :class="[
                    'block px-4 py-2 rounded',
                    isActive('peminjaman') ? 'bg-gray-700' : 'hover:bg-gray-700'
                ]"
            >
                📖 Data Peminjaman
            </Link>

            <!-- PENGATURAN -->
            <div class="text-gray-400 text-xs uppercase px-4 mt-4">
                Pengaturan
            </div>

            <!-- ROLE -->
            <Link
                v-if="hasPermission('view roles')"
                @click="open = false"
                :href="route('roles.index')"
                :class="[
                    'block px-4 py-2 rounded',
                    isActive('roles') ? 'bg-gray-700' : 'hover:bg-gray-700'
                ]"
            >
                🔑 Role Permissions
            </Link>

            <!-- PERMISSION -->
            <Link
                v-if="hasPermission('view permissions')"
                @click="open = false"
                :href="route('permissions.index')"
                :class="[
                    'block px-4 py-2 rounded',
                    isActive('permissions') ? 'bg-gray-700' : 'hover:bg-gray-700'
                ]"
            >
                ⚙️ Permissions
            </Link>

        </nav>
    </aside>

    <!-- CONTENT -->
    <div class="flex-1 flex flex-col">

        <!-- NAVBAR -->
        <nav class="border-b border-gray-100 bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between">

                    <!-- LEFT -->
                    <div class="flex items-center">
                        <button @click="open = !open" class="mr-4 md:hidden text-gray-600 text-xl">
                            ☰
                        </button>
                    </div>

                    <!-- RIGHT -->
                    <div class="hidden sm:flex sm:items-center">

                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button class="inline-flex items-center px-3 py-2 text-sm text-gray-500">
                                    {{ $page.props.auth.user.name }}
                                </button>
                            </template>

                            <template #content>
                                <DropdownLink :href="route('profile.edit')">
                                    Profile
                                </DropdownLink>

                                <DropdownLink :href="route('logout')" method="post" as="button">
                                    Log Out
                                </DropdownLink>
                            </template>
                        </Dropdown>

                    </div>

                </div>
            </div>
        </nav>

        <!-- MAIN -->
        <main class="p-6">
            <slot />
        </main>

    </div>
</div>
</template>