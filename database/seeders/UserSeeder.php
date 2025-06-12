<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pelapor;
use App\Models\Mediator;
use App\Models\Terlapor;
use App\Models\KepalaDinas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Pelapor Test User
        $user = User::create([
            'email' => 'pelapor@example.com',
            'password' => Hash::make('password'),
            'role' => 'pelapor',
        ]);

        Pelapor::create([
            'user_id' => $user->user_id,
            'nama_pelapor' => 'Test User',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Test Address',
            'no_hp' => '081234567890',
            'perusahaan' => 'Test Company',
            'npk' => 'TEST001',
            'email' => 'test@example.com',
        ]);

        // Terlapor
        $user1 = User::create([
            'email' => 'terlapor@example.com',
            'password' => Hash::make('password'),
            'role' => 'terlapor',
        ]);

        Terlapor::create([
            'user_id' => $user1->user_id,
            'nama_perusahaan' => 'Terlapor Company',
            'alamat_kantor_cabang' => 'Terlapor Address',
            'email' => 'terlapor@example.com',
        ]);

        // Mediator
        $user2 = User::create([
            'email' => 'mediator@example.com',
            'password' => Hash::make('password'),
            'role' => 'mediator',
        ]);

        Mediator::create([
            'user_id' => $user2->user_id,
            'nama_mediator' => 'Mediator User',
            'nip' => '1982xxxxxxxxx',
        ]);

        // Kepala Dinas
        $user3 = User::create([
            'email' => 'kepaladinas@example.com',
            'password' => Hash::make('password'),
            'role' => 'kepala_dinas',
        ]);

        KepalaDinas::create([
            'user_id' => $user3->user_id,
            'nama_kepala_dinas' => 'Kepala Dinas User',
            'nip' => '1970xxxxxxxx',
        ]);
    }
}
