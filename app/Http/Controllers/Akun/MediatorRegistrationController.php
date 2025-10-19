<?php

namespace App\Http\Controllers\Akun;

use App\Http\Controllers\Controller;
use App\Models\Mediator;
use App\Models\User;
use App\Notifications\NewMediatorRegistrationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MediatorRegistrationController extends Controller
{
    /**
     * Show the mediator registration form
     */
    public function showRegistrationForm()
    {
        return view('akun.mediator-register');
    }

    /**
     * Handle mediator registration
     */
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_mediator' => 'required|string|max:255',
            'nip' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    // Cek apakah NIP sudah terdaftar dengan status bukan rejected
                    $existingMediator = Mediator::where('nip', $value)
                        ->where('status', '!=', 'rejected')
                        ->first();

                    if ($existingMediator) {
                        $fail('NIP sudah terdaftar');
                    }
                },
            ],
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    // Cek apakah email sudah terdaftar dengan status bukan rejected
                    $existingUser = User::where('email', $value)->first();

                    if ($existingUser) {
                        // Cek apakah user ini adalah mediator yang ditolak
                        $mediator = Mediator::where('user_id', $existingUser->user_id)->first();

                        if (!$mediator || $mediator->status !== 'rejected') {
                            $fail('Email sudah terdaftar');
                        }
                    }
                },
            ],
            'sk_file' => 'required|file|mimes:pdf|max:5120', // 5MB = 5120KB
        ], [
            'nama_mediator.required' => 'Nama mediator wajib diisi',
            'nip.required' => 'NIP wajib diisi',
            'nip.unique' => 'NIP sudah terdaftar',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'sk_file.required' => 'File SK wajib diupload',
            'sk_file.mimes' => 'File SK harus berformat PDF',
            'sk_file.max' => 'Ukuran file SK maksimal 5MB',
        ]);

        try {
            // Upload file SK
            $skFile = $request->file('sk_file');
            $fileName = 'sk_' . time() . '_' . Str::random(10) . '.pdf';
            $filePath = $skFile->storeAs('sk_mediator', $fileName, 'public');

            // Cek apakah ada mediator yang ditolak dengan NIP/email yang sama
            $existingMediator = Mediator::where('nip', $request->nip)
                ->where('status', 'rejected')
                ->with('user')
                ->first();

            if ($existingMediator && $existingMediator->user->email === $request->email) {
                // Update mediator yang ditolak
                $existingMediator->update([
                    'nama_mediator' => $request->nama_mediator,
                    'sk_file_path' => $filePath,
                    'sk_file_name' => $skFile->getClientOriginalName(),
                    'sk_file_size' => $skFile->getSize(),
                    'status' => 'pending',
                    'rejection_reason' => null,
                    'rejection_date' => null,
                ]);

                $mediator = $existingMediator;
                $user = $existingMediator->user;

                Log::info('Mediator registration updated (re-registration)', [
                    'mediator_id' => $mediator->mediator_id,
                    'nama_mediator' => $mediator->nama_mediator,
                    'email' => $user->email,
                ]);
            } else {
                // Generate random password
                $password = Str::random(12);

                // Buat user account baru
                $user = User::create([
                    'user_id' => (string) Str::uuid(),
                    'email' => $request->email,
                    'password' => Hash::make($password),
                    'roles' => ['mediator'],
                    'active_role' => 'mediator',
                    'is_active' => false, // Belum aktif sampai di-approve
                    'email_verified_at' => null,
                ]);

                // Buat mediator record baru
                $mediator = Mediator::create([
                    'mediator_id' => (string) Str::uuid(),
                    'user_id' => $user->user_id,
                    'nama_mediator' => $request->nama_mediator,
                    'nip' => $request->nip,
                    'sk_file_path' => $filePath,
                    'sk_file_name' => $skFile->getClientOriginalName(),
                    'sk_file_size' => $skFile->getSize(),
                    'status' => 'pending',
                ]);

                Log::info('Mediator registration submitted (new)', [
                    'mediator_id' => $mediator->mediator_id,
                    'nama_mediator' => $mediator->nama_mediator,
                    'email' => $user->email,
                ]);
            }

            // Kirim notifikasi ke Kepala Dinas
            $this->notifyKepalaDinas($mediator);

            return redirect()->route('mediator.register.success')
                ->with('success', 'Pendaftaran berhasil! Akun Anda sedang menunggu persetujuan dari Kepala Dinas.');
        } catch (\Exception $e) {
            Log::error('Error in mediator registration', [
                'error' => $e->getMessage(),
                'request_data' => $request->except(['sk_file'])
            ]);

            // Hapus file jika ada error
            if (isset($filePath) && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.');
        }
    }

    /**
     * Show registration success page
     */
    public function success()
    {
        return view('akun.mediator-register-success');
    }

    /**
     * Send notification to Kepala Dinas about new mediator registration
     */
    private function notifyKepalaDinas(Mediator $mediator)
    {
        try {
            Log::info('Starting notifyKepalaDinas', [
                'mediator_id' => $mediator->mediator_id,
                'mediator_name' => $mediator->nama_mediator
            ]);

            // Cari semua user dengan role kepala_dinas
            $kepalaDinasUsers = User::whereJsonContains('roles', 'kepala_dinas')
                ->where('is_active', true)
                ->get();

            Log::info('Found Kepala Dinas users', [
                'count' => $kepalaDinasUsers->count(),
                'emails' => $kepalaDinasUsers->pluck('email')->toArray()
            ]);

            if ($kepalaDinasUsers->isEmpty()) {
                Log::warning('No active Kepala Dinas found');
                return;
            }

            // Kirim email langsung menggunakan Mail facade
            foreach ($kepalaDinasUsers as $kepalaDinas) {
                Log::info('Sending email to Kepala Dinas', [
                    'email' => $kepalaDinas->email,
                    'user_id' => $kepalaDinas->user_id
                ]);

                try {
                    \Mail::send('emails.new-mediator-registration', [
                        'mediator' => $mediator,
                        'actionUrl' => route('kepala-dinas.mediator.approval.index')
                    ], function ($message) use ($kepalaDinas, $mediator) {
                        $message->to($kepalaDinas->email)
                            ->subject('Mediator Baru Mendaftar - Perlu Approval - SIPPPHI');
                    });

                    Log::info('Email sent successfully', [
                        'email' => $kepalaDinas->email
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send email', [
                        'email' => $kepalaDinas->email,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            Log::info('All emails sent successfully', [
                'mediator_id' => $mediator->mediator_id,
                'kepala_dinas_count' => $kepalaDinasUsers->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send notification to Kepala Dinas', [
                'mediator_id' => $mediator->mediator_id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
    }
}
