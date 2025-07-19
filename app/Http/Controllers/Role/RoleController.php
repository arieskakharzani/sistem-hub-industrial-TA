<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Show the role selection page.
     */
    public function selection()
    {
        $user = Auth::user();
        return view('dashboard.role-selection', [
            'roles' => $user->roles
        ]);
    }

    /**
     * Set the active role for the user.
     */
    public function setRole(Request $request)
    {
        $request->validate([
            'role' => 'required|string|in:pelapor,terlapor,mediator,kepala_dinas'
        ]);

        $user = Auth::user();
        
        // Verify that user has this role
        if (!in_array($request->role, $user->roles)) {
            return back()->withErrors(['role' => 'You do not have access to this role.']);
        }

        // Set the active role
        $user->setActiveRole($request->role);

        // Redirect based on new role
        switch ($request->role) {
            case 'pelapor':
                return redirect()->route('dashboard.pelapor');
            case 'terlapor':
                return redirect()->route('dashboard.terlapor');
            case 'mediator':
                return redirect()->route('dashboard.mediator');
            case 'kepala_dinas':
                return redirect()->route('dashboard.kepala-dinas');
            default:
                return redirect()->route('dashboard');
        }
    }
} 