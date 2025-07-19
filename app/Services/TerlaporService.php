<?php

namespace App\Services;

use App\Models\User;
use App\Models\Terlapor;
use App\Models\Pengaduan;
use Illuminate\Support\Str;
use App\Notifications\TerlaporAccountCreated;
use Illuminate\Support\Facades\DB;

class TerlaporService
{
    /**
     * Handle pengaduan baru dan manajemen terlapor
     */
    public function handlePengaduan(Pengaduan $pengaduan)
    {
        // Cari terlapor berdasarkan nama perusahaan dan email
        $terlapor = Terlapor::findByCompanyInfo(
            $pengaduan->nama_terlapor,
            $pengaduan->email_terlapor
        );

        if (!$terlapor) {
            // Jika terlapor belum ada, buat baru
            $terlapor = Terlapor::create([
                'nama_terlapor' => $pengaduan->nama_terlapor,
                'email_terlapor' => $pengaduan->email_terlapor,
                'no_hp_terlapor' => $pengaduan->no_hp_terlapor,
                'alamat_kantor_cabang' => $pengaduan->alamat_kantor_cabang,
                'has_account' => false,
                'is_active' => true
            ]);
        }

        // Record pengaduan baru
        $terlapor->recordPengaduan();

        // Update pengaduan dengan terlapor_id
        $pengaduan->update(['terlapor_id' => $terlapor->terlapor_id]);

        return $terlapor;
    }

