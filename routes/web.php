<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Akun\AkunController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\Jadwal\JadwalController;
use App\Http\Controllers\Debug\EmailTestController;
use App\Http\Controllers\Dokumen\AnjuranController;
use App\Http\Controllers\Dokumen\DokumenController;
use App\Http\Controllers\Laporan\LaporanController;
use App\Http\Controllers\Risalah\RisalahController;
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
        $role = $user->active_role;

        switch ($role) {
            case 'pelapor':
                return redirect()->route('dashboard.pelapor');
            case 'terlapor':
                return redirect()->route('dashboard.terlapor');
            case 'mediator':
                return redirect()->route('dashboard.mediator');
            case 'kepala_dinas':
                return redirect()->route('dashboard.kepala-dinas');
            default:
                abort(403, 'Role tidak valid: ' . $role);
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

    // Index untuk kepala dinas - mode lihat saja
    Route::get('/index-kepala-dinas', [PengaduanController::class, 'indexKepalaDinas'])
        ->middleware('check.role:kepala_dinas')
        ->name('index-kepala-dinas');

    // Show detail untuk kepala dinas - mode lihat saja
    Route::get('/show-kepala-dinas/{pengaduan:pengaduan_id}', [PengaduanController::class, 'showKepalaDinas'])
        ->middleware('check.role:kepala_dinas')
        ->name('show-kepala-dinas');

    // Show, Edit, Update, Delete - dengan authorization di controller
    Route::get('/{pengaduan:pengaduan_id}', [PengaduanController::class, 'show'])->name('show');
    Route::get('/{pengaduan:pengaduan_id}/edit', [PengaduanController::class, 'edit'])->name('edit');
    Route::put('/{pengaduan:pengaduan_id}', [PengaduanController::class, 'update'])->name('update');
    Route::delete('/{pengaduan:pengaduan_id}', [PengaduanController::class, 'destroy'])->name('destroy');

    // Actions untuk mediator - juga menggunakan pengaduan_id
    Route::post('/{pengaduan:pengaduan_id}/update-status', [PengaduanController::class, 'updateStatus'])->name('updateStatus');
    Route::post('/{pengaduan:pengaduan_id}/assign', [PengaduanController::class, 'assign'])->name('assign');
});

// Route untuk notifikasi pengaduan baru ke terlapor yang sudah ada
Route::post('/pengaduan/{pengaduan}/notify-existing-terlapor', [PengaduanController::class, 'notifyExistingTerlapor'])
    ->name('pengaduan.notify-existing-terlapor')
    ->middleware(['auth', 'role:mediator']);

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

// Routes untuk Konfirmasi Kehadiran jadwal
Route::middleware(['auth', 'verified'])->group(function () {

    // Routes khusus untuk Pelapor dan Terlapor
    Route::middleware(['check.role:pelapor,terlapor'])->group(function () {

        // Konfirmasi jadwal - menggunakan view di folder Jadwal/
        Route::prefix('konfirmasi')->name('konfirmasi.')->group(function () {
            Route::get('/', [KonfirmasiController::class, 'index'])->name('index'); // View: Jadwal/konfirmasi-index.blade.php
            Route::get('/{jadwal}', [KonfirmasiController::class, 'show'])->name('show'); // View: Jadwal/konfirmasi-show.blade.php
            Route::post('/{jadwal}/konfirmasi', [KonfirmasiController::class, 'konfirmasi'])->name('konfirmasi');
            Route::delete('/{jadwal}/cancel', [KonfirmasiController::class, 'cancel'])->name('cancel');
        });
    });
});

// Routes untuk penyelesaian
Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        // Route::post('/publish-anjuran/{anjuran}', [PenyelesaianController::class, 'publishAnjuran'])->name('publish-anjuran');
    });
});

// Penyelesaian - finalize dokumen (kirim final)
// Route::middleware(['auth', 'verified'])->post('/penyelesaian/finalize', [PenyelesaianController::class, 'finalizeDocument'])->name('penyelesaian.finalize');

