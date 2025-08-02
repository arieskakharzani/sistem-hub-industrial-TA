# Logika Konfirmasi Jadwal

## Overview

Sistem ini sekarang memiliki logika lengkap untuk menangani konfirmasi kehadiran jadwal, termasuk deadline dan auto-handling untuk jadwal yang tidak dikonfirmasi.

## Timeline Konfirmasi

### 1. **Deadline Konfirmasi**

-   **Batas waktu**: 1 hari sebelum jadwal
-   **Contoh**: Jadwal tanggal 15 Januari 2025, deadline konfirmasi = 14 Januari 2025

### 2. **Reminder System**

-   **Kapan**: 24 jam sebelum deadline konfirmasi
-   **Frekuensi**: Setiap jam (via scheduled command)
-   **Notifikasi**: Email + In-app notification

### 3. **Auto-Handling Overdue**

-   **Kapan**: Setelah waktu jadwal lewat tanpa konfirmasi
-   **Frekuensi**: Setiap hari jam 6 pagi (via scheduled command)
-   **Aksi**: Status jadwal diubah menjadi 'dibatalkan'

## Komponen Sistem

### 1. **Model Jadwal - Method Baru**

```php
// Check deadline
$jadwal->isConfirmationDeadlinePassed()
$jadwal->isConfirmationDeadlineApproaching()
$jadwal->getConfirmationDeadline()

// Check overdue
$jadwal->isOverdue()
$jadwal->handleOverdueJadwal()
```

### 2. **Commands**

-   `jadwal:send-reminder` - Kirim reminder konfirmasi
-   `jadwal:handle-overdue` - Handle jadwal yang lewat waktu
-   `jadwal:schedule` - Setup schedule commands

### 3. **Notifications**

-   `ConfirmationReminderNotification` - Reminder email
-   Template: `emails/confirmation-reminder.blade.php`

## Alur Kerja

### **Skenario 1: Konfirmasi Normal**

1. Mediator membuat jadwal
2. Sistem kirim notifikasi ke pelapor & terlapor
3. Para pihak konfirmasi kehadiran
4. Jika semua hadir → jadwal berjalan normal
5. Jika ada yang tidak hadir → jadwal diubah menjadi 'ditunda'

### **Skenario 2: Tidak Ada Konfirmasi**

1. Mediator membuat jadwal
2. Sistem kirim notifikasi ke pelapor & terlapor
3. **24 jam sebelum deadline** → Sistem kirim reminder
4. **Deadline lewat** → Sistem kirim reminder final
5. **Waktu jadwal lewat** → Sistem auto-cancel jadwal

### **Skenario 3: Konfirmasi Sebagian**

1. Mediator membuat jadwal
2. Pelapor konfirmasi hadir, terlapor belum konfirmasi
3. **Deadline lewat** → Sistem kirim reminder ke terlapor
4. **Waktu jadwal lewat** → Sistem auto-cancel jadwal

## Status Jadwal

### **Status yang Ada:**

-   `dijadwalkan` - Jadwal dibuat, menunggu konfirmasi
-   `berlangsung` - Jadwal sedang berlangsung
-   `selesai` - Jadwal telah selesai
-   `ditunda` - Jadwal ditunda (ada yang tidak hadir)
-   `dibatalkan` - Jadwal dibatalkan (auto-cancel atau manual)

## Logging & Monitoring

### **Log Events:**

-   `🚨 Jadwal auto-cancelled due to no confirmation`
-   `📧 Reminder sent to pelapor/terlapor`
-   `⏰ Deadline approaching for jadwal`

### **Monitoring:**

-   Dashboard mediator dapat melihat jadwal yang perlu perhatian
-   Email notifications untuk mediator tentang jadwal yang dibatalkan
-   Log file untuk tracking semua events

## Setup & Deployment

### **1. Register Commands**

Tambahkan ke `app/Console/Commands/`:

-   `HandleOverdueJadwal.php`
-   `SendConfirmationReminder.php`
-   `ScheduleJadwalCommands.php`

### **2. Setup Cron Job**

```bash
# Tambahkan ke crontab
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

### **3. Test Commands**

```bash
# Test reminder
php artisan jadwal:send-reminder

# Test overdue handling
php artisan jadwal:handle-overdue

# Setup schedule
php artisan jadwal:schedule
```

## Keuntungan Sistem

### **1. Otomatis**

-   Tidak perlu intervensi manual
-   Sistem berjalan 24/7
-   Konsisten dalam penanganan

### **2. Transparan**

-   Semua pihak mendapat notifikasi
-   Log lengkap untuk audit trail
-   Status jadwal yang jelas

### **3. Efisien**

-   Menghemat waktu mediator
-   Mencegah jadwal yang tidak terkonfirmasi
-   Memastikan follow-up yang tepat

## Troubleshooting

### **Common Issues:**

1. **Email tidak terkirim** → Cek konfigurasi SMTP
2. **Command tidak jalan** → Cek cron job setup
3. **Jadwal tidak auto-cancel** → Cek timezone setting

### **Debug Commands:**

```bash
# Cek jadwal yang perlu reminder
php artisan tinker
>>> App\Models\Jadwal::where('status_jadwal', 'dijadwalkan')->where('tanggal', '>', now())->get()->filter(fn($j) => $j->isConfirmationDeadlineApproaching())

# Cek jadwal yang overdue
>>> App\Models\Jadwal::where('status_jadwal', 'dijadwalkan')->where('tanggal', '<', now())->get()->filter(fn($j) => $j->isOverdue())
```
