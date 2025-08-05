<?php

namespace App\Http\Controllers\Pengaduan;

use App\Models\Pelapor;
use App\Models\Mediator;
use App\Models\Terlapor;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use App\Events\PengaduanCreated;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\BukuRegisterPerselisihan;
use App\Models\DokumenHubunganIndustrial;
use App\Models\Risalah;
use App\Notifications\TerlaporPengaduanNotification;

class PengaduanController extends Controller
{
    /**
     * Display a listing of pengaduan for regular users (pelapor)
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->active_role === 'pelapor') {
            $pelapor = Pelapor::where('user_id', $user->user_id)->first();

            if (!$pelapor) {
                // Jika belum ada record pelapor, tampilkan halaman kosong dengan pagination
                $pengaduans = new \Illuminate\Pagination\LengthAwarePaginator(
                    collect(), // empty collection
                    0, // total items
                    10, // items per page
                    1, // current page
                    ['path' => request()->url(), 'pageName' => 'page']
                );
            } else {
                // Pelapor hanya bisa lihat pengaduan sendiri dengan force refresh
                $pengaduans = Pengaduan::where('pelapor_id', $pelapor->pelapor_id)
                    ->with(['pelapor', 'mediator.user'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

                // Force refresh untuk memastikan status terbaru
                $pengaduans->getCollection()->each(function ($pengaduan) {
                    $pengaduan->refresh();
                });
            }
        } else {
            // Redirect jika bukan pelapor
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return view('pengaduan.index', compact('user', 'pengaduans', 'pelapor'));
    }

    /**
     * Display a listing of pengaduan for terlapor
     */
    public function indexTerlapor()
    {
        $user = Auth::user();

        // Pastikan user adalah terlapor
        if ($user->active_role !== 'terlapor') {
            abort(403, 'Access denied');
        }

        // Ambil data terlapor berdasarkan user_id
        $terlapor = Terlapor::where('user_id', $user->user_id)->first();

        if (!$terlapor) {
            return redirect()->route('dashboard.terlapor')
                ->with('error', 'Profil terlapor tidak ditemukan. Silakan hubungi administrator.');
        }

        // Ambil semua pengaduan yang melibatkan terlapor ini
        $pengaduans = Pengaduan::where('terlapor_id', $terlapor->terlapor_id)
            ->with(['pelapor', 'mediator.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Statistik untuk terlapor
        $stats = [
            'total_pengaduan' => $pengaduans->total(),
            'proses' => Pengaduan::where('terlapor_id', $terlapor->terlapor_id)->where('status', 'proses')->count(),
            'selesai' => Pengaduan::where('terlapor_id', $terlapor->terlapor_id)->where('status', 'selesai')->count(),
        ];

        return view('pengaduan.index-terlapor', compact('pengaduans', 'stats', 'terlapor'));
    }

    /**
     * Show specific pengaduan for terlapor
     */
    public function showTerlapor(Pengaduan $pengaduan)
    {
        $user = Auth::user();

        // Pastikan user adalah terlapor
        if ($user->active_role !== 'terlapor') {
            abort(403, 'Access denied');
        }

        // Ambil data terlapor berdasarkan user_id
        $terlapor = Terlapor::where('user_id', $user->user_id)->first();

        if (!$terlapor) {
            return redirect()->route('dashboard.terlapor')
                ->with('error', 'Profil terlapor tidak ditemukan. Silakan hubungi administrator.');
        }

        // Pastikan pengaduan ini terkait dengan terlapor yang sedang login
        if ($pengaduan->terlapor_id !== $terlapor->terlapor_id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat pengaduan ini.');
        }

        // Load relasi yang diperlukan
        $pengaduan->load(['pelapor', 'mediator.user', 'terlapor', 'jadwal']);

        return view('pengaduan.show-terlapor', compact('pengaduan', 'terlapor'));
    }

    /**
     * Semua mediator bisa melihat semua pengaduan
     */
    public function kelola(Request $request)
    {
        $user = Auth::user();

        if (!in_array($user->active_role, ['mediator', 'kepala_dinas'])) {
            abort(403, 'Access denied');
        }

        $query = Pengaduan::with([
            'pelapor.user',
            'terlapor.user',
            'mediator.user',
            'jadwal',
            'dokumenHI.risalah'
        ]);

        // Filter pencarian
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('nomor_pengaduan', 'like', "%$q%")
                    ->orWhere('perihal', 'like', "%$q%")
                    ->orWhereHas('pelapor', function ($q2) use ($q) {
                        $q2->where('nama_pelapor', 'like', "%$q%")
                            ->orWhere('email', 'like', "%$q%");
                    })
                    ->orWhereHas('terlapor', function ($q2) use ($q) {
                        $q2->where('nama_terlapor', 'like', "%$q%")
                            ->orWhere('email_terlapor', 'like', "%$q%");
                    });
            });
        }

