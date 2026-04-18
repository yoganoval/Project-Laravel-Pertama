<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AppSeeder extends Seeder
{
    public function run(): void
    {
        // ========================
        // PERMISSIONS
        // ========================
        $permissions = [

            // PEMINJAMAN
            'view peminjaman',
            'create peminjaman',
            'delete peminjaman',

            // PERMISSIONS
            'view permissions',
            'create permissions',
            'edit permissions',
            'delete permissions',

            // ROLES
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',

            // BOOKS
            'view books',
            'create books',
            'edit books',
            'delete books',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ========================
        // ROLES
        // ========================
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $petugas = Role::firstOrCreate(['name' => 'petugas']);
        $anggota = Role::firstOrCreate(['name' => 'anggota']);

        // ADMIN → semua akses
        $admin->syncPermissions(Permission::all());

        // PETUGAS
        $petugas->syncPermissions([
            'view peminjaman',
            'create peminjaman',
            'delete peminjaman',

            'view books',
            'create books',
            'edit books',
            'delete books',
        ]);

        // ANGGOTA
        $anggota->syncPermissions([
            'view peminjaman',
            'create peminjaman',
        ]);

        // ========================
        // USERS
        // ========================
        $users = [
            ['name' => 'Moh. Noval Prayoga', 'username' => 'noval'],
            ['name' => 'Ivan Maulana', 'username' => 'ivanfi'],
            ['name' => 'toyiyb', 'username' => 'yiyib'],
            ['name' => 'putrikecil', 'username' => 'putri'],
            ['name' => 'arwani', 'username' => 'arwani'],
        ];

        foreach ($users as $u) {
            $user = User::firstOrCreate(
                ['username' => $u['username']],
                [
                    'name' => $u['name'],
                    'password' => Hash::make('password'),
                ]
            );

            // Assign Role
            if ($u['username'] === 'noval') {
                $user->assignRole($admin);
            } elseif ($u['username'] === 'ivanfi') {
                $user->assignRole($petugas);
            } else {
                $user->assignRole($anggota);
            }
        }
    }
}