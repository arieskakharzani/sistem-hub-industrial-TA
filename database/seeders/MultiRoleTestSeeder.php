<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pelapor;
use App\Models\Terlapor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MultiRoleTestSeeder extends Seeder
{
    public function run()
    {
        // 1. User dengan role terlapor saja
        $terlaporOnly = User::create([
            'email' => 'terlapor.only@test.com',
            'password' => Hash::make('password123'),
            'roles' => ['terlapor'],
            'active_role' => 'terlapor',
            'is_active' => true
        ]);

        Terlapor::create([
            'user_id' => $terlaporOnly->user_id,
            'nama_terlapor' => 'PT Terlapor Only',
            'email_terlapor' => 'terlapor.only@test.com',
            'alamat_kantor_cabang' => 'Jl. Test No. 1',
            'no_hp_terlapor' => '08123456789',
            'has_account' => true,
            'is_active' => true
        ]);

        // 2. User dengan role pelapor saja
        $pelaporOnly = User::create([
            'email' => 'pelapor.only@test.com',
            'password' => Hash::make('password123'),
            'roles' => ['pelapor'],
            'active_role' => 'pelapor',
            'is_active' => true
        ]);

        Pelapor::create([
            'user_id' => $pelaporOnly->user_id,
            'nama_pelapor' => 'Pelapor Only',
            'email' => 'pelapor.only@test.com',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Test No. 2',
            'no_hp' => '08123456788',
            'perusahaan' => 'PT ABC',
            'npk' => '12345'
        ]);

        // 3. User dengan multi-role (terlapor dan pelapor)
        $multiRole = User::create([
            'email' => 'multi.role@test.com',
            'password' => Hash::make('password123'),
            'roles' => ['terlapor', 'pelapor'],
            'active_role' => 'terlapor', // Default role
            'is_active' => true
        ]);

        Terlapor::create([
            'user_id' => $multiRole->user_id,
            'nama_terlapor' => 'PT Multi Role',
            'email_terlapor' => 'multi.role@test.com',
            'alamat_kantor_cabang' => 'Jl. Test No. 3',
            'no_hp_terlapor' => '08123456787',
            'has_account' => true,
            'is_active' => true
        ]);

        Pelapor::create([
            'user_id' => $multiRole->user_id,
            'nama_pelapor' => 'Multi Role Person',
            'email' => 'multi.role@test.com',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1992-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Test No. 4',
            'no_hp' => '08123456786',
            'perusahaan' => 'PT XYZ',
            'npk' => '67890'
        ]);
    }
} 