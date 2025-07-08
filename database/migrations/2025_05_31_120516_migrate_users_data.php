<?php
// database/migrations/2025_05_31_120516_migrate_users_data.php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        // Cek apakah users_backup ada
        if (!Schema::hasTable('users_backup')) {
            echo "Warning: users_backup table not found. Skipping data migration.\n";
            return;
        }

        $users_backup = DB::table('users_backup')->get();

        if ($users_backup->isEmpty()) {
            echo "Warning: No data found in users_backup. Skipping data migration.\n";
            return;
        }

        foreach ($users_backup as $old_user) {
            // Generate UUID untuk user baru
            $user_id = (string) Str::uuid();

            // Insert ke tabel users baru - PERBAIKAN: tidak assign ke variable
            DB::table('users')->insert([
                'user_id' => $user_id,
                'email' => $old_user->email,
                'password' => $old_user->password,
                'role' => $old_user->role,
                'is_active' => true,
                'created_at' => $old_user->created_at,
                'updated_at' => $old_user->updated_at,
            ]);

            echo "Migrated user: {$old_user->email} with ID: {$user_id}\n";

            // Insert ke tabel role sesuai role user
            switch ($old_user->role) {
                case 'pelapor':
                    DB::table('pelapor')->insert([
                        'pelapor_id' => (string) Str::uuid(),
                        'user_id' => $user_id, // Gunakan UUID yang sudah digenerate
                        'nama_pelapor' => $old_user->name,
                        'tempat_lahir' => $old_user->tempat_lahir,
                        'tanggal_lahir' => $old_user->tanggal_lahir,
                        'jenis_kelamin' => $old_user->jenis_kelamin,
                        'alamat' => $old_user->alamat,
                        'no_hp' => $old_user->no_hp,
                        'perusahaan' => $old_user->perusahaan,
                        'npk' => $old_user->npk,
                        'email' => $old_user->email,
                        'created_at' => $old_user->created_at,
                        'updated_at' => $old_user->updated_at,
                    ]);
                    break;

                case 'terlapor':
                    DB::table('terlapor')->insert([
                        'terlapor_id' => (string) Str::uuid(),
                        'user_id' => $user_id,
                        'nama_terlapor' => $old_user->name, // PERBAIKAN: gunakan 'name' bukan 'nama_terlapor'
                        'alamat_kantor_cabang' => $old_user->alamat,
                        'email_terlapor' => $old_user->email,
                        'no_hp_terlapor' => $old_user->no_hp,
                        'created_at' => $old_user->created_at,
                        'updated_at' => $old_user->updated_at,
                    ]);
                    break;

                case 'mediator':
                    DB::table('mediator')->insert([
                        'mediator_id' => (string) Str::uuid(),
                        'user_id' => $user_id,
                        'nama_mediator' => $old_user->name,
                        'nip' => $old_user->npk,
                        'created_at' => $old_user->created_at,
                        'updated_at' => $old_user->updated_at,
                    ]);
                    break;

                case 'kepala_dinas':
                    DB::table('kepala_dinas')->insert([
                        'kepala_dinas_id' => (string) Str::uuid(),
                        'user_id' => $user_id,
                        'nama_kepala_dinas' => $old_user->name,
                        'nip' => $old_user->npk,
                        'created_at' => $old_user->created_at,
                        'updated_at' => $old_user->updated_at,
                    ]);
                    break;

                default:
                    echo "Warning: Unknown role '{$old_user->role}' for user {$old_user->email}\n";
                    break;
            }
        }

        echo "Data migration completed successfully.\n";
    }

    public function down()
    {
        // Rollback data jika diperlukan
        DB::table('users')->truncate();
        DB::table('pelapor')->truncate();
        DB::table('terlapor')->truncate();
        DB::table('mediator')->truncate();
        DB::table('kepala_dinas')->truncate();
    }
};
