<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Akun\AkunController;
use App\Http\Controllers\Jadwal\JadwalController;
use App\Http\Controllers\Debug\EmailTestController;
use App\Http\Controllers\Jadwal\KonfirmasiController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Pengaduan\PengaduanController;
use App\Http\Controllers\Notifikasi\NotificationController;



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
    Route::get('/', [PengaduanController::class, 'index'])->middleware('check.role:pelapor')->name('index');

    //Index untuk terlapor - melihat pengaduan yang melibatkan mereka
    Route::get('/pengaduan-saya', [PengaduanController::class, 'indexTerlapor'])
        ->middleware('check.role:terlapor')
        ->name('index-terlapor');
    // Detail pengaduan untuk terlapor (read-only)
    Route::get('/pengaduan-saya/{pengaduan:pengaduan_id}', [PengaduanController::class, 'showTerlapor'])
        ->middleware('check.role:terlapor')
        ->name('show-terlapor');

    // Create & Store - HANYA UNTUK PELAPOR
    Route::get('/create', [PengaduanController::class, 'create'])->middleware('check.role:pelapor')->name('create');
    Route::post('/store', [PengaduanController::class, 'store'])->name('store');

    // Kelola - KHUSUS UNTUK MEDIATOR
    Route::get('/kelola', [PengaduanController::class, 'kelola'])->name('kelola');

    // Show, Edit, Update, Delete - dengan authorization di controller
    Route::get('/{pengaduan:pengaduan_id}', [PengaduanController::class, 'show'])->name('show');
    Route::get('/{pengaduan:pengaduan_id}/edit', [PengaduanController::class, 'edit'])->name('edit');
    Route::put('/{pengaduan:pengaduan_id}', [PengaduanController::class, 'update'])->name('update');
    Route::delete('/{pengaduan:pengaduan_id}', [PengaduanController::class, 'destroy'])->name('destroy');

    // Actions untuk mediator - juga menggunakan pengaduan_id
    Route::post('/{pengaduan:pengaduan_id}/update-status', [PengaduanController::class, 'updateStatus'])->name('updateStatus');
    Route::post('/{pengaduan:pengaduan_id}/assign', [PengaduanController::class, 'assign'])->name('assign');
});

// Routes untuk jadwal
Route::middleware(['auth', 'verified'])->prefix('jadwal')->name('jadwal.')->group(function () {
    // Index - bisa diakses mediator
    Route::get('/', [JadwalController::class, 'index'])
        ->middleware('check.role:mediator')
        ->name('index');

    // Create & Store - hanya mediator
    Route::get('/create', [JadwalController::class, 'create'])
        ->middleware('check.role:mediator')
        ->name('create');

    Route::post('/store', [JadwalController::class, 'store'])
        ->middleware('check.role:mediator')
        ->name('store');

    // Show - mediator
    Route::get('/{jadwal}', [JadwalController::class, 'show'])
        ->middleware('check.role:mediator')
        ->name('show');

    // Edit & Update - hanya mediator yang memiliki jadwal
    Route::get('/{jadwal}/edit', [JadwalController::class, 'edit'])
        ->middleware('check.role:mediator')
        ->name('edit');

    Route::put('/{jadwal}', [JadwalController::class, 'update'])
        ->middleware('check.role:mediator')
        ->name('update');

    // Delete - hanya mediator yang memiliki jadwal
    Route::delete('/{jadwal}', [JadwalController::class, 'destroy'])
        ->middleware('check.role:mediator')
        ->name('destroy');

    // Update Status via AJAX - hanya mediator yang memiliki jadwal
    Route::patch('/{jadwal}/update-status', [JadwalController::class, 'updateStatus'])
        ->middleware('check.role:mediator')
        ->name('updateStatus');
});

// Routes untuk Konfirmasi Kehadiran Jadwal Mediasi
Route::middleware(['auth', 'verified'])->group(function () {

    // Routes khusus untuk Pelapor dan Terlapor
    Route::middleware(['role:pelapor,terlapor'])->group(function () {

        // Konfirmasi Jadwal Mediasi - menggunakan view di folder Jadwal/
        Route::prefix('konfirmasi')->name('konfirmasi.')->group(function () {
            Route::get('/', [KonfirmasiController::class, 'index'])->name('index'); // View: Jadwal/konfirmasi-index.blade.php
            Route::get('/{jadwal}', [KonfirmasiController::class, 'show'])->name('show'); // View: Jadwal/konfirmasi-show.blade.php
            Route::post('/{jadwal}/konfirmasi', [KonfirmasiController::class, 'konfirmasi'])->name('konfirmasi');
            Route::delete('/{jadwal}/cancel', [KonfirmasiController::class, 'cancel'])->name('cancel');
        });
    });
});

