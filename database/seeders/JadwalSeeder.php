<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Jadwal;
use App\Models\Pengaduan;
use App\Models\Mediator;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil pengaduan dan mediator dummy (pastikan sudah ada di database)
        $pengaduan = Pengaduan::first();
        $mediator = Mediator::first();

        Jadwal::insert([
            [
                'jadwal_id' => (string) Str::uuid(),
                'pengaduan_id' => $pengaduan ? $pengaduan->pengaduan_id : null,
                'mediator_id' => $mediator ? $mediator->mediator_id : null,
                'tanggal' => '2025-08-01',
                'waktu' => '09:00',
                'tempat' => 'Ruang Sidang Mediasi',
                'jenis_jadwal' => 'mediasi',
                'sidang_ke' => 1,
                'status_jadwal' => 'dijadwalkan',
                'catatan_jadwal' => 'Sidang mediasi pertama',
                'konfirmasi_pelapor' => 'hadir',
                'konfirmasi_terlapor' => 'hadir',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
} 