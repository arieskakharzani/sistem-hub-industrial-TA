<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Pelapor;
use App\Models\Mediator;
use App\Models\Terlapor;
use App\Models\Pengaduan;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Users with different roles
        $pelaporUser = User::create([
            'user_id' => Str::uuid(),
            'email' => 'ecakharzani10@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'roles' => json_encode(['pelapor']),
            'active_role' => 'pelapor'
        ]);

        $mediatorUser = User::create([
            'user_id' => Str::uuid(),
            'email' => 'arieskaeca@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'roles' => json_encode(['mediator']),
            'active_role' => 'mediator'
        ]);

        $terlaporUser = User::create([
            'user_id' => Str::uuid(),
            'email' => 'semuabisa.co@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'roles' => json_encode(['terlapor']),
            'active_role' => 'terlapor'
        ]);

        // 2. Create Pelapor Profile
        $pelapor = Pelapor::create([
            'pelapor_id' => Str::uuid(),
            'user_id' => $pelaporUser->user_id,
            'nama_pelapor' => 'Eca Kharzani',
            'tempat_lahir' => 'Muara Bungo',
            'tanggal_lahir' => '1999-10-10',
            'jenis_kelamin' => 'Perempuan',
            'alamat' => 'Jl. Testing No. 123',
            'no_hp' => '081234567890',
            'perusahaan' => 'PT Testing',
            'npk' => 'EMP123',
            'email' => 'ecakharzani10@gmail.com',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 3. Create Mediator Profile
        $mediator = Mediator::create([
            'mediator_id' => Str::uuid(),
            'user_id' => $mediatorUser->user_id,
            'nama_mediator' => 'Eca Mediator',
            'nip' => '198510102023012001',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 4. Create Terlapor Profile
        $terlapor = Terlapor::create([
            'terlapor_id' => Str::uuid(),
            'user_id' => $terlaporUser->user_id,
            'nama_terlapor' => 'PT Semua Bisa',
            'email_terlapor' => 'semuabisa.co@gmail.com',
            'no_hp_terlapor' => '081234567892',
            'alamat_kantor_cabang' => 'Jl. Perusahaan No. 456',
            'has_account' => true,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 5. Create Test Pengaduan
        $pengaduan1 = Pengaduan::create([
            'pengaduan_id' => Str::uuid(),
            'pelapor_id' => $pelapor->pelapor_id,
            'terlapor_id' => null, // Belum dinotifikasi
            'mediator_id' => null, // Belum diassign
            'tanggal_laporan' => now(),
            'perihal' => 'Perselisihan PHK',
            'masa_kerja' => '2 tahun',
            'nama_terlapor' => 'PT Semua Bisa',
            'email_terlapor' => 'semuabisa.co@gmail.com',
            'no_hp_terlapor' => '081234567892',
            'alamat_kantor_cabang' => 'Jl. Perusahaan No. 456',
            'narasi_kasus' => 'PHK sepihak tanpa pesangon',
            'catatan_tambahan' => 'Perusahaan melakukan PHK sepihak tanpa memberikan pesangon sesuai ketentuan',
            'risalah_bipartit' => 'dummy_risalah_1.pdf',
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $pengaduan2 = Pengaduan::create([
            'pengaduan_id' => Str::uuid(),
            'pelapor_id' => $pelapor->pelapor_id,
            'terlapor_id' => $terlapor->terlapor_id, // Sudah dinotifikasi
            'mediator_id' => $mediator->mediator_id, // Sudah diassign
            'tanggal_laporan' => now(),
            'perihal' => 'Perselisihan Hak',
            'masa_kerja' => '1 tahun',
            'nama_terlapor' => 'PT Semua Bisa',
            'email_terlapor' => 'semuabisa.co@gmail.com',
            'no_hp_terlapor' => '081234567892',
            'alamat_kantor_cabang' => 'Jl. Perusahaan No. 456',
            'narasi_kasus' => 'Tidak membayar THR',
            'catatan_tambahan' => 'Perusahaan tidak membayarkan THR sesuai ketentuan',
            'risalah_bipartit' => 'dummy_risalah_2.pdf',
            'status' => 'proses',
            'assigned_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $this->command->info('Testing data created successfully!');
        $this->command->info('You can now login with these accounts:');
        $this->command->info('Pelapor: ecakharzani10@gmail.com');
        $this->command->info('Mediator: arieskaeca@gmail.com');
        $this->command->info('Terlapor: semuabisa.co@gmail.com');
        $this->command->info('Password for all accounts: password');
    }
} 