// Routes untuk kelola dokumen
Route::middleware(['auth', 'verified'])->prefix('dokumen')->name('dokumen.')->group(function () {
    Route::get('/', [DokumenController::class, 'dokumenIndex'])->name('index');
    // Perjanjian Bersama
    Route::get('/perjanjian-bersama/create/{dokumen_hi_id}', [\App\Http\Controllers\Dokumen\PerjanjianBersamaController::class, 'create'])->name('perjanjian-bersama.create');
    Route::post('/perjanjian-bersama/store', [\App\Http\Controllers\Dokumen\PerjanjianBersamaController::class, 'store'])->name('perjanjian-bersama.store');
    Route::get('/perjanjian-bersama/{id}', [\App\Http\Controllers\Dokumen\PerjanjianBersamaController::class, 'show'])->name('perjanjian-bersama.show');
    Route::get('/perjanjian-bersama/{id}/edit', [\App\Http\Controllers\Dokumen\PerjanjianBersamaController::class, 'edit'])->name('perjanjian-bersama.edit');
    Route::put('/perjanjian-bersama/{id}', [\App\Http\Controllers\Dokumen\PerjanjianBersamaController::class, 'update'])->name('perjanjian-bersama.update');
    Route::delete('/perjanjian-bersama/{id}', [\App\Http\Controllers\Dokumen\PerjanjianBersamaController::class, 'destroy'])->name('perjanjian-bersama.destroy');
    Route::get('/perjanjian-bersama/{id}/pdf', [\App\Http\Controllers\Dokumen\PerjanjianBersamaController::class, 'cetakPdf'])->name('perjanjian-bersama.pdf');

    Route::post('/perjanjian-bersama/{id}/complete', [\App\Http\Controllers\Dokumen\PerjanjianBersamaController::class, 'complete'])->name('perjanjian-bersama.complete');

    // Route khusus untuk pelapor dan terlapor melihat perjanjian bersama
    Route::get('/show-perjanjian-bersama/{id}', [\App\Http\Controllers\Dokumen\PerjanjianBersamaController::class, 'show'])->name('show-perjanjian-bersama');

    // Anjuran
    Route::get('/anjuran/create/{dokumen_hi_id}', [\App\Http\Controllers\Dokumen\AnjuranController::class, 'create'])->name('anjuran.create');
    Route::post('/anjuran/store', [\App\Http\Controllers\Dokumen\AnjuranController::class, 'store'])->name('anjuran.store');

    Route::post('/anjuran/{id}/submit', [\App\Http\Controllers\Dokumen\AnjuranController::class, 'submit'])->name('anjuran.submit');
    Route::post('/anjuran/{id}/approve', [\App\Http\Controllers\Dokumen\AnjuranController::class, 'approve'])->name('anjuran.approve');
    Route::post('/anjuran/{id}/reject', [\App\Http\Controllers\Dokumen\AnjuranController::class, 'reject'])->name('anjuran.reject');
    Route::post('/anjuran/{id}/publish', [\App\Http\Controllers\Dokumen\AnjuranController::class, 'publish'])->name('anjuran.publish');
    Route::post('/anjuran/{id}/finalize-case', [\App\Http\Controllers\Dokumen\AnjuranController::class, 'finalizeCase'])->name('anjuran.finalize-case');

    // Route khusus untuk pending approval anjuran 
    Route::get('/anjuran/pending-approval', [AnjuranController::class, 'pendingApproval'])->name('anjuran.pending-approval');

    // Route untuk show anjuran
    Route::get('/anjuran/{id}', [\App\Http\Controllers\Dokumen\AnjuranController::class, 'show'])->name('anjuran.show');

    Route::get('/anjuran/{id}/edit', [\App\Http\Controllers\Dokumen\AnjuranController::class, 'edit'])->name('anjuran.edit');
    Route::put('/anjuran/{id}', [\App\Http\Controllers\Dokumen\AnjuranController::class, 'update'])->name('anjuran.update');
    Route::delete('/anjuran/{id}', [\App\Http\Controllers\Dokumen\AnjuranController::class, 'destroy'])->name('anjuran.destroy');
    Route::get('/anjuran/{id}/pdf', [\App\Http\Controllers\Dokumen\AnjuranController::class, 'cetakPdf'])->name('anjuran.pdf');

    // Risalah - menggunakan ID untuk konsistensi
    Route::delete('/risalah/{id}', [\App\Http\Controllers\Risalah\RisalahController::class, 'destroy'])->name('risalah.destroy');
});

