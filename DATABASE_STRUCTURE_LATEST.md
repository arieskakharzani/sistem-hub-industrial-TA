# Struktur Database SIPPPHI - Sistem Terbaru

## Overview Sistem

Sistem SIPPPHI (Sistem Informasi Pengaduan dan Penyelesaian Hubungan Industrial) adalah sistem manajemen pengaduan hubungan industrial dengan fitur mediator self-registration dan approval system.

## Tabel Utama

### 1. **users** - Tabel User Utama

```sql
CREATE TABLE users (
    user_id UUID PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    roles JSON NOT NULL,                    -- Array roles: ['mediator', 'pelapor', 'terlapor', 'kepala_dinas']
    active_role VARCHAR(255) NULL,          -- Role aktif saat ini
    is_active BOOLEAN DEFAULT TRUE,         -- Status aktif/nonaktif akun
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

**Keterangan:**

-   `roles`: JSON array berisi semua role yang dimiliki user
-   `active_role`: Role yang sedang aktif digunakan
-   `is_active`: Status akun (true = aktif, false = nonaktif)

### 2. **mediator** - Tabel Mediator (DENGAN FITUR BARU)

```sql
CREATE TABLE mediator (
    mediator_id UUID PRIMARY KEY,
    user_id UUID NOT NULL,
    nama_mediator VARCHAR(255) NOT NULL,
    nip VARCHAR(255) NOT NULL,

    -- FIELD BARU: SK dan Approval System
    sk_file_path VARCHAR(500) NULL,         -- Path file SK di storage
    sk_file_name VARCHAR(255) NULL,         -- Nama file SK asli
    sk_file_size INTEGER NULL,              -- Ukuran file SK (bytes)
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    approved_by UUID NULL,                  -- User ID yang approve
    approved_at TIMESTAMP NULL,             -- Tanggal approval
    rejection_reason TEXT NULL,             -- Alasan penolakan
    rejection_date TIMESTAMP NULL,          -- Tanggal penolakan

    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(user_id) ON DELETE SET NULL
);
```

**Fitur Baru:**

-   **SK Upload**: Upload dokumen Surat Keterangan pengangkatan mediator
-   **Status Tracking**: pending → approved/rejected
-   **Approval System**: Kepala Dinas dapat approve/reject
-   **Audit Trail**: Siapa yang approve dan kapan

### 3. **pelapor** - Tabel Pelapor

```sql
CREATE TABLE pelapor (
    pelapor_id UUID PRIMARY KEY,
    user_id UUID NOT NULL,
    nama_pelapor VARCHAR(255) NOT NULL,
    tempat_lahir VARCHAR(255) NOT NULL,
    tanggal_lahir DATE NOT NULL,
    jenis_kelamin ENUM('Laki-laki', 'Perempuan') NOT NULL,
    alamat TEXT NOT NULL,
    no_hp VARCHAR(255) NOT NULL,
    perusahaan VARCHAR(255) NOT NULL,
    npk VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);
```

### 4. **terlapor** - Tabel Terlapor

```sql
CREATE TABLE terlapor (
    terlapor_id UUID PRIMARY KEY,
    user_id UUID NULL,                      -- Nullable karena tidak semua terlapor punya akun
    nama_terlapor VARCHAR(255) NOT NULL,
    alamat_kantor_cabang TEXT NOT NULL,
    email_terlapor VARCHAR(100) NOT NULL,
    no_hp_terlapor VARCHAR(15) NULL,

    -- Status dan Tracking
    has_account BOOLEAN DEFAULT FALSE,      -- Flag apakah punya akun
    is_active BOOLEAN DEFAULT TRUE,         -- Status aktif/nonaktif
    account_created_at TIMESTAMP NULL,     -- Kapan akun dibuat
    last_login_at TIMESTAMP NULL,          -- Terakhir login

    -- Mediator yang mengelola
    created_by_mediator_id UUID NULL,

    -- Tracking Pengaduan
    total_pengaduan INTEGER DEFAULT 0,    -- Jumlah pengaduan
    last_pengaduan_at TIMESTAMP NULL,      -- Pengaduan terakhir

    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,              -- Soft delete

    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL,
    FOREIGN KEY (created_by_mediator_id) REFERENCES mediator(mediator_id) ON DELETE SET NULL,
    UNIQUE KEY terlapor_company_unique (nama_terlapor, email_terlapor)
);
```

### 5. **kepala_dinas** - Tabel Kepala Dinas

```sql
CREATE TABLE kepala_dinas (
    kepala_dinas_id UUID PRIMARY KEY,
    user_id UUID NOT NULL,
    nama_kepala_dinas VARCHAR(255) NOT NULL,
    nip VARCHAR(255) NOT NULL,
    jabatan VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);
