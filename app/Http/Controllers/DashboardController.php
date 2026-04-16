<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Book;
use App\Models\Peminjaman;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 🔴 ADMIN
        if ($user->hasRole('admin')) {
            return Inertia::render('Admin/Dashboard', [
                'totalUsers' => User::count(),
                'totalBooks' => Book::count(),
                'peminjaman' => Peminjaman::with(['user', 'book'])->latest()->take(5)->get()
            ]);
        }

        // 🟡 PETUGAS
        if ($user->hasRole('petugas')) {
            return Inertia::render('Petugas/Dashboard', [
                'peminjaman' => Peminjaman::with(['user', 'book'])
                    ->where('status', 'dipinjam')
                    ->latest()
                    ->get()
            ]);
        }

        // 🟢 ANGGOTA (PERSONAL)
        if ($user->hasRole('anggota')) {
            return Inertia::render('Anggota/Dashboard', [
                'peminjaman' => Peminjaman::with(['book'])
                    ->where('user_id', $user->id)
                    ->where('status', 'dipinjam')
                    ->latest()
                    ->get()
            ]);
        }

        abort(403);
    }
}