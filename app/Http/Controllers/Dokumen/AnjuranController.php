<?php

namespace App\Http\Controllers\Dokumen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Anjuran;
use App\Models\DokumenHubunganIndustrial;
use App\Models\KepalaDinas;
use App\Models\Pelapor;
use App\Models\Terlapor;
use App\Models\User;
use App\Notifications\AnjuranPendingApprovalNotification;
use App\Notifications\AnjuranApprovedNotification;
use App\Notifications\AnjuranRejectedNotification;
use App\Notifications\AnjuranPublishedNotification;
use App\Mail\FinalCaseDocumentsMail;
use App\Mail\DraftPerjanjianBersamaMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AnjuranController extends Controller
{
    public function create($dokumen_hi_id)
    {
        $dokumenHI = DokumenHubunganIndustrial::with(['risalah' => function ($query) {
            $query->where('jenis_risalah', 'penyelesaian');
        }])->findOrFail($dokumen_hi_id);

        $risalah = $dokumenHI->risalah->first();

        if (!$risalah) {
            return redirect()->back()->with('error', 'Data risalah penyelesaian tidak ditemukan.');
        }

        return view('dokumen.create-anjuran', compact('dokumen_hi_id', 'risalah'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'dokumen_hi_id' => 'required|uuid',
            'nama_pengusaha' => 'required|string',
            'jabatan_pengusaha' => 'required|string',
            'perusahaan_pengusaha' => 'required|string',
            'alamat_pengusaha' => 'required|string',
            'nama_pekerja' => 'required|string',
            'jabatan_pekerja' => 'required|string',
            'perusahaan_pekerja' => 'required|string',
            'alamat_pekerja' => 'required|string',
            'keterangan_pekerja' => 'required|string',
            'keterangan_pengusaha' => 'required|string',
            'pertimbangan_hukum' => 'required|string',
            'isi_anjuran' => 'required|string',
        ]);

        $anjuran = Anjuran::create($data);

        return redirect()->route('dokumen.anjuran.show', $anjuran)
            ->with('success', 'Anjuran berhasil dibuat.');
    }

    public function show(Anjuran $anjuran)
    {
        $user = Auth::user();

        // Load relasi yang diperlukan
        $anjuran->load(['dokumenHI.pengaduan.pelapor', 'dokumenHI.pengaduan.terlapor']);

        // Authorization check
        if ($user->active_role === 'pelapor') {
            // Cek apakah user memiliki relasi pelapor
            if (!$user->relationLoaded('pelapor')) {
                $user->load('pelapor');
            }
            $pelapor = $user->pelapor;
            if (!$pelapor || $anjuran->dokumenHI->pengaduan->pelapor_id !== $pelapor->pelapor_id) {
                abort(403, 'Anda tidak memiliki akses ke anjuran ini.');
            }
        } elseif ($user->active_role === 'terlapor') {
            // Cek apakah user memiliki relasi terlapor
            if (!$user->relationLoaded('terlapor')) {
                $user->load('terlapor');
            }
            $terlapor = $user->terlapor;
            if (!$terlapor || $anjuran->dokumenHI->pengaduan->terlapor_id !== $terlapor->terlapor_id) {
                abort(403, 'Anda tidak memiliki akses ke anjuran ini.');
            }
        } elseif (!in_array($user->active_role, ['mediator', 'kepala_dinas'])) {
            abort(403, 'Anda tidak memiliki akses ke anjuran ini.');
        }

        return view('dokumen.show-anjuran', compact('anjuran'));
    }

    public function edit($id)
    {
        $anjuran = Anjuran::findOrFail($id);
        return view('dokumen.edit-anjuran', compact('anjuran'));
    }

    public function update(Request $request, $id)
    {
        $anjuran = Anjuran::findOrFail($id);

        $data = $request->validate([
            'nama_pengusaha' => 'required|string',
            'jabatan_pengusaha' => 'required|string',
            'perusahaan_pengusaha' => 'required|string',
            'alamat_pengusaha' => 'required|string',
            'nama_pekerja' => 'required|string',
            'jabatan_pekerja' => 'required|string',
            'perusahaan_pekerja' => 'required|string',
            'alamat_pekerja' => 'required|string',
            'keterangan_pekerja' => 'required|string',
            'keterangan_pengusaha' => 'required|string',
            'pertimbangan_hukum' => 'required|string',
            'isi_anjuran' => 'required|string',
        ]);

        $anjuran->update($data);

        return redirect()->route('dokumen.anjuran.show', $anjuran)
            ->with('success', 'Anjuran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $anjuran = Anjuran::findOrFail($id);
        $dokumenHiId = $anjuran->dokumen_hi_id;

        $anjuran->delete();

        return redirect()->route('dokumen.index')
            ->with('success', 'Anjuran berhasil dihapus.');
    }

    public function cetakPdf($id)
    {
        $anjuran = Anjuran::with(['dokumenHI.pengaduan.pelapor', 'dokumenHI.pengaduan.terlapor'])->findOrFail($id);
        $user = Auth::user();

        // Validasi akses untuk pelapor dan terlapor
        if ($user->active_role === 'pelapor') {
            $pelapor = Pelapor::where('user_id', $user->user_id)->first();
            if (!$pelapor || $anjuran->dokumenHI->pengaduan->pelapor_id !== $pelapor->pelapor_id) {
                abort(403, 'Anda tidak memiliki akses ke anjuran ini');
            }
        } elseif ($user->active_role === 'terlapor') {
            $terlapor = Terlapor::where('user_id', $user->user_id)->first();
            if (!$terlapor || $anjuran->dokumenHI->pengaduan->terlapor_id !== $terlapor->terlapor_id) {
                abort(403, 'Anda tidak memiliki akses ke anjuran ini');
            }
        }

        $pdf = Pdf::loadView('dokumen.pdf.anjuran', compact('anjuran'));
        return $pdf->stream('anjuran.pdf');
    }

    public function pendingApproval()
    {
        $user = Auth::user();

        // Validasi: hanya kepala dinas yang bisa akses
        if (!$user || $user->active_role !== 'kepala_dinas') {
            return back()->with('error', 'Hanya kepala dinas yang dapat mengakses halaman ini');
        }

        try {
            $pendingAnjurans = Anjuran::with(['dokumenHI.pengaduan.mediator'])
                ->where('status_approval', 'pending_kepala_dinas')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('dokumen.pending-approval', compact('pendingAnjurans'));
        } catch (Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Method untuk sistem approval
    public function submit($anjuranId)
    {
        $anjuran = Anjuran::with(['dokumenHI.pengaduan.mediator'])->findOrFail($anjuranId);

        $user = Auth::user();

        // Validasi: hanya mediator yang bisa submit
        if ($user->active_role !== 'mediator') {
            return back()->with('error', 'Hanya mediator yang dapat mengirim anjuran untuk approval');
        }

        // Validasi: mediator yang membuat harus sama dengan mediator yang submit
        if ($anjuran->mediator->mediator_id !== $user->mediator->mediator_id) {
            return back()->with('error', 'Anda tidak dapat mengirim anjuran yang dibuat oleh mediator lain');
        }

        $anjuran->update([
            'status_approval' => 'pending_kepala_dinas'
        ]);

        // Kirim notifikasi ke kepala dinas
        $this->notifyKepalaDinas($anjuran);

        return back()->with('success', 'Anjuran telah dikirim untuk approval kepala dinas');
    }

    public function approve($anjuranId)
    {
        $anjuran = Anjuran::with(['dokumenHI.pengaduan.mediator.user'])->findOrFail($anjuranId);
        $user = Auth::user();

        // Validasi: hanya kepala dinas yang bisa approve
        if ($user->active_role !== 'kepala_dinas') {
            return back()->with('error', 'Hanya kepala dinas yang dapat melakukan approval');
        }

        if (!$anjuran->canBeApprovedByKepalaDinas()) {
            return back()->with('error', 'Anjuran tidak dapat diapprove saat ini');
        }

        $anjuran->update([
            'status_approval' => 'approved',
            'approved_by_kepala_dinas_at' => now(),
            'notes_kepala_dinas' => request('notes')
        ]);

        // Kirim notifikasi ke mediator
        $this->notifyMediatorApproved($anjuran);

        return back()->with('success', 'Anjuran telah disetujui');
    }

    public function reject($anjuranId)
    {
        $anjuran = Anjuran::with(['dokumenHI.pengaduan.mediator.user'])->findOrFail($anjuranId);
        $user = Auth::user();

        // Validasi: hanya kepala dinas yang bisa reject
        if ($user->active_role !== 'kepala_dinas') {
            return back()->with('error', 'Hanya kepala dinas yang dapat melakukan rejection');
        }

        if (!$anjuran->canBeApprovedByKepalaDinas()) {
            return back()->with('error', 'Anjuran tidak dapat direject saat ini');
        }

        $reason = request('reason');
        if (empty($reason)) {
            return back()->with('error', 'Alasan rejection harus diisi');
        }

        $anjuran->update([
            'status_approval' => 'rejected',
            'rejected_by_kepala_dinas_at' => now(),
            'notes_kepala_dinas' => $reason
        ]);

        // Kirim notifikasi ke mediator
        $this->notifyMediatorRejected($anjuran, $reason);

        return back()->with('success', 'Anjuran telah ditolak');
    }

    public function publish($anjuranId)
    {
        $anjuran = Anjuran::with(['dokumenHI.pengaduan'])->findOrFail($anjuranId);
        $user = Auth::user();

        // Validasi: hanya mediator yang bisa publish
        if ($user->active_role !== 'mediator') {
            return back()->with('error', 'Hanya mediator yang dapat mempublish anjuran');
        }

        if (!$anjuran->canBePublishedByMediator()) {
            return back()->with('error', 'Anjuran belum disetujui kepala dinas');
        }

        $anjuran->update([
            'status_approval' => 'published',
            'published_at' => now(),
            'deadline_response_at' => now()->addDays(10)
        ]);

        // Kirim ke para pihak
        $this->sendAnjuranToParties($anjuran);

        return back()->with('success', 'Anjuran telah dipublish ke para pihak. Deadline response: ' . $anjuran->deadline_response_at->format('d/m/Y H:i'));
    }

    private function notifyKepalaDinas($anjuran)
    {
        // Kirim notifikasi ke semua user dengan role kepala_dinas
        $kepalaDinasUsers = User::where('active_role', 'kepala_dinas')->get();

        foreach ($kepalaDinasUsers as $user) {
            try {
                $user->notify(new AnjuranPendingApprovalNotification($anjuran));
            } catch (\Exception $e) {
                \Log::error('Error mengirim notifikasi ke ' . $user->email . ': ' . $e->getMessage());
            }
        }
    }

    private function notifyMediatorApproved($anjuran)
    {
        // Kirim notifikasi ke mediator yang membuat anjuran
        $mediator = $anjuran->mediator;
        if ($mediator) {
            // Cari user yang terkait dengan mediator ini
            $user = User::where('active_role', 'mediator')
                ->whereHas('mediator', function ($query) use ($mediator) {
                    $query->where('mediator_id', $mediator->mediator_id);
                })
                ->first();

            if ($user) {
                $user->notify(new AnjuranApprovedNotification($anjuran));
            }
        }
    }

    private function notifyMediatorRejected($anjuran, $reason)
    {
        // Kirim notifikasi ke mediator yang membuat anjuran
        $mediator = $anjuran->mediator;
        if ($mediator) {
            // Cari user yang terkait dengan mediator ini
            $user = User::where('active_role', 'mediator')
                ->whereHas('mediator', function ($query) use ($mediator) {
                    $query->where('mediator_id', $mediator->mediator_id);
                })
                ->first();

            if ($user) {
                $user->notify(new AnjuranRejectedNotification($anjuran, $reason));
            }
        }
    }

    private function sendAnjuranToParties($anjuran)
    {
        $pengaduan = $anjuran->dokumenHI->pengaduan;

        // Kirim ke pelapor
        if ($pengaduan->pelapor) {
            $user = User::where('active_role', 'pelapor')
                ->whereHas('pelapor', function ($query) use ($pengaduan) {
                    $query->where('pelapor_id', $pengaduan->pelapor_id);
                })
                ->first();

            if ($user) {
                $user->notify(new AnjuranPublishedNotification($anjuran));
            }
        }

        // Kirim ke terlapor
        if ($pengaduan->terlapor) {
            $user = User::where('active_role', 'terlapor')
                ->whereHas('terlapor', function ($query) use ($pengaduan) {
                    $query->where('terlapor_id', $pengaduan->terlapor_id);
                })
                ->first();

            if ($user) {
                $user->notify(new AnjuranPublishedNotification($anjuran));
            }
        }
    }

    /**
     * Finalize case when anjuran is rejected
     */
    public function finalizeCase(Anjuran $anjuran)
    {
        $anjuran->load(['dokumenHI.pengaduan.pelapor', 'dokumenHI.pengaduan.terlapor']);
        $user = Auth::user();

        // Only mediator can finalize case
        if ($user->active_role !== 'mediator') {
            abort(403, 'Hanya mediator yang dapat menyelesaikan kasus');
        }

        // Check if response is complete
        if (!$anjuran->isResponseComplete()) {
            return redirect()->back()->with('error', 'Respon para pihak belum lengkap');
        }

        try {
            // Update pengaduan status to selesai
            $pengaduan = $anjuran->dokumenHI->pengaduan;
            $pengaduan->update(['status' => 'selesai']);

            // Check if both parties agree (success case)
            if ($anjuran->isBothPartiesAgree()) {
                // Send draft perjanjian bersama email
                $this->sendDraftPerjanjianBersamaEmail($anjuran);
            } else {
                // Send final documents for rejected case
                $this->sendFinalDocumentsToParties($anjuran);
            }

            // Generate laporan hasil mediasi and buku register perselisihan
            $this->generateFinalReports($anjuran);

            return redirect()->route('dokumen.anjuran.show', $anjuran)
                ->with('success', 'Kasus telah diselesaikan. Dokumen telah dikirim ke para pihak.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Send final documents to parties
     */
    private function sendFinalDocumentsToParties($anjuran)
    {
        // Generate PDF anjuran
        $anjuranPdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('dokumen.pdf.anjuran', compact('anjuran'));
        $anjuranPdfContent = $anjuranPdf->output();

        // Ambil laporan hasil mediasi terbaru untuk dokumen HI ini
        $laporanHasilMediasi = $anjuran->dokumenHI->laporanHasilMediasi()->latest()->first();
        $laporanPdfContent = null;
        if ($laporanHasilMediasi) {
            $laporanPdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.pdf.laporan-hasil-mediasi', [
                'laporanHasilMediasi' => $laporanHasilMediasi
            ]);
            $laporanPdfContent = $laporanPdf->output();
        }

        // Kirim ke pelapor
        if ($anjuran->dokumenHI->pengaduan->pelapor->user->email) {
            \Mail::to($anjuran->dokumenHI->pengaduan->pelapor->user->email)
                ->send(new \App\Mail\FinalCaseDocumentsMail($anjuran, $laporanPdfContent, $anjuranPdfContent));
        }

        // Kirim ke terlapor
        if ($anjuran->dokumenHI->pengaduan->terlapor->email) {
            \Mail::to($anjuran->dokumenHI->pengaduan->terlapor->email)
                ->send(new \App\Mail\FinalCaseDocumentsMail($anjuran, $laporanPdfContent, $anjuranPdfContent));
        }
    }

    /**
     * Send draft perjanjian bersama email
     */
    private function sendDraftPerjanjianBersamaEmail($anjuran)
    {
        // Get perjanjian bersama
        $perjanjianBersama = $anjuran->dokumenHI->perjanjianBersama->first();

        if (!$perjanjianBersama) {
            return;
        }

        // Generate PDF perjanjian bersama
        $perjanjianPdf = Pdf::loadView('dokumen.pdf.perjanjian-bersama', compact('perjanjianBersama'));
        $perjanjianPdfContent = $perjanjianPdf->output();

        // Send to pelapor
        if ($anjuran->dokumenHI->pengaduan->pelapor->user->email) {
            Mail::to($anjuran->dokumenHI->pengaduan->pelapor->user->email)
                ->send(new DraftPerjanjianBersamaMail($perjanjianBersama, 'pelapor', $perjanjianPdfContent));
        }

        // Send to terlapor
        if ($anjuran->dokumenHI->pengaduan->terlapor->email) {
            Mail::to($anjuran->dokumenHI->pengaduan->terlapor->email)
                ->send(new DraftPerjanjianBersamaMail($perjanjianBersama, 'terlapor', $perjanjianPdfContent));
        }
    }

    /**
     * Generate final reports
     */
    private function generateFinalReports($anjuran)
    {
        $pengaduan = $anjuran->dokumenHI->pengaduan;

        // Hitung waktu penyelesaian dari jadwal mediasi pertama hingga anjuran
        $jadwalMediasiPertama = $pengaduan->jadwal()->where('jenis_jadwal', 'mediasi')->orderBy('tanggal')->first();
        $waktuPenyelesaian = '-';
        if ($jadwalMediasiPertama) {
            $tanggalSelesai = $anjuran->created_at ?? now();
            // Gunakan abs() untuk memastikan nilai positif dan bulatkan
            $selisihHari = abs($jadwalMediasiPertama->tanggal->diffInDays($tanggalSelesai));
            $waktuPenyelesaian = round($selisihHari) . ' hari';

            \Log::info('Waktu penyelesaian: ' . $jadwalMediasiPertama->tanggal->format('Y-m-d') . ' hingga ' . $tanggalSelesai->format('Y-m-d') . ' = ' . round($selisihHari) . ' hari');
        }

        // Ambil data dari risalah untuk laporan hasil mediasi
        $risalah = $anjuran->dokumenHI->risalah()->latest()->first();

        // Generate laporan hasil mediasi - data dari anjuran dan risalah
        $laporanHasilMediasi = \App\Models\LaporanHasilMediasi::create([
            'laporan_id' => (string) Str::uuid(),
            'dokumen_hi_id' => $anjuran->dokumen_hi_id,
            'tanggal_penerimaan_pengaduan' => $pengaduan->tanggal_laporan,
            'nama_pekerja' => $anjuran->nama_pekerja,
            'alamat_pekerja' => $anjuran->alamat_pekerja,
            'masa_kerja' => $pengaduan->masa_kerja ?? '-',
            'nama_perusahaan' => $anjuran->perusahaan_pengusaha,
            'alamat_perusahaan' => $anjuran->alamat_pengusaha,
            'jenis_usaha' => $risalah ? ($risalah->jenis_usaha ?: 'Tidak Diketahui') : 'Tidak Diketahui',
            'waktu_penyelesaian_mediasi' => $waktuPenyelesaian,
            'permasalahan' => $pengaduan->perihal,
            'pendapat_pekerja' => $anjuran->keterangan_pekerja ?: '-',
            'pendapat_pengusaha' => $anjuran->keterangan_pengusaha ?: '-',
            'upaya_penyelesaian' => 'Mediasi dengan anjuran yang ditolak oleh para pihak',
        ]);

        // Generate buku register perselisihan
        $bukuRegister = \App\Models\BukuRegisterPerselisihan::create([
            'buku_register_perselisihan_id' => (string) Str::uuid(),
            'dokumen_hi_id' => $anjuran->dokumen_hi_id,
            'tanggal_pencatatan' => $pengaduan->tanggal_laporan,
            'pihak_mencatat' => $anjuran->dokumenHI->pengaduan->mediator->nama_mediator,
            'pihak_pekerja' => $anjuran->nama_pekerja,
            'pihak_pengusaha' => $anjuran->perusahaan_pengusaha,
            'perselisihan_phk' => 'ya',
            'penyelesaian_bipartit' => 'ya', // karena sudah masuk ranah dinas
            'penyelesaian_klarifikasi' => 'ya', // Ada risalah klarifikasi
            'penyelesaian_mediasi' => 'ya',
            'penyelesaian_anjuran' => 'ya',
            'penyelesaian_risalah' => 'ya', // Ada risalah penyelesaian
            'tindak_lanjut_phi' => 'ya',
            'keterangan' => 'Kasus diselesaikan dengan anjuran yang ditolak oleh para pihak',
        ]);
    }
}