```

## Tabel Sistem Utama

### 6. **pengaduan** - Tabel Pengaduan

```sql
CREATE TABLE pengaduan (
    pengaduan_id UUID PRIMARY KEY,
    nomor_pengaduan VARCHAR(255) UNIQUE NOT NULL,
    pelapor_id UUID NOT NULL,
    terlapor_id UUID NOT NULL,
    mediator_id UUID NOT NULL,
    status ENUM('pending', 'proses', 'selesai', 'ditolak') DEFAULT 'pending',
    jenis_perselisihan VARCHAR(255) NOT NULL,
    deskripsi_perselisihan TEXT NOT NULL,
    tanggal_pengaduan TIMESTAMP NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (pelapor_id) REFERENCES pelapor(pelapor_id) ON DELETE CASCADE,
    FOREIGN KEY (terlapor_id) REFERENCES terlapor(terlapor_id) ON DELETE CASCADE,
    FOREIGN KEY (mediator_id) REFERENCES mediator(mediator_id) ON DELETE CASCADE
);
```

### 7. **jadwal** - Tabel Jadwal Mediasi

```sql
CREATE TABLE jadwal (
    jadwal_id UUID PRIMARY KEY,
    pengaduan_id UUID NOT NULL,
    mediator_id UUID NOT NULL,
    nomor_jadwal VARCHAR(255) UNIQUE NOT NULL,
    tanggal_jadwal DATE NOT NULL,
    waktu_mulai TIME NOT NULL,
    waktu_selesai TIME NOT NULL,
    tempat_mediasi VARCHAR(255) NOT NULL,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    sidang_ke ENUM('1', '2', '3', '4', '5') DEFAULT '1',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (pengaduan_id) REFERENCES pengaduan(pengaduan_id) ON DELETE CASCADE,
    FOREIGN KEY (mediator_id) REFERENCES mediator(mediator_id) ON DELETE CASCADE
);
```

## Tabel Notifikasi

### 8. **notifications** - Tabel Notifikasi

```sql
CREATE TABLE notifications (
    id CHAR(36) PRIMARY KEY,
    type VARCHAR(255) NOT NULL,
    notifiable_type VARCHAR(255) NOT NULL,
    notifiable_id BIGINT UNSIGNED NOT NULL,
    data TEXT NOT NULL,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    INDEX notifiable (notifiable_type, notifiable_id)
);
```

## Tabel Dokumen dan Laporan

### 9. **anjuran** - Tabel Anjuran

```sql
CREATE TABLE anjuran (
    anjuran_id UUID PRIMARY KEY,
    pengaduan_id UUID NOT NULL,
    mediator_id UUID NOT NULL,
    nomor_anjuran VARCHAR(255) UNIQUE NOT NULL,
    tanggal_anjuran DATE NOT NULL,
    isi_anjuran TEXT NOT NULL,
    status_approval ENUM('pending_kepala_dinas', 'approved', 'rejected') DEFAULT 'pending_kepala_dinas',
    approved_by UUID NULL,
    approved_at TIMESTAMP NULL,
    rejection_reason TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (pengaduan_id) REFERENCES pengaduan(pengaduan_id) ON DELETE CASCADE,
    FOREIGN KEY (mediator_id) REFERENCES mediator(mediator_id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(user_id) ON DELETE SET NULL
);
```

### 10. **perjanjian_bersama** - Tabel Perjanjian Bersama

```sql
CREATE TABLE perjanjian_bersama (
    perjanjian_id UUID PRIMARY KEY,
    pengaduan_id UUID NOT NULL,
    mediator_id UUID NOT NULL,
    nomor_perjanjian VARCHAR(255) UNIQUE NOT NULL,
    tanggal_perjanjian DATE NOT NULL,
    isi_perjanjian TEXT NOT NULL,
    status ENUM('draft', 'final', 'signed') DEFAULT 'draft',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (pengaduan_id) REFERENCES pengaduan(pengaduan_id) ON DELETE CASCADE,
    FOREIGN KEY (mediator_id) REFERENCES mediator(mediator_id) ON DELETE CASCADE
);
```

## Workflow Sistem Baru

### 1. **Mediator Self-Registration Workflow**

```
1. Mediator baru mengakses /register/mediator
2. Mengisi form: nama, NIP, email, upload SK
3. Sistem validasi: PDF max 5MB, NIP unik, email unik
4. Data disimpan dengan status 'pending'
5. Notifikasi dikirim ke Kepala Dinas
6. Kepala Dinas review dan approve/reject
7. Jika approved: kredensial login dikirim ke email mediator
8. Jika rejected: notifikasi dengan alasan dikirim ke mediator
```

### 2. **Status Mediator**

-   **pending**: Menunggu approval Kepala Dinas
-   **approved**: Sudah disetujui, bisa login
-   **rejected**: Ditolak, bisa registrasi ulang

### 3. **File Storage**

-   **SK Files**: Disimpan di `storage/app/public/sk_mediator/`
-   **Naming**: `sk_{timestamp}_{random}.pdf`
-   **Security**: Hanya Kepala Dinas yang bisa download

## Relasi Antar Tabel

### Foreign Key Relationships

```
users (1) ←→ (1) mediator
users (1) ←→ (1) pelapor
users (1) ←→ (1) terlapor
users (1) ←→ (1) kepala_dinas

mediator (1) ←→ (n) pengaduan
pelapor (1) ←→ (n) pengaduan
terlapor (1) ←→ (n) pengaduan

pengaduan (1) ←→ (n) jadwal
pengaduan (1) ←→ (1) anjuran
pengaduan (1) ←→ (1) perjanjian_bersama

users (1) ←→ (n) mediator.approved_by
users (1) ←→ (n) anjuran.approved_by
```

## Index dan Constraints

### Unique Constraints

-   `users.email` - Email unik
-   `mediator.nip` - NIP mediator unik
-   `pengaduan.nomor_pengaduan` - Nomor pengaduan unik
-   `jadwal.nomor_jadwal` - Nomor jadwal unik
-   `anjuran.nomor_anjuran` - Nomor anjuran unik
-   `perjanjian_bersama.nomor_perjanjian` - Nomor perjanjian unik
-   `terlapor_company_unique` - Kombinasi nama dan email terlapor unik

### Indexes

-   `notifications.notifiable` - Untuk query notifikasi
-   Foreign key indexes otomatis dibuat oleh Laravel

## Data Types dan Constraints

### UUID Usage

-   Semua primary key menggunakan UUID
-   Foreign key menggunakan UUID
-   Menggunakan `Str::uuid()` untuk generate

### Enum Values

-   `mediator.status`: 'pending', 'approved', 'rejected'
-   `pengaduan.status`: 'pending', 'proses', 'selesai', 'ditolak'
-   `jadwal.status`: 'pending', 'confirmed', 'completed', 'cancelled'
-   `anjuran.status_approval`: 'pending_kepala_dinas', 'approved', 'rejected'

### Timestamps

-   Semua tabel memiliki `created_at` dan `updated_at`
-   Field khusus: `approved_at`, `rejection_date`, `last_login_at`

## Security Features

### File Upload Security

-   Validasi file type: PDF only
-   Validasi file size: max 5MB
-   Secure file naming dengan timestamp dan random string
-   File disimpan di storage yang tidak accessible langsung

### Access Control

-   Role-based access control (RBAC)
-   Middleware `check.role` untuk proteksi route
-   Soft delete untuk data sensitif

### Data Validation

-   Unique constraints untuk data kritis
-   Foreign key constraints untuk referential integrity
-   Enum constraints untuk status values

## Migration History

### Key Migrations

1. `2025_05_31_115532_refactor_users_table.php` - Refactor users table
2. `2025_05_31_115800_create_mediator_table.php` - Create mediator table
3. `2025_10_18_144656_add_sk_approval_fields_to_mediator_table.php` - **FITUR BARU**
4. `2025_01_25_000000_enhance_anjuran_table_for_approval_system.php` - Approval system

### Latest Changes

-   **Mediator Self-Registration**: Upload SK, approval workflow
-   **Status Tracking**: pending → approved/rejected
-   **Audit Trail**: Who approved, when, why rejected
-   **File Management**: Secure SK file storage and download
-   **Notification System**: Email notifications for all parties

## Performance Considerations

### Indexing Strategy

-   Primary keys: UUID dengan index otomatis
-   Foreign keys: Index otomatis untuk join performance
-   Unique constraints: Index untuk data integrity
-   Composite indexes: Untuk query yang sering digunakan

### Query Optimization

-   Eager loading dengan `with()` untuk relationship
-   Pagination untuk data besar
-   Soft delete untuk data recovery

## Backup and Recovery

### Data Backup

-   Regular database backup
-   File storage backup untuk SK files
-   Migration rollback capability

### Data Recovery

-   Soft delete untuk data recovery
-   Migration down methods
-   File storage recovery procedures

---

**Catatan**: Struktur ini mencerminkan sistem terbaru dengan fitur mediator self-registration dan approval system yang telah diimplementasikan.
