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

        // Debug log sebelum authenticate
        \Log::info('Before authenticate', [
            'email' => $request->string('email'),
            'session_id' => $request->session()->getId(),
        ]);
        $request->authenticate();

        // Debug log setelah authenticate
        \Log::info('After authenticate', [
            'auth_check' => Auth::check(),
            'auth_id' => Auth::id(),
            'session_id' => $request->session()->getId(),
        ]);

        $request->session()->regenerate();

        // Debug log setelah regenerate
        \Log::info('After session regenerate', [
            'auth_check' => Auth::check(),
            'auth_id' => Auth::id(),
            'new_session_id' => $request->session()->getId(),
        ]);

        $user = Auth::user();

        if (!$user) {
            \Log::error('User not found after authentication');
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Authentication failed. Please try again.',
            ]);
        }

        // ⬅️ Debug user data
        \Log::info('User authenticated', [
            'user_id' => $user->user_id,
            'email' => $user->email,
            'role' => $user->role,
        ]);

        // ✅ Simple approach - langsung akses kolom role
        $role = $user->role ?? null;

        if (!$role) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'User role not found. Please contact administrator.',
            ]);
        }

        //Debug redirect
        \Log::info('Redirecting user', [
            'user_id' => $user->user_id,
            'role' => $role,
        ]);

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
