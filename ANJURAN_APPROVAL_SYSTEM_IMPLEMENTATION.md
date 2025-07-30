# 🚀 Sistem Approval Anjuran - Implementasi Lengkap

## 📋 **Overview**

Sistem approval anjuran telah berhasil diimplementasikan dengan workflow yang dioptimalkan:

```
1. Mediator buat anjuran → Status: 'draft'
2. Mediator submit → Status: 'pending_kepala_dinas' + Notif ke Kepala Dinas
3. Kepala Dinas approve → Status: 'approved' + Notif ke Mediator (siap publish)
4. Kepala Dinas reject → Status: 'rejected' + Notif ke Mediator (dengan alasan)
5. Mediator publish → Status: 'published' + Notif ke Para Pihak + Countdown 10 hari
```

## 🗄️ **Database Enhancement**

### **Migration: `2025_01_25_000000_enhance_anjuran_table_for_approval_system.php`**

Kolom baru yang ditambahkan ke tabel `anjuran`:

```sql
- status_approval ENUM('draft', 'pending_kepala_dinas', 'approved', 'rejected', 'published') DEFAULT 'draft'
- approved_by_kepala_dinas_at TIMESTAMP NULL
- rejected_by_kepala_dinas_at TIMESTAMP NULL
- notes_kepala_dinas TEXT NULL
- published_at TIMESTAMP NULL
- deadline_response_at TIMESTAMP NULL
```

## 📁 **Files yang Dibuat/Dimodifikasi**

### **1. Model Enhancement**

-   **File**: `app/Models/Anjuran.php`
-   **Perubahan**:
    -   Menambahkan fillable fields untuk sistem approval
    -   Menambahkan casts untuk datetime fields
    -   Menambahkan scopes: `pendingApproval()`, `approved()`, `published()`
    -   Menambahkan methods: `isPendingApproval()`, `isApproved()`, `isPublished()`, `canBeApprovedByKepalaDinas()`, `canBePublishedByMediator()`, `getDaysUntilDeadline()`
    -   Menambahkan relasi `mediator()` melalui `dokumenHI.pengaduan.mediator`

### **2. Notification Classes**

-   **File**: `app/Notifications/AnjuranPendingApprovalNotification.php`
-   **File**: `app/Notifications/AnjuranApprovedNotification.php`
-   **File**: `app/Notifications/AnjuranRejectedNotification.php`
-   **File**: `app/Notifications/AnjuranPublishedNotification.php`

Setiap notification class memiliki:

-   `via()`: Menggunakan `['database', 'mail']`
-   `toMail()`: Template email yang serasi
-   `toArray()`: Data untuk in-app notification

### **3. Controller Enhancement**

-   **File**: `app/Http/Controllers/Dokumen/AnjuranController.php`
-   **Method Baru**:
    -   `submit($anjuranId)`: Mediator submit untuk approval
    -   `approve($anjuranId)`: Kepala dinas approve
    -   `reject($anjuranId)`: Kepala dinas reject
    -   `publish($anjuranId)`: Mediator publish ke para pihak
    -   `notifyKepalaDinas($anjuran)`: Kirim notif ke kepala dinas
    -   `notifyMediatorApproved($anjuran)`: Kirim notif ke mediator saat approved
    -   `notifyMediatorRejected($anjuran, $reason)`: Kirim notif ke mediator saat rejected
    -   `sendAnjuranToParties($anjuran)`: Kirim notif ke para pihak

### **4. Email Templates**

-   **File**: `resources/views/emails/anjuran-pending-approval.blade.php`
-   **File**: `resources/views/emails/anjuran-approved.blade.php`
-   **File**: `resources/views/emails/anjuran-rejected.blade.php`
-   **File**: `resources/views/emails/anjuran-published.blade.php`

Semua template email menggunakan desain yang serasi dengan template email yang sudah ada.

### **5. View Enhancement**

-   **File**: `resources/views/dokumen/show-anjuran.blade.php`
-   **Perubahan**:
    -   Menggunakan `<x-app-layout>` untuk konsistensi
    -   Menambahkan section "Status Approval" dengan badge status
    -   Menambahkan countdown timer untuk deadline response
    -   Menambahkan action buttons sesuai role dan status
    -   Menambahkan form untuk approval/rejection dengan catatan

### **6. Routes**

