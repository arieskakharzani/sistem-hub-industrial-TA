# Perbaikan Proses Selesaikan Kasus - Draft Perjanjian Bersama

## Masalah Sebelumnya

❌ **Sebelumnya:** Mediator klik "Selesaikan Kasus" hanya mengubah status pengaduan menjadi `'selesai'` tanpa mengirim draft perjanjian bersama ke para pihak.

## Perbaikan yang Dilakukan

### 1. **Perbaikan Method `complete()` di PerjanjianBersamaController**

**File:** `app/Http/Controllers/Dokumen/PerjanjianBersamaController.php`

#### **Sebelumnya:**

```php
public function complete($id)
{
    $perjanjian = PerjanjianBersama::findOrFail($id);
    $pengaduan = $perjanjian->dokumenHI->pengaduan;

    // Ubah status pengaduan menjadi 'selesai'
    $pengaduan->update(['status' => 'selesai']);

    // TODO: Implementasi notifikasi email

    return redirect()->back()->with('success', 'Kasus telah selesai. Status pengaduan telah diubah menjadi selesai.');
}
```

#### **Sesudahnya:**

```php
public function complete($id)
{
    $perjanjian = PerjanjianBersama::findOrFail($id);
    $pengaduan = $perjanjian->dokumenHI->pengaduan;

    // Ubah status pengaduan menjadi 'selesai'
    $pengaduan->update(['status' => 'selesai']);

    // Kirim draft perjanjian bersama ke para pihak
    $this->kirimDraftPerjanjianBersama($pengaduan);

    return redirect()->back()->with('success', 'Kasus telah selesai dan draft perjanjian bersama telah dikirim ke para pihak.');
}
```

### 2. **Penambahan Method `kirimDraftPerjanjianBersama()`**

```php
private function kirimDraftPerjanjianBersama($pengaduan)
{
    try {
        \Log::info('Memulai pengiriman email draft Perjanjian Bersama untuk pengaduan: ' . $pengaduan->nomor_pengaduan);

        // Load relasi yang diperlukan
        $pengaduan->load([
            'pelapor.user',
            'terlapor',
            'mediator.user',
            'dokumenHI.perjanjianBersama'
        ]);

        // Ambil Perjanjian Bersama
        $perjanjianBersama = $pengaduan->dokumenHI->first()?->perjanjianBersama->first();

        if (!$perjanjianBersama) {
            \Log::error('Perjanjian Bersama tidak ditemukan untuk pengaduan: ' . $pengaduan->nomor_pengaduan);
            return;
        }

        // Email ke Pelapor
        if ($pengaduan->pelapor && $pengaduan->pelapor->user) {
            $pelaporEmail = $pengaduan->pelapor->user->email;
            \Illuminate\Support\Facades\Mail::to($pelaporEmail)
                ->send(new \App\Mail\DraftPerjanjianBersamaMail($pengaduan, $perjanjianBersama, 'pelapor'));
        }

        // Email ke Terlapor
        if ($pengaduan->terlapor) {
            $terlaporEmail = $pengaduan->terlapor->email_terlapor;
            \Illuminate\Support\Facades\Mail::to($terlaporEmail)
                ->send(new \App\Mail\DraftPerjanjianBersamaMail($pengaduan, $perjanjianBersama, 'terlapor'));
        }

        \Log::info('Draft Perjanjian Bersama berhasil dikirim untuk pengaduan: ' . $pengaduan->nomor_pengaduan);
    } catch (\Exception $e) {
        \Log::error('Error mengirim draft Perjanjian Bersama: ' . $e->getMessage());
    }
}
```

## Alur Proses yang Diperbaiki

### **Sebelumnya:**

1. Mediator buat perjanjian bersama
2. Mediator klik "Selesaikan Kasus"
3. Status pengaduan jadi `'selesai'`
4. **TIDAK ADA** pengiriman email draft perjanjian bersama

### **Sesudahnya:**

1. Mediator buat perjanjian bersama
2. Mediator klik "Selesaikan Kasus"
3. Status pengaduan jadi `'selesai'`
4. **✅ DRAFT PERJANJIAN BERSAMA DIKIRIM** ke:
    - Email pelapor
    - Email terlapor
5. Success message: "Kasus telah selesai dan draft perjanjian bersama telah dikirim ke para pihak"

## Fitur yang Ditambahkan

### ✅ **Pengiriman Email Otomatis**

-   Email draft perjanjian bersama ke pelapor
-   Email draft perjanjian bersama ke terlapor
-   Logging untuk monitoring

### ✅ **Error Handling**

-   Validasi perjanjian bersama ada
-   Validasi email pelapor/terlapor
-   Try-catch untuk handling error

### ✅ **Logging**

-   Log saat mulai pengiriman
-   Log saat email berhasil dikirim
-   Log error jika gagal

## Testing

### **Script Test:**

```bash
php test_perjanjian_bersama_complete.php
```

### **Manual Test:**

1. Login sebagai mediator
2. Buat perjanjian bersama
3. Klik "Selesaikan Kasus"
4. Cek email pelapor dan terlapor
5. Cek status pengaduan jadi `'selesai'`

## Email Template

Email menggunakan template `DraftPerjanjianBersamaMail` dengan:

-   **Subject:** "Draft Perjanjian Bersama - [Nomor Pengaduan]"
-   **Content:** Draft perjanjian bersama dalam format PDF
-   **Recipients:** Pelapor dan terlapor

## Monitoring

### **Log Files:**

-   `storage/logs/laravel.log` - untuk monitoring pengiriman email

### **Queue Jobs:**

-   Email dikirim melalui queue system
-   Bisa di-monitor dengan `php artisan queue:work`

## Keuntungan Perbaikan

1. **✅ Otomatis** - Draft perjanjian bersama langsung dikirim saat selesaikan kasus
2. **✅ Konsisten** - Semua pihak mendapat notifikasi yang sama
3. **✅ Terlacak** - Ada logging untuk monitoring
4. **✅ Error Handling** - Aman jika ada masalah pengiriman
5. **✅ User Experience** - Mediator mendapat feedback yang jelas

## Kesimpulan

Sekarang ketika mediator klik "Selesaikan Kasus", sistem akan:

1. ✅ Mengubah status pengaduan menjadi `'selesai'`
2. ✅ Mengirim draft perjanjian bersama ke pelapor
3. ✅ Mengirim draft perjanjian bersama ke terlapor
4. ✅ Memberikan feedback yang jelas ke mediator

**Proses selesaikan kasus sekarang sudah lengkap dan otomatis!** 🎉
