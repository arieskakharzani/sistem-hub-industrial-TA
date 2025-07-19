<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\Pengaduan;
use App\Models\User;
use App\Models\Pelapor;
use App\Models\Terlapor;
use App\Models\Mediator;

class PengaduanSeeder extends Seeder
{
    public function run()
    {
        // Debug: Check if users exist
        $pelapor = Pelapor::whereHas('user', function($q) {
            $q->where('email', 'ecakharzani10@gmail.com');
        })->first();
        
        $terlapor = Terlapor::whereHas('user', function($q) {
            $q->where('email', 'arieskaeca@gmail.com');
        })->first();
        
        $mediator = Mediator::whereHas('user', function($q) {
            $q->where('email', 'daarsyaaa@gmail.com');
        })->first();

        if (!$pelapor) {
            $this->command->error('Pelapor with email ecakharzani10@gmail.com not found!');
            return;
        }
        if (!$terlapor) {
            $this->command->error('Terlapor with email arieskaeca@gmail.com not found!');
            return;
        }
        if (!$mediator) {
            $this->command->error('Mediator with email daarsyaaa@gmail.com not found!');
            return;
        }

        // Kasus 1: Ecak Harzani - Upah Lembur
        $pengaduan1 = Pengaduan::create([
            'pengaduan_id' => Str::uuid(),
            'pelapor_id' => $pelapor->pelapor_id,
            'terlapor_id' => $terlapor->terlapor_id,
            'mediator_id' => $mediator->mediator_id,
            'tanggal_laporan' => Carbon::now(),
            'perihal' => 'Perselisihan Hak',
            'masa_kerja' => '2 tahun 3 bulan',
            'nama_terlapor' => 'PT ABC Technology',
            'email_terlapor' => 'arieskaeca@gmail.com',
            'no_hp_terlapor' => '081234567890',
            'alamat_kantor_cabang' => 'Jl. Teknologi No. 123, Jakarta',
            'narasi_kasus' => 'Upah lembur selama 3 bulan terakhir belum dibayarkan sesuai ketentuan. Total jam lembur 120 jam dengan rincian: Maret (45 jam), April (35 jam), Mei (40 jam).',
            'catatan_tambahan' => 'Sudah dilakukan perundingan bipartit tanggal 1 Juni 2024',
            'risalah_bipartit' => 'risalah_bipartit_1.pdf',
            'lampiran' => json_encode(['slip_gaji.pdf', 'absensi_lembur.pdf']),
            'status' => 'selesai',
            'catatan_mediator' => 'Kasus perlu segera ditindaklanjuti',
            'assigned_at' => Carbon::now(),
        ]);

        // Kasus 2: Siti - THR
        $pelapor2 = Pelapor::whereHas('user', function($q) {
            $q->where('email', 'pelapor2@example.com');
        })->first();
        
        $terlapor2 = Terlapor::whereHas('user', function($q) {
            $q->where('email', 'terlapor@example.com');
        })->first();
        
        $mediator2 = Mediator::whereHas('user', function($q) {
            $q->where('email', 'semuabisa.co@gmail.com');
        })->first();

        $pengaduan2 = Pengaduan::create([
            'pengaduan_id' => Str::uuid(),
            'pelapor_id' => $pelapor2->pelapor_id,
            'terlapor_id' => $terlapor2->terlapor_id,
            'mediator_id' => $mediator2->mediator_id,
            'tanggal_laporan' => Carbon::now()->subDays(5),
            'perihal' => 'Perselisihan Hak',
            'masa_kerja' => '1 tahun 6 bulan',
            'nama_terlapor' => 'PT XYZ Manufacturing',
            'email_terlapor' => 'terlapor@example.com',
            'no_hp_terlapor' => '081234567891',
            'alamat_kantor_cabang' => 'Jl. Industri No. 789, Jakarta',
            'narasi_kasus' => 'THR yang diberikan tidak sesuai dengan ketentuan yang berlaku. Seharusnya menerima 1 bulan gaji penuh namun hanya diberikan 50%.',
            'catatan_tambahan' => 'Perundingan bipartit tidak mencapai kesepakatan',
            'risalah_bipartit' => 'risalah_bipartit_2.pdf',
            'lampiran' => json_encode(['bukti_pembayaran_thr.pdf', 'surat_pengaduan.pdf']),
            'status' => 'selesai',
            'catatan_mediator' => 'Perlu verifikasi perhitungan THR',
            'assigned_at' => Carbon::now()->subDays(5),
        ]);

        // Kasus 3: Budi - PHK
        $pelapor3 = Pelapor::whereHas('user', function($q) {
            $q->where('email', 'pelapor3@example.com');
        })->first();

        $pengaduan3 = Pengaduan::create([
            'pengaduan_id' => Str::uuid(),
            'pelapor_id' => $pelapor3->pelapor_id,
            'terlapor_id' => $terlapor2->terlapor_id,
            'mediator_id' => $mediator2->mediator_id,
            'tanggal_laporan' => Carbon::now()->subDays(10),
            'perihal' => 'Perselisihan PHK',
            'masa_kerja' => '5 tahun 2 bulan',
            'nama_terlapor' => 'PT DEF Trading',
            'email_terlapor' => 'terlapor@example.com',
            'no_hp_terlapor' => '081234567892',
            'alamat_kantor_cabang' => 'Jl. Bisnis No. 456, Jakarta',
            'narasi_kasus' => 'PHK sepihak tanpa alasan yang jelas dan pesangon yang tidak sesuai ketentuan UU Ketenagakerjaan.',
            'catatan_tambahan' => 'Karyawan masih aktif bekerja',
            'risalah_bipartit' => 'risalah_bipartit_3.pdf',
            'lampiran' => json_encode(['surat_phk.pdf', 'perjanjian_kerja.pdf']),
            'status' => 'proses',
            'catatan_mediator' => 'Perlu kajian mendalam terkait alasan PHK',
            'assigned_at' => Carbon::now()->subDays(10),
        ]);

        // Kasus 4: Rina - Perjanjian Kerja
        $pelapor4 = Pelapor::whereHas('user', function($q) {
            $q->where('email', 'pelapor4@example.com');
        })->first();

        $pengaduan4 = Pengaduan::create([
            'pengaduan_id' => Str::uuid(),
            'pelapor_id' => $pelapor4->pelapor_id,
            'terlapor_id' => $terlapor2->terlapor_id,
            'mediator_id' => $mediator->mediator_id,
            'tanggal_laporan' => Carbon::now()->subDays(15),
            'perihal' => 'Perselisihan Kepentingan',
            'masa_kerja' => '3 tahun 8 bulan',
            'nama_terlapor' => 'PT GHI Services',
            'email_terlapor' => 'terlapor@example.com',
            'no_hp_terlapor' => '081234567893',
            'alamat_kantor_cabang' => 'Jl. Layanan No. 234, Jakarta',
            'narasi_kasus' => 'Perubahan status kerja dari PKWTT menjadi PKWT secara sepihak oleh perusahaan.',
            'catatan_tambahan' => 'Sudah mengajukan keberatan secara tertulis',
            'risalah_bipartit' => 'risalah_bipartit_4.pdf',
            'lampiran' => json_encode(['surat_perubahan_status.pdf', 'surat_keberatan.pdf']),
            'status' => 'proses',
            'catatan_mediator' => 'Perlu klarifikasi dasar perubahan status kerja',
            'assigned_at' => Carbon::now()->subDays(15),
        ]);

        // Kasus 5: Dedi - Fasilitas Kerja
        $pelapor5 = Pelapor::whereHas('user', function($q) {
            $q->where('email', 'pelapor5@example.com');
        })->first();

        $pengaduan5 = Pengaduan::create([
            'pengaduan_id' => Str::uuid(),
            'pelapor_id' => $pelapor5->pelapor_id,
            'terlapor_id' => $terlapor2->terlapor_id,
            'mediator_id' => $mediator->mediator_id,
            'tanggal_laporan' => Carbon::now()->subDays(20),
            'perihal' => 'Perselisihan Kepentingan',
            'masa_kerja' => '4 tahun 1 bulan',
            'nama_terlapor' => 'PT JKL Consulting',
            'email_terlapor' => 'terlapor@example.com',
            'no_hp_terlapor' => '081234567894',
            'alamat_kantor_cabang' => 'Jl. Konsultasi No. 567, Jakarta',
            'narasi_kasus' => 'Pengurangan fasilitas kerja dan tunjangan operasional tanpa pemberitahuan dan kesepakatan.',
            'catatan_tambahan' => 'Pengurangan terjadi sejak bulan lalu',
            'risalah_bipartit' => 'risalah_bipartit_5.pdf',
            'lampiran' => json_encode(['kebijakan_lama.pdf', 'kebijakan_baru.pdf']),
            'status' => 'proses',
            'catatan_mediator' => 'Perlu pemeriksaan dokumen pendukung',
            'assigned_at' => Carbon::now()->subDays(20),
        ]);

        $this->command->info('Pengaduan data seeded successfully!');
    }
}
