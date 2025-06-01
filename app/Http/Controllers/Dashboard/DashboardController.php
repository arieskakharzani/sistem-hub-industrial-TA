<?php

// app/Http/Controllers/Dashboard/DashboardController.php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Middleware akan dihandle di routes

    public function pelapor()
    {
        // Gunakan Auth facade untuk lebih aman
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Pastikan user adalah pelapor
        if ($user->role !== 'pelapor') {
            abort(403, 'Access denied');
        }

        // Data khusus untuk pelapor
        $stats = [
            'total_pengaduan' => 0, // Query pengaduan milik user
            'pengaduan_proses' => 0,
            'pengaduan_selesai' => 0,
        ];

        return view('dashboard.pelapor', compact('user', 'stats'));
    }

    public function terlapor()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->role !== 'terlapor') {
            abort(403, 'Access denied');
        }

        // Data khusus untuk terlapor
        $stats = [
            'total_aduan_terhadap_saya' => 0,
            'menunggu_respons' => 0,
            'dalam_mediasi' => 0,
        ];

        return view('dashboard.terlapor', compact('user', 'stats'));
    }

    public function mediator()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->role !== 'mediator') {
            abort(403, 'Access denied');
        }

        // Data khusus untuk mediator
        $stats = [
            'total_kasus_saya' => 0,
            'kasus_aktif' => 0,
            'kasus_selesai' => 0,
            'jadwal_hari_ini' => 0,
        ];

        return view('dashboard.mediator', compact('user', 'stats'));
    }

    public function kepalaDinas()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->role !== 'kepala_dinas') {
            abort(403, 'Access denied');
        }

        // Data khusus untuk kepala dinas
        $stats = [
            'total_pengaduan' => 0,
            'menunggu_approval' => 0,
            'dalam_proses' => 0,
            'selesai_bulan_ini' => 0,
        ];

        return view('dashboard.kepala-dinas', compact('user', 'stats'));
    }
}
