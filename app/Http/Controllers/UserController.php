<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Inertia\Inertia;

class UserController extends Controller
{
    // 🔹 Tampilkan semua user
    public function index()
    {
        return Inertia::render('Users/index', [
            'users' => User::with('roles')->get(),
            'roles' => Role::all()
        ]);
    }
    // Simpan user baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
            'role' => 'required',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // upload foto
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('users', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'photo' => $photoPath
        ]);

        $user->assignRole($request->role);

        return back()->with('success', 'User berhasil ditambahkan');
    }

    // 🔹 Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username,' . $id,
            'role' => 'required',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $user->update([
            'name' => $request->name,
            'username' => $request->username,
        ]);

        // update password
        if ($request->password) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        // upload foto baru
        if ($request->hasFile('photo')) {

            // hapus foto lama
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }

            $photoPath = $request->file('photo')->store('users', 'public');

            $user->update([
                'photo' => $photoPath
            ]);
        }

        $user->syncRoles([$request->role]);

        return back()->with('success', 'User berhasil diupdate');
    }

    // 🔹 Delete user
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus');
    }
}