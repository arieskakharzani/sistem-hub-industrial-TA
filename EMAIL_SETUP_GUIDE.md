# Panduan Setup Email SMTP untuk SIPPPHI

## Status Konfigurasi Saat Ini

✅ **config/mail.php** sudah diubah:

-   Default mailer: `'smtp'` (sebelumnya `'log'`)
-   SMTP configuration sudah siap

## Langkah-langkah Setup Email

### 1. Buat File .env

Buat file `.env` di root project dengan konfigurasi berikut:

```env
APP_NAME="SIPPPHI - Kabupaten Bungo"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sippphi_ta
DB_USERNAME=root
DB_PASSWORD=

# Queue Configuration
QUEUE_CONNECTION=database

# Email Configuration - Pilih salah satu opsi di bawah:

# OPSI A: Gmail SMTP (Recommended)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your_email@gmail.com"
MAIL_FROM_NAME="SIPPPHI - Kabupaten Bungo"

# OPSI B: Mailtrap (Untuk Testing)
# MAIL_MAILER=smtp
# MAIL_HOST=smtp.mailtrap.io
# MAIL_PORT=2525
# MAIL_USERNAME=your_mailtrap_username
# MAIL_PASSWORD=your_mailtrap_password
# MAIL_ENCRYPTION=tls
# MAIL_FROM_ADDRESS="noreply@sippphi.test"
# MAIL_FROM_NAME="SIPPPHI - Kabupaten Bungo"
```

### 2. Setup Gmail SMTP (Opsi A)

#### Langkah 2.1: Aktifkan 2-Factor Authentication

1. Buka [Google Account Settings](https://myaccount.google.com/)
2. Pilih "Security"
3. Aktifkan "2-Step Verification"

#### Langkah 2.2: Buat App Password

1. Di Google Account Settings → Security
2. Pilih "2-Step Verification" → "App passwords"
3. Generate password untuk "Mail"
4. Copy password yang dihasilkan

#### Langkah 2.3: Update .env

```env
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password_here
```

### 3. Setup Mailtrap (Opsi B)

#### Langkah 3.1: Daftar Mailtrap

1. Kunjungi [https://mailtrap.io](https://mailtrap.io)
2. Daftar akun gratis
3. Buat inbox baru

#### Langkah 3.2: Update .env

```env
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
```

### 4. Generate App Key

```bash
php artisan key:generate
```

### 5. Test Konfigurasi

#### Test Email SMTP:

```bash
php test_email_smtp.php
```

#### Test Queue Worker:

```bash
php test_queue_worker.php
```

#### Test Queue Worker (Laravel):

```bash
php artisan queue:work --once
```

### 6. Jalankan Queue Worker

Untuk production, jalankan queue worker secara terus menerus:

```bash
php artisan queue:work
```

Atau untuk development:

```bash
php artisan queue:work --once
```

## Troubleshooting

### Masalah 1: Email tidak terkirim

**Solusi:**

1. Periksa konfigurasi SMTP di `.env`
2. Pastikan username dan password benar
3. Untuk Gmail, pastikan menggunakan App Password
4. Test dengan script `test_email_smtp.php`

### Masalah 2: Notifikasi tidak muncul

**Solusi:**

1. Pastikan queue worker berjalan: `php artisan queue:work`
2. Periksa tabel `jobs` di database
3. Periksa log di `storage/logs/laravel.log`

### Masalah 3: Mediator tidak menerima notifikasi

**Solusi:**

1. Pastikan mediator memiliki `is_active = true`
2. Pastikan mediator memiliki email yang valid
3. Periksa apakah event `PengaduanCreated` terpanggil

## Verifikasi Setup

### 1. Cek Konfigurasi Mail

```php
php artisan tinker
>>> config('mail.default')
>>> config('mail.mailers.smtp')
```

### 2. Cek Mediator Aktif

```php
php artisan tinker
>>> App\Models\Mediator::with('user')->whereHas('user', fn($q) => $q->where('is_active', true))->get()
```

### 3. Test Kirim Email

```php
php artisan tinker
>>> Mail::raw('Test email', function($message) { $message->to('test@example.com')->subject('Test'); });
```

## Notifikasi yang Akan Dikirim

1. **Pengaduan Baru** → Mediator
2. **Jadwal Baru** → Pelapor & Terlapor
3. **Konfirmasi Kehadiran** → Mediator
4. **Reschedule Required** → Mediator
5. **Draft Perjanjian Bersama** → Pelapor & Terlapor

## Monitoring

### Log Email

-   Email yang dikirim akan di-log di `storage/logs/laravel.log`
-   Untuk Mailtrap, email akan masuk ke inbox Mailtrap

### Queue Monitoring

-   Pending jobs: `php artisan queue:failed`
-   Failed jobs: `php artisan queue:failed`
-   Clear failed jobs: `php artisan queue:flush`