-   **File**: `routes/web.php`
-   **Routes Baru**:
    ```php
    Route::post('/anjuran/{anjuran}/submit', [AnjuranController::class, 'submit'])->name('anjuran.submit');
    Route::post('/anjuran/{anjuran}/approve', [AnjuranController::class, 'approve'])->name('anjuran.approve');
    Route::post('/anjuran/{anjuran}/reject', [AnjuranController::class, 'reject'])->name('anjuran.reject');
    Route::post('/anjuran/{anjuran}/publish', [AnjuranController::class, 'publish'])->name('anjuran.publish');
    ```

## 🔄 **Workflow Detail**

### **Step 1: Mediator Buat Anjuran**

-   Status: `'draft'`
-   Mediator dapat edit anjuran
-   Belum ada notifikasi

### **Step 2: Mediator Submit untuk Approval**

-   Status: `'pending_kepala_dinas'`
-   **Notifikasi**: Email + In-app ke semua kepala dinas
-   Mediator tidak bisa edit lagi

### **Step 3A: Kepala Dinas Approve**

-   Status: `'approved'`
-   **Notifikasi**: Email + In-app ke mediator
-   Mediator dapat publish ke para pihak

### **Step 3B: Kepala Dinas Reject**

-   Status: `'rejected'`
-   **Notifikasi**: Email + In-app ke mediator dengan alasan
-   Mediator dapat edit anjuran sesuai catatan

### **Step 4: Mediator Publish ke Para Pihak**

-   Status: `'published'`
-   **Notifikasi**: Email + In-app ke pelapor dan terlapor
-   **Countdown**: 10 hari untuk response
-   Deadline response otomatis dihitung

## 🎯 **Fitur Utama**

### **1. Status Management**

-   **Draft**: Anjuran baru dibuat
-   **Pending**: Menunggu approval kepala dinas
-   **Approved**: Disetujui kepala dinas
-   **Rejected**: Ditolak kepala dinas
-   **Published**: Sudah dikirim ke para pihak

### **2. Role-Based Access Control**

-   **Mediator**: Submit, publish, edit (jika draft/rejected)
-   **Kepala Dinas**: Approve/reject dengan catatan
-   **Pelapor/Terlapor**: View only setelah published

### **3. Notification System**

-   **Email**: Template yang serasi dengan sistem
-   **In-app**: Database notifications
-   **Queue**: Menggunakan Laravel queue untuk performa

### **4. Countdown System**

-   **Deadline**: 10 hari dari publish
-   **Display**: Countdown timer di view
-   **Calculation**: Otomatis menghitung sisa hari

### **5. Audit Trail**

-   **Timestamps**: Semua aksi tercatat dengan waktu
-   **Notes**: Catatan kepala dinas tersimpan
-   **History**: Status perubahan dapat dilacak

## 🧪 **Testing**

### **Test Script**: `test_anjuran_approval_system.php`

Script untuk menguji:

-   Method helper functions
-   Notification classes
-   Workflow status changes
-   Scopes dan queries

## 🚀 **Cara Menggunakan**

### **Untuk Mediator:**

1. Buat anjuran baru
2. Klik "Submit untuk Approval"
3. Tunggu approval kepala dinas
4. Jika approved, klik "Publish ke Para Pihak"

### **Untuk Kepala Dinas:**

1. Lihat notifikasi anjuran pending
2. Review anjuran
3. Klik "Setujui Anjuran" atau "Tolak Anjuran"
4. Isi catatan jika diperlukan

### **Untuk Para Pihak:**

1. Terima notifikasi anjuran published
2. Lihat detail anjuran
3. Berikan response dalam 10 hari

## 🔧 **Konfigurasi**

### **Queue Worker**

Pastikan queue worker berjalan untuk email notifications:

```bash
php artisan queue:work
```

### **Email Configuration**

Pastikan email sudah dikonfigurasi di `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email
MAIL_FROM_NAME="SIPPPHI Kabupaten Bungo"
```

## ✅ **Status Implementasi**

-   ✅ Migration database
-   ✅ Model enhancement
-   ✅ Notification classes
-   ✅ Controller methods
-   ✅ Email templates
-   ✅ View enhancement
-   ✅ Routes
-   ✅ Testing script

## 🎉 **Kesimpulan**

Sistem approval anjuran telah berhasil diimplementasikan dengan fitur lengkap:

-   Workflow yang efisien dan logis
-   Notifikasi yang tepat untuk setiap tahap
-   UI/UX yang konsisten dengan sistem
-   Audit trail yang lengkap
-   Countdown system untuk deadline response

Sistem siap digunakan untuk mengelola approval anjuran mediator dengan workflow yang sesuai dengan kebutuhan bisnis.
