<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; // 🔥 WAJIB
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{

    public function index()
    {
        return Inertia::render('Roles/Index', [
            'roles' => Role::with('permissions')->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Roles/Create', [
            'permissions' => Permission::all()->groupBy(function ($perm) {
                return explode(' ', $perm->name)[1] ?? 'other';
            })
        ]);
    }

    public function store(Request $request)
    {
            dd($request->all());
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array'
        ]);

        $role = Role::create([
            'name' => $request->name
        ]);

        // 🔥 masuk ke role_has_permissions
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index');
    }

    public function edit(Role $role)
    {
        return Inertia::render('Roles/Edit', [
            'role' => $role->load('permissions'),
            'permissions' => Permission::all()->groupBy(function ($perm) {
                return explode(' ', $perm->name)[1] ?? 'other';
            })
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array'
        ]);

        $role->update([
            'name' => $request->name
        ]);

        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return back();
    }
}