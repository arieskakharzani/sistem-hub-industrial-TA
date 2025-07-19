<?php

namespace App\Http\Controllers\Jadwal;

use App\Models\Pengaduan;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Events\JadwalCreated;
use App\Events\JadwalUpdated;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Events\JadwalStatusUpdated;

class JadwalController extends Controller
{
    // Halaman utama daftar jadwal
    public function index(): View
    {
        $user = Auth::user();

        if (!$user || $user->active_role !== 'mediator') {
            abort(403, 'Hanya mediator yang dapat mengakses jadwal');
        }

        $mediator = $user->mediator;
        if (!$mediator) {
            abort(403, 'Data mediator tidak ditemukan');
        }

        // Ambil SEMUA jadwal, bukan hanya milik mediator ini
        $query = Jadwal::with(['pengaduan.pelapor', 'mediator']);

        // Filter berdasarkan jenis jadwal
        if (request('jenis_jadwal')) {
            $query->where('jenis_jadwal', request('jenis_jadwal'));
        }

        // Filter berdasarkan status 
        if (request('status')) {
            $query->byStatus(request('status'));
        }

        // Filter berdasarkan bulan 
        if (request('bulan')) {
            $query->whereMonth('tanggal', request('bulan'));
        }

        $jadwalList = $query->orderBy('tanggal', 'desc')
            ->orderBy('waktu', 'desc')
            ->paginate(10);

        // Hitung statistik
        $stats = [
            'total' => Jadwal::count(),
            'hari_ini' => Jadwal::whereDate('tanggal', today())->count(),
            'dijadwalkan' => Jadwal::byStatus('dijadwalkan')->count(),
            'selesai' => Jadwal::byStatus('selesai')->count(),
            'total_saya' => Jadwal::byMediator($mediator->mediator_id)->count(),
        ];

        return view('jadwal.index', compact('jadwalList', 'stats', 'mediator'));
    }

