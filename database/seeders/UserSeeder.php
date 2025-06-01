<?php

// database/seeders/UserRoleSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Pelapor;
use App\Models\Terlapor;
use App\Models\Mediator;
use App\Models\KepalaDinas;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clean tables
        DB::table('kepala_dinas')->truncate();
        DB::table('mediator')->truncate();
        DB::table('terlapor')->truncate();
        DB::table('pelapor')->truncate();
        DB::table('users')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->createPelapor();
        $this->createTerlapor();
        $this->createMediator();
        $this->createKepalaDinas();

        $this->command->info('✅ UserRoleSeeder completed successfully!');
        $this->command->info('📊 Created users summary:');
        $this->command->info('👤 Pelapor: ' . Pelapor::count() . ' users');
        $this->command->info('🏢 Terlapor: ' . Terlapor::count() . ' users');
        $this->command->info('⚖️ Mediator: ' . Mediator::count() . ' users');
        $this->command->info('👑 Kepala Dinas: ' . KepalaDinas::count() . ' users');
        $this->command->info('📈 Total Users: ' . User::count());
    }

    private function createPelapor(): void
    {
        $pelapors = [
            [
                'email' => 'pelapor1@example.com',
                'password' => 'password123',
                'nama_pelapor' => 'Ahmad Rizki Pratama',         // ✅ Updated field name
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1990-05-15',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                'no_hp' => '081234567890',
                'perusahaan' => 'PT. Teknologi Maju',
                'npk' => 'NPK001',
            ],
            [
                'email' => 'pelapor2@example.com',
                'password' => 'password123',
                'nama_pelapor' => 'Siti Nurhaliza',              // ✅ Updated field name
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1992-08-22',
                'jenis_kelamin' => 'Perempuan',
                'alamat' => 'Jl. Braga No. 45, Bandung',
                'no_hp' => '081234567891',
                'perusahaan' => 'PT. Garment Indonesia',
                'npk' => 'NPK002',
            ],
            [
                'email' => 'pelapor3@example.com',
                'password' => 'password123',
                'nama_pelapor' => 'Budi Santoso',                // ✅ Updated field name
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '1988-12-10',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Jl. Pemuda No. 78, Surabaya',
                'no_hp' => '081234567892',
                'perusahaan' => 'PT. Manufaktur Jaya',
                'npk' => 'NPK003',
            ],
        ];

        foreach ($pelapors as $pelaporData) {
            // ✅ Create user first with correct structure
            $user = User::create([
                'email' => $pelaporData['email'],
                'password' => Hash::make($pelaporData['password']),
                'role' => 'pelapor',                             // ✅ Set role in users table
            ]);

            // ✅ Create pelapor profile with correct field names
            Pelapor::create([
                'user_id' => $user->user_id,
                'nama_pelapor' => $pelaporData['nama_pelapor'],  // ✅ Correct field name
                'tempat_lahir' => $pelaporData['tempat_lahir'],
                'tanggal_lahir' => $pelaporData['tanggal_lahir'],
                'jenis_kelamin' => $pelaporData['jenis_kelamin'],
                'alamat' => $pelaporData['alamat'],
                'no_hp' => $pelaporData['no_hp'],
                'perusahaan' => $pelaporData['perusahaan'],
                'npk' => $pelaporData['npk'],
                'email' => $pelaporData['email'],
            ]);
        }

        $this->command->info('✅ Created ' . count($pelapors) . ' Pelapor users');
    }

    private function createTerlapor(): void
    {
        $terlapors = [
            [
                'email' => 'terlapor1@company.com',
                'password' => 'password123',
                'nama_perusahaan' => 'PT. Konstruksi Megah',
                'alamat_kantor_cabang' => 'Jl. TB Simatupang No. 100, Jakarta Selatan',
                'no_hp' => '021-5551001',
            ],
            [
                'email' => 'terlapor2@company.com',
                'password' => 'password123',
                'nama_perusahaan' => 'PT. Tekstil Nusantara',
                'alamat_kantor_cabang' => 'Jl. Asia Afrika No. 200, Bandung',
                'no_hp' => '022-5551002',
            ],
        ];

        foreach ($terlapors as $terlaporData) {
            $user = User::create([
                'email' => $terlaporData['email'],
                'password' => Hash::make($terlaporData['password']),
                'role' => 'terlapor',                            // ✅ Set role in users table
            ]);

            Terlapor::create([
                'user_id' => $user->user_id,
                'nama_perusahaan' => $terlaporData['nama_perusahaan'],
                'alamat_kantor_cabang' => $terlaporData['alamat_kantor_cabang'],
                'email' => $terlaporData['email'],
                'no_hp' => $terlaporData['no_hp'],               // ✅ Include no_hp
            ]);
        }

        $this->command->info('✅ Created ' . count($terlapors) . ' Terlapor users');
    }

    private function createMediator(): void
    {
        $mediators = [
            [
                'email' => 'mediator1@dinaker.go.id',
                'password' => 'password123',
                'nama_mediator' => 'Dr. Agus Wijaya, S.H., M.H.',
                'nip' => '196505151990031001',
            ],
            [
                'email' => 'mediator2@dinaker.go.id',
                'password' => 'password123',
                'nama_mediator' => 'Dra. Sri Mulyani, M.Si.',
                'nip' => '197208101995032002',
            ],
        ];

        foreach ($mediators as $mediatorData) {
            $user = User::create([
                'email' => $mediatorData['email'],
                'password' => Hash::make($mediatorData['password']),
                'role' => 'mediator',                            // ✅ Set role in users table
            ]);

            Mediator::create([
                'user_id' => $user->user_id,
                'nama_mediator' => $mediatorData['nama_mediator'],
                'nip' => $mediatorData['nip'],
            ]);
        }

        $this->command->info('✅ Created ' . count($mediators) . ' Mediator users');
    }

    private function createKepalaDinas(): void
    {
        $kepalaDinas = [
            [
                'email' => 'kepala.dinas@dinaker.go.id',
                'password' => 'password123',
                'nama' => 'Prof. Dr. Ir. Soekarno Hatta, M.Sc.',  // ✅ Use 'nama' field
                'nip' => '196212171985031001',
            ],
        ];

        foreach ($kepalaDinas as $kepalaData) {
            $user = User::create([
                'email' => $kepalaData['email'],
                'password' => Hash::make($kepalaData['password']),
                'role' => 'kepala_dinas',                        // ✅ Set role in users table
            ]);

            KepalaDinas::create([
                'user_id' => $user->user_id,
                'nama' => $kepalaData['nama'],                   // ✅ Use 'nama' field
                'nip' => $kepalaData['nip'],
            ]);
        }

        $this->command->info('✅ Created ' . count($kepalaDinas) . ' Kepala Dinas users');
    }
}
