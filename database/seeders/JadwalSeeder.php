<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\Jadwal;
use App\Models\Pengaduan;

class JadwalSeeder extends Seeder
{
    public function run()
    {
        // Kasus 1: Ecak Harzani - Upah Lembur
        $pengaduan1 = Pengaduan::where('narasi_kasus', 'like', '%Upah lembur selama 3 bulan%')->first();
        if ($pengaduan1) {
            // Jadwal Klarifikasi
            Jadwal::create([
                'jadwal_id' => Str::uuid(),
                'pengaduan_id' => $pengaduan1->pengaduan_id,
                'mediator_id' => $pengaduan1->mediator_id,
                'tanggal' => Carbon::now()->addDays(3),
                'waktu' => '09:00:00',
                'tempat' => 'Ruang Sidang Mediasi 1',
                'jenis_jadwal' => 'klarifikasi',
                'status_jadwal' => 'selesai',
                'catatan_jadwal' => 'Perlu membawa dokumen pendukung perhitungan lembur',
                'hasil' => 'Perlu dilanjutkan ke tahap mediasi',
                'konfirmasi_pelapor' => 'hadir',
                'konfirmasi_terlapor' => 'hadir',
                'tanggal_konfirmasi_pelapor' => Carbon::now(),
                'tanggal_konfirmasi_terlapor' => Carbon::now(),
                'catatan_konfirmasi_pelapor' => 'Akan membawa dokumen lengkap',
                'catatan_konfirmasi_terlapor' => 'Akan membawa perhitungan dari HRD',
            ]);

            // Jadwal Mediasi
            Jadwal::create([
                'jadwal_id' => Str::uuid(),
                'pengaduan_id' => $pengaduan1->pengaduan_id,
                'mediator_id' => $pengaduan1->mediator_id,
                'tanggal' => Carbon::now()->addDays(7),
                'waktu' => '10:00:00',
                'tempat' => 'Ruang Sidang Mediasi 1',
                'jenis_jadwal' => 'mediasi',
                'sidang_ke' => '1',
                'status_jadwal' => 'selesai',
                'catatan_jadwal' => 'Mediasi untuk mencapai kesepakatan pembayaran lembur',
                'hasil' => 'Tercapai kesepakatan pembayaran',
                'konfirmasi_pelapor' => 'hadir',
                'konfirmasi_terlapor' => 'hadir',
                'tanggal_konfirmasi_pelapor' => Carbon::now()->addDays(1),
                'tanggal_konfirmasi_terlapor' => Carbon::now()->addDays(1),
                'catatan_konfirmasi_pelapor' => 'Siap mengikuti mediasi',
                'catatan_konfirmasi_terlapor' => 'Akan membawa draft kesepakatan',
            ]);
        }

        // Kasus 2: Budi - THR (Hanya Klarifikasi)
        $pengaduan2 = Pengaduan::where('narasi_kasus', 'like', '%THR yang diberikan tidak sesuai%')->first();
        if ($pengaduan2) {
            Jadwal::create([
                'jadwal_id' => Str::uuid(),
                'pengaduan_id' => $pengaduan2->pengaduan_id,
                'mediator_id' => $pengaduan2->mediator_id,
                'tanggal' => Carbon::now()->addDays(4),
                'waktu' => '13:00:00',
                'tempat' => 'Ruang Sidang Mediasi 2',
                'jenis_jadwal' => 'klarifikasi',
                'status_jadwal' => 'selesai',
                'catatan_jadwal' => 'Perlu membawa bukti pembayaran THR',
                'hasil' => 'Sepakat kembali ke perundingan bipartit',
                'konfirmasi_pelapor' => 'hadir',
                'konfirmasi_terlapor' => 'hadir',
                'tanggal_konfirmasi_pelapor' => Carbon::now()->addDays(1),
                'tanggal_konfirmasi_terlapor' => Carbon::now()->addDays(1),
                'catatan_konfirmasi_pelapor' => 'Akan membawa slip THR',
                'catatan_konfirmasi_terlapor' => 'Akan membawa kebijakan THR perusahaan',
            ]);
        }

        // Kasus 3: Ahmad - PHK (Proses Mediasi)
        $pengaduan3 = Pengaduan::where('narasi_kasus', 'like', '%PHK sepihak tanpa alasan%')->first();
        if ($pengaduan3) {
            // Jadwal Klarifikasi
            Jadwal::create([
                'jadwal_id' => Str::uuid(),
                'pengaduan_id' => $pengaduan3->pengaduan_id,
                'mediator_id' => $pengaduan3->mediator_id,
                'tanggal' => Carbon::now()->addDays(5),
                'waktu' => '09:00:00',
                'tempat' => 'Ruang Sidang Mediasi 1',
                'jenis_jadwal' => 'klarifikasi',
                'status_jadwal' => 'selesai',
                'catatan_jadwal' => 'Perlu membawa dokumen terkait PHK',
                'hasil' => 'Perlu dilanjutkan ke tahap mediasi',
                'konfirmasi_pelapor' => 'hadir',
                'konfirmasi_terlapor' => 'hadir',
                'tanggal_konfirmasi_pelapor' => Carbon::now()->addDays(1),
                'tanggal_konfirmasi_terlapor' => Carbon::now()->addDays(1),
                'catatan_konfirmasi_pelapor' => 'Akan hadir dengan pendamping',
                'catatan_konfirmasi_terlapor' => 'Akan membawa bukti pelanggaran',
            ]);

            // Jadwal Mediasi
            Jadwal::create([
                'jadwal_id' => Str::uuid(),
                'pengaduan_id' => $pengaduan3->pengaduan_id,
                'mediator_id' => $pengaduan3->mediator_id,
                'tanggal' => Carbon::now()->addDays(10),
                'waktu' => '10:00:00',
                'tempat' => 'Ruang Sidang Mediasi 1',
                'jenis_jadwal' => 'mediasi',
                'sidang_ke' => '1',
                'status_jadwal' => 'dijadwalkan',
                'catatan_jadwal' => 'Mediasi pembahasan pesangon',
                'hasil' => null,
                'konfirmasi_pelapor' => 'pending',
                'konfirmasi_terlapor' => 'pending',
                'tanggal_konfirmasi_pelapor' => null,
                'tanggal_konfirmasi_terlapor' => null,
                'catatan_konfirmasi_pelapor' => null,
                'catatan_konfirmasi_terlapor' => null,
            ]);
        }

        // Kasus 4: Rina - Perjanjian Kerja
        $pengaduan4 = Pengaduan::where('narasi_kasus', 'like', '%Perubahan status kerja%')->first();
        if ($pengaduan4) {
            // Jadwal Klarifikasi
            Jadwal::create([
                'jadwal_id' => Str::uuid(),
                'pengaduan_id' => $pengaduan4->pengaduan_id,
                'mediator_id' => $pengaduan4->mediator_id,
                'tanggal' => Carbon::now()->addDays(6),
                'waktu' => '13:00:00',
                'tempat' => 'Ruang Sidang Mediasi 2',
                'jenis_jadwal' => 'klarifikasi',
                'status_jadwal' => 'selesai',
                'catatan_jadwal' => 'Perlu membawa dokumen PKWTT dan PKWT',
                'hasil' => 'Perlu dilanjutkan ke tahap mediasi',
                'konfirmasi_pelapor' => 'hadir',
                'konfirmasi_terlapor' => 'hadir',
                'tanggal_konfirmasi_pelapor' => Carbon::now()->addDays(1),
                'tanggal_konfirmasi_terlapor' => Carbon::now()->addDays(1),
                'catatan_konfirmasi_pelapor' => 'Akan membawa kontrak kerja',
                'catatan_konfirmasi_terlapor' => 'Akan membawa SK Direksi',
            ]);

            // Jadwal Mediasi
            Jadwal::create([
                'jadwal_id' => Str::uuid(),
                'pengaduan_id' => $pengaduan4->pengaduan_id,
                'mediator_id' => $pengaduan4->mediator_id,
                'tanggal' => Carbon::now()->addDays(12),
                'waktu' => '14:00:00',
                'tempat' => 'Ruang Sidang Mediasi 2',
                'jenis_jadwal' => 'mediasi',
                'sidang_ke' => '1',
                'status_jadwal' => 'dijadwalkan',
                'catatan_jadwal' => 'Mediasi pembahasan status kerja',
                'hasil' => null,
                'konfirmasi_pelapor' => 'pending',
                'konfirmasi_terlapor' => 'pending',
                'tanggal_konfirmasi_pelapor' => null,
                'tanggal_konfirmasi_terlapor' => null,
                'catatan_konfirmasi_pelapor' => null,
                'catatan_konfirmasi_terlapor' => null,
            ]);
        }

        // Kasus 5: Dedi - Fasilitas Kerja
        $pengaduan5 = Pengaduan::where('narasi_kasus', 'like', '%Pengurangan fasilitas kerja%')->first();
        if ($pengaduan5) {
            // Jadwal Klarifikasi
            Jadwal::create([
                'jadwal_id' => Str::uuid(),
                'pengaduan_id' => $pengaduan5->pengaduan_id,
                'mediator_id' => $pengaduan5->mediator_id,
                'tanggal' => Carbon::now()->addDays(8),
                'waktu' => '09:00:00',
                'tempat' => 'Ruang Sidang Mediasi 3',
                'jenis_jadwal' => 'klarifikasi',
                'status_jadwal' => 'dijadwalkan',
                'catatan_jadwal' => 'Perlu membawa dokumen fasilitas kerja',
                'hasil' => null,
                'konfirmasi_pelapor' => 'pending',
                'konfirmasi_terlapor' => 'pending',
                'tanggal_konfirmasi_pelapor' => null,
                'tanggal_konfirmasi_terlapor' => null,
                'catatan_konfirmasi_pelapor' => null,
                'catatan_konfirmasi_terlapor' => null,
            ]);
        }

        $this->command->info('Jadwal data seeded successfully!');
    }
} 