// Routes untuk penyelesaian
Route::middleware(['auth', 'verified'])->prefix('penyelesaian')->name('penyelesaian.')->group(function () {
    Route::get('/', function () {
        return view('penyelesaian.index');
    })->name('index');

    Route::get('/perjanjian-bersama', function () {
        return view('penyelesaian.perjanjian-bersama');
    })->name('perjanjian-bersama');

    Route::get('/anjuran-tertulis', function () {
        return view('penyelesaian.anjuran-tertulis');
    })->name('anjuran-tertulis');
});

// Routes untuk laporan mediasi
Route::middleware(['auth', 'verified'])->prefix('mediasi')->name('mediasi.')->group(function () {
    Route::get('/', function () {
        return view('mediasi.laporan');
    })->name('laporan');
});

// Routes untuk kelola surat/dokumen
Route::middleware(['auth', 'verified'])->prefix('dokumen')->name('dokumen.')->group(function () {
    Route::get('/', function () {
        return view('dokumen.index');
    })->name('index');
});

// Route untuk mediator mengelola akun terlapor dan pelapor
Route::middleware(['auth', 'role:mediator'])->prefix('mediator')->name('mediator.')->group(function () {
    Route::prefix('akun')->name('akun.')->group(function () {
        // Route utama untuk halaman kelola akun (terlapor & pelapor)
        Route::get('/', [AkunController::class, 'index'])->name('index');

        // Route untuk terlapor 
        Route::get('/create/{pengaduan_id?}', [AkunController::class, 'create'])->name('create');
        Route::get('/{id}', [AkunController::class, 'show'])->name('show');
        Route::post('/store', [AkunController::class, 'store'])->name('store');
        Route::patch('{id}/deactivate', [AkunController::class, 'deactivate'])->name('deactivate');
        Route::patch('{id}/activate', [AkunController::class, 'activate'])->name('activate');

        // Route untuk pelapor
        Route::prefix('pelapor')->name('pelapor.')->group(function () {
            Route::get('/{id}', [AkunController::class, 'showPelapor'])->name('show');
            Route::patch('/{id}/activate', [AkunController::class, 'activatePelapor'])->name('activate');
            Route::patch('/{id}/deactivate', [AkunController::class, 'deactivatePelapor'])->name('deactivate');
        });

        // Route untuk AJAX calls (sesuai dengan JavaScript di view)
        // Terlapor AJAX routes
        Route::post('/{id}/activate', [AkunController::class, 'activate'])->name('ajax.activate');
        Route::post('/{id}/deactivate', [AkunController::class, 'deactivate'])->name('ajax.deactivate');

        // Pelapor AJAX routes
        Route::post('/pelapor/{id}/activate', [AkunController::class, 'activatePelapor'])->name('pelapor.ajax.activate');
        Route::post('/pelapor/{id}/deactivate', [AkunController::class, 'deactivatePelapor'])->name('pelapor.ajax.deactivate');
    });
});

// Routes untuk notifikasi (khusus mediator)
Route::middleware(['auth', 'verified', 'check.role:mediator'])->prefix('notifications')->name('notifications.')->group(function () {
    // Halaman index notifikasi
    Route::get('/', [NotificationController::class, 'index'])->name('index');

    // AJAX routes untuk dropdown
    Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
    Route::get('/recent', [NotificationController::class, 'getRecent'])->name('recent');

    // Actions
    Route::post('/{notificationId}/read', [NotificationController::class, 'markAsRead'])->name('read');
    Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    Route::delete('/{notificationId}', [NotificationController::class, 'delete'])->name('delete');
    Route::post('/clear-all', [NotificationController::class, 'clearAll'])->name('clear-all');
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Debug email tests
Route::get('/debug/email-test', [\App\Http\Controllers\Debug\EmailTestController::class, 'testEmail']);
Route::get('/debug/event-test', [\App\Http\Controllers\Debug\EmailTestController::class, 'testEventOnly']);
Route::get('/debug/basic-email', [\App\Http\Controllers\Debug\EmailTestController::class, 'testBasicEmail']);

// Route untuk debugging auth
Route::get('/debug-auth', function () {
    // Test 1: Cek apakah user ada
    $user = App\Models\User::where('email', 'pelapor1@example.com')->first();

    if (!$user) {
        return 'User tidak ditemukan di database';
    }

    // Test 2: Cek struktur user
    return [
        'user_exists' => true,
        'user_id' => $user->user_id,
        'email' => $user->email,
        'primary_key' => $user->getKeyName(),
        'auth_identifier' => $user->getAuthIdentifierName(),
        'auth_id' => $user->getAuthIdentifier(),
    ];
});

Route::get('/test-login', function () {
    $user = App\Models\User::where('email', 'pelapor1@example.com')->first();

    if ($user) {
        // Manual login
        Auth::login($user);

        return [
            'login_attempt' => 'success',
            'auth_check' => Auth::check(),
            'auth_id' => Auth::id(),
            'session_id' => session()->getId(),
            'user_data' => Auth::user(),
        ];
    }

    return 'User tidak ditemukan';
});

require __DIR__ . '/auth.php';
