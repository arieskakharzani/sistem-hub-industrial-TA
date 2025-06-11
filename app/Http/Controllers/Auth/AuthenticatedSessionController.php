<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        // ✅ Simple approach - langsung akses kolom role
        $role = $user->role ?? null;

        if (!$role) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'User role not found. Please contact administrator.',
            ]);
        }

        switch ($role) {
            case 'pelapor':
                return redirect()->intended(route('dashboard.pelapor'));
            case 'terlapor':
                return redirect()->intended(route('dashboard.terlapor'));
            case 'mediator':
                return redirect()->intended(route('dashboard.mediator'));
            case 'kepala_dinas':
                return redirect()->intended(route('dashboard.kepala-dinas'));
            default:
                Auth::logout();
                return redirect()->route('login')->withErrors([
                    'email' => 'Invalid user role.',
                ]);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
