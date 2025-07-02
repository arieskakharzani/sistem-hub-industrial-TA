<?php

// app/Http/Controllers/Dashboard/DashboardController.php
namespace App\Http\Controllers\Dashboard;

use App\Models\Pelapor;
use App\Models\Pengaduan;
use App\Models\JadwalMediasi;
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
        $jadwalMediasi = collect();

        // Stats default
        $stats = [
            'total_pengaduan' => 0,
            'pengaduan_proses' => 0,
            'pengaduan_selesai' => 0,
            'jadwal_menunggu_konfirmasi' => 0,
        ];

        // Jika pelapor ada, ambil pengaduan dan hitung stats
        if ($pelapor) {
            // Ambil semua pengaduan milik pelapor ini
            $pengaduans = Pengaduan::where('pelapor_id', $pelapor->pelapor_id)
                ->orderBy('created_at', 'desc')
                ->get();

            // Ambil jadwal mediasi yang perlu dikonfirmasi
            $jadwalMediasi = JadwalMediasi::with(['pengaduan', 'mediator'])
                ->whereHas('pengaduan', function ($query) use ($pelapor) {
                    $query->where('pelapor_id', $pelapor->pelapor_id);
                })
                ->where('status_jadwal', 'dijadwalkan')
                ->orderBy('tanggal_mediasi', 'asc')
                ->get();

            // Hitung stats berdasarkan pengaduan real
            $stats = [
                'total_pengaduan' => $pengaduans->count(),
                'pengaduan_proses' => $pengaduans->whereIn('status', ['pending', 'proses'])->count(),
                'pengaduan_selesai' => $pengaduans->where('status', 'selesai')->count(),
                'jadwal_menunggu_konfirmasi' => $jadwalMediasi->where('konfirmasi_pelapor', 'pending')->count(),
            ];
        }

        return view('dashboard.pelapor', compact('user', 'stats', 'pengaduans', 'pelapor', 'jadwalMediasi'));
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

        // Inisialisasi
        $jadwalMediasi = collect();

        // Data khusus untuk terlapor
        $stats = [
            'total_aduan_terhadap_saya' => 0,
            'menunggu_respons' => 0,
            'dalam_mediasi' => 0,
            'jadwal_menunggu_konfirmasi' => 0,
        ];

        // Ambil data pengaduan yang melibatkan terlapor ini
        if ($terlapor = $user->terlapor) {
            // Ambil semua pengaduan yang melibatkan terlapor ini
            $pengaduans = Pengaduan::where('terlapor_id', $terlapor->terlapor_id)
                ->orderBy('created_at', 'desc')
                ->get();

            // Ambil jadwal mediasi yang perlu dikonfirmasi
            $jadwalMediasi = JadwalMediasi::with(['pengaduan', 'mediator'])
                ->whereHas('pengaduan', function ($query) use ($terlapor) {
                    $query->where('terlapor_id', $terlapor->terlapor_id);
                })
                ->where('status_jadwal', 'dijadwalkan')
                ->orderBy('tanggal_mediasi', 'asc')
                ->get();

            // Hitung stats berdasarkan pengaduan real
            $stats = [
                'total_aduan_terhadap_saya' => $pengaduans->count() ?? 0,
                'menunggu_respons' => $pengaduans->where('status', 'pending')->count() ?? 0,
                'dalam_mediasi' => $pengaduans->where('status', 'proses')->count() ?? 0,
                'jadwal_menunggu_konfirmasi' => $jadwalMediasi->where('konfirmasi_terlapor', 'pending')->count(),
            ];
        }

        return view('dashboard.terlapor', compact('user', 'stats', 'jadwalMediasi'));
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
            'jadwal_hari_ini' => JadwalMediasi::whereDate('tanggal_mediasi', today())->count(),
            'menunggu_konfirmasi' => JadwalMediasi::menungguKonfirmasi()->count(),
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
            'total_pengaduan' => Pengaduan::count(),
            'menunggu_approval' => Pengaduan::where('status', 'pending')->count(),
            'dalam_proses' => Pengaduan::where('status', 'proses')->count(),
            'selesai_bulan_ini' => Pengaduan::where('status', 'selesai')
                ->whereMonth('updated_at', now()->month)
                ->whereYear('updated_at', now()->year)
                ->count(),
        ];

        return view('dashboard.kepala-dinas', compact('user', 'stats'));
    }
}
