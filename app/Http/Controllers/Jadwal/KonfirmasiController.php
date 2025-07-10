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

        if (!in_array($user->role, ['pelapor', 'terlapor'])) {
            abort(403, 'Akses ditolak');
        }

        // Ambil jadwal berdasarkan role user
        if ($user->role === 'pelapor' && $user->pelapor) {
            $jadwal = Jadwal::with(['pengaduan', 'mediator'])
                ->whereHas('pengaduan', function ($query) use ($user) {
                    $query->where('pelapor_id', $user->pelapor->pelapor_id);
                })
                ->whereIn('status_jadwal', ['dijadwalkan', 'ditunda'])
                ->orderBy('tanggal', 'asc')
                ->get();
        } elseif ($user->role === 'terlapor' && $user->terlapor) {
            $jadwal = Jadwal::with(['pengaduan', 'mediator'])
                ->whereHas('pengaduan', function ($query) use ($user) {
                    $query->where('terlapor_id', $user->terlapor->terlapor_id);
                })
                ->whereIn('status_jadwal', ['dijadwalkan', 'ditunda'])
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

        if (!in_array($user->role, ['pelapor', 'terlapor'])) {
            abort(403, 'Akses ditolak');
        }

        $jadwal = Jadwal::with(['pengaduan.pelapor', 'pengaduan.terlapor', 'mediator'])
            ->findOrFail($jadwalId);

        // Pastikan user berhak mengakses jadwal ini
        $pengaduan = $jadwal->pengaduan;
        $hasAccess = false;

        if ($user->role === 'pelapor' && $user->pelapor) {
            $hasAccess = $pengaduan->pelapor_id == $user->pelapor->pelapor_id;
        } elseif ($user->role === 'terlapor' && $user->terlapor) {
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

        if (!in_array($user->role, ['pelapor', 'terlapor'])) {
            abort(403, 'Akses ditolak');
        }

        $jadwal = Jadwal::with(['pengaduan.pelapor', 'pengaduan.terlapor', 'mediator'])
            ->findOrFail($jadwalId);

        // Pastikan user berhak mengakses jadwal ini
        $pengaduan = $jadwal->pengaduan;
        $hasAccess = false;

        if ($user->role === 'pelapor' && $user->pelapor) {
            $hasAccess = $pengaduan->pelapor_id == $user->pelapor->pelapor_id;
        } elseif ($user->role === 'terlapor' && $user->terlapor) {
            $hasAccess = $pengaduan->terlapor_id == $user->terlapor->terlapor_id;
        }

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses untuk konfirmasi jadwal ini');
        }

        // Cek apakah sudah pernah konfirmasi
        if ($user->role === 'pelapor' && $jadwal->konfirmasi_pelapor !== 'pending') {
            return redirect()->back()->with('error', 'Anda sudah melakukan konfirmasi sebelumnya');
        }

        if ($user->role === 'terlapor' && $jadwal->konfirmasi_terlapor !== 'pending') {
            return redirect()->back()->with('error', 'Anda sudah melakukan konfirmasi sebelumnya');
        }

        try {
            DB::beginTransaction();

            // Update konfirmasi berdasarkan role
            if ($user->role === 'pelapor') {
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
                // Update status jadwal menjadi ditunda
                $jadwal->update(['status_jadwal' => 'ditunda']);

                // Determine who is absent
                $absentParty = '';
                if ($jadwal->konfirmasi_pelapor === 'tidak_hadir' && $jadwal->konfirmasi_terlapor === 'tidak_hadir') {
                    $absentParty = 'both';
                } elseif ($jadwal->konfirmasi_pelapor === 'tidak_hadir') {
                    $absentParty = 'pelapor';
                } else {
                    $absentParty = 'terlapor';
                }

                // Trigger event konfirmasi kehadiran (for standard notification)
                event(new KonfirmasiKehadiran($jadwal, $user->role, $request->konfirmasi));

                // Trigger event reschedule needed (for special handling)
                event(new JadwalRescheduleNeeded($jadwal, $absentParty, $request->catatan ?? ''));

                Log::info('🚨 Reschedule needed - absent party detected', [
                    'jadwal_id' => $jadwal->jadwal_id,
                    'absent_party' => $absentParty,
                    'status_updated_to' => 'ditunda'
                ]);
            } else {
                // Normal confirmation - trigger standard event
                event(new KonfirmasiKehadiran($jadwal, $user->role, $request->konfirmasi));

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
                    $message = 'Konfirmasi kehadiran berhasil. Kedua belah pihak siap hadir. Mediasi akan dilaksanakan sesuai jadwal.';
                } else {
                    $message = 'Konfirmasi kehadiran berhasil. Terima kasih!';
                }
            } else {
                $message = 'Konfirmasi ketidakhadiran berhasil. Tim mediator akan menghubungi Anda untuk penjadwalan ulang.';
            }

            return redirect()->route('konfirmasi.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in konfirmasi process', [
                'error' => $e->getMessage(),
                'jadwal_id' => $jadwalId,
                'user_role' => $user->role
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan konfirmasi');
        }
    }

    public function cancel($jadwalId)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['pelapor', 'terlapor'])) {
            abort(403, 'Akses ditolak');
        }

        $jadwal = Jadwal::with(['pengaduan.pelapor', 'pengaduan.terlapor'])
            ->findOrFail($jadwalId);

        // Pastikan user berhak mengakses jadwal ini
        $pengaduan = $jadwal->pengaduan;
        $hasAccess = false;

        if ($user->role === 'pelapor' && $user->pelapor) {
            $hasAccess = $pengaduan->pelapor_id == $user->pelapor->pelapor_id;
        } elseif ($user->role === 'terlapor' && $user->terlapor) {
            $hasAccess = $pengaduan->terlapor_id == $user->terlapor->terlapor_id;
        }

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses untuk membatalkan konfirmasi jadwal ini');
        }

        try {
            DB::beginTransaction();

            // Reset konfirmasi berdasarkan role
            if ($user->role === 'pelapor') {
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
