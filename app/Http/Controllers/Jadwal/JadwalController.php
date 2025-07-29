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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\JadwalNotificationMail;
use App\Notifications\MediatorInAppNotification;

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

        // Ambil pengaduan yang bisa dijadwalkan
        // Untuk sidang mediasi berikutnya, pengaduan yang sudah pernah dijadwalkan tetap bisa dipilih
        $pengaduanList = Pengaduan::with('pelapor')
            ->where('mediator_id', $mediator->mediator_id)
            ->where('status', 'proses')
            ->whereDoesntHave('jadwal', function ($query) {
                $query->whereIn('status_jadwal', ['dijadwalkan', 'berlangsung']);
            })
            ->whereDoesntHave('dokumenHI.risalah', function ($query) {
                $query->where('jenis_risalah', 'penyelesaian');
            })
            ->get();

        return view('jadwal.create', compact('pengaduanList'));
    }

    // Simpan jadwal baru
    public function store(Request $request): RedirectResponse
    {
        try {
            $user = Auth::user();

            if (!$user || $user->active_role !== 'mediator') {
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

            DB::beginTransaction();
            try {
                // Log data pengaduan sebelum membuat jadwal
                Log::info('Data pengaduan sebelum membuat jadwal:', [
                    'pengaduan_id' => $pengaduan->pengaduan_id,
                    'pelapor_id' => $pengaduan->pelapor_id,
                    'pelapor_email' => $pengaduan->pelapor->email ?? 'not found',
                    'terlapor_id' => $pengaduan->terlapor_id,
                    'terlapor_email' => $pengaduan->terlapor->email_terlapor ?? $pengaduan->email_terlapor ?? 'not found'
                ]);

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

                // Log jadwal yang baru dibuat
                Log::info('Jadwal baru dibuat:', [
                    'jadwal_id' => $jadwal->jadwal_id,
                    'pengaduan_id' => $jadwal->pengaduan_id,
                    'jenis_jadwal' => $jadwal->jenis_jadwal
                ]);

                // Load relasi yang diperlukan untuk notifikasi
                $jadwal->load(['pengaduan.pelapor.user', 'pengaduan.terlapor', 'mediator.user']);

                // Log relasi yang sudah dimuat
                Log::info('Relasi jadwal dimuat:', [
                    'pelapor_loaded' => $jadwal->pengaduan->pelapor ? 'yes' : 'no',
                    'pelapor_email' => $jadwal->pengaduan->pelapor->email ?? 'not found',
                    'terlapor_loaded' => $jadwal->pengaduan->terlapor ? 'yes' : 'no',
                    'terlapor_email' => $jadwal->pengaduan->terlapor->email_terlapor ?? $jadwal->pengaduan->email_terlapor ?? 'not found',
                    'mediator_loaded' => $jadwal->mediator ? 'yes' : 'no'
                ]);

                // Kirim email langsung ke pelapor dan terlapor
                try {
                    // Kirim ke pelapor
                    if ($jadwal->pengaduan->pelapor && $jadwal->pengaduan->pelapor->email) {
                        Mail::to($jadwal->pengaduan->pelapor->email)
                            ->queue(new JadwalNotificationMail(
                                $jadwal,
                                [
                                    'name' => $jadwal->pengaduan->pelapor->nama_pelapor,
                                    'email' => $jadwal->pengaduan->pelapor->email,
                                    'role' => 'pelapor'
                                ],
                                'created'
                            ));
                        Log::info('Email jadwal dikirim ke pelapor: ' . $jadwal->pengaduan->pelapor->email);
                    }

                    // Kirim ke terlapor
                    $email_terlapor = $jadwal->pengaduan->terlapor->email_terlapor ?? $jadwal->pengaduan->email_terlapor;
                    if ($email_terlapor) {
                        Mail::to($email_terlapor)
                            ->queue(new JadwalNotificationMail(
                                $jadwal,
                                [
                                    'name' => $jadwal->pengaduan->terlapor->nama_terlapor ?? $jadwal->pengaduan->nama_terlapor,
                                    'email' => $email_terlapor,
                                    'role' => 'terlapor'
                                ],
                                'created'
                            ));
                        Log::info('Email jadwal dikirim ke terlapor: ' . $email_terlapor);
                    }
                } catch (\Exception $e) {
                    Log::error('Gagal mengirim email jadwal: ' . $e->getMessage());
                }

                // Trigger event untuk notifikasi lainnya jika diperlukan
                event(new JadwalCreated($jadwal));

                Log::info('Event JadwalCreated dipanggil untuk jadwal_id: ' . $jadwal->jadwal_id);

                // Di method store atau create
                $notification_data = [
                    'title' => 'Jadwal ' . $jadwal->jenis_jadwal . ' Baru',
                    'message' => 'Jadwal ' . $jadwal->jenis_jadwal . ' telah dibuat untuk pengaduan #' . $jadwal->pengaduan->nomor_pengaduan,
                    'type' => 'jadwal_created',
                    'jadwal_id' => $jadwal->jadwal_id
                ];

                // Kirim notifikasi ke pelapor
                if ($jadwal->pengaduan->pelapor && $jadwal->pengaduan->pelapor->user) {
                    $jadwal->pengaduan->pelapor->user->notify(new MediatorInAppNotification(
                        $jadwal,
                        'jadwal_created',
                        null,
                        $notification_data
                    ));
                }

                // Kirim notifikasi ke terlapor
                if ($jadwal->pengaduan->terlapor && $jadwal->pengaduan->terlapor->user) {
                    $jadwal->pengaduan->terlapor->user->notify(new MediatorInAppNotification(
                        $jadwal,
                        'jadwal_created',
                        null,
                        $notification_data
                    ));
                }

                DB::commit();

                return redirect()->route('jadwal.show', $jadwal)
                    ->with('success', 'Jadwal berhasil dibuat dan notifikasi telah dikirim');
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Error creating jadwal: ' . $e->getMessage());
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error in JadwalController@store: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Halaman detail jadwal
    public function show($jadwalId): View
    {
        $jadwal = Jadwal::with(['pengaduan.pelapor', 'mediator', 'risalahKlarifikasi', 'risalahPenyelesaian'])
            ->findOrFail($jadwalId);

        $user = Auth::user();

        if (!$user || !$user->active_role === 'mediator') {
            abort(403, 'Hanya mediator yang dapat membuat jadwal');
        }

        $mediator = $user->mediator;
        if (!$mediator || $jadwal->mediator_id !== $mediator->mediator_id) {
            abort(403, 'Anda tidak dapat melihat jadwal mediator lain');
        }

        // Ambil detail mediasi terakhir (jika ada)
        $detailMediasiTerakhir = $jadwal->detailMediasi()->orderByDesc('sidang_ke')->first();

        return view('jadwal.show', compact('jadwal', 'detailMediasiTerakhir'));
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
        try {
            $jadwal = Jadwal::findOrFail($jadwalId);

            $user = Auth::user();

            if (!$user || $user->active_role !== 'mediator') {
                abort(403, 'Hanya mediator yang dapat mengupdate jadwal');
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

            // Simpan data lama untuk event
            $oldData = $jadwal->only([
                'tanggal',
                'waktu',
                'tempat',
                'status_jadwal',
                'catatan_jadwal',
                'hasil'
            ]);

            DB::beginTransaction();
            try {
                // Update jadwal
                $jadwal->update([
                    'tanggal' => $request->tanggal,
                    'waktu' => $request->waktu,
                    'tempat' => $request->tempat,
                    'status_jadwal' => $request->status_jadwal,
                    'catatan_jadwal' => $request->catatan_jadwal,
                    'hasil' => $request->hasil
                ]);

                // Load relasi yang diperlukan untuk notifikasi
                $jadwal->load(['pengaduan.pelapor.user', 'pengaduan.terlapor', 'mediator.user']);

                // Kirim email langsung ke pelapor dan terlapor
                try {
                    $eventType = $oldData['status_jadwal'] !== $request->status_jadwal ? 'status_updated' : 'updated';

                    // Kirim ke pelapor
                    if ($jadwal->pengaduan->pelapor && $jadwal->pengaduan->pelapor->email) {
                        Mail::to($jadwal->pengaduan->pelapor->email)
                            ->queue(new JadwalNotificationMail(
                                $jadwal,
                                [
                                    'name' => $jadwal->pengaduan->pelapor->nama_pelapor,
                                    'email' => $jadwal->pengaduan->pelapor->email,
                                    'role' => 'pelapor'
                                ],
                                $eventType,
                                $eventType === 'status_updated' ? ['old_status' => $oldData['status_jadwal']] : $oldData
                            ));
                        Log::info('Email update jadwal dikirim ke pelapor: ' . $jadwal->pengaduan->pelapor->email);
                    }

                    // Kirim ke terlapor
                    $email_terlapor = $jadwal->pengaduan->terlapor->email_terlapor ?? $jadwal->pengaduan->email_terlapor;
                    if ($email_terlapor) {
                        Mail::to($email_terlapor)
                            ->queue(new JadwalNotificationMail(
                                $jadwal,
                                [
                                    'name' => $jadwal->pengaduan->terlapor->nama_terlapor ?? $jadwal->pengaduan->nama_terlapor,
                                    'email' => $email_terlapor,
                                    'role' => 'terlapor'
                                ],
                                $eventType,
                                $eventType === 'status_updated' ? ['old_status' => $oldData['status_jadwal']] : $oldData
                            ));
                        Log::info('Email update jadwal dikirim ke terlapor: ' . $email_terlapor);
                    }
                } catch (\Exception $e) {
                    Log::error('Gagal mengirim email update jadwal: ' . $e->getMessage());
                }

                // Trigger event untuk notifikasi lainnya jika diperlukan
                if ($oldData['status_jadwal'] !== $request->status_jadwal) {
                    event(new JadwalStatusUpdated($jadwal, $oldData['status_jadwal']));
                } else {
                    event(new JadwalUpdated($jadwal, $oldData));
                }

                DB::commit();

                Log::info('Jadwal berhasil diupdate dan email dikirim', [
                    'jadwal_id' => $jadwal->jadwal_id,
                    'jenis_jadwal' => $jadwal->jenis_jadwal,
                    'old_status' => $oldData['status_jadwal'],
                    'new_status' => $jadwal->status_jadwal,
                    'pelapor_email' => $jadwal->pengaduan->pelapor->email ?? 'not found',
                    'terlapor_email' => $jadwal->pengaduan->terlapor->email_terlapor ?? $jadwal->pengaduan->email_terlapor ?? 'not found'
                ]);

                return redirect()->route('jadwal.show', $jadwal)
                    ->with('success', 'Jadwal berhasil diupdate dan notifikasi telah dikirim');
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Error updating jadwal: ' . $e->getMessage());
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error in JadwalController@update: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Update status via AJAX
    public function updateStatus(Request $request, $jadwalId): JsonResponse
    {
        try {
            $jadwal = Jadwal::findOrFail($jadwalId);

            $user = Auth::user();

            if (!$user || $user->active_role !== 'mediator') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $mediator = $user->mediator;
            if (!$mediator || $jadwal->mediator_id !== $mediator->mediator_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat mengupdate jadwal ini'
                ], 403);
            }

            $request->validate([
                'status_jadwal' => 'required|in:dijadwalkan,berlangsung,selesai,ditunda,dibatalkan'
            ]);

            $oldStatus = $jadwal->status_jadwal;

            DB::beginTransaction();
            try {
                // Update status jadwal
                $jadwal->update([
                    'status_jadwal' => $request->status_jadwal
                ]);

                // Load relasi yang diperlukan untuk notifikasi
                $jadwal->load(['pengaduan.pelapor.user', 'pengaduan.terlapor', 'mediator.user']);

                // Kirim email ke pelapor dan terlapor
                try {
                    // Kirim ke pelapor
                    if ($jadwal->pengaduan->pelapor && $jadwal->pengaduan->pelapor->email) {
                        Mail::to($jadwal->pengaduan->pelapor->email)
                            ->queue(new JadwalNotificationMail(
                                $jadwal,
                                [
                                    'name' => $jadwal->pengaduan->pelapor->nama_pelapor,
                                    'email' => $jadwal->pengaduan->pelapor->email,
                                    'role' => 'pelapor'
                                ],
                                'status_updated',
                                ['old_status' => $oldStatus]
                            ));
                        Log::info('Email update status jadwal dikirim ke pelapor: ' . $jadwal->pengaduan->pelapor->email);
                    }

                    // Kirim ke terlapor
                    $email_terlapor = $jadwal->pengaduan->terlapor->email_terlapor ?? $jadwal->pengaduan->email_terlapor;
                    if ($email_terlapor) {
                        Mail::to($email_terlapor)
                            ->queue(new JadwalNotificationMail(
                                $jadwal,
                                [
                                    'name' => $jadwal->pengaduan->terlapor->nama_terlapor ?? $jadwal->pengaduan->nama_terlapor,
                                    'email' => $email_terlapor,
                                    'role' => 'terlapor'
                                ],
                                'status_updated',
                                ['old_status' => $oldStatus]
                            ));
                        Log::info('Email update status jadwal dikirim ke terlapor: ' . $email_terlapor);
                    }

                    // Kirim notifikasi in-app ke pelapor dan terlapor
                    if ($jadwal->pengaduan->pelapor && $jadwal->pengaduan->pelapor->user) {
                        $notification_data = [
                            'title' => 'Status Jadwal Diperbarui',
                            'message' => 'Status jadwal ' . $jadwal->jenis_jadwal . ' telah diubah dari ' . $oldStatus . ' menjadi ' . $request->status_jadwal,
                            'type' => 'jadwal_updated',
                            'jadwal_id' => $jadwal->jadwal_id
                        ];
                        $jadwal->pengaduan->pelapor->user->notify(new MediatorInAppNotification(
                            $jadwal,
                            'jadwal_updated',
                            $oldStatus,
                            $notification_data
                        ));
                        Log::info('Notifikasi in-app dikirim ke pelapor');
                    }

                    if ($jadwal->pengaduan->terlapor && $jadwal->pengaduan->terlapor->user) {
                        $notification_data = [
                            'title' => 'Status Jadwal Diperbarui',
                            'message' => 'Status jadwal ' . $jadwal->jenis_jadwal . ' telah diubah dari ' . $oldStatus . ' menjadi ' . $request->status_jadwal,
                            'type' => 'jadwal_updated',
                            'jadwal_id' => $jadwal->jadwal_id
                        ];
                        $jadwal->pengaduan->terlapor->user->notify(new MediatorInAppNotification(
                            $jadwal,
                            'jadwal_updated',
                            $oldStatus,
                            $notification_data
                        ));
                        Log::info('Notifikasi in-app dikirim ke terlapor');
                    }
                } catch (\Exception $e) {
                    Log::error('Gagal mengirim notifikasi update status jadwal: ' . $e->getMessage());
                }

                // Trigger event
                event(new JadwalStatusUpdated($jadwal, $oldStatus));

                DB::commit();

                Log::info('Status jadwal berhasil diupdate dan notifikasi dikirim', [
                    'jadwal_id' => $jadwal->jadwal_id,
                    'old_status' => $oldStatus,
                    'new_status' => $request->status_jadwal,
                    'pelapor_email' => $jadwal->pengaduan->pelapor->email ?? 'not found',
                    'terlapor_email' => $email_terlapor ?? 'not found'
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Status jadwal berhasil diupdate dan notifikasi telah dikirim',
                    'status' => $request->status_jadwal
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Error updating jadwal status: ' . $e->getMessage());
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error in JadwalController@updateStatus: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
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
