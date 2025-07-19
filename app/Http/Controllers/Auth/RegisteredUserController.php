<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Pelapor;
use App\Models\Terlapor;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:100'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', 'string', 'in:Laki-laki,Perempuan'],
            'alamat' => ['required', 'string'],
            'no_hp' => ['required', 'string', 'max:15'],
            'perusahaan' => ['required', 'string', 'max:100'],
            'npk' => ['required', 'string', 'max:20'],
        ]);

        DB::beginTransaction();

        try {
            // Cek apakah email sudah ada
            $existingUser = User::where('email', $validated['email'])->first();

            if ($existingUser) {
                // Jika user sudah ada, tambahkan role pelapor
                if (!in_array('pelapor', $existingUser->roles)) {
                    $roles = $existingUser->roles;
                    $roles[] = 'pelapor';
                    $existingUser->update([
                        'roles' => array_unique($roles),
                        'active_role' => 'pelapor' // Set role aktif ke pelapor
                    ]);
                    $user = $existingUser;
                } else {
                    throw new \Exception('Anda sudah terdaftar sebagai pelapor.');
                }
            } else {
                // Jika user belum ada, buat user baru
                $user = User::create([
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'roles' => ['pelapor'],
                    'active_role' => 'pelapor'
                ]);
            }

            // Cek apakah sudah ada data pelapor
            $existingPelapor = Pelapor::where('email', $validated['email'])->first();
            
            if (!$existingPelapor) {
                // Buat data pelapor baru
                $pelapor = Pelapor::create([
                    'user_id' => $user->user_id,
                    'nama_pelapor' => $validated['name'],
                    'tempat_lahir' => $validated['tempat_lahir'],
                    'tanggal_lahir' => $validated['tanggal_lahir'],
                    'jenis_kelamin' => $validated['jenis_kelamin'],
                    'alamat' => $validated['alamat'],
                    'no_hp' => $validated['no_hp'],
                    'perusahaan' => $validated['perusahaan'],
                    'npk' => $validated['npk'],
                    'email' => $validated['email'],
                ]);
            } else {
                // Update data pelapor yang ada
                $existingPelapor->update([
                    'user_id' => $user->user_id,
                    'nama_pelapor' => $validated['name'],
                    'tempat_lahir' => $validated['tempat_lahir'],
                    'tanggal_lahir' => $validated['tanggal_lahir'],
                    'jenis_kelamin' => $validated['jenis_kelamin'],
                    'alamat' => $validated['alamat'],
                    'no_hp' => $validated['no_hp'],
                    'perusahaan' => $validated['perusahaan'],
                    'npk' => $validated['npk']
                ]);
            }

            DB::commit();

            // Jika user baru, trigger event registered
            if (!$existingUser) {
                event(new Registered($user));
            }

            // Login dengan role pelapor
            Auth::login($user);
            session(['active_role' => 'pelapor']);

            return redirect(route('dashboard.pelapor', absolute: false));
        } catch (\Exception $e) {
            DB::rollBack();
            error_log('Registration failed: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->withErrors(['email' => $e->getMessage()]);
        }
    }
}
