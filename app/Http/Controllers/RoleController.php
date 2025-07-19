<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function showSelection()
    {
        return view('dashboard.role-selection');
    }

    public function switch(Request $request)
    {
        $request->validate([
            'role' => 'required|in:pelapor,terlapor,mediator,kepala_dinas'
        ]);

        $user = auth()->user();

        if (!$user->hasRole($request->role)) {
            return back()->with('error', 'Anda tidak memiliki akses ke role tersebut.');
        }

        $user->setActiveRole($request->role);

        // Redirect ke dashboard sesuai role
        return redirect()->route('dashboard')->with('success', 'Role berhasil diubah.');
    }
} 