        $pengaduans = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        if ($user->active_role === 'mediator') {
            $mediator = $user->mediator;
            $stats = [
                'total_semua_pengaduan' => Pengaduan::count(),
                'total_kasus' => Pengaduan::count(),
                'kasus_aktif' => Pengaduan::whereIn('status', ['pending', 'proses'])->count(),
                'kasus_selesai' => Pengaduan::where('status', 'selesai')->count(),
                'pengaduan_tersedia' => Pengaduan::whereNull('mediator_id')->count(),
            ];
        } else {
            $stats = [
                'total_kasus' => Pengaduan::count(),
                'kasus_aktif' => Pengaduan::whereIn('status', ['pending', 'proses'])->count(),
                'kasus_selesai' => Pengaduan::where('status', 'selesai')->count(),
            ];
        }

        return view('pengaduan.kelola', compact('pengaduans', 'stats'));
    }

    /**
     * Display a listing of pengaduan for kepala dinas (read-only mode)
     */
    public function indexKepalaDinas(Request $request)
    {
        $user = Auth::user();

        if ($user->active_role !== 'kepala_dinas') {
            abort(403, 'Access denied');
        }

        $query = Pengaduan::with([
            'pelapor.user',
            'terlapor.user',
            'mediator.user',
            'jadwal',
            'dokumenHI.risalah'
        ]);

        // Filter pencarian
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('nomor_pengaduan', 'like', "%$q%")
                    ->orWhere('perihal', 'like', "%$q%")
                    ->orWhereHas('pelapor', function ($q2) use ($q) {
                        $q2->where('nama_pelapor', 'like', "%$q%")
                            ->orWhere('email', 'like', "%$q%");
                    })
                    ->orWhereHas('terlapor', function ($q2) use ($q) {
                        $q2->where('nama_terlapor', 'like', "%$q%")
                            ->orWhere('email_terlapor', 'like', "%$q%");
                    });
            });
        }

        $pengaduans = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $stats = [
            'total_kasus' => Pengaduan::count(),
            'kasus_aktif' => Pengaduan::whereIn('status', ['pending', 'proses'])->count(),
            'kasus_selesai' => Pengaduan::where('status', 'selesai')->count(),
        ];

        return view('pengaduan.index-kepala-dinas', compact('pengaduans', 'stats'));
    }

    /**
     * Show pengaduan detail for kepala dinas (read-only mode)
     */
    public function showKepalaDinas(Pengaduan $pengaduan)
    {
        $user = Auth::user();

        if ($user->active_role !== 'kepala_dinas') {
            abort(403, 'Access denied');
        }

        // Load semua relasi yang diperlukan
        $pengaduan->load([
            'pelapor.user',
            'terlapor.user',
            'mediator.user',
            'jadwal' => function ($query) {
                $query->orderBy('tanggal', 'asc');
            },
            'dokumenHI.risalah.detailKlarifikasi',
            'dokumenHI.risalah.detailMediasi',
            'dokumenHI.risalah.detailPenyelesaian',
            'dokumenHI.anjuran',
            'dokumenHI.perjanjianBersama'
        ]);

        return view('pengaduan.show-kepala-dinas', compact('pengaduan'));
    }

    /**
     * Show the form for creating a new pengaduan
     */
    public function create()
    {
        $user = Auth::user();

        // Hanya pelapor yang bisa buat pengaduan
        if ($user->active_role !== 'pelapor') {
            abort(403, 'Access denied');
        }

        $pelapor = Pelapor::where('user_id', $user->user_id)->first();
        if (!$pelapor) {
            return redirect()->route('pengaduan.index')
                ->with('error', 'Silakan lengkapi profil pelapor Anda terlebih dahulu sebelum membuat pengaduan.');
        }

        $perihalOptions = Pengaduan::getPerihalOptions();

        return view('pengaduan.create', compact('perihalOptions', 'pelapor', 'user'));
    }

    /**
     * Store a newly created pengaduan
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->active_role !== 'pelapor') {
            abort(403, 'Access denied');
        }

        $pelapor = Pelapor::where('user_id', $user->user_id)->first();
        if (!$pelapor) {
            return redirect()->route('pengaduan.index')
                ->with('error', 'Profil pelapor tidak ditemukan');
        }

        $validated = $request->validate([
            'tanggal_laporan' => 'required|date',
            'perihal' => 'required|in:' . implode(',', Pengaduan::getPerihalOptions()),
            'masa_kerja' => 'required|string|max:100',
            'nama_terlapor' => 'required|string|max:255',
            'email_terlapor' => 'required|string|max:100',
            'no_hp_terlapor' => 'required|string|max:15',
            'alamat_kantor_cabang' => 'nullable|string',
            'narasi_kasus' => 'required|string',
            'catatan_tambahan' => 'nullable|string',
            'risalah_bipartit' => 'required|file|mimes:pdf|max:10240',
            'lampiran.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120' // 5MB max
        ]);

        // Handle risalah bipartit upload
        $risalahBipartitPath = null;
        if ($request->hasFile('risalah_bipartit')) {
            $risalahBipartitPath = $request->file('risalah_bipartit')->store('risalah-bipartit', 'public');
        }

        // Handle file uploads
        $lampiranPaths = [];
        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $path = $file->store('pengaduan-lampiran', 'public');
                $lampiranPaths[] = $path;
            }
        }

        $pengaduan = new Pengaduan($validated);
        $pengaduan->pelapor_id = $pelapor->pelapor_id;
        $pengaduan->risalah_bipartit = $risalahBipartitPath;
        $pengaduan->lampiran = $lampiranPaths;
        $pengaduan->status = 'pending';
        $pengaduan->save();

        // Load relasi yang diperlukan untuk notifikasi
        $pengaduan->load('pelapor');

        // Debug log
        \Log::info('Triggering PengaduanCreated event', [
            'pengaduan_id' => $pengaduan->pengaduan_id,
            'pelapor' => $pengaduan->pelapor->nama_pelapor
        ]);

        // Dispatch event untuk notifikasi
        event(new PengaduanCreated($pengaduan));

        \Log::info('PengaduanCreated event triggered successfully');


        return redirect()->route('dashboard')
            ->with('success', 'Pengaduan berhasil dibuat');
    }

    /**
     * Semua mediator bisa melihat detail semua pengaduan
     */
    public function show(Pengaduan $pengaduan)
    {
        $user = Auth::user();

        //Authorization berdasarkan role - semua mediator bisa lihat
        if ($user->active_role === 'pelapor') {
            $pelapor = Pelapor::where('user_id', $user->user_id)->first();
            if (!$pelapor || $pengaduan->pelapor_id !== $pelapor->pelapor_id) {
                abort(403, 'Access denied');
            }

            // Force refresh data dari database untuk memastikan status terbaru
            $pengaduan->refresh();

            // Load relasi yang diperlukan untuk pelapor
            $pengaduan->load(['pelapor', 'mediator.user', 'terlapor']);

            // Return view khusus untuk pelapor
            return view('pengaduan.show-pelapor', compact('pengaduan', 'pelapor'));
        } elseif ($user->active_role === 'mediator') {
            // Tidak ada restriction lagi berdasarkan assignment
            $mediator = $user->mediator;
            if (!$mediator) {
                return redirect()->route('dashboard')->with('error', 'Profil mediator tidak ditemukan.');
            }
            // Authorization check dihapus - semua mediator bisa lihat
        } elseif (!in_array($user->active_role, ['kepala_dinas'])) {
            abort(403, 'Access denied');
        }

        // Load dengan relationship yang benar
        $pengaduan->load(['pelapor', 'mediator.user', 'terlapor']);

        return view('pengaduan.show', compact('pengaduan'));
    }

    /**
     * Show the form for editing the specified pengaduan
     */
    public function edit(Pengaduan $pengaduan)
    {
        $user = Auth::user();

        // Hanya pelapor yang bisa edit pengaduan sendiri, dan hanya jika status masih pending
        if ($user->active_role !== 'pelapor' || $pengaduan->status !== 'pending') {
            if ($user->active_role === 'pelapor' && $pengaduan->status !== 'pending') {
                return redirect()->route('pengaduan.show', $pengaduan->pengaduan_id)
                    ->with('error', 'Pengaduan tidak dapat diubah karena sudah direview oleh mediator.');
            }
            abort(403, 'Access denied');
        }

        $pelapor = Pelapor::where('user_id', $user->user_id)->first();
        if (!$pelapor || $pengaduan->pelapor_id !== $pelapor->pelapor_id) {
            abort(403, 'Access denied');
        }

        $perihalOptions = Pengaduan::getPerihalOptions();

        return view('pengaduan.edit', compact('pengaduan', 'perihalOptions', 'pelapor'));
    }

    /**
     * Update the specified pengaduan
     */
    public function update(Request $request, Pengaduan $pengaduan)
    {
        $user = Auth::user();

        if ($user->active_role !== 'pelapor' || $pengaduan->status !== 'pending') {
            abort(403, 'Access denied');
        }

        $pelapor = Pelapor::where('user_id', $user->user_id)->first();
        if (!$pelapor || $pengaduan->pelapor_id !== $pelapor->pelapor_id) {
            abort(403, 'Access denied');
        }

        $validated = $request->validate([
            'tanggal_laporan' => 'required|date',
            'perihal' => 'required|in:' . implode(',', Pengaduan::getPerihalOptions()),
            'masa_kerja' => 'required|string|max:100',
            'nama_terlapor' => 'required|string|max:255',
            'email_terlapor' => 'required|string|max:100',
            'no_hp_terlapor' => 'required|string|max:15',
            'alamat_kantor_cabang' => 'nullable|string',
            'narasi_kasus' => 'required|string',
            'catatan_tambahan' => 'nullable|string',
            'risalah_bipartit' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
            'lampiran.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120'
        ]);

        // Handle risalah bipartit upload (jika ada file baru)
        if ($request->hasFile('risalah_bipartit')) {
            // Hapus file lama jika ada
            if ($pengaduan->risalah_bipartit && file_exists(storage_path('app/public/' . $pengaduan->risalah_bipartit))) {
                unlink(storage_path('app/public/' . $pengaduan->risalah_bipartit));
            }

            $validated['risalah_bipartit'] = $request->file('risalah_bipartit')->store('risalah-bipartit', 'public');
        }

        // Handle new file uploads
        $existingLampiran = $pengaduan->lampiran ?? [];
        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $path = $file->store('pengaduan-lampiran', 'public');
                $existingLampiran[] = $path;
            }
        }

        $validated['lampiran'] = $existingLampiran;
        $pengaduan->update($validated);

        return redirect()->route('pengaduan.show', $pengaduan->pengaduan_id)
            ->with('success', 'Pengaduan berhasil diperbarui');
    }

    /**
     * Remove the specified pengaduan
     */
    public function destroy(Pengaduan $pengaduan)
    {
        $user = Auth::user();

        // Hanya pelapor yang bisa hapus pengaduan sendiri, dan hanya jika status masih pending
        if ($user->active_role !== 'pelapor' || $pengaduan->status !== 'pending') {
            abort(403, 'Access denied');
        }

        $pelapor = Pelapor::where('user_id', $user->user_id)->first();
        if (!$pelapor || $pengaduan->pelapor_id !== $pelapor->pelapor_id) {
            abort(403, 'Access denied');
        }

        $pengaduan->delete();

        return redirect()->route('pengaduan.index')
            ->with('success', 'Pengaduan berhasil dihapus');
    }

    /**
     * Assign pengaduan ke mediator saat ini
     */
    public function assign(Pengaduan $pengaduan)
    {
        $user = Auth::user();

        // Hanya mediator yang bisa mengambil pengaduan
        if ($user->active_role !== 'mediator') {
            abort(403, 'Access denied');
        }

        $mediator = $user->mediator;
        if (!$mediator) {
            return redirect()->back()
                ->with('error', 'Profil mediator tidak ditemukan. Silakan hubungi administrator.');
        }

        // Cek apakah pengaduan sudah diambil oleh mediator lain
        if ($pengaduan->mediator_id && $pengaduan->mediator_id !== $mediator->mediator_id) {
            return redirect()->back()
                ->with('error', 'Pengaduan ini sudah diambil oleh mediator lain.');
        }

        // Assign pengaduan ke mediator_id
        $pengaduan->update([
            'mediator_id' => $mediator->mediator_id,
            'assigned_at' => now(),
            'status' => 'proses' // Otomatis ubah status ke proses ketika diambil
        ]);

        return redirect()->back()
            ->with('success', 'Pengaduan berhasil diambil. Anda sekarang bertanggung jawab atas kasus ini.');
    }

    /**
     * Hanya mediator yang assigned atau kepala dinas yang bisa update
     */
    public function updateStatus(Request $request, Pengaduan $pengaduan)
    {
        $user = Auth::user();

        // Hanya mediator atau kepala dinas yang bisa update status
        if (!in_array($user->active_role, ['mediator', 'kepala_dinas'])) {
            abort(403, 'Access denied');
        }

        // ✅ PERUBAHAN: Untuk mediator, WAJIB yang assigned yang bisa update
        if ($user->active_role === 'mediator') {
            $mediator = $user->mediator;
            if (!$mediator || $pengaduan->mediator_id !== $mediator->mediator_id) {
                return redirect()->back()
                    ->with('error', 'Anda tidak dapat mengelola pengaduan ini karena bukan mediator yang bertanggung jawab atas kasus ini.');
            }
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,proses,selesai',
            'catatan_mediator' => 'nullable|string'
        ]);

        $status = $request->input('status');

        // Validasi khusus untuk status selesai
        if ($status === 'selesai') {
            // Cek apakah kasus selesai melalui klarifikasi atau mediasi
            $hasKlarifikasiRisalah = $pengaduan->dokumenHI()
                ->whereHas('risalah', function ($query) {
                    $query->where('jenis_risalah', 'klarifikasi');
                })->exists();

            $hasMediasiRisalah = $pengaduan->dokumenHI()
                ->whereHas('risalah', function ($query) {
                    $query->where('jenis_risalah', 'mediasi');
                })->exists();

            $hasPenyelesaianRisalah = $pengaduan->dokumenHI()
                ->whereHas('risalah', function ($query) {
                    $query->where('jenis_risalah', 'penyelesaian');
                })->exists();

            $hasPerjanjianBersama = $pengaduan->dokumenHI()
                ->whereHas('perjanjianBersama')->exists();

            // Kasus selesai jika:
            // 1. Ada risalah klarifikasi (bipartit_lagi) - TIDAK perlu PB
            // 2. Ada risalah penyelesaian + perjanjian bersama (mediasi berhasil)
            // 3. Anjuran ditolak (kedua pihak tidak setuju atau mixed response)
            $canBeCompleted = false;
            $completionType = '';

            if ($hasKlarifikasiRisalah) {
                // Cek kesimpulan klarifikasi
                $klarifikasiRisalah = $pengaduan->dokumenHI()
                    ->whereHas('risalah', function ($query) {
                        $query->where('jenis_risalah', 'klarifikasi');
                    })->first()->risalah()->where('jenis_risalah', 'klarifikasi')->first();

                if ($klarifikasiRisalah && $klarifikasiRisalah->kesimpulan_klarifikasi === 'bipartit_lagi') {
                    $canBeCompleted = true;
                    $completionType = 'klarifikasi_bipartit';
                }
            }

            if ($hasPenyelesaianRisalah && $hasPerjanjianBersama) {
                $canBeCompleted = true;
                $completionType = 'mediasi_berhasil';
            }

            // Cek apakah ada anjuran yang ditolak
            $hasAnjuran = $pengaduan->dokumenHI()->whereHas('anjuran')->exists();
            if ($hasAnjuran) {
                $anjuran = $pengaduan->dokumenHI()->first()->anjuran()->first();
                if (
                    $anjuran && $anjuran->bothPartiesResponded() &&
                    ($anjuran->isBothPartiesDisagree() || $anjuran->isMixedResponse())
                ) {
                    $canBeCompleted = true;
                    $completionType = 'anjuran_ditolak';
                }
            }

            if (!$canBeCompleted) {
                return redirect()->back()
                    ->with('error', 'Kasus belum dapat diselesaikan. Pastikan ada risalah klarifikasi dengan kesimpulan "Bipartit Lagi", risalah penyelesaian dengan perjanjian bersama, atau anjuran yang ditolak oleh para pihak.');
            }

            // Update status
            $pengaduan->status = $status;
            $pengaduan->save();

            // Buat buku register otomatis
            $this->createBukuRegisterOtomatis($pengaduan, $completionType);

            // Kirim email sesuai jenis penyelesaian
            if ($completionType === 'mediasi_berhasil') {
                \Log::info('Memanggil method kirimDraftPerjanjianBersama untuk pengaduan: ' . $pengaduan->nomor_pengaduan);
                $this->kirimDraftPerjanjianBersama($pengaduan);
            }

            // Generate laporan otomatis
            $laporanService = new \App\Services\LaporanService();
            $laporanService->generateLaporanOtomatis($pengaduan);

            $successMessage = $completionType === 'klarifikasi_bipartit'
                ? 'Kasus berhasil diselesaikan melalui klarifikasi dengan kesimpulan bipartit lagi.'
                : 'Kasus berhasil diselesaikan. Draft Perjanjian Bersama telah dikirim ke email para pihak.';

            return redirect()->back()->with('success', $successMessage);
        }

        // Untuk status lain, update normal
        $pengaduan->status = $status;
        $pengaduan->save();

        // Jika status diubah menjadi 'proses' dan sebelumnya 'selesai', hapus laporan yang sudah ada
        if ($status === 'proses' && $pengaduan->getOriginal('status') === 'selesai') {
            $this->cleanupReportsWhenStatusChangedToProses($pengaduan);
        }

        return redirect()->back()
            ->with('success', 'Status pengaduan berhasil diperbarui');
    }

    /**
     * Cleanup laporan dan buku register ketika status diubah menjadi 'proses'
     */
    private function cleanupReportsWhenStatusChangedToProses(Pengaduan $pengaduan)
    {
        try {
            // Hapus laporan hasil mediasi yang terkait dengan pengaduan ini
            $deletedLaporan = \App\Models\LaporanHasilMediasi::whereHas('dokumenHI', function ($query) use ($pengaduan) {
                $query->where('pengaduan_id', $pengaduan->pengaduan_id);
            })->delete();

            // Hapus buku register perselisihan yang terkait dengan pengaduan ini
            $deletedBukuRegister = \App\Models\BukuRegisterPerselisihan::whereHas('dokumenHI', function ($query) use ($pengaduan) {
                $query->where('pengaduan_id', $pengaduan->pengaduan_id);
            })->delete();

            \Log::info('Cleanup reports when status changed to proses', [
                'pengaduan_id' => $pengaduan->pengaduan_id,
                'nomor_pengaduan' => $pengaduan->nomor_pengaduan,
                'deleted_laporan_count' => $deletedLaporan,
                'deleted_buku_register_count' => $deletedBukuRegister
            ]);
        } catch (\Exception $e) {
            \Log::error('Error cleaning up reports: ' . $e->getMessage(), [
                'pengaduan_id' => $pengaduan->pengaduan_id,
                'nomor_pengaduan' => $pengaduan->nomor_pengaduan
            ]);
        }
    }

    /**
     * Buat buku register otomatis ketika kasus selesai
     * Method ini akan menganalisis jenis penyelesaian dan mengisi buku register sesuai dengan completionType
     */
    private function createBukuRegisterOtomatis(Pengaduan $pengaduan, string $completionType)
    {
        try {
            // Cek apakah sudah ada buku register untuk pengaduan ini
            $existingBukuRegister = \App\Models\BukuRegisterPerselisihan::whereHas('dokumenHI', function ($query) use ($pengaduan) {
                $query->where('pengaduan_id', $pengaduan->pengaduan_id);
            })->exists();

            if ($existingBukuRegister) {
                \Log::info('Buku register sudah ada untuk pengaduan: ' . $pengaduan->nomor_pengaduan);
                return;
            }

            $dokumenHI = $pengaduan->dokumenHI;
            if (!$dokumenHI) {
                \Log::error('Dokumen HI tidak ditemukan untuk pengaduan: ' . $pengaduan->nomor_pengaduan);
                return;
            }

            // Data dasar buku register
            $bukuRegisterData = [
                'dokumen_hi_id' => $dokumenHI->dokumen_hi_id,
                'tanggal_pencatatan' => $pengaduan->tanggal_laporan->format('Y-m-d'), // Ambil dari tanggal laporan pengaduan
                'pihak_mencatat' => $pengaduan->mediator->nama_mediator ?? 'Mediator',
                'keterangan' => 'Dibuat otomatis saat kasus selesai',
            ];

            // Ambil data pekerja dan pengusaha dari risalah terkait
            $risalah = $dokumenHI->risalah()->latest()->first();
            if ($risalah) {
                $bukuRegisterData['pihak_pekerja'] = $risalah->nama_pekerja ?? $pengaduan->pelapor->nama_pelapor ?? 'Pekerja';
                $bukuRegisterData['pihak_pengusaha'] = $risalah->nama_perusahaan ?? $pengaduan->terlapor->nama_terlapor ?? 'Pengusaha';
            } else {
                // Fallback jika tidak ada risalah
                $bukuRegisterData['pihak_pekerja'] = $pengaduan->pelapor->nama_pelapor ?? 'Pekerja';
                $bukuRegisterData['pihak_pengusaha'] = $pengaduan->terlapor->nama_terlapor ?? 'Pengusaha';
            }

            // Analisis jenis perselisihan berdasarkan perihal pengaduan
            $perihal = strtolower($pengaduan->perihal);
            $bukuRegisterData['perselisihan_hak'] = str_contains($perihal, 'hak') ? 'ya' : 'tidak';
            $bukuRegisterData['perselisihan_kepentingan'] = str_contains($perihal, 'kepentingan') ? 'ya' : 'tidak';
            $bukuRegisterData['perselisihan_phk'] = str_contains($perihal, 'phk') ? 'ya' : 'tidak';
            $bukuRegisterData['perselisihan_sp_sb'] = str_contains($perihal, 'serikat') ? 'ya' : 'tidak';

            // Analisis proses penyelesaian berdasarkan completionType
            switch ($completionType) {
                case 'klarifikasi_bipartit':
                    // Kasus selesai melalui klarifikasi dengan kesimpulan bipartit_lagi
                    $bukuRegisterData['penyelesaian_bipartit'] = 'ya'; // Selalu ya karena sudah masuk dinas
                    $bukuRegisterData['penyelesaian_klarifikasi'] = 'ya';
                    $bukuRegisterData['penyelesaian_mediasi'] = 'tidak';
                    $bukuRegisterData['penyelesaian_anjuran'] = 'tidak';
                    $bukuRegisterData['penyelesaian_pb'] = 'tidak';
                    $bukuRegisterData['penyelesaian_risalah'] = 'tidak';
                    $bukuRegisterData['tindak_lanjut_phi'] = 'tidak';
                    break;

                case 'mediasi_berhasil':
                    // Kasus selesai melalui mediasi berhasil dengan perjanjian bersama
                    $bukuRegisterData['penyelesaian_bipartit'] = 'ya';
                    $bukuRegisterData['penyelesaian_klarifikasi'] = 'ya';
                    $bukuRegisterData['penyelesaian_mediasi'] = 'ya';
                    $bukuRegisterData['penyelesaian_anjuran'] = 'tidak';
                    $bukuRegisterData['penyelesaian_pb'] = 'ya';
                    $bukuRegisterData['penyelesaian_risalah'] = 'ya';
                    $bukuRegisterData['tindak_lanjut_phi'] = 'tidak';
                    break;

                case 'anjuran_ditolak':
                    // Kasus selesai karena anjuran ditolak (kedua pihak tidak setuju atau mixed response)
                    $bukuRegisterData['penyelesaian_bipartit'] = 'ya';
                    $bukuRegisterData['penyelesaian_klarifikasi'] = 'ya';
                    $bukuRegisterData['penyelesaian_mediasi'] = 'ya';
                    $bukuRegisterData['penyelesaian_anjuran'] = 'ya';
                    $bukuRegisterData['penyelesaian_pb'] = 'tidak';
                    $bukuRegisterData['penyelesaian_risalah'] = 'ya';
                    $bukuRegisterData['tindak_lanjut_phi'] = 'ya'; // Ya karena anjuran ditolak
                    break;

                case 'anjuran_diterima':
                    // Kasus selesai karena anjuran diterima dan dibuat perjanjian bersama
                    $bukuRegisterData['penyelesaian_bipartit'] = 'ya';
                    $bukuRegisterData['penyelesaian_klarifikasi'] = 'ya';
                    $bukuRegisterData['penyelesaian_mediasi'] = 'ya';
                    $bukuRegisterData['penyelesaian_anjuran'] = 'ya';
                    $bukuRegisterData['penyelesaian_pb'] = 'ya';
                    $bukuRegisterData['penyelesaian_risalah'] = 'ya';
                    $bukuRegisterData['tindak_lanjut_phi'] = 'tidak';
                    break;
            }

            // Buat buku register
            \App\Models\BukuRegisterPerselisihan::create($bukuRegisterData);

            \Log::info('Buku register berhasil dibuat otomatis untuk pengaduan: ' . $pengaduan->nomor_pengaduan . ' dengan completionType: ' . $completionType);
        } catch (\Exception $e) {
            \Log::error('Error creating buku register otomatis: ' . $e->getMessage());
        }
    }

    /**
     * Kirim draft Perjanjian Bersama ke email para pihak
     */
    private function kirimDraftPerjanjianBersama(Pengaduan $pengaduan)
    {
        try {
            \Log::info('Memulai pengiriman email draft Perjanjian Bersama untuk pengaduan: ' . $pengaduan->nomor_pengaduan);

            // Load relasi yang diperlukan
            $pengaduan->load([
                'pelapor.user',
                'terlapor',
                'mediator.user',
                'dokumenHI.perjanjianBersama'
            ]);

            // Ambil Perjanjian Bersama
            $perjanjianBersama = $pengaduan->dokumenHI->first()?->perjanjianBersama->first();

            if (!$perjanjianBersama) {
                \Log::error('Perjanjian Bersama tidak ditemukan untuk pengaduan: ' . $pengaduan->nomor_pengaduan);
                return;
            }

            \Log::info('Perjanjian Bersama ditemukan: ' . $perjanjianBersama->perjanjian_bersama_id);

            // Email ke Pelapor
            if ($pengaduan->pelapor && $pengaduan->pelapor->user) {
                $pelaporEmail = $pengaduan->pelapor->user->email;
                \Log::info('Mengirim email ke pelapor: ' . $pelaporEmail);

                Mail::to($pelaporEmail)
                    ->send(new \App\Mail\DraftPerjanjianBersamaMail($perjanjianBersama, 'pelapor'));

                \Log::info('Email berhasil dikirim ke pelapor: ' . $pelaporEmail);
            } else {
                \Log::warning('Pelapor atau user pelapor tidak ditemukan');
            }

            // Email ke Terlapor
            if ($pengaduan->terlapor) {
                $terlaporEmail = $pengaduan->terlapor->email_terlapor;
                \Log::info('Mengirim email ke terlapor: ' . $terlaporEmail);

                Mail::to($terlaporEmail)
                    ->send(new \App\Mail\DraftPerjanjianBersamaMail($perjanjianBersama, 'terlapor'));

                \Log::info('Email berhasil dikirim ke terlapor: ' . $terlaporEmail);
            } else {
                \Log::warning('Terlapor tidak ditemukan');
            }

            \Log::info('Draft Perjanjian Bersama berhasil dikirim untuk pengaduan: ' . $pengaduan->nomor_pengaduan);
        } catch (\Exception $e) {
            \Log::error('Error mengirim draft Perjanjian Bersama: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
        }
    }

    /**
     * Auto assign pengaduan ke mediator dengan beban kerja teringan (untuk kepala dinas)
     */
    public function autoAssign(Pengaduan $pengaduan)
    {
        $user = Auth::user();

        // Hanya kepala dinas yang bisa auto assign
        if ($user->active_role !== 'kepala_dinas') {
            abort(403, 'Access denied');
        }

        // Cek apakah pengaduan sudah diambil
        if ($pengaduan->mediator_id) {
            return redirect()->back()
                ->with('error', 'Pengaduan ini sudah ditangani oleh mediator.');
        }

        // Cari mediator dengan beban kerja teringan
        $mediator = Mediator::withCount(['pengaduans as active_cases' => function ($query) {
            $query->whereIn('status', ['pending', 'proses']);
        }])
            ->orderBy('active_cases', 'asc')
            ->first();

        if (!$mediator) {
            return redirect()->back()
                ->with('error', 'Tidak ada mediator yang tersedia saat ini.');
        }

        // Assign ke mediator
        $pengaduan->update([
            'mediator_id' => $mediator->mediator_id,
            'assigned_at' => now(),
            'status' => 'proses'
        ]);

        return redirect()->back()
            ->with('success', "Pengaduan berhasil ditugaskan ke {$mediator->nama_mediator}.");
    }

    /**
     * Release pengaduan dari mediator (untuk kepala dinas)
     */
    public function releasePengaduan(Pengaduan $pengaduan)
    {
        $user = Auth::user();

        // Hanya kepala dinas yang bisa release
        if ($user->active_role !== 'kepala_dinas') {
            abort(403, 'Access denied');
        }

        if (!$pengaduan->mediator_id) {
            return redirect()->back()
                ->with('error', 'Pengaduan ini belum ditugaskan ke mediator manapun.');
        }

        $mediatorLama = $pengaduan->mediator->nama_mediator ?? 'Unknown';

        $pengaduan->update([
            'mediator_id' => null,
            'assigned_at' => null,
            'status' => 'pending'
        ]);

        return redirect()->back()
            ->with('success', "Pengaduan berhasil dilepas dari {$mediatorLama} dan kembali ke status pending.");
    }

    /**
     * Kirim notifikasi ke terlapor yang sudah ada tentang pengaduan baru
     */
    public function notifyExistingTerlapor(Pengaduan $pengaduan)
    {
        try {
            // Cek apakah terlapor sudah ada dan punya akun
            $terlapor = Terlapor::where('email_terlapor', $pengaduan->email_terlapor)
                ->whereHas('user')
                ->first();

            if (!$terlapor) {
                return back()->with('error', 'Terlapor belum memiliki akun.');
            }

            // Kirim notifikasi
            $terlapor->user->notify(new TerlaporPengaduanNotification($pengaduan));

            // Update pengaduan dengan terlapor_id yang sudah ada
            $pengaduan->update(['terlapor_id' => $terlapor->terlapor_id]);

            // Update counter pengaduan
            $terlapor->increment('total_pengaduan');
            $terlapor->update(['last_pengaduan_at' => now()]);

            return back()->with('success', 'Notifikasi pengaduan baru berhasil dikirim ke terlapor.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim notifikasi: ' . $e->getMessage());
        }
    }
}
