<?php

namespace App\Http\Controllers\Auth;

use Log;
use App\Models\User;
use App\Models\Pelapor;
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
        $request->validate([
            'nama_pelapor' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', 'string', 'in:Laki-laki,Perempuan'],
            'alamat' => ['required', 'string'],
            'no_hp' => ['required', 'string', 'max:15'],
            'perusahaan' => ['required', 'string', 'max:100'],
            'npk' => ['required', 'string', 'max:20'],
        ]);

        //✅ Use Database Transaction untuk atomicity
        // DB::beginTransaction();

        // try {
        // ✅ 1. Create User dulu di tabel users
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pelapor',  // ✅ Set role di tabel users
        ]);

        // ✅ 2. Create Pelapor profile di tabel pelapor
        $pelapor = Pelapor::create([
            'user_id' => $user->user_id,                    // ✅ Link ke user
            'nama_pelapor' => $request->nama_pelapor,       // ✅ Update field name
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'perusahaan' => $request->perusahaan,
            'npk' => $request->npk,
            'email' => $request->email,                     // ✅ Email juga di tabel pelapor
        ]);

        // DB::commit();

        // ✅ Trigger registered event dengan user object
        event(new Registered($user));

        // ✅ Login dengan user object (bukan pelapor object)
        Auth::login($user);

        return redirect(route('dashboard.pelapor', absolute: false));

        // } catch (\Exception $e) {
        // DB::rollBack();

        // Log error untuk debugging
        //    \Log::error('Registration failed: ' . $e->getMessage());

        // return redirect()->back()
        //     ->withInput()
        //     ->withErrors(['email' => 'Registration failed. Please try again.']);
        // }
    }
}
