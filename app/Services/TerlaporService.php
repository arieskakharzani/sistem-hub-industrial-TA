<?php

namespace App\Services;

use App\Models\Terlapor;
use App\Models\User;
use App\Models\Pengaduan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class TerlaporService
{
    /**
     * Create terlapor account
     */
    public function createTerlaporAccount(array $data, string $mediatorId): array
    {
        DB::beginTransaction();

        try {
            // Generate temporary password
            $tempPassword = Str::random(12);

            // 1. Create user account
            $user = User::create([
                'email' => $data['email_terlapor'],
                'password' => Hash::make($tempPassword),
                'role' => 'terlapor',
                'is_active' => true,
                'email_verified_at' => now()
            ]);

            // 2. Create terlapor record
            $terlapor = Terlapor::create([
                'user_id' => $user->user_id,
                'nama_terlapor' => $data['nama_terlapor'],
                'email_terlapor' => $data['email_terlapor'],
                'no_hp_terlapor' => $data['no_hp_terlapor'] ?? null,
                'alamat_kantor_cabang' => $data['alamat_kantor_cabang'],
                'status' => 'active',
                'created_by_mediator_id' => $mediatorId
            ]);

            // 3. Send credentials email
            $this->sendCredentialsEmail($terlapor, $tempPassword, $data['pengaduan_id'] ?? null);

            DB::commit();

            Log::info("Terlapor account created successfully", [
                'terlapor_id' => $terlapor->terlapor_id,
                'user_id' => $user->user_id,
                'mediator_id' => $mediatorId,
                'pengaduan_id' => $data['pengaduan_id'] ?? null
            ]);

            return [
                'terlapor' => $terlapor,
                'user' => $user,
                'temporary_password' => $tempPassword
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating terlapor account: ' . $e->getMessage(), [
                'data' => $data,
                'mediator_id' => $mediatorId
            ]);
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
     * Deactivate terlapor
     */
    public function deactivateTerlapor(string $terlaporId, string $mediatorId): bool
    {
        try {
            $terlapor = Terlapor::with('user')
                ->where('terlapor_id', $terlaporId)
                ->where('created_by_mediator_id', $mediatorId)
                ->first();

            if (!$terlapor) {
                return false;
            }

            DB::beginTransaction();

            // Update terlapor status
            $terlapor->update(['status' => 'inactive']);

            // Update user status
            if ($terlapor->user) {
                $terlapor->user->update(['is_active' => false]);
            }

            DB::commit();

            Log::info("Terlapor deactivated", [
                'terlapor_id' => $terlaporId,
                'mediator_id' => $mediatorId
            ]);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deactivating terlapor: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Activate terlapor
     */
    public function activateTerlapor(string $terlaporId, string $mediatorId): bool
    {
        try {
            $terlapor = Terlapor::with('user')
                ->where('terlapor_id', $terlaporId)
                ->where('created_by_mediator_id', $mediatorId)
                ->first();

            if (!$terlapor) {
                return false;
            }

            DB::beginTransaction();

            // Update terlapor status
            $terlapor->update(['status' => 'active']);

            // Update user status
            if ($terlapor->user) {
                $terlapor->user->update(['is_active' => true]);
            }

            DB::commit();

            Log::info("Terlapor activated", [
                'terlapor_id' => $terlaporId,
                'mediator_id' => $mediatorId
            ]);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error activating terlapor: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Send credentials email to terlapor
     */
    private function sendCredentialsEmail(Terlapor $terlapor, string $tempPassword, ?string $pengaduanId = null): void
    {
        try {
            $emailData = [
                'nama_terlapor' => $terlapor->nama_terlapor,
                'email' => $terlapor->email_terlapor,
                'password' => $tempPassword,
                'login_url' => route('login'),
                'pengaduan_id' => $pengaduanId
            ];

            // Send email (adjust with your mail template)
            Mail::send('akun.terlapor-credentials', $emailData, function ($message) use ($terlapor) {
                $message->to($terlapor->email_terlapor, $terlapor->nama_terlapor)
                    ->subject('Akun Sistem Penyelesaian Hubungan Industrial - Kredensial Login');
            });

            Log::info("Credentials email sent", [
                'terlapor_id' => $terlapor->terlapor_id,
                'email' => $terlapor->email_terlapor,
                'pengaduan_id' => $pengaduanId
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send credentials email: ' . $e->getMessage(), [
                'terlapor_id' => $terlapor->terlapor_id,
                'email' => $terlapor->email_terlapor
            ]);
        }
    }
}
