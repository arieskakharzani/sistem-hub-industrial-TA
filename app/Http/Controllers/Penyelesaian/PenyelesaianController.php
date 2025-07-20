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

        // Ambil dokumen yang perlu ditandatangani berdasarkan role
        $dokumenPending = [];

        if ($role === 'mediator') {
            // Risalah yang perlu ditandatangani mediator
            $risalahPending = Risalah::where('ttd_mediator', false)
                ->whereHas('jadwal', function ($q) use ($user) {
                    $q->where('mediator_id', $user->mediator->mediator_id);
                })->get();

            // Perjanjian Bersama yang perlu ditandatangani mediator
            $perjanjianPending = PerjanjianBersama::where('ttd_mediator', false)
                ->where(function ($q) {
                    $q->where('ttd_pekerja', true)
                        ->where('ttd_pengusaha', true);
                })
                ->whereHas('dokumenHI.risalah.jadwal', function ($q) use ($user) {
                    $q->where('mediator_id', $user->mediator->mediator_id);
                })->get();

            // Anjuran yang perlu ditandatangani mediator
            $anjuranPending = Anjuran::where('ttd_mediator', false)
                ->whereHas('dokumenHI.risalah.jadwal', function ($q) use ($user) {
                    $q->where('mediator_id', $user->mediator->mediator_id);
                })->get();

            $dokumenPending = [
                'risalah' => $risalahPending,
                'perjanjian_bersama' => $perjanjianPending,
                'anjuran' => $anjuranPending
            ];
        } elseif ($role === 'kepala_dinas') {
            // Anjuran yang perlu diapprove kepala dinas
            $anjuranPending = Anjuran::where('ttd_kepala_dinas', false)
                ->where('ttd_mediator', true)
                ->where('kepala_dinas_id', $user->kepalaDinas->kepala_dinas_id)
                ->get();

            $dokumenPending = [
                'anjuran' => $anjuranPending
            ];
        } elseif ($role === 'pelapor') {
            // Perjanjian Bersama yang perlu ditandatangani pelapor
            $perjanjianPending = PerjanjianBersama::where('ttd_pekerja', false)
                ->whereHas('dokumenHI.risalah.jadwal.pengaduan', function ($q) use ($user) {
                    $q->where('pelapor_id', $user->pelapor->pelapor_id);
                })->get();

            $dokumenPending = [
                'perjanjian_bersama' => $perjanjianPending
            ];
        } elseif ($role === 'terlapor') {
            // Perjanjian Bersama yang perlu ditandatangani terlapor (setelah pelapor)
            $perjanjianPending = PerjanjianBersama::where('ttd_pengusaha', false)
                ->where('ttd_pekerja', true)
                ->whereHas('dokumenHI.risalah.jadwal.pengaduan', function ($q) use ($user) {
                    $q->where('terlapor_id', $user->terlapor->terlapor_id);
                })->get();

            $dokumenPending = [
                'perjanjian_bersama' => $perjanjianPending
            ];
        }

        return view('penyelesaian.index', compact('dokumenPending'));
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
