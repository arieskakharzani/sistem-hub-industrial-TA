// database/migrations/xxxx_migrate_users_data.php
<?php

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
            // Insert ke tabel users baru
            $user_id = DB::table('users')->insertGetId([
                'email' => $old_user->email,
                'password' => $old_user->password,
                'role' => $old_user->role,
                // 'is_active' => true,
                'created_at' => $old_user->created_at,
                'updated_at' => $old_user->updated_at,
            ]);

            // Insert ke tabel role sesuai role user
            switch ($old_user->role) {
                case 'pelapor':
                    DB::table('pelapor')->insert([
                        'user_id' => $user_id,
                        'nama_pelapor' => $old_user->name,
                        'tempat_lahir' => $old_user->tempat_lahir,
                        'tanggal_lahir' => $old_user->tanggal_lahir,
                        'jenis_kelamin' => $old_user->jenis_kelamin,
                        'alamat' => $old_user->alamat,
                        'no_hp' => $old_user->no_hp,
                        'perusahaan' => $old_user->perusahaan,
                        'npk' => $old_user->npk,
                        'email' => $old_user->email,
                        // 'email_verified_at' => $old_user->email_verified_at,
                        // 'role' => $old_user->role,
                        'created_at' => $old_user->created_at,
                        'updated_at' => $old_user->updated_at,
                    ]);
                    break;

                case 'terlapor':
                    DB::table('terlapor')->insert([
                        'user_id' => $user_id,
                        'nama_perusahaan' => $old_user->perusahaan, // mapping field
                        'alamat_kantor_cabang' => $old_user->alamat,
                        'email' => $old_user->email,
                        // 'no_hp' => $old_user->no_hp,
                        'created_at' => $old_user->created_at,
                        'updated_at' => $old_user->updated_at,
                    ]);
                    break;

                case 'mediator':
                    DB::table('mediator')->insert([
                        'user_id' => $user_id,
                        'nama_mediator' => $old_user->name,
                        'nip' => $old_user->npk, // mapping field
                        'created_at' => $old_user->created_at,
                        'updated_at' => $old_user->updated_at,
                    ]);
                    break;

                case 'kepala_dinas':
                    DB::table('kepala_dinas')->insert([
                        'user_id' => $user_id,
                        'nama_kepala_dinas' => $old_user->name,
                        'nip' => $old_user->npk, // mapping field
                        'created_at' => $old_user->created_at,
                        'updated_at' => $old_user->updated_at,
                    ]);
                    break;

                default:
                    echo "Warning: Unknown role '{$old_user->role}' for user {$old_user->email}\n";
                    break;
            }
            echo "Data migration completed successfully.\n";
        }
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
