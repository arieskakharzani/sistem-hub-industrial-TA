<?php

namespace App\Http\Controllers\Jadwal;

use App\Models\Pengaduan;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use Illuminate\Support\Facades\DB;
use App\Events\KonfirmasiKehadiran;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Events\JadwalRescheduleNeeded;

class KonfirmasiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!in_array($user->active_role, ['pelapor', 'terlapor'])) {
            abort(403, 'Akses ditolak');
        }

        // Ambil semua jadwal berdasarkan role user (untuk konfirmasi dan riwayat)
        if ($user->active_role === 'pelapor' && $user->pelapor) {
            $jadwal = Jadwal::with(['pengaduan', 'mediator'])
                ->whereHas('pengaduan', function ($query) use ($user) {
                    $query->where('pelapor_id', $user->pelapor->pelapor_id);
                })
                ->orderBy('tanggal', 'asc')
                ->get();
        } elseif ($user->active_role === 'terlapor' && $user->terlapor) {
            $jadwal = Jadwal::with(['pengaduan', 'mediator'])
                ->whereHas('pengaduan', function ($query) use ($user) {
                    $query->where('terlapor_id', $user->terlapor->terlapor_id);
                })
                ->orderBy('tanggal', 'asc')
                ->get();
        } else {
            $jadwal = collect();
        }

        return view('Jadwal.konfirmasi-index', compact('jadwal', 'user'));
    }

    public function show($jadwalId)
    {
        $user = Auth::user();

        if (!in_array($user->active_role, ['pelapor', 'terlapor'])) {
            abort(403, 'Akses ditolak');
        }

        $jadwal = Jadwal::with(['pengaduan.pelapor', 'pengaduan.terlapor', 'mediator'])
            ->findOrFail($jadwalId);

        // Auto-correct: untuk mediasi, jika sebelumnya terset 'ditunda' padahal ada pihak tidak hadir/pending,
        // kembalikan ke 'dijadwalkan' sesuai kebijakan baru
        if (
            $jadwal->jenis_jadwal === 'mediasi' &&
            $jadwal->status_jadwal === 'ditunda' &&
            (
                $jadwal->konfirmasi_pelapor !== 'hadir' ||
                $jadwal->konfirmasi_terlapor !== 'hadir'
            )
        ) {
            $jadwal->update(['status_jadwal' => 'dijadwalkan']);
        }

        // Pastikan jadwal masih dalam status yang bisa dikonfirmasi
        if ($jadwal->status_jadwal !== 'dijadwalkan') {
            abort(404, 'Jadwal ini sudah tidak memerlukan konfirmasi kehadiran');
        }

        // Block akses jika jadwal mediasi sudah lewat hari H (auto dianggap selesai via command)
        if ($jadwal->jenis_jadwal === 'mediasi' && $jadwal->tanggal < now()->toDateString()) {
            abort(404, 'Jadwal mediasi sudah lewat dan tidak lagi memerlukan konfirmasi');
        }

        // Pastikan user berhak mengakses jadwal ini
        $pengaduan = $jadwal->pengaduan;
        $hasAccess = false;

        if ($user->active_role === 'pelapor' && $user->pelapor) {
            $hasAccess = $pengaduan->pelapor_id == $user->pelapor->pelapor_id;
        } elseif ($user->active_role === 'terlapor' && $user->terlapor) {
            $hasAccess = $pengaduan->terlapor_id == $user->terlapor->terlapor_id;
        }

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses untuk melihat jadwal ini');
        }

        return view('Jadwal.konfirmasi-show', compact('jadwal', 'user'));
    }

    public function konfirmasi(Request $request, $jadwalId)
    {
        $request->validate([
            'konfirmasi' => 'required|in:hadir,tidak_hadir',
            'catatan' => 'nullable|string|max:500'
        ]);

        $user = Auth::user();

        if (!in_array($user->active_role, ['pelapor', 'terlapor'])) {
            abort(403, 'Akses ditolak');
        }

        $jadwal = Jadwal::with(['pengaduan.pelapor', 'pengaduan.terlapor', 'mediator'])
            ->findOrFail($jadwalId);

        // Pastikan jadwal masih dalam status yang bisa dikonfirmasi
        if ($jadwal->status_jadwal !== 'dijadwalkan') {
            return redirect()->back()->with('error', 'Jadwal ini sudah tidak memerlukan konfirmasi kehadiran');
        }

        // Cegah konfirmasi jika jadwal mediasi sudah lewat hari H
        if ($jadwal->jenis_jadwal === 'mediasi' && $jadwal->tanggal < now()->toDateString()) {
            return redirect()->route('konfirmasi.index')->with('error', 'Jadwal mediasi sudah lewat dan otomatis ditandai selesai.');
        }

        // Pastikan user berhak mengakses jadwal ini
        $pengaduan = $jadwal->pengaduan;
        $hasAccess = false;

        if ($user->active_role === 'pelapor' && $user->pelapor) {
            $hasAccess = $pengaduan->pelapor_id == $user->pelapor->pelapor_id;
        } elseif ($user->active_role === 'terlapor' && $user->terlapor) {
            $hasAccess = $pengaduan->terlapor_id == $user->terlapor->terlapor_id;
        }

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses untuk konfirmasi jadwal ini');
        }

        // Cek apakah sudah pernah konfirmasi
        if ($user->active_role === 'pelapor' && $jadwal->konfirmasi_pelapor !== 'pending') {
            return redirect()->back()->with('error', 'Anda sudah melakukan konfirmasi sebelumnya');
        }

        if ($user->active_role === 'terlapor' && $jadwal->konfirmasi_terlapor !== 'pending') {
            return redirect()->back()->with('error', 'Anda sudah melakukan konfirmasi sebelumnya');
        }

        try {
            DB::beginTransaction();

            // Update konfirmasi berdasarkan role
            if ($user->active_role === 'pelapor') {
                $jadwal->update([
                    'konfirmasi_pelapor' => $request->konfirmasi,
                    'tanggal_konfirmasi_pelapor' => now(),
                    'catatan_konfirmasi_pelapor' => $request->catatan
                ]);
            } else {
                $jadwal->update([
                    'konfirmasi_terlapor' => $request->konfirmasi,
                    'tanggal_konfirmasi_terlapor' => now(),
                    'catatan_konfirmasi_terlapor' => $request->catatan
                ]);
            }

            // Reload untuk mendapatkan data terbaru
            $jadwal->refresh();

            // ENHANCED LOGIC: Cek berbagai kondisi dan trigger event yang sesuai
            if ($jadwal->adaYangTidakHadir()) {
                // Determine who is absent
                $absentParty = '';
                if ($jadwal->konfirmasi_pelapor === 'tidak_hadir' && $jadwal->konfirmasi_terlapor === 'tidak_hadir') {
                    $absentParty = 'both';
                } elseif ($jadwal->konfirmasi_pelapor === 'tidak_hadir') {
                    $absentParty = 'pelapor';
                } else {
                    $absentParty = 'terlapor';
                }

                // LOGIC KHUSUS: Untuk klarifikasi, tetap lanjutkan meski ada yang tidak hadir
                if ($jadwal->jenis_jadwal === 'klarifikasi') {
                    // Status tetap dijadwalkan untuk klarifikasi
                    $jadwal->update(['status_jadwal' => 'dijadwalkan']);

                    // Trigger event konfirmasi kehadiran (for standard notification)
                    event(new KonfirmasiKehadiran($jadwal, $user->active_role, $request->konfirmasi));

                    // Trigger event khusus klarifikasi - akan dilanjutkan tanpa reschedule
                    event(new \App\Events\KlarifikasiProceedWithoutConfirmation($jadwal, $absentParty, $request->catatan ?? ''));

                    Log::info('📋 Klarifikasi akan dilanjutkan meski ada yang tidak hadir', [
                        'jadwal_id' => $jadwal->jadwal_id,
                        'absent_party' => $absentParty,
                        'status_updated_to' => 'dijadwalkan',
                        'reason' => 'Klarifikasi dapat dilanjutkan tanpa kehadiran semua pihak'
                    ]);
                } else {
                    if ($jadwal->jenis_jadwal === 'mediasi') {
                        // Untuk mediasi: tetap 'dijadwalkan' meskipun ada yang tidak hadir.
                        $jadwal->update(['status_jadwal' => 'dijadwalkan']);

                        event(new KonfirmasiKehadiran($jadwal, $user->active_role, $request->konfirmasi));
                        // Opsional: trigger notifikasi proceed
                        if (class_exists('App\\Events\\MediasiProceedWithoutConfirmation')) {
                            event(new \App\Events\MediasiProceedWithoutConfirmation($jadwal, $absentParty, $request->catatan ?? ''));
                        }

                        Log::info('📋 Mediasi tetap dijadwalkan meski ada yang tidak hadir', [
                            'jadwal_id' => $jadwal->jadwal_id,
                            'absent_party' => $absentParty,
                            'status_updated_to' => 'dijadwalkan'
                        ]);
                    } else {
                        // Untuk TTD tetap ditunda
                        $jadwal->update(['status_jadwal' => 'ditunda']);
                        event(new KonfirmasiKehadiran($jadwal, $user->active_role, $request->konfirmasi));
                        event(new JadwalRescheduleNeeded($jadwal, $absentParty, $request->catatan ?? ''));
                        Log::info('🚨 Reschedule needed - absent party detected', [
                            'jadwal_id' => $jadwal->jadwal_id,
                            'absent_party' => $absentParty,
                            'status_updated_to' => 'ditunda'
                        ]);
                    }
                }
            } else {
                // Normal confirmation - trigger standard event
                event(new KonfirmasiKehadiran($jadwal, $user->active_role, $request->konfirmasi));

                // Check if both parties have confirmed attendance
                if ($jadwal->sudahDikonfirmasiSemua() && !$jadwal->adaYangTidakHadir()) {
                    Log::info('✅ Both parties confirmed attendance', [
                        'jadwal_id' => $jadwal->jadwal_id,
                        'konfirmasi_pelapor' => $jadwal->konfirmasi_pelapor,
                        'konfirmasi_terlapor' => $jadwal->konfirmasi_terlapor
                    ]);
                }
            }

            DB::commit();

            // Return appropriate message
            if ($request->konfirmasi === 'hadir') {
                if ($jadwal->sudahDikonfirmasiSemua() && !$jadwal->adaYangTidakHadir()) {
                    $message = 'Konfirmasi kehadiran berhasil. Kedua belah pihak siap hadir. ' . $jadwal->getJenisJadwalLabel() . ' akan dilaksanakan sesuai jadwal.';
                } else {
                    $message = 'Konfirmasi kehadiran berhasil. Terima kasih!';
                }
            } else {
                // Pesan khusus untuk klarifikasi
                if ($jadwal->jenis_jadwal === 'klarifikasi') {
                    $message = 'Konfirmasi ketidakhadiran berhasil. Proses klarifikasi tetap akan dilanjutkan dan mediator akan melanjutkan ke tahap mediasi setelah klarifikasi selesai.';
                } else {
                    $message = 'Konfirmasi ketidakhadiran berhasil. Tim mediator akan menghubungi Anda untuk penjadwalan ulang.';
                }
            }

            return redirect()->route('konfirmasi.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in konfirmasi process', [
                'error' => $e->getMessage(),
                'jadwal_id' => $jadwalId,
                'user_role' => $user->active_role
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan konfirmasi');
        }
    }

    public function cancel($jadwalId)
    {
        $user = Auth::user();

        if (!in_array($user->active_role, ['pelapor', 'terlapor'])) {
            abort(403, 'Akses ditolak');
        }

        $jadwal = Jadwal::with(['pengaduan.pelapor', 'pengaduan.terlapor'])
            ->findOrFail($jadwalId);

        // Pastikan jadwal masih dalam status yang bisa dikonfirmasi
        if ($jadwal->status_jadwal !== 'dijadwalkan') {
            return redirect()->back()->with('error', 'Jadwal ini sudah tidak memerlukan konfirmasi kehadiran');
        }

        // Pastikan user berhak mengakses jadwal ini
        $pengaduan = $jadwal->pengaduan;
        $hasAccess = false;

        if ($user->active_role === 'pelapor' && $user->pelapor) {
            $hasAccess = $pengaduan->pelapor_id == $user->pelapor->pelapor_id;
        } elseif ($user->active_role === 'terlapor' && $user->terlapor) {
            $hasAccess = $pengaduan->terlapor_id == $user->terlapor->terlapor_id;
        }

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses untuk membatalkan konfirmasi jadwal ini');
        }

        try {
            DB::beginTransaction();

            // Reset konfirmasi berdasarkan role
            if ($user->active_role === 'pelapor') {
                $jadwal->update([
                    'konfirmasi_pelapor' => 'pending',
                    'tanggal_konfirmasi_pelapor' => null,
                    'catatan_konfirmasi_pelapor' => null
                ]);
            } else {
                $jadwal->update([
                    'konfirmasi_terlapor' => 'pending',
                    'tanggal_konfirmasi_terlapor' => null,
                    'catatan_konfirmasi_terlapor' => null
                ]);
            }

            // Jika status ditunda karena ada yang tidak hadir, kembalikan ke dijadwalkan
            if ($jadwal->status_jadwal === 'ditunda') {
                $jadwal->update(['status_jadwal' => 'dijadwalkan']);
            }

            DB::commit();

            return redirect()->route('konfirmasi.index')->with('success', 'Konfirmasi berhasil dibatalkan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membatalkan konfirmasi');
        }
    }
}