// Routes untuk respon anjuran dari pelapor dan terlapor
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/anjuran-response/pelapor', [\App\Http\Controllers\AnjuranResponseController::class, 'indexPelapor'])->name('anjuran-response.index-pelapor');
    Route::get('/anjuran-response/terlapor', [\App\Http\Controllers\AnjuranResponseController::class, 'indexTerlapor'])->name('anjuran-response.index-terlapor');
    Route::get('/anjuran-response/{id}', [\App\Http\Controllers\AnjuranResponseController::class, 'show'])->name('anjuran-response.show');
    Route::post('/anjuran-response/{id}/submit', [\App\Http\Controllers\AnjuranResponseController::class, 'submitResponse'])->name('anjuran-response.submit');
});

// Route untuk mediator mengelola akun terlapor dan pelapor
Route::middleware(['auth', 'verified', 'check.role:mediator'])->prefix('mediator')->name('mediator.')->group(function () {
    Route::prefix('akun')->name('akun.')->group(function () {
        // Route utama untuk halaman kelola akun (terlapor & pelapor)
        Route::get('/', [AkunController::class, 'index'])->name('index');

        // Route untuk terlapor 
        Route::get('/create/{pengaduan_id?}', [AkunController::class, 'create'])->name('create');
        Route::get('/{id}', [AkunController::class, 'show'])->name('show');
        Route::post('/store', [AkunController::class, 'store'])->name('store');
        Route::patch('/{id}/deactivate', [AkunController::class, 'deactivate'])->name('deactivate');
        Route::patch('/{id}/activate', [AkunController::class, 'activate'])->name('activate');

        // Route untuk pelapor
        Route::prefix('pelapor')->name('pelapor.')->group(function () {
            Route::get('/{id}', [AkunController::class, 'showPelapor'])->name('show');
            Route::patch('/{id}/activate', [AkunController::class, 'activatePelapor'])->name('activate');
            Route::patch('/{id}/deactivate', [AkunController::class, 'deactivatePelapor'])->name('deactivate');
        });
    });
});

// Routes untuk notifikasi (semua role)
Route::middleware(['auth', 'verified'])->prefix('notifications')->name('notifications.')->group(function () {
    // Halaman index notifikasi
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::get('/{notification}', [NotificationController::class, 'show'])->name('show');

    // AJAX routes untuk dropdown
    Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
    Route::get('/recent', [NotificationController::class, 'getRecent'])->name('recent');

    // Actions
    Route::post('/{notificationId}/read', [NotificationController::class, 'markAsRead'])->name('markAsRead');
    Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
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
// Route::get('/debug/email-test', [\App\Http\Controllers\Debug\EmailTestController::class, 'testEmail']);
// Route::get('/debug/event-test', [\App\Http\Controllers\Debug\EmailTestController::class, 'testEventOnly']);
// Route::get('/debug/basic-email', [\App\Http\Controllers\Debug\EmailTestController::class, 'testBasicEmail']);

// Role Selection Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/role-selection', [RoleController::class, 'showSelection'])->name('role.selection');
    Route::post('/role-switch', [RoleController::class, 'switch'])->name('role.switch');
});

// Routes untuk role selection
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard/role-selection', [DashboardController::class, 'roleSelection'])
        ->name('dashboard.role-selection');
    Route::post('/dashboard/set-role', [DashboardController::class, 'setRole'])
        ->name('dashboard.set-role');
});

