<?php

namespace App\Http\Controllers\Pengaduan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengaduan;
use App\Models\Pelapor;
use App\Models\Mediator;

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
                    ->with('pelapor')
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
     * Display pengaduan management page for mediator
     */
    public function kelola()
    {
        $user = Auth::user();

        // Hanya mediator atau kepala dinas yang bisa akses
        if (!in_array($user->role, ['mediator', 'kepala_dinas'])) {
            abort(403, 'Access denied');
        }

        // Ambil semua pengaduan untuk kelola
        $pengaduans = Pengaduan::with('pelapor') // Eager load pelapor relationship
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Stats untuk dashboard kelola pengaduan
        $stats = [
            'total_kasus_saya' => Pengaduan::count(),
            'kasus_aktif' => Pengaduan::whereIn('status', ['pending', 'proses'])->count(),
            'kasus_selesai' => Pengaduan::where('status', 'selesai')->count(),
            'jadwal_hari_ini' => Pengaduan::whereDate('tanggal_laporan', today())->count(),
        ];

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

        // ✅ PERBAIKAN: Gunakan user_id bukan id
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

        // ✅ PERBAIKAN: Gunakan user_id bukan id
        $pelapor = Pelapor::where('user_id', $user->user_id)->first();
        if (!$pelapor) {
            return redirect()->route('pengaduan.index')
                ->with('error', 'Profil pelapor tidak ditemukan');
        }

        $validated = $request->validate([
            'tanggal_laporan' => 'required|date',
            'perihal' => 'required|in:' . implode(',', Pengaduan::getPerihalOptions()),
            'masa_kerja' => 'required|string|max:100',
            'kontak_pekerja' => 'required|string|max:100',
            'nama_perusahaan' => 'required|string|max:255',
            'kontak_perusahaan' => 'required|string|max:100',
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
     * Menampilkan the detail pengaduan
     */
    public function show(Pengaduan $pengaduan)
    {
        $user = Auth::user();

        // Authorization berdasarkan role
        if ($user->role === 'pelapor') {
            // ✅ PERBAIKAN: Gunakan user_id bukan id
            $pelapor = Pelapor::where('user_id', $user->user_id)->first();
            if (!$pelapor || $pengaduan->pelapor_id !== $pelapor->pelapor_id) {
                abort(403, 'Access denied');
            }
        } elseif (!in_array($user->role, ['mediator', 'kepala_dinas'])) {
            abort(403, 'Access denied');
        }

        $pengaduan->load('pelapor');

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

        // ✅ PERBAIKAN: Gunakan user_id bukan id
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

        // ✅ PERBAIKAN: Gunakan user_id bukan id
        $pelapor = Pelapor::where('user_id', $user->user_id)->first();
        if (!$pelapor || $pengaduan->pelapor_id !== $pelapor->pelapor_id) {
            abort(403, 'Access denied');
        }

        $validated = $request->validate([
            'tanggal_laporan' => 'required|date',
            'perihal' => 'required|in:' . implode(',', Pengaduan::getPerihalOptions()),
            'masa_kerja' => 'required|string|max:100',
            'kontak_pekerja' => 'required|string|max:100',
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

        // ✅ PERBAIKAN: Gunakan user_id bukan id
        $pelapor = Pelapor::where('user_id', $user->user_id)->first();
        if (!$pelapor || $pengaduan->pelapor_id !== $pelapor->pelapor_id) {
            abort(403, 'Access denied');
        }

        $pengaduan->delete();

        return redirect()->route('pengaduan.index')
            ->with('success', 'Pengaduan berhasil dihapus');
    }

    /**
     * Update status pengaduan (untuk mediator)
     */
    public function updateStatus(Request $request, Pengaduan $pengaduan)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['mediator', 'kepala_dinas'])) {
            abort(403, 'Access denied');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,proses,selesai'
        ]);

        $pengaduan->update([
            'status' => $validated['status']
        ]);

        return redirect()->back()
            ->with('success', 'Status pengaduan berhasil diperbarui');
    }
}
