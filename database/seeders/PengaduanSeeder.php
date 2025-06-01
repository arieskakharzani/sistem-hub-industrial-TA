<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pengaduan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PengaduanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test users if they don't exist
        // $user1 = User::firstOrCreate(
        //     ['email' => 'john.doe@company.com'],
        //     [
        //         'name' => 'John Doe',
        //         'npk' => 'EMP001',
        //         'phone' => '081234567890',
        //         'department' => 'IT Department',
        //         'position' => 'Software Developer',
        //         'hire_date' => '2020-01-15',
        //         'password' => Hash::make('password'),
        //         'email_verified_at' => now(),
        //     ]
        // );

        // $user2 = User::firstOrCreate(
        //     ['email' => 'jane.smith@company.com'],
        //     [
        //         'name' => 'Jane Smith',
        //         'npk' => 'EMP002',
        //         'phone' => '081234567891',
        //         'department' => 'HR Department',
        //         'position' => 'HR Specialist',
        //         'hire_date' => '2019-05-20',
        //         'password' => Hash::make('password'),
        //         'email_verified_at' => now(),
        //     ]
        // );

        // $user3 = User::firstOrCreate(
        //     ['email' => 'admin@company.com'],
        //     [
        //         'name' => 'Admin User',
        //         'npk' => 'ADM001',
        //         'phone' => '081234567892',
        //         'department' => 'Management',
        //         'position' => 'System Administrator',
        //         'hire_date' => '2018-03-10',
        //         'password' => Hash::make('password'),
        //         'email_verified_at' => now(),
        //     ]
        // );

        // Create sample pengaduan data
        //     $pengaduanData = [
        //         [
        //             'user_id' => $user1->id,
        //             'tanggal_laporan' => '2024-01-15',
        //             'perihal' => 'Perselisihan Hak',
        //             'masa_kerja' => '4 tahun 2 bulan',
        //             'kontak_pekerja' => 'john.doe@company.com',
        //             'nama_perusahaan' => 'PT. Teknologi Maju',
        //             'kontak_perusahaan' => 'hr@teknologimaju.com',
        //             'alamat_kantor' => 'Jl. Sudirman No. 123, Jakarta Pusat, DKI Jakarta 10220',
        //             'narasi_kasus' => 'Saya mengalami keterlambatan pembayaran gaji selama 3 bulan berturut-turut tanpa penjelasan yang jelas dari perusahaan. Hal ini sangat mempengaruhi kondisi keuangan keluarga saya. Saya telah mencoba berkomunikasi dengan bagian HRD namun tidak mendapat tanggapan yang memuaskan.',
        //             'catatan_tambahan' => 'Terlampir slip gaji bulan terakhir yang dibayarkan dan email komunikasi dengan HRD.',
        //             'status' => 'pending',
        //         ],
        //         [
        //             'user_id' => $user2->id,
        //             'tanggal_laporan' => '2024-01-20',
        //             'perihal' => 'Perselisihan PHK',
        //             'masa_kerja' => '5 tahun 8 bulan',
        //             'kontak_pekerja' => 'jane.smith@company.com',
        //             'nama_perusahaan' => 'PT. Sejahtera Bersama',
        //             'kontak_perusahaan' => 'contact@sejahterabersama.com',
        //             'alamat_kantor' => 'Jl. HR Rasuna Said Kav. 45, Jakarta Selatan, DKI Jakarta 12940',
        //             'narasi_kasus' => 'Saya di-PHK secara sepihak tanpa mengikuti prosedur yang semestinya. Tidak ada surat peringatan sebelumnya dan alasan PHK tidak jelas. Perusahaan hanya memberikan kompensasi yang tidak sesuai dengan ketentuan perundang-undangan.',
        //             'catatan_tambahan' => 'Saya memiliki kontrak kerja dan tidak pernah mendapat teguran tertulis.',
        //             'status' => 'diproses',
        //             'processed_at' => now(),
        //             'processed_by' => $user3->id,
        //         ],
        //         [
        //             'user_id' => $user1->id,
        //             'tanggal_laporan' => '2024-02-01',
        //             'perihal' => 'Perselisihan Kepentingan',
        //             'masa_kerja' => '4 tahun 3 bulan',
        //             'kontak_pekerja' => 'john.doe@company.com',
        //             'nama_perusahaan' => 'PT. Teknologi Maju',
        //             'kontak_perusahaan' => 'hr@teknologimaju.com',
        //             'alamat_kantor' => 'Jl. Sudirman No. 123, Jakarta Pusat, DKI Jakarta 10220',
        //             'narasi_kasus' => 'Terjadi perubahan jam kerja tanpa kesepakatan bersama dengan pekerja. Manajemen secara sepihak mengubah jam kerja dari 8 jam menjadi 10 jam per hari tanpa kompensasi tambahan yang sesuai.',
        //             'catatan_tambahan' => 'Kebijakan ini berlaku untuk seluruh karyawan di departemen IT.',
        //             'status' => 'selesai',
        //             'processed_at' => now()->subDays(5),
        //             'processed_by' => $user3->id,
        //             'keterangan_admin' => 'Kasus telah diselesaikan melalui mediasi. Perusahaan setuju untuk memberikan kompensasi lembur sesuai ketentuan.',
        //         ],
        //     ];

        //     foreach ($pengaduanData as $data) {
        //         Pengaduan::firstOrCreate(
        //             [
        //                 'user_id' => $data['user_id'],
        //                 'tanggal_laporan' => $data['tanggal_laporan'],
        //                 'perihal' => $data['perihal']
        //             ],
        //             $data
        //         );
        //     }

        //     $this->command->info('Sample pengaduan data created successfully!');
        //     $this->command->info('Test users created:');
        //     $this->command->info('- john.doe@company.com (password: password)');
        //     $this->command->info('- jane.smith@company.com (password: password)');
        //     $this->command->info('- admin@company.com (password: password)');
    }
}