    /**
     * Buat akun untuk terlapor
     */
    public function createAccount(Terlapor $terlapor, string $mediatorId)
    {
        // Validasi
        if (!$terlapor->canCreateAccount()) {
            throw new \Exception('Terlapor sudah memiliki akun.');
        }

        DB::beginTransaction();
        try {
            // Generate password
            $password = Str::random(10);

            // Buat user account
            $user = User::create([
                'email' => $terlapor->email_terlapor,
                'password' => bcrypt($password),
                'roles' => ['terlapor'],
                'active_role' => 'terlapor',
                'is_active' => true
            ]);

            // Update terlapor
            $terlapor->createAccount($user->user_id, $mediatorId);

            // Kirim notifikasi
            $user->notify(new TerlaporAccountCreated($password));

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Aktivasi/deaktivasi akun terlapor
     */
    public function toggleAccountStatus(Terlapor $terlapor, bool $activate)
    {
        if (!$terlapor->has_account) {
            throw new \Exception('Terlapor belum memiliki akun.');
        }

        DB::beginTransaction();
        try {
            if ($activate) {
                $terlapor->activateAccount();
            } else {
                $terlapor->deactivateAccount();
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get terlapor by mediator
     */
    public function getTerlaporByMediator(string $mediatorId)
    {
        return Terlapor::with(['user'])
            ->where('created_by_mediator_id', $mediatorId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get active terlapor
     */
    public function getActiveTerlapor()
    {
        return Terlapor::with(['user'])
            ->active()
            ->hasAccount()
            ->orderBy('nama_terlapor')
            ->get();
    }

    /**
     * Get terlapor statistics
     */
    public function getTerlaporStats()
    {
        return [
            'total' => Terlapor::count(),
            'with_account' => Terlapor::hasAccount()->count(),
            'active' => Terlapor::active()->count(),
            'total_pengaduan' => Terlapor::sum('total_pengaduan')
        ];
    }

    /**
     * Create terlapor account from registration
     */
    public function createTerlaporAccount(array $data, string $mediatorId)
    {
        DB::beginTransaction();
        try {
            // Cek apakah email sudah ada di tabel pelapor
            $existingPelapor = \App\Models\Pelapor::where('email', $data['email_terlapor'])->first();
            
            // Cek apakah email sudah ada di tabel terlapor
            $existingTerlapor = Terlapor::where('email_terlapor', $data['email_terlapor'])->first();
            
            // Jika email sudah terdaftar sebagai pelapor
            if ($existingPelapor) {
                // Cek apakah user sudah memiliki role terlapor
                $user = User::find($existingPelapor->user_id);
                if ($user) {
                    if (!in_array('terlapor', $user->roles)) {
                        // Tambahkan role terlapor ke user yang sudah ada
                        $roles = $user->roles;
                        $roles[] = 'terlapor';
                        $user->update(['roles' => array_unique($roles)]);
                    }

                    // Buat data terlapor baru atau update yang ada
                    if ($existingTerlapor) {
                        $existingTerlapor->update([
                            'nama_terlapor' => $data['nama_terlapor'],
                            'alamat_kantor_cabang' => $data['alamat_kantor_cabang'],
                            'no_hp_terlapor' => $data['no_hp_terlapor'] ?? $existingTerlapor->no_hp_terlapor,
                            'has_account' => true,
                            'is_active' => true,
                            'created_by_mediator_id' => $mediatorId,
                            'user_id' => $user->user_id
                        ]);
                        $terlapor = $existingTerlapor;
                    } else {
                        $terlapor = Terlapor::create([
                            'nama_terlapor' => $data['nama_terlapor'],
                            'email_terlapor' => $data['email_terlapor'],
                            'no_hp_terlapor' => $data['no_hp_terlapor'] ?? null,
                            'alamat_kantor_cabang' => $data['alamat_kantor_cabang'],
                            'has_account' => true,
                            'is_active' => true,
                            'created_by_mediator_id' => $mediatorId,
                            'user_id' => $user->user_id
                        ]);
                    }

                    // Update pengaduan jika ada
                    if (isset($data['pengaduan_id'])) {
                        Pengaduan::where('pengaduan_id', $data['pengaduan_id'])
                            ->update(['terlapor_id' => $terlapor->terlapor_id]);
                    }

                    DB::commit();
                    return [
                        'terlapor' => $terlapor->fresh(),
                        'user' => $user,
                        'status' => 'existing_pelapor_updated'
                    ];
                }
            }

            // Jika email sudah ada di tabel terlapor
            if ($existingTerlapor) {
                // Jika terlapor sudah memiliki akun
                if ($existingTerlapor->has_account) {
                    throw new \Exception('Email ini sudah terdaftar sebagai akun terlapor.');
                }

                // Update data terlapor yang ada
                $existingTerlapor->update([
                    'nama_terlapor' => $data['nama_terlapor'],
                    'alamat_kantor_cabang' => $data['alamat_kantor_cabang'],
                    'no_hp_terlapor' => $data['no_hp_terlapor'] ?? $existingTerlapor->no_hp_terlapor,
                    'has_account' => true,
                    'is_active' => true,
                    'created_by_mediator_id' => $mediatorId
                ]);

                // Generate password
                $password = Str::random(10);

                // Buat user account
                $user = User::create([
                    'email' => $data['email_terlapor'],
                    'password' => bcrypt($password),
                    'roles' => ['terlapor'],
                    'active_role' => 'terlapor',
                    'is_active' => true
                ]);

                // Link user dengan terlapor
                $existingTerlapor->update([
                    'user_id' => $user->user_id
                ]);

                // Kirim notifikasi
                $user->notify(new TerlaporAccountCreated($password));

                DB::commit();

                return [
                    'terlapor' => $existingTerlapor->fresh(),
                    'user' => $user,
                    'temporary_password' => $password,
                    'status' => 'existing_updated'
                ];
            }

            // Jika email belum ada, buat terlapor baru
            $terlapor = Terlapor::create([
                'nama_terlapor' => $data['nama_terlapor'],
                'email_terlapor' => $data['email_terlapor'],
                'no_hp_terlapor' => $data['no_hp_terlapor'] ?? null,
                'alamat_kantor_cabang' => $data['alamat_kantor_cabang'],
                'has_account' => true,
                'is_active' => true,
                'created_by_mediator_id' => $mediatorId
            ]);

            // Generate password
            $password = Str::random(10);

            // Buat user account
            $user = User::create([
                'email' => $data['email_terlapor'],
                'password' => bcrypt($password),
                'roles' => ['terlapor'],
                'active_role' => 'terlapor',
                'is_active' => true
            ]);

            // Link user dengan terlapor
            $terlapor->update([
                'user_id' => $user->user_id
            ]);

            // Kirim notifikasi
            $user->notify(new TerlaporAccountCreated($password));

            // Jika ada pengaduan_id, update pengaduan
            if (isset($data['pengaduan_id'])) {
                Pengaduan::where('pengaduan_id', $data['pengaduan_id'])
                    ->update(['terlapor_id' => $terlapor->terlapor_id]);
            }

            DB::commit();

            return [
                'terlapor' => $terlapor->fresh(),
                'user' => $user,
                'temporary_password' => $password,
                'status' => 'new_created'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
