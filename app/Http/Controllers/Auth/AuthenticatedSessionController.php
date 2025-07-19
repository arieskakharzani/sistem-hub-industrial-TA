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

        if (!$user) {
            \Log::error('User not found after authentication');
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Authentication failed. Please try again.',
            ]);
        }

        // Debug user data
        \Log::info('User authenticated', [
            'user_id' => $user->user_id,
            'email' => $user->email,
            'roles' => $user->roles,
            'active_role' => $user->active_role
        ]);

        // Check if user has multiple roles
        if (count($user->roles) > 1) {
            return redirect()->route('dashboard.role-selection');
        }

        // Get active role
        $role = $user->active_role;

        if (!$role) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'User role not found. Please contact administrator.',
            ]);
        }

        // Debug redirect
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
