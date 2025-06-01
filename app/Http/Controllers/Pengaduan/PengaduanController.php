<?php

namespace App\Http\Controllers\Pengaduan;

use App\Models\Pengaduan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PengaduanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Base query berdasarkan role
        if ($user->role === 'mediator') {
            // Mediator bisa lihat semua pengaduan di halaman kelola
            $query = Pengaduan::with('user');

            // Filter by status if provided
            if ($request->has('status') && $request->status !== '') {
                $query->where('status', $request->status);
            }

            // Filter by perihal if provided
            if ($request->has('perihal') && $request->perihal !== '') {
                $query->where('perihal', $request->perihal);
            }

            // Search functionality
            if ($request->has('search') && $request->search !== '') {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama_perusahaan', 'like', "%{$search}%")
                        ->orWhere('narasi_kasus', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%");
                        });
                });
            }

            $pengaduans = $query->latest()->paginate(15);

            // Calculate statistics
            $stats = [
                'total' => Pengaduan::count(),
                'pending' => Pengaduan::where('status', 'pending')->count(),
                'proses' => Pengaduan::where('status', 'proses')->count(),
                'selesai' => Pengaduan::where('status', 'selesai')->count(),
            ];

            return view('pengaduan.kelola', compact('pengaduans', 'stats'));
        } else {
            // Pelapor hanya bisa lihat pengaduan sendiri
            $pengaduans = Pengaduan::with('user')
                ->where('user_id', Auth::id())
                ->latest()
                ->paginate(10);

            // Calculate statistics
            $totalPengaduans = Pengaduan::where('user_id', Auth::id())->count();
            $pendingCount = Pengaduan::where('user_id', Auth::id())->where('status', 'pending')->count();
            $prosesCount = Pengaduan::where('user_id', Auth::id())->where('status', 'proses')->count();
            $selesaiCount = Pengaduan::where('user_id', Auth::id())->where('status', 'selesai')->count();

            return view('pengaduan.index', compact('pengaduans', 'totalPengaduans', 'pendingCount', 'prosesCount', 'selesaiCount'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * HANYA PELAPOR YANG BISA AKSES
     */
    public function create()
    {
        $user = Auth::user();

        // Cek apakah user adalah pelapor
        if ($user->role !== 'pelapor') {
            abort(403, 'Unauthorized. Hanya pelapor yang dapat mengisi form pengaduan.');
        }

        return view('pengaduan.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     * HANYA PELAPOR YANG BISA SUBMIT
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Cek apakah user adalah pelapor
        if ($user->role !== 'pelapor') {
            abort(403, 'Unauthorized. Hanya pelapor yang dapat mengajukan pengaduan.');
        }

        // Validasi input
        $validated = $request->validate([
            'tanggal_laporan' => 'required|date',
            'perihal' => [
                'required',
                Rule::in([
                    'Perselisihan Hak',
                    'Perselisihan Kepentingan',
                    'Perselisihan PHK',
                    'Perselisihan antar SP/SB'
                ])
            ],
            'masa_kerja' => 'required|string|max:100',
            'kontak_pekerja' => 'required|string|max:100',
            'nama_perusahaan' => 'required|string|max:255',
            'kontak_perusahaan' => 'required|string|max:100',
            'alamat_kantor_cabang' => 'required|string',
            'narasi_kasus' => 'required|string',
            'catatan_tambahan' => 'nullable|string',
            'lampiran.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5MB max
        ], [
            'tanggal_laporan.required' => 'Tanggal laporan harus diisi.',
            'perihal.required' => 'Perihal harus dipilih.',
            'perihal.in' => 'Perihal yang dipilih tidak valid.',
            'masa_kerja.required' => 'Masa kerja harus diisi.',
            'kontak_pekerja.required' => 'Kontak pekerja harus diisi.',
            'nama_perusahaan.required' => 'Nama perusahaan harus diisi.',
            'kontak_perusahaan.required' => 'Kontak perusahaan harus diisi.',
            'alamat_kantor_cabang.required' => 'Alamat kantor cabang harus diisi.',
            'narasi_kasus.required' => 'Narasi kasus harus diisi.',
            'lampiran.*.mimes' => 'File harus berformat PDF, DOC, DOCX, JPG, JPEG, atau PNG.',
            'lampiran.*.max' => 'Ukuran file maksimal 5MB.',
        ]);

        // Handle file uploads
        $lampiranPaths = [];
        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('pengaduan/lampiran', $filename, 'public');
                $lampiranPaths[] = $path;
            }
        }

        // Create pengaduan - simpan ke database
        $pengaduan = Pengaduan::create([
            'user_id' => Auth::id(),
            'tanggal_laporan' => $validated['tanggal_laporan'],
            'perihal' => $validated['perihal'],
            'masa_kerja' => $validated['masa_kerja'],
            'kontak_pekerja' => $validated['kontak_pekerja'],
            'nama_perusahaan' => $validated['nama_perusahaan'],
            'kontak_perusahaan' => $validated['kontak_perusahaan'],
            'alamat_kantor_cabang' => $validated['alamat_kantor_cabang'],
            'narasi_kasus' => $validated['narasi_kasus'],
            'catatan_tambahan' => $validated['catatan_tambahan'],
            'lampiran' => $lampiranPaths,
            'status' => 'pending' // Status awal pending
        ]);

        // Redirect ke halaman index pengaduan dengan pesan sukses
        return redirect()->route('pengaduan.index')
            ->with('success', 'Pengaduan berhasil dikirimkan! Pengaduan Anda akan segera diproses oleh mediator.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengaduan $pengaduan)
    {
        $user = Auth::user();

        // Check authorization
        if ($user->role === 'pelapor' && $pengaduan->user_id !== Auth::id()) {
            abort(403, 'Anda hanya dapat melihat pengaduan sendiri.');
        }

        $pengaduan->load('user', 'mediator');

        // Return different views based on role
        if ($user->role === 'mediator') {
            return view('pengaduan.show-mediator', compact('pengaduan'));
        } else {
            return view('pengaduan.show', compact('pengaduan'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     * HANYA PELAPOR YANG BISA EDIT PENGADUAN SENDIRI
     */
    public function edit(Pengaduan $pengaduan)
    {
        $user = Auth::user();

        // Cek apakah user adalah pelapor
        if ($user->role !== 'pelapor') {
            abort(403, 'Unauthorized. Hanya pelapor yang dapat mengedit pengaduan.');
        }

        // Make sure user can only edit their own pengaduan and only if status is pending
        if ($pengaduan->user_id !== Auth::id()) {
            abort(403, 'Anda hanya dapat mengedit pengaduan sendiri.');
        }

        if ($pengaduan->status !== 'pending') {
            abort(403, 'Pengaduan yang sudah diproses tidak dapat diedit.');
        }

        return view('pengaduan.edit', compact('pengaduan', 'user'));
    }

    /**
     * Update the specified resource in storage.
     * HANYA PELAPOR YANG BISA UPDATE PENGADUAN SENDIRI
     */
    public function update(Request $request, Pengaduan $pengaduan)
    {
        $user = Auth::user();

        // Cek apakah user adalah pelapor
        if ($user->role !== 'pelapor') {
            abort(403, 'Unauthorized. Hanya pelapor yang dapat mengupdate pengaduan.');
        }

        // Make sure user can only update their own pengaduan and only if status is pending
        if ($pengaduan->user_id !== Auth::id()) {
            abort(403, 'Anda hanya dapat mengupdate pengaduan sendiri.');
        }

        if ($pengaduan->status !== 'pending') {
            abort(403, 'Pengaduan yang sudah diproses tidak dapat diubah.');
        }

        $validated = $request->validate([
            'tanggal_laporan' => 'required|date',
            'perihal' => [
                'required',
                Rule::in([
                    'Perselisihan Hak',
                    'Perselisihan Kepentingan',
                    'Perselisihan PHK',
                    'Perselisihan antar SP/SB'
                ])
            ],
            'masa_kerja' => 'required|string|max:100',
            'kontak_pekerja' => 'required|string|max:100',
            'nama_perusahaan' => 'required|string|max:255',
            'kontak_perusahaan' => 'required|string|max:100',
            'alamat_kantor_cabang' => 'required|string',
            'narasi_kasus' => 'required|string',
            'catatan_tambahan' => 'nullable|string',
            'lampiran.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        // Handle file uploads if new files are uploaded
        $lampiranPaths = $pengaduan->lampiran ?? [];
        if ($request->hasFile('lampiran')) {
            // Delete old files
            if (!empty($pengaduan->lampiran)) {
                foreach ($pengaduan->lampiran as $oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            // Upload new files
            $lampiranPaths = [];
            foreach ($request->file('lampiran') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('pengaduan/lampiran', $filename, 'public');
                $lampiranPaths[] = $path;
            }
        }

        // Update pengaduan
        $pengaduan->update([
            'tanggal_laporan' => $validated['tanggal_laporan'],
            'perihal' => $validated['perihal'],
            'masa_kerja' => $validated['masa_kerja'],
            'kontak_pekerja' => $validated['kontak_pekerja'],
            'nama_perusahaan' => $validated['nama_perusahaan'],
            'kontak_perusahaan' => $validated['kontak_perusahaan'],
            'alamat_kantor_cabang' => $validated['alamat_kantor_cabang'],
            'narasi_kasus' => $validated['narasi_kasus'],
            'catatan_tambahan' => $validated['catatan_tambahan'],
            'lampiran' => $lampiranPaths,
        ]);

        return redirect()->route('pengaduan.index')
            ->with('success', 'Pengaduan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     * HANYA PELAPOR YANG BISA DELETE PENGADUAN SENDIRI
     */
    public function destroy(Pengaduan $pengaduan)
    {
        $user = Auth::user();

        // Cek apakah user adalah pelapor
        if ($user->role !== 'pelapor') {
            abort(403, 'Unauthorized. Hanya pelapor yang dapat menghapus pengaduan.');
        }

        // Make sure user can only delete their own pengaduan and only if status is pending
        if ($pengaduan->user_id !== Auth::id()) {
            abort(403, 'Anda hanya dapat menghapus pengaduan sendiri.');
        }

        if ($pengaduan->status !== 'pending') {
            abort(403, 'Pengaduan yang sudah diproses tidak dapat dihapus.');
        }

        // Delete files
        if (!empty($pengaduan->lampiran)) {
            foreach ($pengaduan->lampiran as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $pengaduan->delete();

        return redirect()->route('pengaduan.index')
            ->with('success', 'Pengaduan berhasil dihapus!');
    }

    /**
     * Update status pengaduan (KHUSUS UNTUK MEDIATOR)
     */
    public function updateStatus(Request $request, Pengaduan $pengaduan)
    {
        // Check if user is mediator
        if (Auth::user()->role !== 'mediator') {
            abort(403, 'Unauthorized. Hanya mediator yang dapat mengubah status pengaduan.');
        }

        $request->validate([
            'status' => 'required|in:pending,proses,selesai',
            'catatan_mediator' => 'nullable|string|max:1000'
        ]);

        $pengaduan->update([
            'status' => $request->status,
            'catatan_mediator' => $request->catatan_mediator,
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Status pengaduan berhasil diperbarui!');
    }

    /**
     * Assign mediator to pengaduan (KHUSUS UNTUK MEDIATOR)
     */
    public function assign(Request $request, Pengaduan $pengaduan)
    {
        // Check if user is mediator
        if (Auth::user()->role !== 'mediator') {
            abort(403, 'Unauthorized. Hanya mediator yang dapat mengambil pengaduan.');
        }

        $pengaduan->update([
            'mediator_id' => Auth::id(),
            'status' => 'proses',
            'assigned_at' => now()
        ]);

        return redirect()->back()->with('success', 'Pengaduan berhasil diambil untuk ditangani!');
    }

    /**
     * Show kelola pengaduan page (KHUSUS UNTUK MEDIATOR)
     */
    public function kelola(Request $request)
    {
        // Check if user is mediator
        if (Auth::user()->role !== 'mediator') {
            abort(403, 'Unauthorized. Hanya mediator yang dapat mengakses halaman kelola pengaduan.');
        }

        // Reuse index logic for mediator
        return $this->index($request);
    }
}
