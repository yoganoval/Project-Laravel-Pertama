<script setup>
import { ref, computed } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'

import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'

const dropdownOpen = ref(false)

const page = usePage()

// sidebar toggle
const open = ref(false)
const collapsed = ref(false)

// permissions
const permissionSet = computed(() => new Set(page.props.auth.user?.permissions || []))
const hasPermission = (perm) => permissionSet.value.has(perm)

// active menu
const isActive = (prefix) => route().current().startsWith(prefix)

// 🌙 DARK MODE
const toggleDark = () => {
    if (localStorage.theme === 'dark') {
        localStorage.theme = 'light'
        document.documentElement.classList.remove('dark')
    } else {
        localStorage.theme = 'dark'
        document.documentElement.classList.add('dark')
    }
}
</script>

<template>
<div class="flex min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">

    <!-- SIDEBAR -->
    <aside
        :class="[
            'fixed z-20 inset-y-0 left-0 bg-white dark:bg-gray-900 text-gray-800 dark:text-white transform transition-all duration-300 ease-in-out',
            open ? 'translate-x-0' : '-translate-x-full',
            'md:translate-x-0 md:static',
            collapsed ? 'w-20' : 'w-64'
        ]"
    >
        <!-- HEADER -->
        <div class="flex items-center justify-center h-16 bg-gray-200 dark:bg-gray-800 font-bold">
            <span v-if="!collapsed">📚 Perpustakaan</span>
            <span v-else>📚</span>
        </div>

        <nav class="mt-4 px-2 space-y-2">

            <!-- DASHBOARD -->
            <Link
                @click="open = false"
                :href="route('dashboard')"
                :class="[
                    'flex items-center px-4 py-2 rounded',
                    isActive('dashboard') ? 'bg-gray-300 dark:bg-gray-700' : 'hover:bg-gray-200 dark:hover:bg-gray-700'
                ]"
            >
                🏠 <span v-if="!collapsed" class="ml-2">Dashboard</span>
            </Link>

            <!-- MASTER DATA -->
            <div v-if="!collapsed" class="text-gray-400 dark:text-gray-500 text-xs uppercase px-4 mt-4">
                Master Data
            </div>

            <!-- USERS -->
            <Link
                v-if="hasPermission('view users')"
                @click="open = false"
                :href="route('users.index')"
                :class="[
                    'flex items-center px-4 py-2 rounded',
                    isActive('users') ? 'bg-gray-300 dark:bg-gray-700' : 'hover:bg-gray-200 dark:hover:bg-gray-700'
                ]"
            >
                👤 <span v-if="!collapsed" class="ml-2">Users</span>
            </Link>

            <!-- BOOKS -->
            <Link
                v-if="hasPermission('view books')"
                @click="open = false"
                :href="route('books.index')"
                :class="[
                    'flex items-center px-4 py-2 rounded',
                    isActive('books') ? 'bg-gray-300 dark:bg-gray-700' : 'hover:bg-gray-200 dark:hover:bg-gray-700'
                ]"
            >
                📚 <span v-if="!collapsed" class="ml-2">Data Buku</span>
            </Link>

            <!-- TRANSAKSI -->
            <div v-if="!collapsed" class="text-gray-400 dark:text-gray-500 text-xs uppercase px-4 mt-4">
                Transaksi
            </div>

            <!-- PEMINJAMAN -->
            <Link
                v-if="hasPermission('view peminjaman')"
                @click="open = false"
                :href="route('peminjaman.index')"
                :class="[
                    'flex items-center px-4 py-2 rounded',
                    isActive('peminjaman') ? 'bg-gray-300 dark:bg-gray-700' : 'hover:bg-gray-200 dark:hover:bg-gray-700'
                ]"
            >
                📖 <span v-if="!collapsed" class="ml-2">Data Peminjaman</span>
            </Link>

            <!-- PENGATURAN -->
            <div v-if="!collapsed" class="text-gray-400 dark:text-gray-500 text-xs uppercase px-4 mt-4">
                Pengaturan
            </div>

            <!-- ROLE -->
            <Link
                v-if="hasPermission('view roles')"
                @click="open = false"
                :href="route('roles.index')"
                :class="[
                    'flex items-center px-4 py-2 rounded',
                    isActive('roles') ? 'bg-gray-300 dark:bg-gray-700' : 'hover:bg-gray-200 dark:hover:bg-gray-700'
                ]"
            >
                🔑 <span v-if="!collapsed" class="ml-2">Role Permissions</span>
            </Link>

            <!-- PERMISSION -->
            <Link
                v-if="hasPermission('view permissions')"
                @click="open = false"
                :href="route('permissions.index')"
                :class="[
                    'flex items-center px-4 py-2 rounded',
                    isActive('permissions') ? 'bg-gray-300 dark:bg-gray-700' : 'hover:bg-gray-200 dark:hover:bg-gray-700'
                ]"
            >
                ⚙️ <span v-if="!collapsed" class="ml-2">Permissions</span>
            </Link>

        </nav>
    </aside>

    <!-- CONTENT -->
    <div class="flex-1 flex flex-col">

        <!-- NAVBAR -->
        <nav class="border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
            <div class="w-full pl-2 pr-4 sm:pl-4 sm:pr-6 lg:pl-6 lg:pr-8">
                <div class="flex h-16 justify-between">

                    <!-- LEFT -->
                    <div class="flex items-center">
                        <!-- mobile -->
                        <button 
                            @click="open = !open" 
                            class="mr-2 md:hidden text-gray-600 dark:text-gray-300 text-xl"
                        >
                            ☰
                        </button>

                        <!-- collapse -->
                        <button 
                            @click="collapsed = !collapsed"
                            class="mr-2 px-3 py-2 rounded bg-gray-200 dark:bg-gray-700 text-sm text-gray-700 dark:text-gray-200"
                        >
                            {{ collapsed ? '➡️' : '⬅️' }}
                        </button>
                    </div>

                    <!-- RIGHT -->
                    <div class="flex items-center">

                        <!-- dark mode -->
                        <button 
                            @click="toggleDark"
                            class="mr-4 px-3 py-2 rounded bg-gray-200 dark:bg-gray-700 text-sm text-gray-700 dark:text-gray-200"
                        >
                            🌙
                        </button>

                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button
                                    @click="dropdownOpen = !dropdownOpen"
                                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-sm text-gray-600 dark:text-gray-300 transition"
                                >
                                    <img
                                        v-if="$page.props.auth.user.photo"
                                        :src="`/storage/${$page.props.auth.user.photo}`"
                                        alt="Foto User"
                                        class="w-8 h-8 rounded-full object-cover border border-gray-300 dark:border-gray-600"
                                    >

                                    <div
                                        v-else
                                        class="w-8 h-8 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-xs font-bold text-white"
                                    >
                                        {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                                    </div>

                                    <span>{{ $page.props.auth.user.name }}</span>

                                    <svg
                                        class="w-4 h-4 transition-transform duration-200 text-gray-500 dark:text-gray-300"
                                        :class="{ 'rotate-180': dropdownOpen }"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 9l-7 7-7-7"
                                        />
                                    </svg>
                                </button>
                            </template>

                            <template #content>
                                <div class="min-w-[180px]">
                                    <DropdownLink
                                        :href="route('profile.edit')"
                                        class="block bg-transparent dark:bg-slate-800 px-4 py-3 text-sm text-slate-200 hover:bg-slate-700 hover:text-white transition"
                                    >
                                        Profile
                                    </DropdownLink>

                                    <DropdownLink
                                        :href="route('logout')"
                                        method="post"
                                        as="button"
                                        class="block w-full text-left bg-transparent dark:bg-slate-800 px-4 py-3 text-sm text-slate-200 hover:bg-slate-700 hover:text-white transition"
                                    >
                                        Log Out
                                    </DropdownLink>
                                </div>
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