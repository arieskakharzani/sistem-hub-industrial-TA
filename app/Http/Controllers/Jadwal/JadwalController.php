<?php

namespace App\Http\Controllers\Jadwal;

use App\Models\Pengaduan;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\JadwalMediasi;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Events\JadwalMediasiCreated;
use App\Events\JadwalMediasiUpdated;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Events\JadwalMediasiStatusUpdated;

class JadwalController extends Controller
{
    // Halaman utama daftar jadwal
    public function index(): View
    {
        $user = Auth::user();

        if (!$user || !$user->role === 'mediator') {
            abort(403, 'Hanya mediator yang dapat membuat jadwal');
        }

        $mediator = $user->mediator;

        if (!$mediator) {
            abort(403, 'Data mediator tidak ditemukan');
        }

        // Ambil jadwal dengan filter 
        $query = JadwalMediasi::with(['pengaduan.pelapor'])
            ->byMediator($mediator->mediator_id);

        // Filter berdasarkan status 
        if (request('status')) {
            $query->byStatus(request('status'));
        }

        // Filter berdasarkan bulan 
        if (request('bulan')) {
            $query->whereMonth('tanggal_mediasi', request('bulan'));
        }

        $jadwalList = $query->orderBy('tanggal_mediasi', 'desc')
            ->orderBy('waktu_mediasi', 'desc')
            ->paginate(10);

        // Hitung statistik
        $stats = [
            'total' => JadwalMediasi::byMediator($mediator->mediator_id)->count(),
            'hari_ini' => JadwalMediasi::byMediator($mediator->mediator_id)->hariIni()->count(),
            'dijadwalkan' => JadwalMediasi::byMediator($mediator->mediator_id)->byStatus('dijadwalkan')->count(),
            'selesai' => JadwalMediasi::byMediator($mediator->mediator_id)->byStatus('selesai')->count(),
        ];

        return view('jadwal.index', compact('jadwalList', 'stats'));
    }

    // Halaman form buat jadwal baru
    public function create(): View
    {
        $user = Auth::user();

        if (!$user || !$user->role === 'mediator') {
            abort(403, 'Hanya mediator yang dapat membuat jadwal');
        }

        $mediator = $user->mediator;

        if (!$mediator) {
            abort(403, 'Data mediator tidak ditemukan');
        }

        // Ambil pengaduan yang bisa dijadwalkan (status proses dan belum ada jadwal aktif)
        $pengaduanList = Pengaduan::with('pelapor')
            ->where('mediator_id', $mediator->mediator_id)
            ->where('status', 'proses')
            ->whereDoesntHave('jadwalMediasi', function ($query) {
                $query->whereIn('status_jadwal', ['dijadwalkan', 'berlangsung']);
            })
            ->get();

        return view('jadwal.create', compact('pengaduanList'));
    }

    // Simpan jadwal baru
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (!$user || !$user->role === 'mediator') {
            abort(403, 'Hanya mediator yang dapat membuat jadwal');
        }

        $mediator = $user->mediator;

        if (!$mediator) {
            abort(403, 'Data mediator tidak ditemukan');
        }

        // Validasi input
        $request->validate([
            'pengaduan_id' => 'required|exists:pengaduans,pengaduan_id',
            'tanggal_mediasi' => 'required|date|after_or_equal:today',
            'waktu_mediasi' => 'required|date_format:H:i',
            'tempat_mediasi' => 'required|string|max:255|min:5',
            'catatan_jadwal' => 'nullable|string|max:1000'
        ], [
            'tanggal_mediasi.after_or_equal' => 'Tanggal mediasi tidak boleh di masa lampau',
            'waktu_mediasi.date_format' => 'Format waktu harus HH:MM',
            'tempat_mediasi.min' => 'Tempat mediasi minimal 5 karakter'
        ]);

        // Cek apakah pengaduan milik mediator ini
        $pengaduan = Pengaduan::where('pengaduan_id', $request->pengaduan_id)
            ->where('mediator_id', $mediator->mediator_id)
            ->first();

        if (!$pengaduan) {
            return back()->withErrors(['pengaduan_id' => 'Pengaduan tidak ditemukan atau bukan milik Anda']);
        }

