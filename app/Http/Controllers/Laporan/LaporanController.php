<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Risalah;
use App\Models\PerjanjianBersama;
use App\Models\Anjuran;
use App\Models\Pengaduan;
use App\Models\LaporanHasilMediasi;
use App\Models\BukuRegisterPerselisihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Index laporan untuk semua aktor
     */
    public function index()
    {
        $user = Auth::user();

        // Statistik berdasarkan role
        $stats = $this->getLaporanStats($user);

        // Laporan terbaru
        $recentReports = $this->getRecentReports($user);

        return view('laporan.index', compact('stats', 'recentReports', 'user'));
    }

    /**
     * Laporan untuk pihak terkait dan pengadilan HI
     */
    public function laporanPihakTerkait()
    {
        $user = Auth::user();

        // Filter berdasarkan role
        $query = Pengaduan::with(['pelapor', 'terlapor', 'mediator', 'dokumenHI'])
            ->where('status', 'selesai');

        if ($user->active_role === 'pelapor') {
            $pelapor = $user->pelapor;
            $query->where('pelapor_id', $pelapor->pelapor_id);
        } elseif ($user->active_role === 'terlapor') {
            $terlapor = $user->terlapor;
            $query->where('terlapor_id', $terlapor->terlapor_id);
        } elseif ($user->active_role === 'mediator') {
            $mediator = $user->mediator;
            $query->where('mediator_id', $mediator->mediator_id);
        }
        // Kepala dinas bisa lihat semua

        $pengaduans = $query->orderBy('updated_at', 'desc')->paginate(15);

        return view('laporan.pihak-terkait', compact('pengaduans', 'user'));
    }

    /**
     * Laporan kasus selesai dengan detail penyelesaian
     */
    public function laporanKasusSelesai()
    {
        $user = Auth::user();

        $query = Pengaduan::with([
            'pelapor',
            'terlapor',
            'mediator',
            'dokumenHI.risalah',
            'dokumenHI.perjanjianBersama',
            'dokumenHI.anjuran',
            'dokumenHI.laporanHasilMediasi'
        ])->where('status', 'selesai');

        // Filter berdasarkan role
        if ($user->active_role === 'pelapor') {
            $pelapor = $user->pelapor;
            $query->where('pelapor_id', $pelapor->pelapor_id);
        } elseif ($user->active_role === 'terlapor') {
            $terlapor = $user->terlapor;
            $query->where('terlapor_id', $terlapor->terlapor_id);
        } elseif ($user->active_role === 'mediator') {
            $mediator = $user->mediator;
            $query->where('mediator_id', $mediator->mediator_id);
        }

        // Filter tambahan
        $perihal = request('perihal');
        $tanggal_mulai = request('tanggal_mulai');
        $tanggal_akhir = request('tanggal_akhir');
        $jenis_penyelesaian = request('jenis_penyelesaian');

        if ($perihal) {
            $query->where('perihal', $perihal);
        }

        if ($tanggal_mulai) {
            $query->whereDate('updated_at', '>=', $tanggal_mulai);
        }

        if ($tanggal_akhir) {
            $query->whereDate('updated_at', '<=', $tanggal_akhir);
        }

        $pengaduans = $query->orderBy('updated_at', 'desc')->paginate(15);

        // Statistik
        $stats = [
            'total_selesai' => $query->count(),
            'sepakat' => $query->whereHas('dokumenHI.perjanjianBersama')->count(),
            'tidak_sepakat' => $query->whereHas('dokumenHI.anjuran')->count(),
            'bulan_ini' => $query->whereMonth('updated_at', now()->month)->count(),
        ];

        return view('laporan.kasus-selesai', compact('pengaduans', 'stats', 'user'));
    }

    /**
     * Laporan untuk pengadilan HI (kasus tidak sepakat)
     */
    public function laporanPengadilanHI()
    {
        $user = Auth::user();

        // Hanya kasus yang tidak sepakat (ada anjuran)
        $query = Pengaduan::with([
            'pelapor',
            'terlapor',
            'mediator',
            'dokumenHI.anjuran',
            'dokumenHI.laporanHasilMediasi'
        ])
            ->where('status', 'selesai')
            ->whereHas('dokumenHI.anjuran');

        // Filter berdasarkan role
        if ($user->active_role === 'mediator') {
            $mediator = $user->mediator;
            $query->where('mediator_id', $mediator->mediator_id);
        }

        $pengaduans = $query->orderBy('updated_at', 'desc')->paginate(15);

        return view('laporan.pengadilan-hi', compact('pengaduans', 'user'));
    }

    /**
     * Generate laporan PDF untuk kasus tertentu
     */
    public function generateLaporanPDF(Pengaduan $pengaduan)
    {
        $user = Auth::user();

        // Cek akses
        if ($user->active_role === 'pelapor') {
            $pelapor = $user->pelapor;
            if ($pengaduan->pelapor_id !== $pelapor->pelapor_id) {
                abort(403, 'Access denied');
            }
        } elseif ($user->active_role === 'terlapor') {
            $terlapor = $user->terlapor;
            if ($pengaduan->terlapor_id !== $terlapor->terlapor_id) {
                abort(403, 'Access denied');
            }
        } elseif ($user->active_role === 'mediator') {
            $mediator = $user->mediator;
            if ($pengaduan->mediator_id !== $mediator->mediator_id) {
                abort(403, 'Access denied');
            }
        }

        // Load relasi yang diperlukan
        $pengaduan->load([
            'pelapor',
            'terlapor',
            'mediator',
            'dokumenHI.risalah',
            'dokumenHI.perjanjianBersama',
            'dokumenHI.anjuran',
            'dokumenHI.laporanHasilMediasi'
        ]);

        // Tentukan jenis laporan berdasarkan dokumen yang ada
        $jenisLaporan = 'umum';
        if ($pengaduan->dokumenHI->first()?->anjuran) {
            $jenisLaporan = 'pengadilan_hi';
        } elseif ($pengaduan->dokumenHI->first()?->perjanjianBersama) {
            $jenisLaporan = 'sepakat';
        }

        return view("laporan.pdf.{$jenisLaporan}", compact('pengaduan', 'jenisLaporan'));
    }

    /**
     * Get laporan statistics berdasarkan role
     */
    private function getLaporanStats($user)
    {
        $baseQuery = Pengaduan::where('status', 'selesai');

        // Filter berdasarkan role
        if ($user->active_role === 'pelapor') {
            $pelapor = $user->pelapor;
            $baseQuery->where('pelapor_id', $pelapor->pelapor_id);
        } elseif ($user->active_role === 'terlapor') {
            $terlapor = $user->terlapor;
            $baseQuery->where('terlapor_id', $terlapor->terlapor_id);
        } elseif ($user->active_role === 'mediator') {
            $mediator = $user->mediator;
            $baseQuery->where('mediator_id', $mediator->mediator_id);
        }

        return [
            'total_selesai' => $baseQuery->count(),
            'sepakat' => $baseQuery->whereHas('dokumenHI.perjanjianBersama')->count(),
            'tidak_sepakat' => $baseQuery->whereHas('dokumenHI.anjuran')->count(),
            'bulan_ini' => $baseQuery->whereMonth('updated_at', now()->month)->count(),
            'tahun_ini' => $baseQuery->whereYear('updated_at', now()->year)->count(),
        ];
    }

    /**
     * Get recent reports berdasarkan role
     */
    private function getRecentReports($user)
    {
        $query = Pengaduan::with(['pelapor', 'terlapor', 'mediator'])
            ->where('status', 'selesai')
            ->orderBy('updated_at', 'desc')
            ->limit(5);

        // Filter berdasarkan role
        if ($user->active_role === 'pelapor') {
            $pelapor = $user->pelapor;
            $query->where('pelapor_id', $pelapor->pelapor_id);
        } elseif ($user->active_role === 'terlapor') {
            $terlapor = $user->terlapor;
            $query->where('terlapor_id', $terlapor->terlapor_id);
        } elseif ($user->active_role === 'mediator') {
            $mediator = $user->mediator;
            $query->where('mediator_id', $mediator->mediator_id);
        }

        return $query->get();
    }
}
