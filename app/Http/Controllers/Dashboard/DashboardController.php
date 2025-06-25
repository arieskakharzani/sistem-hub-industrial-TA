<?php

// app/Http/Controllers/Dashboard/DashboardController.php
namespace App\Http\Controllers\Dashboard;

use App\Models\Pelapor;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

        // Ambil data pelapor berdasarkan user_id
        $pelapor = Pelapor::where('user_id', $user->user_id)->first();

        // Inisialisasi pengaduans sebagai collection kosong
        $pengaduans = collect();

        // Stats default
        $stats = [
            'total_pengaduan' => 0,
            'pengaduan_proses' => 0,
            'pengaduan_selesai' => 0,
        ];

        // Jika pelapor ada, ambil pengaduan dan hitung stats
        if ($pelapor) {
            // Ambil semua pengaduan milik pelapor ini
            $pengaduans = Pengaduan::where('pelapor_id', $pelapor->pelapor_id)
                ->orderBy('created_at', 'desc')
                ->get();

            // Hitung stats berdasarkan pengaduan real
            $stats = [
                'total_pengaduan' => $pengaduans->count(),
                'pengaduan_proses' => $pengaduans->whereIn('status', ['pending', 'proses'])->count(),
                'pengaduan_selesai' => $pengaduans->where('status', 'selesai')->count(),
            ];
        }

        return view('dashboard.pelapor', compact('user', 'stats', 'pengaduans', 'pelapor'));
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
        // Ambil data pengaduan yang melibatkan terlapor ini
        if ($terlapor = $user->terlapor) {
            // Ambil semua pengaduan milik pelapor ini
            $pengaduans = Pengaduan::where('terlapor_id', $terlapor->terlapor_id)
                ->orderBy('created_at', 'desc')
                ->get();

            // Hitung stats berdasarkan pengaduan real
            $stats = [
                'total_aduan_terhadap_saya' => $pengaduans->count() ?? 0,
                'menunggu_respons' => 0,
                'dalam_mediasi' => 0,
            ];
        }

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
            'total_kasus_saya' => Pengaduan::count(),
            'kasus_aktif' => Pengaduan::whereIn('status', ['pending', 'proses'])->count(),
            'kasus_selesai' => Pengaduan::where('status', 'selesai')->count(),
            'jadwal_hari_ini' => Pengaduan::whereDate('tanggal_laporan', today())->count(),
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
