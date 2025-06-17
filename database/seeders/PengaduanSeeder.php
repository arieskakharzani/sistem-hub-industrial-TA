<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengaduanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pengaduans = [
            [
                'pelapor_id' => 1,
                'tanggal_laporan' => '2024-01-15',
                'perihal' => 'Perselisihan Hak',
                'masa_kerja' => '3 Tahun 6 Bulan',
                'kontak_pekerja' => '081234567890',
                'nama_perusahaan' => 'PT Sejahtera Mandiri',
                'kontak_perusahaan' => '021-8765432',
                'alamat_kantor_cabang' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                'narasi_kasus' => 'Saya tidak menerima upah lembur yang seharusnya dibayarkan sesuai dengan jam kerja tambahan yang telah saya lakukan selama 6 bulan terakhir. Perusahaan menolak memberikan kompensasi dengan alasan tidak ada pencatatan resmi.',
                'catatan_tambahan' => 'Saya memiliki bukti absensi dan foto jam kerja',
                'lampiran' => json_encode(['absensi.pdf', 'foto_jam_kerja.jpg']),
                'status' => 'pending',
                'mediator_id' => 1,
                'catatan_mediator' => 'Kasus sedang dalam tahap mediasi dengan perusahaan',
                'assigned_at' => Carbon::parse('2024-01-22 09:00:00'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'pelapor_id' => 2,
                'tanggal_laporan' => '2024-01-20',
                'perihal' => 'Perselisihan PHK',
                'masa_kerja' => '5 Tahun 2 Bulan',
                'kontak_pekerja' => '081987654321',
                'nama_perusahaan' => 'CV Maju Bersama',
                'kontak_perusahaan' => '021-5551234',
                'alamat_kantor_cabang' => 'Jl. Gatot Subroto No. 45, Jakarta Selatan',
                'narasi_kasus' => 'Saya di-PHK secara sepihak tanpa prosedur yang benar dan tanpa diberikan pesangon sesuai ketentuan undang-undang ketenagakerjaan. Alasan PHK tidak jelas dan tidak ada surat peringatan sebelumnya.',
                'catatan_tambahan' => 'Memiliki kontrak kerja dan surat pengangkatan',
                'lampiran' => json_encode(['kontrak_kerja.pdf', 'surat_pengangkatan.pdf']),
                'status' => 'proses',
                'mediator_id' => 2,
                'catatan_mediator' => 'Mediasi berhasil, perusahaan setuju memberikan kenaikan gaji sesuai PKB',
                'assigned_at' => Carbon::parse('2024-02-03 10:30:00'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'pelapor_id' => 3,
                'tanggal_laporan' => '2024-02-01',
                'perihal' => 'Perselisihan Kepentingan',
                'masa_kerja' => '2 Tahun 8 Bulan',
                'kontak_pekerja' => '082123456789',
                'nama_perusahaan' => 'PT Teknologi Nusantara',
                'kontak_perusahaan' => '021-7778889',
                'alamat_kantor_cabang' => 'Jl. HR Rasuna Said No. 78, Jakarta Selatan',
                'narasi_kasus' => 'Perusahaan menolak memberikan kenaikan gaji yang telah disepakati dalam perjanjian kerja bersama (PKB) untuk tahun 2024. Padahal kontribusi karyawan sudah optimal dan target tercapai.',
                'catatan_tambahan' => null,
                'lampiran' => json_encode(['pkb_2024.pdf', 'laporan_kinerja.pdf']),
                'status' => 'selesai',
                'mediator_id' => 1,
                'catatan_mediator' => 'Sedang mengkaji kebijakan cuti perusahaan',
                'assigned_at' => Carbon::parse('2024-02-17 14:00:00'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'pelapor_id' => 4,
                'tanggal_laporan' => '2024-02-10',
                'perihal' => 'Perselisihan antar SP/SB',
                'masa_kerja' => '1 Tahun 4 Bulan',
                'kontak_pekerja' => '083456789012',
                'nama_perusahaan' => 'PT Global Industries',
                'kontak_perusahaan' => '021-4443333',
                'alamat_kantor_cabang' => 'Jl. Thamrin No. 90, Jakarta Pusat',
                'narasi_kasus' => 'Terjadi perselisihan antara serikat pekerja terkait pembagian dana kesejahteraan karyawan dan representasi dalam komite perusahaan. Kedua serikat saling klaim sebagai yang sah.',
                'catatan_tambahan' => 'Melibatkan SP Sejahtera dan SB Mandiri',
                'lampiran' => json_encode(['surat_sp_sejahtera.pdf', 'surat_sb_mandiri.pdf']),
                'status' => 'pending',
                'mediator_id' => null,
                'catatan_mediator' => null,
                'assigned_at' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'pelapor_id' => 5,
                'tanggal_laporan' => '2024-02-15',
                'perihal' => 'Perselisihan Hak',
                'masa_kerja' => '4 Tahun 1 Bulan',
                'kontak_pekerja' => '084567890123',
                'nama_perusahaan' => 'PT Mitra Sejati',
                'kontak_perusahaan' => '021-2221111',
                'alamat_kantor_cabang' => 'Jl. Casablanca No. 15, Jakarta Selatan',
                'narasi_kasus' => 'Hak cuti tahunan tidak diberikan dengan alasan beban kerja tinggi. Sudah 2 tahun tidak mengambil cuti dan permintaan cuti selalu ditolak oleh atasan langsung.',
                'catatan_tambahan' => 'Memiliki bukti email penolakan cuti',
                'lampiran' => json_encode(['email_penolakan_cuti.pdf']),
                'status' => 'proses',
                'mediator_id' => null,
                'catatan_mediator' => null,
                'assigned_at' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            // [
            //     'pelapor_id' => 1,
            //     'tanggal_laporan' => '2024-02-20',
            //     'perihal' => 'Perselisihan Kepentingan',
            //     'masa_kerja' => '3 Tahun 6 Bulan',
            //     'kontak_pekerja' => '081234567890',
            //     'nama_perusahaan' => 'PT Digital Future',
            //     'kontak_perusahaan' => '021-9998887',
            //     'alamat_kantor_cabang' => 'Jl. Kuningan No. 25, Jakarta Selatan',
            //     'narasi_kasus' => 'Perusahaan mengubah sistem shift kerja tanpa konsultasi dengan karyawan. Perubahan ini berdampak pada kehidupan pribadi dan tidak ada kompensasi tambahan.',
            //     'catatan_tambahan' => 'Perubahan berlaku sejak Januari 2024',
            //     'lampiran' => null,
            //     'status' => 'pending',
            //     'mediator_id' => null,
            //     'catatan_mediator' => null,
            //     'assigned_at' => null,
            //     'created_at' => Carbon::now(),
            //     'updated_at' => Carbon::now()
            // ]
        ];

        DB::table('pengaduans')->insert($pengaduans);
    }
}