    // Halaman form buat jadwal baru
    public function create(): View
    {
        $user = Auth::user();

        if (!$user || !$user->active_role === 'mediator') {
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
            ->whereDoesntHave('jadwal', function ($query) {
                $query->whereIn('status_jadwal', ['dijadwalkan', 'berlangsung']);
            })
            ->get();

        return view('jadwal.create', compact('pengaduanList'));
    }

    // Simpan jadwal baru
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (!$user || !$user->active_role === 'mediator') {
            abort(403, 'Hanya mediator yang dapat membuat jadwal');
        }

        $mediator = $user->mediator;

        if (!$mediator) {
            abort(403, 'Data mediator tidak ditemukan');
        }

        // Validasi input
        $request->validate([
            'pengaduan_id' => 'required|exists:pengaduans,pengaduan_id',
            'tanggal' => 'required|date|after_or_equal:today',
            'waktu' => 'required|date_format:H:i',
            'tempat' => 'required|string|max:255|min:5',
            'catatan_jadwal' => 'nullable|string|max:1000'
        ], [
            'tanggal.after_or_equal' => 'Tanggal tidak boleh di masa lampau',
            'waktu.date_format' => 'Format waktu harus HH:MM',
            'tempat.min' => 'Tempat minimal 5 karakter'
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
        $jadwal = Jadwal::create([
            'pengaduan_id' => $request->pengaduan_id,
            'mediator_id' => $mediator->mediator_id,
            'tanggal' => $request->tanggal,
            'waktu' => $request->waktu,
            'tempat' => $request->tempat,
            'jenis_jadwal' => $request->jenis_jadwal ?? 'mediasi',
            'sidang_ke' => $request->sidang_ke,
            'status_jadwal' => 'dijadwalkan',
            'catatan_jadwal' => $request->catatan_jadwal
        ]);

        // DEBUG: Log sebelum trigger event
        Log::info('🧪 [DEBUG] About to trigger JadwalCreated event', [
            'jadwal_id' => $jadwal->jadwal_id,
            'jadwal_exists' => $jadwal ? 'yes' : 'no',
            'event_class' => JadwalCreated::class
        ]);

        event(new JadwalCreated($jadwal));

        Log::info('JadwalCreated event triggered successfully');

        return redirect()->route('jadwal.show', $jadwal)
            ->with('success', 'Jadwal berhasil dibuat');
    }

    // Halaman detail jadwal
    public function show($jadwalId): View
    {
        $jadwal = Jadwal::with(['pengaduan.pelapor', 'mediator'])
            ->findOrFail($jadwalId);

        $user = Auth::user();

        if (!$user || !$user->active_role === 'mediator') {
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
        $jadwal = Jadwal::with(['pengaduan.pelapor'])
            ->findOrFail($jadwalId);

        $user = Auth::user();

        if (!$user || !$user->active_role === 'mediator') {
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
        $jadwal = Jadwal::findOrFail($jadwalId);

        $user = Auth::user();

        if (!$user || !$user->active_role === 'mediator') {
            abort(403, 'Hanya mediator yang dapat membuat jadwal');
        }

        $mediator = $user->mediator;
        if (!$mediator || $jadwal->mediator_id !== $mediator->mediator_id) {
            abort(403, 'Anda tidak dapat mengedit jadwal ini');
        }

        $request->validate([
            'tanggal' => 'required|date|after_or_equal:today',
            'waktu' => 'required|date_format:H:i',
            'tempat' => 'required|string|max:255|min:5',
            'status_jadwal' => 'required|in:dijadwalkan,berlangsung,selesai,ditunda,dibatalkan',
            'catatan_jadwal' => 'nullable|string|max:1000',
            'hasil' => 'nullable|string|max:2000'
        ]);

        // SIMPAN DATA LAMA UNTUK COMPARISON
        $oldData = [
            'tanggal' => $jadwal->tanggal,
            'waktu' => $jadwal->waktu,
            'tempat' => $jadwal->tempat,
            'status_jadwal' => $jadwal->status_jadwal,
            'catatan_jadwal' => $jadwal->catatan_jadwal,
        ];

        // �� DEBUG LOG: Sebelum update
        Log::info('🚀 [JADWAL UPDATE] Before update', [
            'jadwal_id' => $jadwal->jadwal_id,
            'old_data' => $oldData,
            'new_data' => $request->only(['tanggal', 'waktu', 'tempat', 'status_jadwal', 'catatan_jadwal'])
        ]);

        $jadwal->update($request->all());

        // 🚀 DEBUG LOG: Event UPDATE
        Log::info('🚀 [JADWAL UPDATE] Triggering JadwalUpdated event', [
            'jadwal_id' => $jadwal->jadwal_id,
            'has_changes' => $oldData !== $request->only(['tanggal', 'waktu', 'tempat', 'status_jadwal', 'catatan_jadwal'])
        ]);

        event(new JadwalUpdated($jadwal, $oldData));

        Log::info('JadwalUpdated event triggered successfully');

        return redirect()->route('jadwal.show', $jadwal)
            ->with('success', ' berhasil diperbarui');
    }

    // Update status via AJAX
    public function updateStatus(Request $request, $jadwalId): JsonResponse
    {
        $jadwal = Jadwal::findOrFail($jadwalId);

        $user = Auth::user();

        if (!$user || !$user->active_role === 'mediator') {
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
            Log::info('🚀 Status changed, triggering JadwalStatusUpdated event', [
                'jadwal_id' => $jadwal->jadwal_id,
                'old_status' => $oldStatus,
                'new_status' => $request->status_jadwal
            ]);

            event(new JadwalStatusUpdated($jadwal, $oldStatus));

            Log::info('✅ JadwalStatusUpdated event triggered successfully');
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
        $jadwal = Jadwal::findOrFail($jadwalId);

        $user = Auth::user();

        if (!$user || !$user->active_role === 'mediator') {
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
            ->with('success', 'Jadwal berhasil dihapus');
    }
}
