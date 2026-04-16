<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\User;

// ========================
// PUBLIC
// ========================
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// ========================
// AUTH USER
// ========================
Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::prefix('peminjaman')->group(function () {

        Route::get('/', [PeminjamanController::class, 'index'])
            ->middleware('permission:view peminjaman')
            ->name('peminjaman.index');

        Route::post('/', [PeminjamanController::class, 'store'])
            ->middleware('permission:create peminjaman')
            ->name('peminjaman.store');

        Route::get('/{id}', [PeminjamanController::class, 'show'])
            ->middleware('permission:view peminjaman')
            ->name('peminjaman.show');

        Route::put('/{id}/kembali', [PeminjamanController::class, 'kembali'])
            ->name('peminjaman.kembali');

        Route::put('/{id}', [PeminjamanController::class, 'update'])
            ->name('peminjaman.update');

        // 🔥 BAYAR DENDA
        Route::post('/{id}/bayar-denda', [PeminjamanController::class, 'bayarDenda'])
            ->name('peminjaman.bayarDenda');

        Route::delete('/{id}', [PeminjamanController::class, 'destroy'])
            ->middleware('permission:delete peminjaman')
            ->name('peminjaman.destroy');

    });

    Route::prefix('permissions')->group(function () {

        Route::get('/', [PermissionController::class, 'index'])
            ->middleware('permission:view permissions')
            ->name('permissions.index');

        Route::get('/create', [PermissionController::class, 'create'])
            ->middleware('permission:create permissions')
            ->name('permissions.create');

        Route::post('/', [PermissionController::class, 'store'])
            ->middleware('permission:create permissions')
            ->name('permissions.store');

        Route::get('/{permission}/edit', [PermissionController::class, 'edit'])
            ->middleware('permission:edit permissions')
            ->name('permissions.edit');

        Route::put('/{permission}', [PermissionController::class, 'update'])
            ->middleware('permission:edit permissions')
            ->name('permissions.update');

        Route::delete('/{permission}', [PermissionController::class, 'destroy'])
            ->middleware('permission:delete permissions')
            ->name('permissions.destroy');

    });

    Route::prefix('roles')->group(function () {

        Route::get('/', [RoleController::class, 'index'])
            ->middleware('permission:view roles')
            ->name('roles.index');

        Route::get('/create', [RoleController::class, 'create'])
            ->middleware('permission:create roles')
            ->name('roles.create');

        Route::post('/', [RoleController::class, 'store'])
            ->middleware('permission:create roles')
            ->name('roles.store');

        Route::get('/{role}/edit', [RoleController::class, 'edit'])
            ->middleware('permission:edit roles')
            ->name('roles.edit');

        Route::put('/{role}', [RoleController::class, 'update'])
            ->middleware('permission:edit roles')
            ->name('roles.update');

        Route::delete('/{role}', [RoleController::class, 'destroy'])
            ->middleware('permission:delete roles')
            ->name('roles.destroy');

    });

    Route::prefix('books')->group(function () {

        Route::get('/', [BookController::class, 'index'])
            ->middleware('permission:view books')
            ->name('books.index');

        Route::post('/', [BookController::class, 'store'])
            ->middleware('permission:create books')
            ->name('books.store');

        Route::put('/{book}', [BookController::class, 'update'])
            ->middleware('permission:edit books')
            ->name('books.update');

        Route::delete('/{book}', [BookController::class, 'destroy'])
            ->middleware('permission:delete books')
            ->name('books.destroy');

    });

    Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ========================
    // ADMIN ONLY
    // ========================
    Route::middleware('role:admin')->group(function () {

        Route::get('/admin', function () {
            return 'HALAMAN ADMIN';
        });

        Route::resource('users', UserController::class);

    });

});

require __DIR__.'/auth.php';