// Risalah Routes
Route::middleware(['auth', 'verified'])->prefix('risalah')->name('risalah.')->group(function () {
    Route::get('/create/{jadwal}/{jenis_risalah}', [RisalahController::class, 'create'])->name('create');
    Route::post('/store/{jadwal}/{jenis_risalah}', [RisalahController::class, 'store'])->name('store');
    Route::get('/{id}', [RisalahController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [RisalahController::class, 'edit'])->name('edit');
    Route::put('/{id}', [RisalahController::class, 'update'])->name('update');
    Route::delete('/{id}', [RisalahController::class, 'destroy'])->name('destroy');
    Route::get('/{id}/pdf', [RisalahController::class, 'exportPDF'])->name('pdf');
    Route::get('/{id}/pdf-preview', [RisalahController::class, 'previewPDF'])->name('pdf.preview');
    Route::get('/{id}/pdf-download', [RisalahController::class, 'downloadPDF'])->name('pdf.download');
});

// Laporan Routes
Route::middleware(['auth', 'verified'])->prefix('laporan')->name('laporan.')->group(function () {
    Route::get('/', [LaporanController::class, 'index'])->name('index');

    // Laporan Hasil Mediasi - untuk semua role
    Route::get('/hasil-mediasi', [LaporanController::class, 'laporanHasilMediasi'])->name('hasil-mediasi');
    Route::get('/hasil-mediasi/{pengaduan}', [LaporanController::class, 'showLaporanHasilMediasi'])->name('hasil-mediasi.show');
    Route::get('/hasil-mediasi/{laporan}/cetak-pdf', [LaporanController::class, 'cetakPdfLaporanHasilMediasi'])->name('hasil-mediasi.cetak-pdf');

    // Buku Register Perselisihan - hanya untuk mediator dan kepala dinas
    Route::middleware(['check.role:mediator,kepala_dinas'])->group(function () {
        Route::get('/buku-register', [LaporanController::class, 'bukuRegisterPerselisihan'])->name('buku-register');
        Route::get('/buku-register/{id}', [LaporanController::class, 'showBukuRegister'])->name('buku-register.show');
    });

    // Routes lama (untuk backward compatibility)
    Route::get('/pihak-terkait', [LaporanController::class, 'laporanPihakTerkait'])->name('pihak-terkait');
    Route::get('/kasus-selesai', [LaporanController::class, 'laporanKasusSelesai'])->name('kasus-selesai');
    Route::get('/pengadilan-hi', [LaporanController::class, 'laporanPengadilanHI'])->name('pengadilan-hi');
    Route::get('/generate-pdf/{pengaduan}', [LaporanController::class, 'generateLaporanPDF'])->name('generate-pdf');
});

// Route untuk testing email
// Route::get('/test-email', function () {
//     try {
//         Mail::to('ecakharzani10@gmail.com')->queue(new \App\Mail\TestMail());
//         return 'Email test telah dikirim ke antrian. Silakan cek email Anda dalam beberapa saat.';
//     } catch (\Exception $e) {
//         return 'Error: ' . $e->getMessage();
//     }
// });

// Route untuk debugging auth
// Route::get('/debug-auth', function () {
//     // Test 1: Cek apakah user ada
//     $user = App\Models\User::where('email', 'pelapor1@example.com')->first();

//     if (!$user) {
//         return 'User tidak ditemukan di database';
//     }

//     // Test 2: Cek struktur user
//     return [
//         'user_exists' => true,
//         'user_id' => $user->user_id,
//         'email' => $user->email,
//         'primary_key' => $user->getKeyName(),
//         'auth_identifier' => $user->getAuthIdentifierName(),
//         'auth_id' => $user->getAuthIdentifier(),
//     ];
// });

// Route::get('/test-login', function () {
//     $user = App\Models\User::where('email', 'pelapor1@example.com')->first();

//     if ($user) {
//         // Manual login
//         Auth::login($user);

//         return [
//             'login_attempt' => 'success',
//             'auth_check' => Auth::check(),
//             'auth_id' => Auth::id(),
//             'session_id' => session()->getId(),
//             'user_data' => Auth::user(),
//         ];
//     }

//     return 'User tidak ditemukan';
// });

//Test Listener
// Route::get('/test-simple-mail', function () {
//     try {
//         Log::info('🧪 Testing simple mail to arieskaeeca@gmail.com');

//         Mail::raw('Test email sederhana dari sistem - ' . now(), function ($message) {
//             $message->to('arieskaeeca@gmail.com') // test ke email yang sama dengan sender
//                 ->subject('Test Email Simple - ' . now());
//         });

//         Log::info('✅ Simple mail sent successfully');
//         return 'Simple email sent! Check inbox and spam folder.';
//     } catch (\Exception $e) {
//         Log::error('❌ Simple mail failed: ' . $e->getMessage());
//         return 'Email failed: ' . $e->getMessage();
//     }
// });

require __DIR__ . '/auth.php';
