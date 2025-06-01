<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Pengaduan\PengaduanController; // Import controller pengaduan
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard Routes 
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard Pelapor
    Route::get('/dashboard/pelapor', [DashboardController::class, 'pelapor'])
        ->name('dashboard.pelapor');

    // Dashboard Terlapor
    Route::get('/dashboard/terlapor', [DashboardController::class, 'terlapor'])
        ->name('dashboard.terlapor');

    // Dashboard Mediator
    Route::get('/dashboard/mediator', [DashboardController::class, 'mediator'])
        ->name('dashboard.mediator');

    // Dashboard Kepala Dinas
    Route::get('/dashboard/kepala-dinas', [DashboardController::class, 'kepalaDinas'])
        ->name('dashboard.kepala-dinas');

    // Default dashboard redirect
    Route::get('/dashboard', function () {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        switch ($user->role) {
            case 'pelapor':
                return redirect()->route('dashboard.pelapor');
            case 'terlapor':
                return redirect()->route('dashboard.terlapor');
            case 'mediator':
                return redirect()->route('dashboard.mediator');
            case 'kepala_dinas':
                return redirect()->route('dashboard.kepala-dinas');
            default:
                abort(403, 'Role tidak valid: ' . $user->role);
        }
    })->name('dashboard');
});

// Routes Pengaduan - MENGGUNAKAN CONTROLLER
Route::middleware(['auth', 'verified'])->prefix('pengaduan')->name('pengaduan.')->group(function () {

    // Index - untuk pelapor lihat pengaduan sendiri, untuk mediator lihat semua
    Route::get('/', [PengaduanController::class, 'index'])->name('index');

    // Create & Store - HANYA UNTUK PELAPOR
    Route::get('/create', [PengaduanController::class, 'create'])->name('create');
    Route::post('/store', [PengaduanController::class, 'store'])->name('store');

    // Kelola - KHUSUS UNTUK MEDIATOR
    Route::get('/kelola', [PengaduanController::class, 'kelola'])->name('kelola');

    // Show, Edit, Update, Delete - dengan authorization di controller
    Route::get('/{pengaduan}', [PengaduanController::class, 'show'])->name('show');
    Route::get('/{pengaduan}/edit', [PengaduanController::class, 'edit'])->name('edit');
    Route::put('/{pengaduan}', [PengaduanController::class, 'update'])->name('update');
    Route::delete('/{pengaduan}', [PengaduanController::class, 'destroy'])->name('destroy');

    // Actions untuk mediator
    Route::post('/{pengaduan}/update-status', [PengaduanController::class, 'updateStatus'])->name('updateStatus');
    Route::post('/{pengaduan}/assign', [PengaduanController::class, 'assign'])->name('assign');
});

// Routes untuk jadwal
Route::middleware(['auth', 'verified'])->prefix('jadwal')->name('jadwal.')->group(function () {
    Route::get('/', function () {
        return view('jadwal.index');
    })->name('index');
});

// Routes untuk penyelesaian
Route::middleware(['auth', 'verified'])->prefix('penyelesaian')->name('penyelesaian.')->group(function () {
    Route::get('/', function () {
        return view('penyelesaian.index');
    })->name('index');
});

// Routes untuk laporan mediasi
Route::middleware(['auth', 'verified'])->prefix('mediasi')->name('mediasi.')->group(function () {
    Route::get('/', function () {
        return view('mediasi.laporan');
    })->name('laporan');
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
