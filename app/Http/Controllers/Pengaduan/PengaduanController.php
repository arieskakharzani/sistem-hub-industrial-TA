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
                // Pelapor hanya bisa lihat pengaduan sendiri
                $pengaduans = Pengaduan::where('pelapor_id', $pelapor->pelapor_id)
                    ->with(['pelapor', 'mediator.user'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
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
            'pending' => Pengaduan::where('terlapor_id', $terlapor->terlapor_id)->where('status', 'pending')->count(),
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
    public function kelola()
    {
        $user = Auth::user();

        // Hanya mediator atau kepala dinas yang bisa akses
        if (!in_array($user->active_role, ['mediator', 'kepala_dinas'])) {
            abort(403, 'Access denied');
        }

        // Semua mediator bisa melihat semua pengaduan
        if ($user->active_role === 'mediator') {
            $mediator = $user->mediator;

            if (!$mediator) {
                return redirect()->route('dashboard')->with('error', 'Profil mediator tidak ditemukan.');
            }

            // Tampilkan SEMUA pengaduan dengan relasi lengkap
            $pengaduans = Pengaduan::with([
                'pelapor.user',
                'terlapor.user',
                'mediator.user',
                'jadwal',
                'dokumenHI.risalah'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

            // Stats untuk mediator
            $stats = [
                'total_semua_pengaduan' => Pengaduan::count(),
                'total_kasus_saya' => $mediator->pengaduans()->count(),
                'kasus_aktif_saya' => $mediator->pengaduans()->whereIn('status', ['pending', 'proses'])->count(),
                'kasus_selesai_saya' => $mediator->pengaduans()->where('status', 'selesai')->count(),
                'pengaduan_tersedia' => Pengaduan::whereNull('mediator_id')->count(),
            ];
        } else {
            // Kepala dinas bisa lihat semua dengan relasi lengkap
            $pengaduans = Pengaduan::with([
                'pelapor.user',
                'terlapor.user',
                'mediator.user',
                'jadwal',
                'dokumenHI.risalah'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

            // Stats untuk kepala dinas
            $stats = [
                'total_kasus_saya' => Pengaduan::count(),
                'kasus_aktif' => Pengaduan::whereIn('status', ['pending', 'proses'])->count(),
                'kasus_selesai' => Pengaduan::where('status', 'selesai')->count(),
                'mediator_aktif' => Mediator::count(),
            ];
        }

        return view('pengaduan.kelola', compact('pengaduans', 'stats'));
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


        return redirect()->route('pengaduan.index')
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
        $pengaduan->status = $status;
        $pengaduan->save();
        // Jika status selesai, buat register otomatis jika belum ada
        if ($status === 'selesai') {
            // Ambil dokumen HI utama (misal: yang terbaru)
            $dokumenHI = $pengaduan->dokumenHI()->latest()->first();
            if ($dokumenHI && !BukuRegisterPerselisihan::where('dokumen_hi_id', $dokumenHI->dokumen_hi_id)->exists()) {
                // Ambil risalah penyelesaian (jika ada)
                $risalah = Risalah::where('jadwal_id', $dokumenHI->jadwal_id ?? null)
                    ->where('jenis_risalah', 'penyelesaian')->latest()->first();
                // Ambil mediator
                $mediator = $pengaduan->mediator;
                BukuRegisterPerselisihan::create([
                    'dokumen_hi_id' => $dokumenHI->dokumen_hi_id,
                    'tanggal_pencatatan' => $pengaduan->tanggal_laporan,
                    'pihak_mencatat' => $mediator ? $mediator->nama_mediator : '-',
                    'pihak_pekerja' => $risalah ? $risalah->nama_pekerja : '-',
                    'pihak_pengusaha' => $risalah ? $risalah->nama_perusahaan : '-',
                    'perselisihan_hak' => $pengaduan->perihal === 'Perselisihan Hak' ? 'ya' : 'tidak',
                    'perselisihan_kepentingan' => $pengaduan->perihal === 'Perselisihan Kepentingan' ? 'ya' : 'tidak',
                    'perselisihan_phk' => $pengaduan->perihal === 'Perselisihan PHK' ? 'ya' : 'tidak',
                    'perselisihan_sp_sb' => $pengaduan->perihal === 'Perselisihan antar SP/SB' ? 'ya' : 'tidak',
                    // Penyelesaian dan tindak lanjut bisa diisi otomatis/manual sesuai kebutuhan
                    'penyelesaian_bipartit' => 'tidak',
                    'penyelesaian_klarifikasi' => 'tidak',
                    'penyelesaian_mediasi' => 'tidak',
                    'penyelesaian_pb' => 'tidak',
                    'penyelesaian_anjuran' => 'tidak',
                    'penyelesaian_risalah' => 'ya',
                    'tindak_lanjut_phi' => 'tidak',
                    'tindak_lanjut_ma' => 'tidak',
                    'keterangan' => null,
                ]);
            }
        }

        return redirect()->back()
            ->with('success', 'Status pengaduan berhasil diperbarui');
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
