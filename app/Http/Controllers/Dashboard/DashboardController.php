<?php

// app/Http/Controllers/Dashboard/DashboardController.php
namespace App\Http\Controllers\Dashboard;

use App\Models\Pelapor;
use App\Models\Pengaduan;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function roleSelection()
    {
        return view('dashboard.role-selection');
    }

    public function setRole(Request $request)
    {
        $user = Auth::user();
        $role = $request->input('role');

        if (!in_array($role, $user->roles)) {
            return back()->withErrors(['role' => 'Role tidak valid.']);
        }

        $user->setActiveRole($role);

        switch ($role) {
            case 'pelapor':
                return redirect()->route('dashboard.pelapor');
            case 'terlapor':
                return redirect()->route('dashboard.terlapor');
            case 'mediator':
                return redirect()->route('dashboard.mediator');
            case 'kepala_dinas':
                return redirect()->route('dashboard.kepala-dinas');
            default:
                return redirect()->route('dashboard.role-selection')
                    ->withErrors(['role' => 'Role tidak valid.']);
        }
    }

    public function pelapor()
    {
        // Gunakan Auth facade untuk lebih aman
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Pastikan user adalah pelapor
        if ($user->active_role !== 'pelapor') {
            abort(403, 'Access denied');
        }

        // Ambil data pelapor berdasarkan user_id
        $pelapor = Pelapor::where('user_id', $user->user_id)->first();

        // Inisialisasi pengaduans sebagai collection kosong
        $pengaduans = collect();
        $jadwal = collect();

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

            // Ambil jadwal yang aktif (dijadwalkan) untuk ditampilkan
            $jadwal = Jadwal::with(['pengaduan', 'mediator'])
                ->whereHas('pengaduan', function ($query) use ($pelapor) {
                    $query->where('pelapor_id', $pelapor->pelapor_id);
                })
                ->where('status_jadwal', 'dijadwalkan')
                ->orderBy('tanggal', 'asc')
                ->get();

            // Hitung stats berdasarkan pengaduan real
            $stats = [
                'total_pengaduan' => $pengaduans->count(),
                'pengaduan_proses' => $pengaduans->whereIn('status', ['pending', 'proses'])->count(),
                'pengaduan_selesai' => $pengaduans->where('status', 'selesai')->count(),
                'jadwal_menunggu_konfirmasi' => $jadwal->where('konfirmasi_pelapor', 'pending')->count(),
            ];
        }

        // Ambil anjuran yang sudah dipublish tapi belum direspon oleh pelapor
        $pendingAnjuran = collect();
        if ($pelapor) {
            $pendingAnjuran = \App\Models\Anjuran::with(['dokumenHI.pengaduan'])
                ->whereHas('dokumenHI.pengaduan', function ($query) use ($pelapor) {
                    $query->where('pelapor_id', $pelapor->pelapor_id);
                })
                ->where('status_approval', 'published')
                ->where(function ($query) {
                    $query->whereNull('response_pelapor')
                        ->orWhere('response_pelapor', 'pending');
                })
                ->where('created_at', '>=', now()->subDays(10)) // Hanya anjuran dalam 10 hari terakhir
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Tambahkan statistik anjuran yang menunggu respon
        $stats['anjuran_menunggu_respon'] = $pendingAnjuran->count();

        return view('dashboard.pelapor', compact('user', 'stats', 'pengaduans', 'pelapor', 'jadwal', 'pendingAnjuran'));
    }

    public function terlapor()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->active_role !== 'terlapor') {
            abort(403, 'Access denied');
        }

        // Inisialisasi
        $jadwal = collect();

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

            // Ambil jadwal  yang perlu dikonfirmasi
            $jadwal = Jadwal::with(['pengaduan', 'mediator'])
                ->whereHas('pengaduan', function ($query) use ($terlapor) {
                    $query->where('terlapor_id', $terlapor->terlapor_id);
                })
                ->where('status_jadwal', 'dijadwalkan')
                ->orderBy('tanggal', 'asc')
                ->get();

            // Hitung stats berdasarkan pengaduan real
            $stats = [
                'total_aduan_terhadap_saya' => $pengaduans->count() ?? 0,
                'menunggu_respons' => $pengaduans->where('status', 'pending')->count() ?? 0,
                'dalam_mediasi' => $pengaduans->where('status', 'proses')->count() ?? 0,
                'jadwal_menunggu_konfirmasi' => $jadwal->where('konfirmasi_terlapor', 'pending')->count(),
                'selesai' => $pengaduans->where('status', 'selesai')->count() ?? 0,
            ];
        }

        // Ambil anjuran yang sudah dipublish tapi belum direspon oleh terlapor
        $pendingAnjuran = collect();
        if ($terlapor) {
            $pendingAnjuran = \App\Models\Anjuran::with(['dokumenHI.pengaduan'])
                ->whereHas('dokumenHI.pengaduan', function ($query) use ($terlapor) {
                    $query->where('terlapor_id', $terlapor->terlapor_id);
                })
                ->where('status_approval', 'published')
                ->where(function ($query) {
                    $query->whereNull('response_terlapor')
                        ->orWhere('response_terlapor', 'pending');
                })
                ->where('created_at', '>=', now()->subDays(10)) // Hanya anjuran dalam 10 hari terakhir
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Tambahkan statistik anjuran yang menunggu respon
        $stats['anjuran_menunggu_respon'] = $pendingAnjuran->count();

        return view('dashboard.terlapor', compact('user', 'stats', 'jadwal', 'pendingAnjuran'));
    }

    public function mediator()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->active_role !== 'mediator') {
            abort(403, 'Access denied');
        }

        // Data khusus untuk mediator
        $stats = [
            'total_kasus_saya' => Pengaduan::count(),
            'kasus_aktif' => Pengaduan::whereIn('status', ['pending', 'proses'])->count(),
            'kasus_selesai' => Pengaduan::where('status', 'selesai')->count(),
            'jadwal_hari_ini' => Jadwal::whereDate('tanggal', today())->count(),
            'menunggu_konfirmasi' => Jadwal::menungguKonfirmasi()->count(),
        ];

        return view('dashboard.mediator', compact('user', 'stats'));
    }

    public function kepalaDinas()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->active_role !== 'kepala_dinas') {
            abort(403, 'Access denied');
        }

        // Data khusus untuk kepala dinas
        $stats = [
            'total_pengaduan' => Pengaduan::count(),
            'menunggu_approval' => \App\Models\Anjuran::where('status_approval', 'pending_kepala_dinas')->count(),
            'dalam_proses' => Pengaduan::where('status', 'proses')->count(),
            'selesai_bulan_ini' => Pengaduan::where('status', 'selesai')
                ->whereMonth('updated_at', now()->month)
                ->whereYear('updated_at', now()->year)
                ->count(),
        ];

        return view('dashboard.kepala-dinas', compact('user', 'stats'));
    }
}
