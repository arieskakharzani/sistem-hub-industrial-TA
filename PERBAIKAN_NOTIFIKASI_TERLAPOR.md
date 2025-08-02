# Perbaikan Masalah Notifikasi Terlapor

## **🔍 Masalah yang Ditemukan:**

### **❌ Error 403 Unauthorized Access untuk Terlapor:**

```
URL: sippphi_ta.test/jadwal/8ad4e674-6337-45b6-b4d3-28280120607c
Error: "UNAUTHORIZED ACCESS - YOUR ROLE (TERLAPOR) DOES NOT HAVE PERMISSION TO ACCESS THIS PAGE"
```

### **🔍 Analisis Masalah:**

1. **Terlapor klik notifikasi jadwal** → Mengakses `/notifications/show/{notification_id}`
2. **Method `show` di NotificationController** → Redirect ke `jadwal.show` (mediator only)
3. **Route `jadwal.show`** → Hanya bisa diakses mediator
4. **Terlapor tidak bisa akses** → Error 403

## **✅ Solusi yang Diterapkan:**

### **1. Perbaiki Method `show` di NotificationController:**

**Sebelum:**

```php
public function show(DatabaseNotification $notification)
{
    $notification->markAsRead();

    // Redirect ke jadwal jika ada
    if ($notification->data['jadwal_id'] ?? false) {
        return redirect()->route('jadwal.show', $notification->data['jadwal_id']); // ❌ SALAH!
    }

    return redirect()->route('notifications.index');
}
```

**Sesudah:**

```php
public function show(DatabaseNotification $notification)
{
    $notification->markAsRead();
    $user = Auth::user();

    // Redirect ke jadwal jika ada
    if ($notification->data['jadwal_id'] ?? false) {
        // Redirect berdasarkan role user
        if ($user->active_role === 'mediator') {
            return redirect()->route('jadwal.show', $notification->data['jadwal_id']);
        } elseif (in_array($user->active_role, ['pelapor', 'terlapor'])) {
            return redirect()->route('konfirmasi.show', $notification->data['jadwal_id']); // ✅ BENAR!
        }
    }

    return redirect()->route('notifications.index');
}
```

### **2. Perbaiki Middleware Route Konfirmasi (Sudah Diperbaiki Sebelumnya):**

```php
// Sebelum (SALAH)
Route::middleware(['role:pelapor,terlapor'])

// Sesudah (BENAR)
Route::middleware(['check.role:pelapor,terlapor'])
```

## **🔧 Langkah Perbaikan:**

### **1. Clear Cache:**

```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

### **2. Test Akses:**

-   Login sebagai terlapor
-   Klik notifikasi jadwal di dropdown
-   Seharusnya redirect ke halaman konfirmasi, bukan error 403

## **📋 Alur Notifikasi yang Diperbaiki:**

### **❌ Sebelum Perbaikan:**

```
Terlapor klik notifikasi → /notifications/show/{id} → jadwal.show → Error 403
```

### **✅ Sesudah Perbaikan:**

```
Terlapor klik notifikasi → /notifications/show/{id} → konfirmasi.show → ✅ Berhasil
```

## **🎯 Hasil Perbaikan:**

### **✅ Sebelum Perbaikan:**

-   Terlapor klik notifikasi → Error 403
-   Tidak bisa akses halaman konfirmasi jadwal
-   Redirect mengarah ke route yang salah

### **✅ Sesudah Perbaikan:**

-   Terlapor klik notifikasi → Bisa akses normal
-   Bisa melihat halaman konfirmasi jadwal
-   Redirect mengarah ke route yang benar

## **🔍 File yang Diubah:**

1. **`app/Http/Controllers/Notifikasi/NotificationController.php`** - Perbaiki method `show`
2. **`routes/web.php`** - Perbaiki middleware route konfirmasi (sudah diperbaiki sebelumnya)

## **📝 Catatan Penting:**

-   **Dropdown notifikasi** mengarah ke `notifications.show` → method `show` di NotificationController
-   **Method `show`** sekarang redirect berdasarkan role user
-   **Route konfirmasi** sudah menggunakan middleware yang benar
-   **Link di halaman notifikasi** sudah benar untuk pelapor dan terlapor

## **✅ Status: MASALAH SUDAH DIPERBAIKI**

Terlapor sekarang bisa mengakses notifikasi jadwal tanpa error 403 dan akan diarahkan ke halaman konfirmasi yang tepat.