        // Cek apakah sudah ada jadwal aktif
        if ($pengaduan->hasActiveJadwal()) {
            return back()->withErrors(['pengaduan_id' => 'Pengaduan ini sudah memiliki jadwal aktif']);
        }

        // Buat jadwal baru
        $jadwal = JadwalMediasi::create([
            'pengaduan_id' => $request->pengaduan_id,
            'mediator_id' => $mediator->mediator_id,
            'tanggal_mediasi' => $request->tanggal_mediasi,
            'waktu_mediasi' => $request->waktu_mediasi,
            'tempat_mediasi' => $request->tempat_mediasi,
            'status_jadwal' => 'dijadwalkan',
            'catatan_jadwal' => $request->catatan_jadwal
        ]);

        // DEBUG: Log sebelum trigger event
        Log::info('🧪 [DEBUG] About to trigger JadwalMediasiCreated event', [
            'jadwal_id' => $jadwal->jadwal_id,
            'jadwal_exists' => $jadwal ? 'yes' : 'no',
            'event_class' => JadwalMediasiCreated::class
        ]);

        event(new JadwalMediasiCreated($jadwal));

        Log::info('JadwalMediasiCreated event triggered successfully');

        return redirect()->route('jadwal.show', $jadwal)
            ->with('success', 'Jadwal mediasi berhasil dibuat');
    }

    // Halaman detail jadwal
    public function show($jadwalId): View
    {
        $jadwal = JadwalMediasi::with(['pengaduan.pelapor', 'mediator'])
            ->findOrFail($jadwalId);

        $user = Auth::user();

        if (!$user || !$user->role === 'mediator') {
            abort(403, 'Hanya mediator yang dapat membuat jadwal');
        }

        $mediator = $user->mediator;
        if (!$mediator || $jadwal->mediator_id !== $mediator->mediator_id) {
            abort(403, 'Anda tidak dapat melihat jadwal mediator lain');
        }

        return view('jadwal.show', compact('jadwal'));
    }

    // Halaman edit jadwal
    public function edit($jadwalId): View
    {
        $jadwal = JadwalMediasi::with(['pengaduan.pelapor'])
            ->findOrFail($jadwalId);

        $user = Auth::user();

        if (!$user || !$user->role === 'mediator') {
            abort(403, 'Hanya mediator yang dapat membuat jadwal');
        }

        $mediator = $user->mediator;
        if (!$mediator || $jadwal->mediator_id !== $mediator->mediator_id) {
            abort(403, 'Anda tidak dapat mengedit jadwal ini');
        }

        // Tidak bisa edit jika sudah selesai atau dibatalkan
        // if (in_array($jadwal->status_jadwal, ['selesai', 'dibatalkan'])) {
        //     return redirect()->route('jadwal.show', $jadwal)
        //         ->with('error', 'Jadwal yang sudah selesai atau dibatalkan tidak dapat diedit');
        // }

        return view('jadwal.edit', compact('jadwal'));
    }

    // Update jadwal
    public function update(Request $request, $jadwalId): RedirectResponse
    {
        $jadwal = JadwalMediasi::findOrFail($jadwalId);

        $user = Auth::user();

        if (!$user || !$user->role === 'mediator') {
            abort(403, 'Hanya mediator yang dapat membuat jadwal');
        }

        $mediator = $user->mediator;
        if (!$mediator || $jadwal->mediator_id !== $mediator->mediator_id) {
            abort(403, 'Anda tidak dapat mengedit jadwal ini');
        }

        $request->validate([
            'tanggal_mediasi' => 'required|date|after_or_equal:today',
            'waktu_mediasi' => 'required|date_format:H:i',
            'tempat_mediasi' => 'required|string|max:255|min:5',
            'status_jadwal' => 'required|in:dijadwalkan,berlangsung,selesai,ditunda,dibatalkan',
            'catatan_jadwal' => 'nullable|string|max:1000',
            'hasil_mediasi' => 'nullable|string|max:2000'
        ]);

        // SIMPAN DATA LAMA UNTUK COMPARISON
        $oldData = [
            'tanggal_mediasi' => $jadwal->tanggal_mediasi,
            'waktu_mediasi' => $jadwal->waktu_mediasi,
            'tempat_mediasi' => $jadwal->tempat_mediasi,
            'status_jadwal' => $jadwal->status_jadwal,
            'catatan_jadwal' => $jadwal->catatan_jadwal,
        ];

        // 🚀 DEBUG LOG: Sebelum update
        Log::info('🚀 [JADWAL UPDATE] Before update', [
            'jadwal_id' => $jadwal->jadwal_id,
            'old_data' => $oldData,
            'new_data' => $request->only(['tanggal_mediasi', 'waktu_mediasi', 'tempat_mediasi', 'status_jadwal', 'catatan_jadwal'])
        ]);

        $jadwal->update($request->all());

        // 🚀 DEBUG LOG: Event UPDATE
        Log::info('🚀 [JADWAL UPDATE] Triggering JadwalMediasiUpdated event', [
            'jadwal_id' => $jadwal->jadwal_id,
            'has_changes' => $oldData !== $request->only(['tanggal_mediasi', 'waktu_mediasi', 'tempat_mediasi', 'status_jadwal', 'catatan_jadwal'])
        ]);

        event(new JadwalMediasiUpdated($jadwal, $oldData));

        Log::info('JadwalMediasiUpdated event triggered successfully');

        return redirect()->route('jadwal.show', $jadwal)
            ->with('success', 'Jadwal mediasi berhasil diperbarui');
    }

    // Update status via AJAX
    public function updateStatus(Request $request, $jadwalId): JsonResponse
    {
        $jadwal = JadwalMediasi::findOrFail($jadwalId);

        $user = Auth::user();

        if (!$user || !$user->role === 'mediator') {
            abort(403, 'Hanya mediator yang dapat membuat jadwal');
        }

        $mediator = $user->mediator;
        if (!$mediator || $jadwal->mediator_id !== $mediator->mediator_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status_jadwal' => 'required|in:dijadwalkan,berlangsung,selesai,ditunda,dibatalkan',
            'catatan_jadwal' => 'nullable|string|max:1000'
        ]);

        // SIMPAN STATUS LAMA
        $oldStatus = $jadwal->status_jadwal;

        $jadwal->update([
            'status_jadwal' => $request->status_jadwal,
            'catatan_jadwal' => $request->catatan_jadwal
        ]);

        // TRIGGER EVENT - STATUS UPDATED (hanya jika status berubah)
        if ($oldStatus !== $request->status_jadwal) {
            Log::info('🚀 Status changed, triggering JadwalMediasiStatusUpdated event', [
                'jadwal_id' => $jadwal->jadwal_id,
                'old_status' => $oldStatus,
                'new_status' => $request->status_jadwal
            ]);

            event(new JadwalMediasiStatusUpdated($jadwal, $oldStatus));

            Log::info('✅ JadwalMediasiStatusUpdated event triggered successfully');
        } else {
            Log::info('ℹ️  Status unchanged, no event triggered', [
                'jadwal_id' => $jadwal->jadwal_id,
                'status' => $request->status_jadwal
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status jadwal berhasil diperbarui'
        ]);
    }

    // Hapus jadwal
    public function destroy($jadwalId): RedirectResponse
    {
        $jadwal = JadwalMediasi::findOrFail($jadwalId);

        $user = Auth::user();

        if (!$user || !$user->role === 'mediator') {
            abort(403, 'Hanya mediator yang dapat membuat jadwal');
        }

        $mediator = $user->mediator;
        if (!$mediator || $jadwal->mediator_id !== $mediator->mediator_id) {
            abort(403, 'Anda tidak dapat menghapus jadwal ini');
        }

        // Tidak bisa hapus jika sedang berlangsung atau selesai
        if (in_array($jadwal->status_jadwal, ['berlangsung', 'selesai'])) {
            return redirect()->route('jadwal.index')
                ->with('error', 'Jadwal yang sedang berlangsung atau sudah selesai tidak dapat dihapus');
        }

        $jadwal->delete();

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal mediasi berhasil dihapus');
    }
}
