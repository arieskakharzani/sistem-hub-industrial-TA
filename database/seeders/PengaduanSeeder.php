<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Pelapor;
use App\Models\Pengaduan;
use App\Models\Terlapor;
use App\Models\Mediator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;

class PengaduanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dummy pelapor, terlapor, mediator (pastikan ada di database)
        $pelapor = Pelapor::first();
        $terlapor = Terlapor::first();
        $mediator = Mediator::first();

        // Buat pengaduan dummy dan simpan pengaduan_id
        $pengaduan = Pengaduan::create([
            'pengaduan_id' => (string) Str::uuid(),
            'pelapor_id' => $pelapor ? $pelapor->pelapor_id : null,
            'terlapor_id' => $terlapor ? $terlapor->terlapor_id : null,
            'tanggal_laporan' => '2024-02-01',
            'perihal' => 'Perselisihan Kepentingan',
            'masa_kerja' => '2 Tahun 8 Bulan',
            'nama_terlapor' => 'PT Teknologi Nusantara',
            'email_terlapor' => 'technusantara@gmail.com',
            'no_hp_terlapor' => '021-8718432',
            'alamat_kantor_cabang' => 'Jl. HR Rasuna Said No. 78, Jakarta Selatan',
            'narasi_kasus' => 'Perusahaan menolak memberikan kenaikan gaji yang telah disepakati dalam perjanjian kerja bersama (PKB) untuk tahun 2024. Padahal kontribusi karyawan sudah optimal dan target tercapai.',
            'catatan_tambahan' => null,
            'lampiran' => json_encode(['pkb_2024.pdf', 'laporan_kinerja.pdf']),
            'status' => 'selesai',
            'mediator_id' => $mediator ? $mediator->mediator_id : null,
            'risalah_bipartit' => 'risalah_bipartit.pdf',
            'catatan_mediator' => 'Sedang mengkaji kebijakan cuti perusahaan',
            'assigned_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Simpan pengaduan_id ke file sementara untuk digunakan di JadwalSeeder
        file_put_contents(database_path('seeders/pengaduan_id.txt'), $pengaduan->pengaduan_id);
    }
}
