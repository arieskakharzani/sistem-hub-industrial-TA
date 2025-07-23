<?php

namespace App\Http\Controllers\Penyelesaian;

use App\Models\Anjuran;
use App\Models\Risalah;
use Illuminate\Http\Request;
use App\Events\RisalahSigned;
use App\Events\AnjuranPublished;
use App\Models\PerjanjianBersama;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Events\AnjuranMediatorSigned;
use Illuminate\Support\Facades\Storage;
use App\Events\AnjuranKepalaDinasSigned;
use App\Events\PerjanjianBersamaCompleted;
use App\Events\PerjanjianBersamaPelaporSigned;
use App\Events\PerjanjianBersamaTerlaporSigned;

class PenyelesaianController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->active_role;

        $filter = request('jenis_dokumen');
        $jenisDokumenList = ['Risalah Klarifikasi', 'Risalah Penyelesaian', 'Perjanjian Bersama', 'Anjuran'];

        // --- Dokumen Pending (perlu ditandatangani user aktif) ---
        $risalahPending = collect();
        $perjanjianPending = collect();
        $anjuranPending = collect();

        if ($role === 'mediator' && $user->mediator) {
            $mediatorId = $user->mediator->mediator_id;
            $risalahPending = Risalah::with(['jadwal.mediator'])
                ->where('ttd_mediator', false)
                ->whereHas('jadwal', function ($q) use ($mediatorId) {
                    $q->where('mediator_id', $mediatorId);
                });
            $perjanjianPending = PerjanjianBersama::with(['dokumenHI.risalah.jadwal.mediator'])
                ->where('ttd_mediator', false)
                ->where('ttd_pekerja', true)
                ->where('ttd_pengusaha', true)
                ->whereHas('dokumenHI.risalah.jadwal', function ($q) use ($mediatorId) {
                    $q->where('mediator_id', $mediatorId);
                });
            $anjuranPending = Anjuran::with(['dokumenHI.risalah.jadwal.mediator'])
                ->where('ttd_mediator', false)
                ->whereHas('dokumenHI.risalah.jadwal', function ($q) use ($mediatorId) {
                    $q->where('mediator_id', $mediatorId);
                });
        } elseif ($role === 'pelapor' && $user->pelapor) {
            $pelaporId = $user->pelapor->pelapor_id;
            // Pelapor hanya bisa menandatangani perjanjian bersama, risalahPending dikosongkan
            $risalahPending = collect();
            $perjanjianPending = PerjanjianBersama::with(['dokumenHI.pengaduan', 'dokumenHI.risalah.jadwal.mediator'])
                ->where('ttd_pekerja', false)
                ->whereHas('dokumenHI.pengaduan', function ($q) use ($pelaporId) {
                    $q->where('pelapor_id', $pelaporId);
                });
            $anjuranPending = Anjuran::with(['dokumenHI.pengaduan', 'dokumenHI.risalah.jadwal.mediator'])
                ->where('ttd_mediator', true)
                ->whereHas('dokumenHI.pengaduan', function ($q) use ($pelaporId) {
                    $q->where('pelapor_id', $pelaporId);
                });
        } elseif ($role === 'terlapor' && $user->terlapor) {
            $terlaporId = $user->terlapor->terlapor_id;
            // Terlapor tidak boleh menandatangani risalah, kosongkan risalahPending
            $risalahPending = collect();
            $perjanjianPending = PerjanjianBersama::with(['dokumenHI.pengaduan', 'dokumenHI.risalah.jadwal.mediator'])
                ->where('ttd_pengusaha', false)
                ->whereHas('dokumenHI.pengaduan', function ($q) use ($terlaporId) {
                    $q->where('terlapor_id', $terlaporId);
                });
            $anjuranPending = Anjuran::with(['dokumenHI.pengaduan', 'dokumenHI.risalah.jadwal.mediator'])
                ->where('ttd_mediator', true)
                ->whereHas('dokumenHI.pengaduan', function ($q) use ($terlaporId) {
                    $q->where('terlapor_id', $terlaporId);
                });
        } elseif ($role === 'kepala_dinas' && $user->kepalaDinas) {
            $kepalaDinasId = $user->kepalaDinas->kepala_dinas_id;
            $anjuranPending = Anjuran::with(['dokumenHI.pengaduan', 'dokumenHI.risalah.jadwal.mediator'])
                ->where('ttd_mediator', true)
                ->where('ttd_kepala_dinas', false)
                ->where('kepala_dinas_id', $kepalaDinasId);
        }

        // Filter by jenis dokumen (optional)
        if ($filter === 'Risalah Klarifikasi') {
            $risalahPending = $risalahPending->where('jenis_risalah', 'klarifikasi');
        } elseif ($filter === 'Risalah Penyelesaian') {
            $risalahPending = $risalahPending->where('jenis_risalah', 'penyelesaian');
        }
        if ($filter === 'Perjanjian Bersama') {
            // no-op
        } elseif ($filter && !in_array($filter, ['Perjanjian Bersama', 'Semua', null])) {
            $perjanjianPending = $perjanjianPending->whereRaw('1=0');
        }
        if ($filter === 'Anjuran') {
            // no-op
        } elseif ($filter && !in_array($filter, ['Anjuran', 'Semua', null])) {
            $anjuranPending = $anjuranPending->whereRaw('1=0');
        }

        // Terapkan filter jenis dokumen pada semua query
        if ($filter && $filter !== 'Semua') {
            if ($filter === 'Perjanjian Bersama') {
                if (isset($perjanjianPending) && $perjanjianPending instanceof \Illuminate\Database\Eloquent\Builder) {
                    // ok
                } else {
                    $perjanjianPending = collect();
                }
                if (isset($perjanjianSignedByUser) && $perjanjianSignedByUser instanceof \Illuminate\Database\Eloquent\Builder) {
                    // ok
                } else {
                    $perjanjianSignedByUser = collect();
                }
                if (isset($perjanjianSigned) && $perjanjianSigned instanceof \Illuminate\Database\Eloquent\Builder) {
                    // ok
                } else {
                    $perjanjianSigned = collect();
                }
            } else {
                $perjanjianPending = collect();
                $perjanjianSignedByUser = collect();
                $perjanjianSigned = collect();
            }
        }

        // Ambil data dan pastikan hanya model, tanpa nested collection
        $risalahPending = $risalahPending instanceof \Illuminate\Database\Eloquent\Builder ? $risalahPending->get() : collect();
        $perjanjianPending = $perjanjianPending instanceof \Illuminate\Database\Eloquent\Builder ? $perjanjianPending->get() : collect();
        $anjuranPending = $anjuranPending instanceof \Illuminate\Database\Eloquent\Builder ? $anjuranPending->get() : collect();
        // FILTER & FLATTEN EKSTRA KETAT
        $risalahPending = collect($risalahPending)->flatten(1)->filter(fn($item) => $item instanceof \App\Models\Risalah)->values();
        $perjanjianPending = collect($perjanjianPending)->flatten(1)->filter(fn($item) => $item instanceof \App\Models\PerjanjianBersama)->values();
        $anjuranPending = collect($anjuranPending)->flatten(1)->filter(fn($item) => $item instanceof \App\Models\Anjuran)->values();
        // LOG DEBUG
        \Log::info('DEBUG risalahPending', [
            'type' => gettype($risalahPending),
            'is_collection' => $risalahPending instanceof \Illuminate\Support\Collection,
            'first_type' => $risalahPending->first() ? get_class($risalahPending->first()) : null,
            'has_nested' => $risalahPending->contains(fn($item) => $item instanceof \Illuminate\Support\Collection),
        ]);

        $dokumenPending = [
            'risalah' => $risalahPending,
            'perjanjian_bersama' => $perjanjianPending,
            'anjuran' => $anjuranPending
        ];

        // --- Dokumen Signed/Final (sudah ditandatangani semua pihak) ---
        $risalahSigned = collect();
        $perjanjianSigned = collect();
        $anjuranSigned = collect();
        if ($role === 'mediator' && $user->mediator) {
            $mediatorId = $user->mediator->mediator_id;
            $risalahSignedQ = Risalah::with(['jadwal.mediator'])
                ->where('ttd_mediator', true)
                ->whereHas('jadwal', function ($q) use ($mediatorId) {
                    $q->where('mediator_id', $mediatorId);
                });
            $perjanjianSignedQ = PerjanjianBersama::with(['dokumenHI.risalah.jadwal.mediator'])
                ->where('ttd_mediator', true)
                ->where('ttd_pekerja', true)
                ->where('ttd_pengusaha', true)
                ->whereHas('dokumenHI.risalah.jadwal', function ($q) use ($mediatorId) {
                    $q->where('mediator_id', $mediatorId);
                });
            $anjuranSignedQ = Anjuran::with(['dokumenHI.risalah.jadwal.mediator'])
                ->where('ttd_mediator', true)
                ->where('ttd_kepala_dinas', true)
                ->whereHas('dokumenHI.risalah.jadwal', function ($q) use ($mediatorId) {
                    $q->where('mediator_id', $mediatorId);
                });
        } elseif ($role === 'pelapor' && $user->pelapor) {
            $pelaporId = $user->pelapor->pelapor_id;
            $risalahSignedQ = Risalah::with(['jadwal.mediator'])
                ->where('ttd_mediator', true)
                ->whereHas('jadwal.pengaduan', function ($q) use ($pelaporId) {
                    $q->where('pelapor_id', $pelaporId);
                });
            $perjanjianSignedQ = PerjanjianBersama::with(['dokumenHI.pengaduan', 'dokumenHI.risalah.jadwal.mediator'])
                ->where('ttd_pekerja', true)
                ->where('ttd_pengusaha', true)
                ->where('ttd_mediator', true)
                ->whereHas('dokumenHI.pengaduan', function ($q) use ($pelaporId) {
                    $q->where('pelapor_id', $pelaporId);
                });
            $anjuranSignedQ = Anjuran::with(['dokumenHI.pengaduan', 'dokumenHI.risalah.jadwal.mediator'])
                ->where('ttd_kepala_dinas', true)
                ->whereHas('dokumenHI.pengaduan', function ($q) use ($pelaporId) {
                    $q->where('pelapor_id', $pelaporId);
                });
        } elseif ($role === 'terlapor' && $user->terlapor) {
            $terlaporId = $user->terlapor->terlapor_id;
            $risalahSignedQ = Risalah::with(['jadwal.mediator'])
                ->where('ttd_mediator', true)
                ->whereHas('jadwal.pengaduan', function ($q) use ($terlaporId) {
                    $q->where('terlapor_id', $terlaporId);
                });
            $perjanjianSignedQ = PerjanjianBersama::with(['dokumenHI.pengaduan', 'dokumenHI.risalah.jadwal.mediator'])
                ->where('ttd_pekerja', true)
                ->where('ttd_pengusaha', true)
                ->where('ttd_mediator', true)
                ->whereHas('dokumenHI.pengaduan', function ($q) use ($terlaporId) {
                    $q->where('terlapor_id', $terlaporId);
                });
            $anjuranSignedQ = Anjuran::with(['dokumenHI.pengaduan', 'dokumenHI.risalah.jadwal.mediator'])
                ->where('ttd_kepala_dinas', true)
                ->whereHas('dokumenHI.pengaduan', function ($q) use ($terlaporId) {
                    $q->where('terlapor_id', $terlaporId);
                });
        } elseif ($role === 'kepala_dinas' && $user->kepalaDinas) {
            $kepalaDinasId = $user->kepalaDinas->kepala_dinas_id;
            $anjuranSignedQ = Anjuran::with(['dokumenHI.pengaduan', 'dokumenHI.risalah.jadwal.mediator'])
                ->where('ttd_kepala_dinas', true)
                ->where('kepala_dinas_id', $kepalaDinasId);
        }
        // Terapkan filter pada query builder sebelum get()
        if ($filter === 'Risalah Klarifikasi') {
            if (isset($risalahSignedQ)) $risalahSignedQ = $risalahSignedQ->where('jenis_risalah', 'klarifikasi');
        } elseif ($filter === 'Risalah Penyelesaian') {
            if (isset($risalahSignedQ)) $risalahSignedQ = $risalahSignedQ->where('jenis_risalah', 'penyelesaian');
        }
        if ($filter && !in_array($filter, ['Risalah Klarifikasi', 'Risalah Penyelesaian', 'Semua', null])) {
            $risalahSignedQ = null;
        }
        if ($filter && $filter !== 'Perjanjian Bersama' && $filter !== 'Semua' && $filter !== null) {
            $perjanjianSignedQ = null;
        }
        if ($filter && $filter !== 'Anjuran' && $filter !== 'Semua' && $filter !== null) {
            $anjuranSignedQ = null;
        }
        $risalahSigned = isset($risalahSignedQ) ? $risalahSignedQ->get()->filter(fn($item) => $item instanceof \App\Models\Risalah)->values() : collect();
        $perjanjianSigned = isset($perjanjianSignedQ) ? $perjanjianSignedQ->get()->filter(fn($item) => $item instanceof \App\Models\PerjanjianBersama)->values() : collect();
        $anjuranSigned = isset($anjuranSignedQ) ? $anjuranSignedQ->get()->filter(fn($item) => $item instanceof \App\Models\Anjuran)->values() : collect();
        $dokumenSigned = [
            'risalah' => $risalahSigned,
            'perjanjian_bersama' => $perjanjianSigned,
            'anjuran' => $anjuranSigned
        ];

        // --- Dokumen Sudah Ditandatangani User (tapi belum final) ---
        $risalahSignedByUser = collect();
        $perjanjianSignedByUser = collect();
        $anjuranSignedByUser = collect();
        if ($role === 'pelapor' && $user->pelapor) {
            $pelaporId = $user->pelapor->pelapor_id;
            $perjanjianSignedByUserQ = PerjanjianBersama::with(['dokumenHI.pengaduan', 'dokumenHI.risalah.jadwal.mediator'])
                ->where('ttd_pekerja', true)
                ->where(function ($q) {
                    $q->where('ttd_pengusaha', false)->orWhere('ttd_mediator', false);
                })
                ->whereHas('dokumenHI.pengaduan', function ($q) use ($pelaporId) {
                    $q->where('pelapor_id', $pelaporId);
                });
            $anjuranSignedByUserQ = Anjuran::with(['dokumenHI.pengaduan', 'dokumenHI.risalah.jadwal.mediator'])
                ->where('ttd_mediator', true)
                ->where('ttd_kepala_dinas', false)
                ->whereHas('dokumenHI.pengaduan', function ($q) use ($pelaporId) {
                    $q->where('pelapor_id', $pelaporId);
                });
        } elseif ($role === 'terlapor' && $user->terlapor) {
            $terlaporId = $user->terlapor->terlapor_id;
            $perjanjianSignedByUserQ = PerjanjianBersama::with(['dokumenHI.pengaduan', 'dokumenHI.risalah.jadwal.mediator'])
                ->where('ttd_pengusaha', true)
                ->where(function ($q) {
                    $q->where('ttd_pekerja', false)->orWhere('ttd_mediator', false);
                })
                ->whereHas('dokumenHI.pengaduan', function ($q) use ($terlaporId) {
                    $q->where('terlapor_id', $terlaporId);
                });
            $anjuranSignedByUserQ = Anjuran::with(['dokumenHI.pengaduan', 'dokumenHI.risalah.jadwal.mediator'])
                ->where('ttd_mediator', true)
                ->where('ttd_kepala_dinas', false)
                ->whereHas('dokumenHI.pengaduan', function ($q) use ($terlaporId) {
                    $q->where('terlapor_id', $terlaporId);
                });
        }
        // Terapkan filter pada query builder sebelum get()
        if ($filter === 'Risalah Klarifikasi') {
            if (isset($risalahSignedByUserQ)) $risalahSignedByUserQ = $risalahSignedByUserQ->where('jenis_risalah', 'klarifikasi');
        } elseif ($filter === 'Risalah Penyelesaian') {
            if (isset($risalahSignedByUserQ)) $risalahSignedByUserQ = $risalahSignedByUserQ->where('jenis_risalah', 'penyelesaian');
        }
        if ($filter && !in_array($filter, ['Risalah Klarifikasi', 'Risalah Penyelesaian', 'Semua', null])) {
            $risalahSignedByUserQ = null;
        }
        if ($filter && $filter !== 'Perjanjian Bersama' && $filter !== 'Semua' && $filter !== null) {
            $perjanjianSignedByUserQ = null;
        }
        if ($filter && $filter !== 'Anjuran' && $filter !== 'Semua' && $filter !== null) {
            $anjuranSignedByUserQ = null;
        }
        $risalahSignedByUser = isset($risalahSignedByUserQ) ? $risalahSignedByUserQ->get()->filter(fn($item) => $item instanceof \App\Models\Risalah)->values() : collect();
        $perjanjianSignedByUser = isset($perjanjianSignedByUserQ) ? $perjanjianSignedByUserQ->get()->filter(fn($item) => $item instanceof \App\Models\PerjanjianBersama)->values() : collect();
        $anjuranSignedByUser = isset($anjuranSignedByUserQ) ? $anjuranSignedByUserQ->get()->filter(fn($item) => $item instanceof \App\Models\Anjuran)->values() : collect();
        $dokumenSignedByUser = [
            'risalah' => $risalahSignedByUser,
            'perjanjian_bersama' => $perjanjianSignedByUser,
            'anjuran' => $anjuranSignedByUser
        ];

        // Filter by jenis dokumen (optional) UNTUK SIGNED-BY-USER
        if ($filter === 'Risalah Klarifikasi') {
            if (isset($risalahSignedByUser) && $risalahSignedByUser instanceof \Illuminate\Database\Eloquent\Builder) {
                $risalahSignedByUser = $risalahSignedByUser->where('jenis_risalah', 'klarifikasi');
            } else {
                $risalahSignedByUser = collect();
            }
        } elseif ($filter === 'Risalah Penyelesaian') {
            if (isset($risalahSignedByUser) && $risalahSignedByUser instanceof \Illuminate\Database\Eloquent\Builder) {
                $risalahSignedByUser = $risalahSignedByUser->where('jenis_risalah', 'penyelesaian');
            } else {
                $risalahSignedByUser = collect();
            }
        }
        if ($filter === 'Perjanjian Bersama') {
            // no-op
        } elseif ($filter && !in_array($filter, ['Perjanjian Bersama', 'Semua', null])) {
            $perjanjianSignedByUser = collect();
        }
        if ($filter === 'Anjuran') {
            // no-op
        } elseif ($filter && !in_array($filter, ['Anjuran', 'Semua', null])) {
            $anjuranSignedByUser = collect();
        }

        // Filter by jenis dokumen (optional) UNTUK FINAL
        if ($filter === 'Risalah Klarifikasi') {
            if (isset($risalahSigned) && $risalahSigned instanceof \Illuminate\Database\Eloquent\Builder) {
                $risalahSigned = $risalahSigned->where('jenis_risalah', 'klarifikasi');
            } else {
                $risalahSigned = collect();
            }
        } elseif ($filter === 'Risalah Penyelesaian') {
            if (isset($risalahSigned) && $risalahSigned instanceof \Illuminate\Database\Eloquent\Builder) {
                $risalahSigned = $risalahSigned->where('jenis_risalah', 'penyelesaian');
            } else {
                $risalahSigned = collect();
            }
        }
        if ($filter === 'Perjanjian Bersama') {
            // no-op
        } elseif ($filter && !in_array($filter, ['Perjanjian Bersama', 'Semua', null])) {
            $perjanjianSigned = collect();
        }
        if ($filter === 'Anjuran') {
            // no-op
        } elseif ($filter && !in_array($filter, ['Anjuran', 'Semua', null])) {
            $anjuranSigned = collect();
        }

        return view('penyelesaian.index', compact('dokumenPending', 'dokumenSigned', 'dokumenSignedByUser', 'jenisDokumenList', 'filter'));
    }

    public function signDocument(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:risalah,perjanjian_bersama,anjuran',
            'document_id' => 'required|uuid',
            'signature' => 'required|string' // Base64 encoded signature
        ]);

        $user = Auth::user();
        $role = $user->active_role;

        // Simpan tanda tangan sebagai file
        $signatureData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->signature));
        $filename = uniqid() . '_signature.png';
        Storage::disk('public')->put('signatures/' . $filename, $signatureData);

        // Update dokumen berdasarkan tipe
        switch ($request->document_type) {
            case 'risalah':
                $document = Risalah::findOrFail($request->document_id);
                if ($role === 'mediator') {
                    $document->update([
                        'ttd_mediator' => true,
                        'tanggal_ttd_mediator' => now(),
                        'signature_mediator' => $filename
                    ]);
                    event(new RisalahSigned($document));
                }
                break;

            case 'perjanjian_bersama':
                $document = PerjanjianBersama::findOrFail($request->document_id);
                if ($role === 'mediator') {
                    $document->update([
                        'ttd_mediator' => true,
                        'tanggal_ttd_mediator' => now(),
                        'signature_mediator' => $filename
                    ]);
                    event(new PerjanjianBersamaCompleted($document));
                } elseif ($role === 'pelapor') {
                    $document->update([
                        'ttd_pekerja' => true,
                        'tanggal_ttd_pekerja' => now(),
                        'signature_pekerja' => $filename
                    ]);
                    event(new PerjanjianBersamaPelaporSigned($document));
                } elseif ($role === 'terlapor') {
                    $document->update([
                        'ttd_pengusaha' => true,
                        'tanggal_ttd_pengusaha' => now(),
                        'signature_pengusaha' => $filename
                    ]);
                    event(new PerjanjianBersamaTerlaporSigned($document));
                }
                break;

            case 'anjuran':
                $document = Anjuran::findOrFail($request->document_id);
                if ($role === 'mediator') {
                    $document->update([
                        'ttd_mediator' => true,
                        'tanggal_ttd_mediator' => now(),
                        'signature_mediator' => $filename
                    ]);
                    event(new AnjuranMediatorSigned($document));
                } elseif ($role === 'kepala_dinas') {
                    $document->update([
                        'ttd_kepala_dinas' => true,
                        'tanggal_ttd_kepala_dinas' => now(),
                        'signature_kepala_dinas' => $filename,
                        'status_approval' => 'approved',
                        'tanggal_approval' => now()
                    ]);
                    event(new AnjuranKepalaDinasSigned($document));
                }
                break;
        }

        return redirect()->back()->with('success', 'Dokumen berhasil ditandatangani.');
    }

    public function publishAnjuran(Anjuran $anjuran)
    {
        // Pastikan anjuran sudah ditandatangani oleh mediator dan kepala dinas
        if (!$anjuran->isFullySigned()) {
            return redirect()->back()->with('error', 'Anjuran belum ditandatangani lengkap.');
        }

        event(new AnjuranPublished($anjuran));

        return redirect()->back()->with('success', 'Anjuran berhasil dipublikasikan kepada para pihak.');
    }

    public function finalizeDocument(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:risalah,perjanjian_bersama,anjuran',
            'document_id' => 'required|uuid',
        ]);

        $type = $request->input('document_type');
        $id = $request->input('document_id');
        $user = Auth::user();

        if ($type === 'risalah') {
            $risalah = Risalah::findOrFail($id);
            if ($risalah->isSignedByMediator()) {
                // Kirim notifikasi/email ke pelapor & terlapor
                $pengaduan = $risalah->jadwal->pengaduan;
                $pelaporUser = $pengaduan->pelapor ? $pengaduan->pelapor->user : null;
                $terlaporUser = $pengaduan->terlapor ? $pengaduan->terlapor->user : null;
                if ($pelaporUser) {
                    $pelaporUser->notify(new \App\Notifications\RisalahSignedNotification($risalah));
                }
                if ($terlaporUser) {
                    $terlaporUser->notify(new \App\Notifications\RisalahSignedNotification($risalah));
                }
                // Update status
                $risalah->jadwal->status_jadwal = 'selesai';
                $risalah->jadwal->save();
                $pengaduan->status = 'selesai';
                $pengaduan->save();
            } else {
                return back()->with('error', 'Dokumen belum ditandatangani mediator.');
            }
        }
        if ($type === 'perjanjian_bersama') {
            $pb = PerjanjianBersama::findOrFail($id);
            if ($pb->isFullySigned()) {
                $pengaduan = $pb->dokumenHI->pengaduan;
                $pelaporUser = $pengaduan->pelapor ? $pengaduan->pelapor->user : null;
                $terlaporUser = $pengaduan->terlapor ? $pengaduan->terlapor->user : null;
                if ($pelaporUser) {
                    $pelaporUser->notify(new \App\Notifications\PerjanjianBersamaCompletedNotification($pb));
                }
                if ($terlaporUser) {
                    $terlaporUser->notify(new \App\Notifications\PerjanjianBersamaCompletedNotification($pb));
                }
                $pb->dokumenHI->pengaduan->status = 'selesai';
                $pb->dokumenHI->pengaduan->save();
            } else {
                return back()->with('error', 'Dokumen belum ditandatangani semua pihak.');
            }
        }
        if ($type === 'anjuran') {
            $anjuran = Anjuran::findOrFail($id);
            if ($anjuran->isFullySigned()) {
                $pengaduan = $anjuran->dokumenHI->pengaduan;
                $pelaporUser = $pengaduan->pelapor ? $pengaduan->pelapor->user : null;
                $terlaporUser = $pengaduan->terlapor ? $pengaduan->terlapor->user : null;
                if ($pelaporUser) {
                    $pelaporUser->notify(new \App\Notifications\AnjuranPublishedNotification($anjuran));
                }
                if ($terlaporUser) {
                    $terlaporUser->notify(new \App\Notifications\AnjuranPublishedNotification($anjuran));
                }
                $anjuran->dokumenHI->pengaduan->status = 'selesai';
                $anjuran->dokumenHI->pengaduan->save();
            } else {
                return back()->with('error', 'Anjuran belum ditandatangani mediator dan kepala dinas.');
            }
        }
        return back()->with('success', 'Dokumen berhasil dikirim dan status kasus selesai.');
    }
}
