<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pelapor;
use App\Models\Mediator;
use App\Models\Terlapor;
use App\Models\KepalaDinas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Pelapor 1
        $user1 = User::create([
            'email' => 'ecakharzani10@gmail.com',
            'password' => Hash::make('password'),
            'roles' => ['pelapor'],
            'active_role' => 'pelapor',
            'is_active' => true
        ]);

        Pelapor::create([
            'user_id' => $user1->user_id,
            'nama_pelapor' => 'Arieska Kharzani',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Sudirman No. 10, Jakarta',
            'no_hp' => '081234567890',
            'perusahaan' => 'PT Sejahtera Mandiri',
            'npk' => 'EMP001',
            'email' => 'ecakharzani10@gmail.com',
        ]);

        // Pelapor 2
        $user2 = User::create([
            'email' => 'pelapor2@example.com',
            'password' => Hash::make('password'),
            'roles' => ['pelapor'],
            'active_role' => 'pelapor',
            'is_active' => true
        ]);

        Pelapor::create([
            'user_id' => $user2->user_id,
            'nama_pelapor' => 'Siti Nurhaliza',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1988-05-15',
            'jenis_kelamin' => 'Perempuan',
            'alamat' => 'Jl. Asia Afrika No. 25, Bandung',
            'no_hp' => '081987654321',
            'perusahaan' => 'CV Maju Bersama',
            'npk' => 'EMP002',
            'email' => 'siti.nurhaliza@company.com',
        ]);

        // Pelapor 3
        $user3 = User::create([
            'email' => 'pelapor3@example.com',
            'password' => Hash::make('password'),
            'roles' => ['pelapor'],
            'active_role' => 'pelapor',
            'is_active' => true
        ]);

        Pelapor::create([
            'user_id' => $user3->user_id,
            'nama_pelapor' => 'Budi Santoso',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1985-12-20',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Pemuda No. 45, Surabaya',
            'no_hp' => '082123456789',
            'perusahaan' => 'PT Teknologi Nusantara',
            'npk' => 'EMP003',
            'email' => 'budi.santoso@company.com',
        ]);

        // Pelapor 4
        $user4 = User::create([
            'email' => 'pelapor4@example.com',
            'password' => Hash::make('password'),
            'roles' => ['pelapor'],
            'active_role' => 'pelapor',
            'is_active' => true
        ]);

        Pelapor::create([
            'user_id' => $user4->user_id,
            'nama_pelapor' => 'Rina Marlina',
            'tempat_lahir' => 'Medan',
            'tanggal_lahir' => '1992-03-08',
            'jenis_kelamin' => 'Perempuan',
            'alamat' => 'Jl. Gatot Subroto No. 12, Medan',
            'no_hp' => '083456789012',
            'perusahaan' => 'PT Global Industries',
            'npk' => 'EMP004',
            'email' => 'rina.marlina@company.com',
        ]);

        // Pelapor 5
        $user5 = User::create([
            'email' => 'pelapor5@example.com',
            'password' => Hash::make('password'),
            'roles' => ['pelapor'],
            'active_role' => 'pelapor',
            'is_active' => true
        ]);

        Pelapor::create([
            'user_id' => $user5->user_id,
            'nama_pelapor' => 'Dedi Setiawan',
            'tempat_lahir' => 'Yogyakarta',
            'tanggal_lahir' => '1987-09-12',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Malioboro No. 88, Yogyakarta',
            'no_hp' => '084567890123',
            'perusahaan' => 'PT Mitra Sejati',
            'npk' => 'EMP005',
            'email' => 'dedi.setiawan@company.com',
        ]);

        // Terlapor
        $userTerlapor = User::create([
            'email' => 'arieskaeca@gmail.com',
            'password' => Hash::make('password'),
            'roles' => ['terlapor'],
            'active_role' => 'terlapor',
            'is_active' => true
        ]);

        Terlapor::create([
            'user_id' => $userTerlapor->user_id,
            'nama_terlapor' => 'PT ABC Technology',
            'alamat_kantor_cabang' => 'Jl. Teknologi No. 123, Jakarta',
            'email_terlapor' => 'arieskaeca@gmail.com',
            'no_hp_terlapor' => '081234567890',
            'has_account' => true,
            'is_active' => true
        ]);

        // Terlapor 2 (untuk kasus lain)
        $userTerlapor2 = User::create([
            'email' => 'terlapor@example.com',
            'password' => Hash::make('password'),
            'roles' => ['terlapor'],
            'active_role' => 'terlapor',
            'is_active' => true
        ]);

        Terlapor::create([
            'user_id' => $userTerlapor2->user_id,
            'nama_terlapor' => 'Terlapor User',
            'alamat_kantor_cabang' => 'Terlapor Address',
            'email_terlapor' => 'terlapor@example.com',
            'no_hp_terlapor' => '085678901234',
            'has_account' => true,
            'is_active' => true
        ]);

        // Mediator 1
        $userMediator1 = User::create([
            'email' => 'daarsyaaa@gmail.com',
            'password' => Hash::make('password'),
            'roles' => ['mediator'],
            'active_role' => 'mediator',
            'is_active' => true
        ]);

        Mediator::create([
            'user_id' => $userMediator1->user_id,
            'nama_mediator' => 'Mochammad Effendi',
            'nip' => '19821015001',
        ]);

        // Mediator 2
        $userMediator2 = User::create([
            'email' => 'semuabisa.co@gmail.com',
            'password' => Hash::make('password'),
            'roles' => ['mediator'],
            'active_role' => 'mediator',
            'is_active' => true
        ]);

        Mediator::create([
            'user_id' => $userMediator2->user_id,
            'nama_mediator' => 'Dra. Sri Rahayu',
            'nip' => '19751208002',
        ]);

        // Kepala Dinas
        $userKepala = User::create([
            'email' => 'kepaladinas@example.com',
            'password' => Hash::make('password'),
            'roles' => ['kepala_dinas'],
            'active_role' => 'kepala_dinas',
            'is_active' => true
        ]);

        KepalaDinas::create([
            'user_id' => $userKepala->user_id,
            'nama_kepala_dinas' => 'Drs. Bambang Sutrisno, M.Si',
            'nip' => '1970010112345',
        ]);
    }
}
