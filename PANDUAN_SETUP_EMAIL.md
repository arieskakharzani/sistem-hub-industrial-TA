# Panduan Setup Email untuk SIPP PHI

## Masalah Email Saat Ini

Email reminder konfirmasi tidak masuk karena konfigurasi email belum diset dengan benar.

## Solusi Setup Email

### **1. Setup untuk Production (Gmail SMTP)**

Tambahkan konfigurasi berikut ke file `.env`:

```env
# Konfigurasi Email SMTP
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="SIPP PHI Bungo"
```

### **2. Cara Dapat App Password dari Google**

1. Buka [Google Account Settings](https://myaccount.google.com/)
2. Pilih "Security" → "2-Step Verification"
3. Pilih "App passwords"
4. Generate app password untuk "Mail"
5. Copy password dan paste ke `MAIL_PASSWORD`

### **3. Setup untuk Development (Log Driver)**

Untuk development, gunakan log driver agar email tersimpan di log file:

```env
MAIL_MAILER=log
```

Email akan tersimpan di `storage/logs/laravel.log`

### **4. Test Setup Email**

Setelah setup, jalankan:

```bash
# Clear config cache
php artisan config:cache

# Test kirim email
php test_email_reminder.php

# Test command reminder
php artisan jadwal:kirim-reminder
```

## Command Baru dengan Nama Bahasa Indonesia

### **1. Kirim Reminder Konfirmasi**

```bash
php artisan jadwal:kirim-reminder
```

**Fungsi**: Kirim reminder konfirmasi kehadiran untuk jadwal

### **2. Tangani Jadwal Lewat Waktu**

```bash
php artisan jadwal:tangani-lewat-waktu
```

**Fungsi**: Tangani jadwal yang sudah lewat waktu tanpa konfirmasi

### **3. Setup Schedule Commands**

```bash
php artisan jadwal:setup-schedule
```

**Fungsi**: Setup schedule untuk menjalankan commands otomatis

## Timeline Konfirmasi Jadwal

### **Untuk Jadwal 3 Agustus 2025:**

```
Sekarang: 2 Agustus 22:08
├── ✅ Reminder dikirim (deadline lewat, jadwal belum lewat)
├── Deadline: 2 Agustus 10:00 (sudah lewat -12 jam)
├── Jadwal: 3 Agustus 10:00 (belum lewat waktu)
└── Auto-cancel: 3 Agustus 10:00+ (setelah jadwal lewat)
```

## Troubleshooting Email

### **1. Email Tidak Masuk**

-   Cek konfigurasi SMTP di `.env`
-   Pastikan app password benar
-   Cek spam folder

### **2. Error SMTP**

-   Pastikan port 587 terbuka
-   Cek firewall settings
-   Pastikan 2FA aktif di Google

### **3. Development Testing**

-   Gunakan log driver untuk testing
-   Cek email di `storage/logs/laravel.log`

## File yang Sudah Diubah ke Bahasa Indonesia

### **Commands:**

-   `KirimReminderKonfirmasi.php` (sebelumnya `SendConfirmationReminder.php`)
-   `TanganiJadwalLewatWaktu.php` (sebelumnya `HandleOverdueJadwal.php`)

### **Methods di Model Jadwal:**

-   `isConfirmationDeadlinePassed()` → Cek deadline lewat
-   `isOverdue()` → Cek jadwal lewat waktu
-   `handleOverdueJadwal()` → Tangani jadwal lewat waktu
-   `getConfirmationDeadline()` → Ambil deadline konfirmasi
-   `isConfirmationDeadlineApproaching()` → Cek deadline mendekati

## Keuntungan Setup Email

### **1. Otomatis**

-   Reminder dikirim otomatis sesuai timeline
-   Tidak perlu intervensi manual

### **2. Transparan**

-   Semua pihak mendapat notifikasi
-   Log lengkap untuk audit trail

### **3. Efisien**

-   Menghemat waktu mediator
-   Mencegah jadwal yang tidak terkonfirmasi

## Langkah Selanjutnya

1. **Setup konfigurasi email** di `.env`
2. **Test pengiriman email** dengan command baru
3. **Setup cron job** untuk otomatisasi
4. **Monitor log** untuk tracking email

## Support

Jika ada masalah dengan setup email, hubungi:

-   **Email**: nakertrans@bungokab.go.id
-   **Telepon**: (0747) 21013
