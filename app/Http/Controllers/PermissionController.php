<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Inertia\Inertia;
use Illuminate\Http\Request;
// use Spatie\Permission\Models\Permission;
// use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    public function index()
    {
        return Inertia::render('Permissions/Index', [
            'permissions' => Permission::all()
        ]);
    }

    public function create()
    {
        return Inertia::render('Permissions/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'actions' => 'required|array|min:1'
        ]);

        // ambil nama module → jadikan plural
        $module = Str::plural($request->name);

        foreach ($request->actions as $action) {
            Permission::firstOrCreate([
                'name' => $action . ' ' . $module
            ]);
        }

        return redirect()->route('permissions.index')
            ->with('success', 'Permission berhasil dibuat');
    }
}