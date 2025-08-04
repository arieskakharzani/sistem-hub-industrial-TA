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

    /**
     * Laporan Hasil Mediasi - untuk semua role
     */
    public function laporanHasilMediasi()
    {
        $user = Auth::user();
        $query = LaporanHasilMediasi::with(['dokumenHI.pengaduan.pelapor', 'dokumenHI.pengaduan.terlapor', 'dokumenHI.pengaduan.mediator']);

        // Filter berdasarkan role
        if ($user->active_role === 'pelapor') {
            $pelapor = $user->pelapor;
            $query->whereHas('dokumenHI.pengaduan', function ($q) use ($pelapor) {
                $q->where('pelapor_id', $pelapor->pelapor_id);
            });
        } elseif ($user->active_role === 'terlapor') {
            $terlapor = $user->terlapor;
            $query->whereHas('dokumenHI.pengaduan', function ($q) use ($terlapor) {
                $q->where('terlapor_id', $terlapor->terlapor_id);
            });
        } elseif ($user->active_role === 'mediator') {
            // Mediator bisa lihat semua laporan hasil mediasi
            // Tidak perlu filter berdasarkan mediator_id
        }

        $laporanHasilMediasi = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('laporan.hasil-mediasi', compact('laporanHasilMediasi', 'user'));
    }

    /**
     * Show detail laporan hasil mediasi
     */
    public function showLaporanHasilMediasi($pengaduanId)
    {
        $user = Auth::user();
        $pengaduan = Pengaduan::with(['pelapor', 'terlapor', 'mediator'])->findOrFail($pengaduanId);

        // Authorization check
        if ($user->active_role === 'pelapor') {
            $pelapor = $user->pelapor;
            if ($pengaduan->pelapor_id !== $pelapor->pelapor_id) {
                abort(403, 'Anda tidak memiliki akses ke laporan ini.');
            }
        } elseif ($user->active_role === 'terlapor') {
            $terlapor = $user->terlapor;
            if ($pengaduan->terlapor_id !== $terlapor->terlapor_id) {
                abort(403, 'Anda tidak memiliki akses ke laporan ini.');
            }
        } elseif (!in_array($user->active_role, ['mediator', 'kepala_dinas'])) {
            abort(403, 'Anda tidak memiliki akses ke laporan ini.');
        }

        $laporanHasilMediasi = LaporanHasilMediasi::whereHas('dokumenHI.pengaduan', function ($q) use ($pengaduanId) {
            $q->where('pengaduan_id', $pengaduanId);
        })->first();

        if (!$laporanHasilMediasi) {
            abort(404, 'Laporan hasil mediasi tidak ditemukan.');
        }

        return view('laporan.show-hasil-mediasi', compact('laporanHasilMediasi', 'pengaduan', 'user'));
    }

    /**
     * Buku Register Perselisihan - hanya untuk mediator dan kepala dinas
     */
    public function bukuRegisterPerselisihan()
    {
        $user = Auth::user();

        // Authorization check
        if (!in_array($user->active_role, ['mediator', 'kepala_dinas'])) {
            abort(403, 'Akses terbatas untuk internal dinas.');
        }

        $query = BukuRegisterPerselisihan::with(['dokumenHI.pengaduan.pelapor', 'dokumenHI.pengaduan.terlapor', 'dokumenHI.pengaduan.mediator']);

        // Filter berdasarkan role
        if ($user->active_role === 'mediator') {
            // Mediator bisa lihat semua buku register perselisihan
            // Tidak perlu filter berdasarkan mediator_id
        }

        $bukuRegister = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('laporan.buku-register-perselisihan', compact('bukuRegister', 'user'));
    }

    /**
     * Show detail buku register perselisihan
     */
    public function showBukuRegister($id)
    {
        $user = Auth::user();

        // Authorization check
        if (!in_array($user->active_role, ['mediator', 'kepala_dinas'])) {
            abort(403, 'Akses terbatas untuk internal dinas.');
        }

        $bukuRegister = BukuRegisterPerselisihan::with(['dokumenHI.pengaduan.pelapor', 'dokumenHI.pengaduan.terlapor', 'dokumenHI.pengaduan.mediator'])->findOrFail($id);

        return view('laporan.show-buku-register', compact('bukuRegister', 'user'));
    }

    /**
     * Cetak PDF laporan hasil mediasi
     */
    public function cetakPdfLaporanHasilMediasi($laporanId)
    {
        $user = Auth::user();
        $laporanHasilMediasi = LaporanHasilMediasi::with(['dokumenHI.pengaduan.pelapor', 'dokumenHI.pengaduan.terlapor', 'dokumenHI.pengaduan.mediator'])->findOrFail($laporanId);

        // Authorization check
        if ($user->active_role === 'pelapor') {
            $pelapor = $user->pelapor;
            if ($laporanHasilMediasi->dokumenHI->pengaduan->pelapor_id !== $pelapor->pelapor_id) {
                abort(403, 'Anda tidak memiliki akses ke laporan ini.');
            }
        } elseif ($user->active_role === 'terlapor') {
            $terlapor = $user->terlapor;
            if ($laporanHasilMediasi->dokumenHI->pengaduan->terlapor_id !== $terlapor->terlapor_id) {
                abort(403, 'Anda tidak memiliki akses ke laporan ini.');
            }
        } elseif (!in_array($user->active_role, ['mediator', 'kepala_dinas'])) {
            abort(403, 'Anda tidak memiliki akses ke laporan ini.');
        }

        $pengaduan = $laporanHasilMediasi->dokumenHI->pengaduan;

        // Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.pdf.laporan-hasil-mediasi', compact('laporanHasilMediasi', 'pengaduan'));

        return $pdf->stream('laporan-hasil-mediasi-' . $pengaduan->nomor_pengaduan . '.pdf');
    }
}
