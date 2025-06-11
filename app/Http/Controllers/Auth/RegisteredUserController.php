<?php

namespace App\Http\Controllers\Auth;

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
        // dd([
        //     'method' => $request->method(),
        //     'all_input_detailed' => $request->all(), // Ini akan show semua field
        //     'input_keys' => array_keys($request->all()), // Nama field yang diterima
        //     'has_nama_pelapor' => $request->has('nama_pelapor'),
        //     'nama_pelapor_value' => $request->get('nama_pelapor'),
        // ]);

        // echo "<pre>";
        // echo "=== FULL DEBUG ===\n";
        // echo "Method: " . $request->method() . "\n";
        // echo "All Input Keys: " . print_r(array_keys($request->all()), true) . "\n";
        // echo "All Input Values: " . print_r($request->all(), true) . "\n";
        // echo "Request URL: " . $request->url() . "\n";
        // echo "Request Path: " . $request->path() . "\n";
        // echo "</pre>";
        // die();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
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

        DB::beginTransaction();

        try {
            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'pelapor',
            ]);

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

            DB::commit();

            event(new Registered($user));
            Auth::login($user);

            return redirect(route('dashboard.pelapor', absolute: false));
        } catch (\Exception $e) {
            DB::rollBack();

            error_log('Registration failed: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->withErrors(['email' => 'Registration failed: ' . $e->getMessage()]);
        }
    }
}
