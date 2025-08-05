<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TestingSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Users
        $mediatorId = Str::uuid();
        $kepalaDinasId = Str::uuid();
        $pelaporId = Str::uuid();
        $terlaporId = Str::uuid();

        // Mediator User
        $mediatorUserId = Str::uuid();
        DB::table('users')->insert([
            'user_id' => $mediatorUserId,
            'email' => 'semuabisa.co@gmail.com',
            'password' => Hash::make('password'),
            'roles' => json_encode(['mediator']),
            'active_role' => 'mediator',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Kepala Dinas User
        $kepalaDinasUserId = Str::uuid();
        DB::table('users')->insert([
            'user_id' => $kepalaDinasUserId,
            'email' => 'daarsyaaa@gmail.com',
            'password' => Hash::make('password'),
            'roles' => json_encode(['kepala_dinas']),
            'active_role' => 'kepala_dinas',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Pelapor User
        $pelaporUserId = Str::uuid();
        DB::table('users')->insert([
            'user_id' => $pelaporUserId,
            'email' => 'ecakharzani10@gmail.com',
            'password' => Hash::make('password'),
            'roles' => json_encode(['pelapor']),
            'active_role' => 'pelapor',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Terlapor User
        $terlaporUserId = Str::uuid();
        DB::table('users')->insert([
            'user_id' => $terlaporUserId,
            'email' => 'arieskaeca@gmail.com',
            'password' => Hash::make('password'),
            'roles' => json_encode(['terlapor']),
            'active_role' => 'terlapor',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 2. Create Role Records
        // Mediator
        DB::table('mediator')->insert([
            'mediator_id' => $mediatorId,
            'user_id' => $mediatorUserId,
            'nama_mediator' => 'Test Mediator',
            'nip' => '198501012010011001',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Kepala Dinas
        DB::table('kepala_dinas')->insert([
            'kepala_dinas_id' => $kepalaDinasId,
            'user_id' => $kepalaDinasUserId,
            'nama_kepala_dinas' => 'Test Kepala Dinas',
            'nip' => '196501012000011001',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Pelapor
        DB::table('pelapor')->insert([
            'pelapor_id' => $pelaporId,
            'user_id' => $pelaporUserId,
            'nama_pelapor' => 'Test Pelapor',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Test No. 1',
            'no_hp' => '081234567890',
            'perusahaan' => 'PT Test Pelapor',
            'npk' => 'P123456',
            'email' => 'pelapor@test.com',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Terlapor
        DB::table('terlapor')->insert([
            'terlapor_id' => $terlaporId,
            'user_id' => $terlaporUserId,
            'nama_terlapor' => 'PT Test Terlapor',
            'alamat_kantor_cabang' => 'Jl. Test No. 2',
            'email_terlapor' => 'terlapor@test.com',
            'no_hp_terlapor' => '081234567891',
            'has_account' => true,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 3. Create Pengaduan
        $pengaduanId = Str::uuid();
        DB::table('pengaduans')->insert([
            'pengaduan_id' => $pengaduanId,
            'pelapor_id' => $pelaporId,
            'terlapor_id' => $terlaporId,
            'tanggal_laporan' => now(),
            'perihal' => 'Perselisihan PHK',
            'masa_kerja' => '5 tahun',
            'nama_terlapor' => 'PT Test Terlapor',
            'email_terlapor' => 'terlapor@test.com',
            'no_hp_terlapor' => '081234567891',
            'alamat_kantor_cabang' => 'Jl. Test No. 2',
            'narasi_kasus' => 'Ini adalah narasi kasus test',
            'risalah_bipartit' => 'risalah_bipartit_test.pdf',
            'status' => 'proses',
            'mediator_id' => $mediatorId,
            'assigned_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 4. Create Jadwal
        $jadwalId = Str::uuid();
        DB::table('jadwal')->insert([
            'jadwal_id' => $jadwalId,
            'pengaduan_id' => $pengaduanId,
            'mediator_id' => $mediatorId,
            'tanggal' => now()->addDays(7),
            'waktu' => '09:00:00',
            'tempat' => 'Ruang Mediasi 1',
            'jenis_jadwal' => 'klarifikasi',
            'status_jadwal' => 'selesai',
            'konfirmasi_pelapor' => 'hadir',
            'konfirmasi_terlapor' => 'hadir',
            'tanggal_konfirmasi_pelapor' => now()->subDays(8),
            'tanggal_konfirmasi_terlapor' => now()->subDays(8),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 5. Create Dokumen HI
        $dokumenHiId = Str::uuid();
        DB::table('dokumen_hubungan_industrial')->insert([
            'dokumen_hi_id' => $dokumenHiId,
            'pengaduan_id' => $pengaduanId,
            'jenis_dokumen' => 'risalah',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 6. Create Risalah
        $risalahId = Str::uuid();
        DB::table('risalah')->insert([
            'risalah_id' => $risalahId,
            'jadwal_id' => $jadwalId,
            'dokumen_hi_id' => $dokumenHiId,
            'jenis_risalah' => 'klarifikasi',
            'nama_perusahaan' => 'PT Test Terlapor',
            'jenis_usaha' => 'Manufaktur',
            'alamat_perusahaan' => 'Jl. Test No. 2',
            'nama_pekerja' => 'Test Pelapor',
            'alamat_pekerja' => 'Jl. Test No. 1',
            'tanggal_perundingan' => now(),
            'tempat_perundingan' => 'Ruang Mediasi 1',
            'pokok_masalah' => 'PHK tidak sesuai prosedur',
            'pendapat_pekerja' => 'PHK dilakukan sepihak',
            'pendapat_pengusaha' => 'PHK sesuai prosedur',
            'ttd_mediator' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 7. Create Perjanjian Bersama
        $perjanjianBersamaId = Str::uuid();
        $dokumenPBId = Str::uuid();
        DB::table('dokumen_hubungan_industrial')->insert([
            'dokumen_hi_id' => $dokumenPBId,
            'pengaduan_id' => $pengaduanId,
            'jenis_dokumen' => 'perjanjian_bersama',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('perjanjian_bersama')->insert([
            'perjanjian_bersama_id' => $perjanjianBersamaId,
            'dokumen_hi_id' => $dokumenPBId,
            'nama_pengusaha' => 'Test Pengusaha',
            'jabatan_pengusaha' => 'Direktur',
            'perusahaan_pengusaha' => 'PT Test Terlapor',
            'alamat_pengusaha' => 'Jl. Test No. 2',
            'nama_pekerja' => 'Test Pelapor',
            'jabatan_pekerja' => 'Staff',
            'perusahaan_pekerja' => 'PT Test Terlapor',
            'alamat_pekerja' => 'Jl. Test No. 1',
            'isi_kesepakatan' => 'Kesepakatan penyelesaian perselisihan',
            // 'nomor_perjanjian' => '001/PB/TEST/2024', // Field dihapus
            'tanggal_perjanjian' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 8. Create Anjuran
        $anjuranId = Str::uuid();
        $dokumenAnjuranId = Str::uuid();
        DB::table('dokumen_hubungan_industrial')->insert([
            'dokumen_hi_id' => $dokumenAnjuranId,
            'pengaduan_id' => $pengaduanId,
            'jenis_dokumen' => 'anjuran',
            // 'tanggal_dokumen' => now(), // Field dihapus
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('anjuran')->insert([
            'anjuran_id' => $anjuranId,
            'dokumen_hi_id' => $dokumenAnjuranId,
            'nama_pengusaha' => 'Test Pengusaha',
            'jabatan_pengusaha' => 'Direktur',
            'perusahaan_pengusaha' => 'PT Test Terlapor',
            'alamat_pengusaha' => 'Jl. Test No. 2',
            'nama_pekerja' => 'Test Pelapor',
            'jabatan_pekerja' => 'Staff',
            'perusahaan_pekerja' => 'PT Test Terlapor',
            'alamat_pekerja' => 'Jl. Test No. 1',
            'keterangan_pekerja' => 'Keterangan dari pihak pekerja',
            'keterangan_pengusaha' => 'Keterangan dari pihak pengusaha',
            'pertimbangan_hukum' => 'Pertimbangan hukum untuk kasus ini',
            'isi_anjuran' => 'Isi anjuran penyelesaian perselisihan',
            'nomor_anjuran' => '001/AJ/TEST/2024',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // =====================
        // KASUS 1: Selesai di Klarifikasi
        // =====================
        $pelapor1Id = Str::uuid();
        $terlapor1Id = Str::uuid();
        $pengaduan1Id = Str::uuid();
        $jadwalKlarifikasi1Id = Str::uuid();
        $dokumenHiKlarifikasi1Id = Str::uuid();
        $risalahKlarifikasi1Id = Str::uuid();

        // Pelapor 1
        DB::table('users')->insert([
            'user_id' => $pelapor1Id,
            'email' => 'pelapor1@test.com',
            'password' => Hash::make('password'),
            'roles' => json_encode(['pelapor']),
            'active_role' => 'pelapor',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('pelapor')->insert([
            'pelapor_id' => $pelapor1Id,
            'user_id' => $pelapor1Id,
            'nama_pelapor' => 'Pelapor Satu',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Satu No. 1',
            'no_hp' => '0811111111',
            'perusahaan' => 'PT Satu',
            'npk' => 'P0001',
            'email' => 'pelapor1@test.com',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        // Terlapor 1
        DB::table('users')->insert([
            'user_id' => $terlapor1Id,
            'email' => 'terlapor1@test.com',
            'password' => Hash::make('password'),
            'roles' => json_encode(['terlapor']),
            'active_role' => 'terlapor',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('terlapor')->insert([
            'terlapor_id' => $terlapor1Id,
            'user_id' => $terlapor1Id,
            'nama_terlapor' => 'PT Satu',
            'alamat_kantor_cabang' => 'Jl. Satu No. 2',
            'email_terlapor' => 'terlapor1@test.com',
            'no_hp_terlapor' => '0811111112',
            'has_account' => true,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        // Pengaduan 1
        DB::table('pengaduans')->insert([
            'pengaduan_id' => $pengaduan1Id,
            'pelapor_id' => $pelapor1Id,
            'terlapor_id' => $terlapor1Id,
            'tanggal_laporan' => now()->subDays(10),
            'perihal' => 'Perselisihan PHK',
            'masa_kerja' => '3 tahun',
            'nama_terlapor' => 'PT Satu',
            'email_terlapor' => 'terlapor1@test.com',
            'no_hp_terlapor' => '0811111112',
            'alamat_kantor_cabang' => 'Jl. Satu No. 2',
            'narasi_kasus' => 'Kasus dummy selesai di klarifikasi',
            'risalah_bipartit' => 'dummy_bipartit1.pdf',
            'status' => 'selesai',
            'mediator_id' => $mediatorId,
            'assigned_at' => now()->subDays(9),
            'created_at' => now()->subDays(10),
            'updated_at' => now()
        ]);
        // Jadwal Klarifikasi 1
        DB::table('jadwal')->insert([
            'jadwal_id' => $jadwalKlarifikasi1Id,
            'pengaduan_id' => $pengaduan1Id,
            'mediator_id' => $mediatorId,
            'tanggal' => now()->subDays(7),
            'waktu' => '09:00:00',
            'tempat' => 'Ruang Mediasi 1',
            'jenis_jadwal' => 'klarifikasi',
            'status_jadwal' => 'selesai',
            'konfirmasi_pelapor' => 'hadir',
            'konfirmasi_terlapor' => 'hadir',
            'tanggal_konfirmasi_pelapor' => now()->subDays(8),
            'tanggal_konfirmasi_terlapor' => now()->subDays(8),
            'created_at' => now()->subDays(7),
            'updated_at' => now()
        ]);
        // Dokumen HI Klarifikasi 1
        DB::table('dokumen_hubungan_industrial')->insert([
            'dokumen_hi_id' => $dokumenHiKlarifikasi1Id,
            'pengaduan_id' => $pengaduan1Id,
            'jenis_dokumen' => 'risalah',
            'created_at' => now()->subDays(7),
            'updated_at' => now()
        ]);
        // Risalah Klarifikasi 1 (bipartit_lagi)
        DB::table('risalah')->insert([
            'risalah_id' => $risalahKlarifikasi1Id,
            'jadwal_id' => $jadwalKlarifikasi1Id,
            'dokumen_hi_id' => $dokumenHiKlarifikasi1Id,
            'jenis_risalah' => 'klarifikasi',
            'nama_perusahaan' => 'PT Satu',
            'jenis_usaha' => 'Manufaktur',
            'alamat_perusahaan' => 'Jl. Satu No. 2',
            'nama_pekerja' => 'Pelapor Satu',
            'alamat_pekerja' => 'Jl. Satu No. 1',
            'tanggal_perundingan' => now()->subDays(7),
            'tempat_perundingan' => 'Ruang Mediasi 1',
            'pokok_masalah' => 'PHK tidak sesuai prosedur',
            'pendapat_pekerja' => 'PHK sepihak',
            'pendapat_pengusaha' => 'PHK sesuai aturan',
            'ttd_mediator' => false,
            'created_at' => now()->subDays(7),
            'updated_at' => now()
        ]);
        // Detail Klarifikasi 1
        DB::table('detail_klarifikasi')->insert([
            'detail_klarifikasi_id' => Str::uuid(),
            'risalah_id' => $risalahKlarifikasi1Id,
            'arahan_mediator' => 'Lanjut bipartit',
            'kesimpulan_klarifikasi' => 'bipartit_lagi',
            'created_at' => now()->subDays(7),
            'updated_at' => now()
        ]);

        // =====================
        // KASUS 2: Lanjut ke Mediasi
        // =====================
        $pelapor2Id = Str::uuid();
        $terlapor2Id = Str::uuid();
        $pengaduan2Id = Str::uuid();
        $jadwalKlarifikasi2Id = Str::uuid();
        $dokumenHiKlarifikasi2Id = Str::uuid();
        $risalahKlarifikasi2Id = Str::uuid();
        $jadwalMediasi2Id = Str::uuid();
        $dokumenHiMediasi2Id = Str::uuid();
        $risalahMediasi2Id = Str::uuid();

        // Pelapor 2
        DB::table('users')->insert([
            'user_id' => $pelapor2Id,
            'email' => 'pelapor2@test.com',
            'password' => Hash::make('password'),
            'roles' => json_encode(['pelapor']),
            'active_role' => 'pelapor',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('pelapor')->insert([
            'pelapor_id' => $pelapor2Id,
            'user_id' => $pelapor2Id,
            'nama_pelapor' => 'Pelapor Dua',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1992-02-02',
            'jenis_kelamin' => 'Perempuan',
            'alamat' => 'Jl. Dua No. 1',
            'no_hp' => '0822222222',
            'perusahaan' => 'PT Dua',
            'npk' => 'P0002',
            'email' => 'pelapor2@test.com',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        // Terlapor 2
        DB::table('users')->insert([
            'user_id' => $terlapor2Id,
            'email' => 'terlapor2@test.com',
            'password' => Hash::make('password'),
            'roles' => json_encode(['terlapor']),
            'active_role' => 'terlapor',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('terlapor')->insert([
            'terlapor_id' => $terlapor2Id,
            'user_id' => $terlapor2Id,
            'nama_terlapor' => 'PT Dua',
            'alamat_kantor_cabang' => 'Jl. Dua No. 2',
            'email_terlapor' => 'terlapor2@test.com',
            'no_hp_terlapor' => '0822222223',
            'has_account' => true,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        // Pengaduan 2
        DB::table('pengaduans')->insert([
            'pengaduan_id' => $pengaduan2Id,
            'pelapor_id' => $pelapor2Id,
            'terlapor_id' => $terlapor2Id,
            'tanggal_laporan' => now()->subDays(10),
            'perihal' => 'Perselisihan PHK',
            'masa_kerja' => '2 tahun',
            'nama_terlapor' => 'PT Dua',
            'email_terlapor' => 'terlapor2@test.com',
            'no_hp_terlapor' => '0822222223',
            'alamat_kantor_cabang' => 'Jl. Dua No. 2',
            'narasi_kasus' => 'Kasus dummy lanjut ke mediasi',
            'risalah_bipartit' => 'dummy_bipartit2.pdf',
            'status' => 'proses',
            'mediator_id' => $mediatorId,
            'assigned_at' => now()->subDays(9),
            'created_at' => now()->subDays(10),
            'updated_at' => now()
        ]);
        // Jadwal Klarifikasi 2
        DB::table('jadwal')->insert([
            'jadwal_id' => $jadwalKlarifikasi2Id,
            'pengaduan_id' => $pengaduan2Id,
            'mediator_id' => $mediatorId,
            'tanggal' => now()->subDays(7),
            'waktu' => '10:00:00',
            'tempat' => 'Ruang Mediasi 2',
            'jenis_jadwal' => 'klarifikasi',
            'status_jadwal' => 'selesai',
            'konfirmasi_pelapor' => 'hadir',
            'konfirmasi_terlapor' => 'hadir',
            'tanggal_konfirmasi_pelapor' => now()->subDays(8),
            'tanggal_konfirmasi_terlapor' => now()->subDays(8),
            'created_at' => now()->subDays(7),
            'updated_at' => now()
        ]);
        // Dokumen HI Klarifikasi 2
        DB::table('dokumen_hubungan_industrial')->insert([
            'dokumen_hi_id' => $dokumenHiKlarifikasi2Id,
            'pengaduan_id' => $pengaduan2Id,
            'jenis_dokumen' => 'risalah',
            'created_at' => now()->subDays(7),
            'updated_at' => now()
        ]);
        // Risalah Klarifikasi 2 (lanjut_ke_tahap_mediasi)
        DB::table('risalah')->insert([
            'risalah_id' => $risalahKlarifikasi2Id,
            'jadwal_id' => $jadwalKlarifikasi2Id,
            'dokumen_hi_id' => $dokumenHiKlarifikasi2Id,
            'jenis_risalah' => 'klarifikasi',
            'nama_perusahaan' => 'PT Dua',
            'jenis_usaha' => 'Manufaktur',
            'alamat_perusahaan' => 'Jl. Dua No. 2',
            'nama_pekerja' => 'Pelapor Dua',
            'alamat_pekerja' => 'Jl. Dua No. 1',
            'tanggal_perundingan' => now()->subDays(7),
            'tempat_perundingan' => 'Ruang Mediasi 2',
            'pokok_masalah' => 'PHK tidak sesuai prosedur',
            'pendapat_pekerja' => 'PHK sepihak',
            'pendapat_pengusaha' => 'PHK sesuai aturan',
            'ttd_mediator' => false,
            'created_at' => now()->subDays(7),
            'updated_at' => now()
        ]);
        // Detail Klarifikasi 2
        DB::table('detail_klarifikasi')->insert([
            'detail_klarifikasi_id' => Str::uuid(),
            'risalah_id' => $risalahKlarifikasi2Id,
            'arahan_mediator' => 'Lanjut ke mediasi',
            'kesimpulan_klarifikasi' => 'lanjut_ke_tahap_mediasi',
            'created_at' => now()->subDays(7),
            'updated_at' => now()
        ]);
        // Jadwal Mediasi 2
        DB::table('jadwal')->insert([
            'jadwal_id' => $jadwalMediasi2Id,
            'pengaduan_id' => $pengaduan2Id,
            'mediator_id' => $mediatorId,
            'tanggal' => now()->subDays(3),
            'waktu' => '13:00:00',
            'tempat' => 'Ruang Mediasi 2',
            'jenis_jadwal' => 'mediasi',
            'status_jadwal' => 'dijadwalkan',
            'konfirmasi_pelapor' => 'hadir',
            'konfirmasi_terlapor' => 'hadir',
            'tanggal_konfirmasi_pelapor' => now()->subDays(4),
            'tanggal_konfirmasi_terlapor' => now()->subDays(4),
            'created_at' => now()->subDays(3),
            'updated_at' => now()
        ]);
        // Dokumen HI Mediasi 2
        DB::table('dokumen_hubungan_industrial')->insert([
            'dokumen_hi_id' => $dokumenHiMediasi2Id,
            'pengaduan_id' => $pengaduan2Id,
            'jenis_dokumen' => 'risalah',
            'created_at' => now()->subDays(3),
            'updated_at' => now()
        ]);
        // Risalah Penyelesaian 2
        DB::table('risalah')->insert([
            'risalah_id' => $risalahMediasi2Id,
            'jadwal_id' => $jadwalMediasi2Id,
            'dokumen_hi_id' => $dokumenHiMediasi2Id,
            'jenis_risalah' => 'penyelesaian',
            'nama_perusahaan' => 'PT Dua',
            'jenis_usaha' => 'Manufaktur',
            'alamat_perusahaan' => 'Jl. Dua No. 2',
            'nama_pekerja' => 'Pelapor Dua',
            'alamat_pekerja' => 'Jl. Dua No. 1',
            'tanggal_perundingan' => now()->subDays(3),
            'tempat_perundingan' => 'Ruang Mediasi 2',
            'pokok_masalah' => 'PHK tidak sesuai prosedur',
            'pendapat_pekerja' => 'PHK sepihak',
            'pendapat_pengusaha' => 'PHK sesuai aturan',
            'ttd_mediator' => false,
            'created_at' => now()->subDays(3),
            'updated_at' => now()
        ]);
    }
}
