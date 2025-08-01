# Komponen PDF - Dokumentasi Penggunaan

## Overview

Komponen ini dibuat untuk memudahkan pembuatan PDF dengan kop surat dan footer yang konsisten di semua dokumen menggunakan komponen Blade modern.

## File Komponen

### 1. `pdf-layout.blade.php`

Komponen utama yang bisa digunakan seperti `<x-pdf-layout>` dengan:

-   Kop surat otomatis
-   Footer otomatis di setiap halaman
-   Margin yang konsisten
-   Page breaks yang proper
-   Responsive design

### 2. `kop-surat.blade.php`

Komponen kop surat yang berisi:

-   Logo Kabupaten Bungo
-   Nama dinas
-   Alamat lengkap
-   Informasi kontak
-   Garis pemisah di bawah

### 3. `footer.blade.php`

Komponen footer yang berisi:

-   Teks footer yang bisa dikustomisasi
-   Tanggal dan waktu approval
-   Posisi fixed di bottom setiap halaman

## Cara Penggunaan

### 1. Menggunakan Komponen PDF Layout (Recommended)

```php
<x-pdf-layout
    title="Judul Dokumen"
    header="HEADER DOKUMEN"
    footerText="Teks footer kustom"
    approvalDate="31 Juli 2025"
    approvalTime="14:30"
>
    <!-- Isi konten dokumen Anda di sini -->
    <div class="document-info">
        <p>Nomor: XXX/2025/001</p>
        <p>Lampiran: -</p>
        <p>Hal: Subject</p>
    </div>

    <div class="section">
        <div class="section-title">A. Bagian Pertama</div>
        <div class="numbered-list">
            Isi konten bagian pertama...
        </div>
    </div>

    <div class="section">
        <div class="section-title">B. Bagian Kedua</div>
        <div class="numbered-list">
            Isi konten bagian kedua...
        </div>
    </div>
</x-pdf-layout>
```

### 2. Menggunakan Komponen Terpisah

```php
<!DOCTYPE html>
<html>
<head>
    <title>Dokumen</title>
    <!-- CSS styles -->
</head>
<body>
    <div class="page">
        <!-- Kop Surat -->
        @include('components.pdf.kop-surat')

        <!-- Header -->
        <div class="header">
            <div class="text-center text-bold">JUDUL DOKUMEN</div>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Isi konten Anda di sini -->
        </div>
    </div>

    <!-- Footer -->
    @include('components.pdf.footer', [
        'footerText' => 'Teks footer kustom',
        'approvalDate' => '31 Juli 2025',
        'approvalTime' => '14:30'
    ])
</body>
</html>
```

## Parameter yang Tersedia

### PDF Layout Component Parameters:

-   `title`: Judul dokumen (untuk tag title)
-   `header`: Header yang ditampilkan di dokumen
-   `footerText`: Teks footer kustom
-   `approvalDate`: Tanggal approval
-   `approvalTime`: Waktu approval

### Footer Parameters:

-   `footerText`: Teks footer (default: teks standar)
-   `approvalDate`: Tanggal approval
-   `approvalTime`: Waktu approval (default: waktu sekarang)

## CSS Classes yang Tersedia

### Layout Classes:

-   `.page`: Container utama halaman
-   `.content`: Container konten
-   `.section`: Bagian konten dengan page break protection
-   `.header`: Header dokumen

### Text Classes:

-   `.text-center`: Teks center
-   `.text-bold`: Teks bold
-   `.section-title`: Judul section
-   `.numbered-list`: List bernomor

### Utility Classes:

-   `.page-break`: Force page break
-   `.new-page`: Halaman baru dengan margin atas
-   `.document-info`: Informasi dokumen
-   `.salutation`: Salam pembuka
-   `.recipients`: Daftar penerima

## Fitur Otomatis

1. **Kop Surat**: Otomatis muncul di setiap halaman
2. **Footer**: Otomatis muncul di bottom setiap halaman
3. **Page Breaks**: Otomatis menangani page breaks dengan margin yang proper
4. **Responsive**: Responsive design untuk berbagai ukuran layar
5. **Print Styles**: Optimized untuk printing

## Contoh Penggunaan di Controller

```php
public function generatePdf($id)
{
    $data = Model::find($id);

    return view('dokumen.pdf.nama-dokumen', [
        'data' => $data,
        'title' => 'Judul Dokumen',
        'header' => 'HEADER DOKUMEN',
        'footerText' => 'Dokumen ini dikeluarkan secara resmi...',
        'approvalDate' => Carbon::now()->translatedFormat('d F Y'),
        'approvalTime' => Carbon::now()->format('H:i')
    ]);
}
```

## Contoh Penggunaan dengan Data Dinamis

```php
<x-pdf-layout
    title="Anjuran"
    header="ANJURAN"
    :footerText="'Anjuran ini dikeluarkan secara resmi oleh Mediator Hubungan Industrial dan disetujui oleh Kepala Dinas Tenaga Kerja dan Transmigrasi Kabupaten Bungo'"
    :approvalDate="$anjuran->status_approval === 'published' ? \Carbon\Carbon::parse($anjuran->published_at)->translatedFormat('d F Y') : \Carbon\Carbon::parse($anjuran->created_at)->translatedFormat('d F Y')"
    :approvalTime="$anjuran->status_approval === 'published' ? \Carbon\Carbon::parse($anjuran->published_at)->format('H:i') : \Carbon\Carbon::parse($anjuran->created_at)->format('H:i')"
>
    <div class="document-info">
        <p style="text-align: right;">Muara Bungo, {{ \Carbon\Carbon::parse($anjuran->created_at)->translatedFormat('d F Y') }}</p>
        <p>Nomor: {{ $anjuran->nomor_anjuran }}</p>
        <p>Lampiran: -</p>
        <p>Hal: Anjuran</p>
    </div>

    <!-- Konten dokumen lainnya -->
</x-pdf-layout>
```

## Keuntungan Menggunakan Komponen

1. **Konsistensi**: Kop surat dan footer sama di semua dokumen
2. **Maintainability**: Mudah diubah di satu tempat
3. **Reusability**: Bisa digunakan untuk berbagai jenis dokumen
4. **Professional**: Layout yang profesional dan konsisten
5. **Efficient**: Tidak perlu menulis ulang CSS dan struktur yang sama
6. **Modern**: Menggunakan komponen Blade yang modern dan type-safe
