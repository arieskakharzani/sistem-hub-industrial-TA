<?php

namespace App\Http\Controllers\Pengaduan;

use App\Models\Pelapor;
use App\Models\Mediator;
use App\Models\Terlapor;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PengaduanController extends Controller
{
    /**
     * Display a listing of pengaduan for regular users (pelapor)
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'pelapor') {
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
        if ($user->role !== 'terlapor') {
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
        if ($user->role !== 'terlapor') {
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
        $pengaduan->load(['pelapor', 'mediator.user', 'terlapor', 'jadwalMediasi']);

        return view('pengaduan.show-terlapor', compact('pengaduan', 'terlapor'));
    }

    /**
     * Semua mediator bisa melihat semua pengaduan
     */
    public function kelola()
    {
        $user = Auth::user();

        // Hanya mediator atau kepala dinas yang bisa akses
        if (!in_array($user->role, ['mediator', 'kepala_dinas'])) {
            abort(403, 'Access denied');
        }

        // Semua mediator bisa melihat semua pengaduan
        if ($user->role === 'mediator') {
            $mediator = $user->mediator;

            if (!$mediator) {
                return redirect()->route('dashboard')->with('error', 'Profil mediator tidak ditemukan.');
            }

            // Tampilkan SEMUA pengaduan, bukan hanya yang assigned atau unassigned
            $pengaduans = Pengaduan::with(['pelapor', 'mediator.user'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            // Stats untuk mediator - hanya yang dia tangani
            $stats = [
                'total_semua_pengaduan' => Pengaduan::count(), // ✅ NEW: Total semua pengaduan
                'total_kasus_saya' => $mediator->pengaduans()->count(), // Yang dia tangani
                'kasus_aktif_saya' => $mediator->pengaduans()->whereIn('status', ['pending', 'proses'])->count(),
                'kasus_selesai_saya' => $mediator->pengaduans()->where('status', 'selesai')->count(),
                'pengaduan_tersedia' => Pengaduan::whereNull('mediator_id')->count(), // Yang belum diambil
            ];
        } else {
            // Kepala dinas bisa lihat semua (tidak berubah)
            $pengaduans = Pengaduan::with(['pelapor', 'mediator.user'])
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
        if ($user->role !== 'pelapor') {
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

        if ($user->role !== 'pelapor') {
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
            'lampiran.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120' // 5MB max
        ]);

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
        $pengaduan->lampiran = $lampiranPaths;
        $pengaduan->status = 'pending';
        $pengaduan->save();

        return redirect()->route('pengaduan.index')
            ->with('success', 'Pengaduan berhasil dibuat');
    }

    /**
     * Semua mediator bisa melihat detail semua pengaduan
     */
    public function show(Pengaduan $pengaduan)
    {
        $user = Auth::user();

        // ✅ PERUBAHAN: Authorization berdasarkan role - semua mediator bisa lihat
        if ($user->role === 'pelapor') {
            $pelapor = Pelapor::where('user_id', $user->user_id)->first();
            if (!$pelapor || $pengaduan->pelapor_id !== $pelapor->pelapor_id) {
                abort(403, 'Access denied');
            }
        } elseif ($user->role === 'mediator') {
            // ✅ PERUBAHAN: Semua mediator bisa melihat semua pengaduan
            // Tidak ada restriction lagi berdasarkan assignment
            $mediator = $user->mediator;
            if (!$mediator) {
                return redirect()->route('dashboard')->with('error', 'Profil mediator tidak ditemukan.');
            }
            // Authorization check dihapus - semua mediator bisa lihat
        } elseif (!in_array($user->role, ['kepala_dinas'])) {
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
        if ($user->role !== 'pelapor' || $pengaduan->status !== 'pending') {
            abort(403, 'Access denied');
        }

        $pelapor = Pelapor::where('user_id', $user->user_id)->first();
        if (!$pelapor || $pengaduan->pelapor_id !== $pelapor->pelapor_id) {
            abort(403, 'Access denied');
        }

        $perihalOptions = Pengaduan::getPerihalOptions();

        return view('pengaduan.edit', compact('pengaduan', 'perihalOptions'));
    }

    /**
     * Update the specified pengaduan
     */
    public function update(Request $request, Pengaduan $pengaduan)
    {
        $user = Auth::user();

        if ($user->role !== 'pelapor' || $pengaduan->status !== 'pending') {
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
            'nama_perusahaan' => 'required|string|max:255',
            'kontak_perusahaan' => 'required|string|max:100',
            'alamat_kantor_cabang' => 'nullable|string',
            'narasi_kasus' => 'required|string',
            'catatan_tambahan' => 'nullable|string',
            'lampiran.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120'
        ]);

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
        if ($user->role !== 'pelapor' || $pengaduan->status !== 'pending') {
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
        if ($user->role !== 'mediator') {
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
     * ✅ UPDATED: Update status pengaduan
     * Hanya mediator yang assigned atau kepala dinas yang bisa update
     */
    public function updateStatus(Request $request, Pengaduan $pengaduan)
    {
        $user = Auth::user();

        // Hanya mediator atau kepala dinas yang bisa update status
        if (!in_array($user->role, ['mediator', 'kepala_dinas'])) {
            abort(403, 'Access denied');
        }

        // ✅ PERUBAHAN: Untuk mediator, WAJIB yang assigned yang bisa update
        if ($user->role === 'mediator') {
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

        $pengaduan->update($validated);

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
        if ($user->role !== 'kepala_dinas') {
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
        if ($user->role !== 'kepala_dinas') {
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
}
