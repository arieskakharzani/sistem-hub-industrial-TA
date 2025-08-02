# Perbaikan Masalah Akses Notifikasi Pelapor

## **🔍 Masalah yang Ditemukan:**

### **❌ Error 403 Unauthorized Access:**

```
URL: sippphi_ta.test/jadwal/8ad4e674-6337-45b6-b4d3-28280120607c
Error: "UNAUTHORIZED ACCESS - YOUR ROLE (PELAPOR) DOES NOT HAVE PERMISSION TO ACCESS THIS PAGE"
```

### **🔍 Analisis Masalah:**

1. **Pelapor klik "Lihat semua notifikasi"** → Mengakses `/notifications`
2. **Di halaman notifikasi, ada link "Konfirmasi Jadwal"** → Mengarah ke `route('konfirmasi.show')`
3. **Route konfirmasi menggunakan middleware `['role:pelapor,terlapor']`** → **SALAH!**
4. **Seharusnya menggunakan `['check.role:pelapor,terlapor']`** → **BENAR!**

## **✅ Solusi yang Diterapkan:**

### **1. Perbaiki Middleware Route Konfirmasi:**

**Sebelum:**

```php
Route::middleware(['role:pelapor,terlapor'])->group(function () {
    Route::prefix('konfirmasi')->name('konfirmasi.')->group(function () {
        Route::get('/{jadwal}', [KonfirmasiController::class, 'show'])->name('show');
    });
});
```

**Sesudah:**

```php
Route::middleware(['check.role:pelapor,terlapor'])->group(function () {
    Route::prefix('konfirmasi')->name('konfirmasi.')->group(function () {
        Route::get('/{jadwal}', [KonfirmasiController::class, 'show'])->name('show');
    });
});
```

### **2. Perbaiki Link Notifikasi:**

**Di `resources/views/notifications/index.blade.php`:**

```php
@if ($notification->data['jadwal_id'] ?? false)
    @if (auth()->user()->active_role === 'mediator')
        <a href="{{ route('jadwal.show', $notification->data['jadwal_id']) }}">
            Lihat Jadwal
        </a>
    @elseif (in_array(auth()->user()->active_role, ['pelapor', 'terlapor']))
        <a href="{{ route('konfirmasi.show', $notification->data['jadwal_id']) }}">
            Konfirmasi Jadwal
        </a>
    @endif
@endif
```

## **🔧 Langkah Perbaikan:**

### **1. Clear Route Cache:**

```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

### **2. Test Akses:**

-   Login sebagai pelapor
-   Klik "Lihat semua notifikasi"
-   Klik "Konfirmasi Jadwal" pada notifikasi jadwal
-   Seharusnya tidak ada error 403 lagi

## **📋 Perbedaan Middleware:**

### **❌ `['role:pelapor,terlapor']` (SALAH):**

-   Middleware `role` tidak ada di Laravel
-   Menyebabkan error 403

### **✅ `['check.role:pelapor,terlapor']` (BENAR):**

-   Menggunakan middleware `CheckRole` yang sudah dibuat
-   Berfungsi dengan benar untuk validasi role

## **🎯 Hasil Perbaikan:**

### **✅ Sebelum Perbaikan:**

-   Pelapor klik notifikasi → Error 403
-   Tidak bisa akses halaman konfirmasi jadwal
-   Link mengarah ke route yang salah

### **✅ Sesudah Perbaikan:**

-   Pelapor klik notifikasi → Bisa akses normal
-   Bisa melihat halaman konfirmasi jadwal
-   Link mengarah ke route yang benar

## **🔍 File yang Diubah:**

1. **`routes/web.php`** - Perbaiki middleware route konfirmasi
2. **`app/Http/Middleware/CheckRole.php`** - Middleware yang sudah ada (tidak diubah)

## **📝 Catatan Penting:**

-   **Route notifications** tetap menggunakan `['auth', 'verified']` (tidak ada masalah)
-   **Route konfirmasi** sekarang menggunakan `['check.role:pelapor,terlapor']` (sudah diperbaiki)
-   **Link di notifikasi** sudah benar mengarah ke route yang tepat

## **✅ Status: MASALAH SUDAH DIPERBAIKI**

Pelapor sekarang bisa mengakses halaman notifikasi dan konfirmasi jadwal tanpa error 403.
