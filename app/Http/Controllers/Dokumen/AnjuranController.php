<?php

namespace App\Http\Controllers\Dokumen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
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

class AnjuranController extends Controller
{
    public function create($dokumen_hi_id)
    {
        $dokumenHI = DokumenHubunganIndustrial::with(['pengaduan', 'risalah' => function ($query) {
            $query->where('jenis_risalah', 'penyelesaian');
        }])->findOrFail($dokumen_hi_id);

        $risalah = $dokumenHI->risalah->first();

        // Cek apakah pengaduan memenuhi syarat untuk membuat anjuran
        $pengaduan = $dokumenHI->pengaduan;
        if (!$pengaduan->canCreateAnjuran()) {
            return redirect()->back()->with('error', 'Pengaduan ini belum memenuhi syarat untuk membuat anjuran.');
        }

        // Cek apakah anjuran sudah pernah dibuat
        if ($pengaduan->hasAnjuran()) {
            $anjuran = $pengaduan->getAnjuran();
            return redirect()->route('dokumen.anjuran.show', $anjuran->anjuran_id)
                ->with('info', 'Anjuran untuk pengaduan ini sudah pernah dibuat.');
        }

        // Jika tidak ada risalah penyelesaian, buat data default untuk mixed attendance failure
        if (!$risalah) {
            $risalah = (object) [
                'risalah_id' => null,
                'jenis_risalah' => 'penyelesaian',
                'nama_perusahaan' => $pengaduan->terlapor->nama_terlapor ?? '',
                'alamat_perusahaan' => $pengaduan->terlapor->alamat_kantor_cabang ?? '',
                'nama_pekerja' => $pengaduan->pelapor->nama_pelapor ?? '',
                'alamat_pekerja' => $pengaduan->pelapor->alamat_pelapor ?? '',
                'pokok_masalah' => $pengaduan->narasi_kasus ?? '',
                'tanggal_perundingan' => now(),
                'tempat_perundingan' => 'Ruang Mediasi Dinas Tenaga Kerja',
            ];
        }

        return view('dokumen.create-anjuran', compact('dokumen_hi_id', 'risalah'));
    }

    public function store(Request $request)
    {
        try {
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

            // Generate anjuran_id jika belum ada
            if (empty($data['anjuran_id'])) {
                $data['anjuran_id'] = (string) Str::uuid();
            }

            // Generate nomor anjuran otomatis
            $year = now()->year;
            $last = Anjuran::whereYear('created_at', $year)
                ->whereNotNull('nomor_anjuran')
                ->orderByDesc('nomor_anjuran')
                ->first();
            $next = 1;
            if ($last && preg_match('/ANJ-' . $year . '-(\\d{4})$/', $last->nomor_anjuran, $matches)) {
                $next = intval($matches[1]) + 1;
            }
            $data['nomor_anjuran'] = sprintf('ANJ-%d-%04d', $year, $next);

            // Set status default
            $data['status_approval'] = 'draft';

            $anjuran = Anjuran::create($data);

            \Log::info('Anjuran created successfully', [
                'anjuran_id' => $anjuran->anjuran_id,
                'nomor_anjuran' => $anjuran->nomor_anjuran,
                'dokumen_hi_id' => $anjuran->dokumen_hi_id
            ]);

            return redirect()->route('dokumen.anjuran.show', $anjuran)
                ->with('success', 'Anjuran berhasil dibuat.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error in anjuran store', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Error creating anjuran', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan anjuran: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $anjuran = Anjuran::findOrFail($id);
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
    public function finalizeCase($id)
    {
        $anjuran = Anjuran::findOrFail($id);
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

            // Determine completion type based on response
            $completionType = $anjuran->isBothPartiesAgree() ? 'anjuran_diterima' : 'anjuran_ditolak';

            // Create buku register otomatis with proper completion type
            $this->createBukuRegisterOtomatis($pengaduan, $completionType);

            // Generate laporan hasil mediasi
            $this->generateFinalReports($anjuran);

            // Check if both parties agree (success case)
            \Log::info('Finalizing case', [
                'anjuran_id' => $anjuran->anjuran_id,
                'overall_response_status' => $anjuran->overall_response_status,
                'is_both_parties_agree' => $anjuran->isBothPartiesAgree(),
                'response_pelapor' => $anjuran->response_pelapor,
                'response_terlapor' => $anjuran->response_terlapor,
                'completion_type' => $completionType
            ]);

            if ($anjuran->isBothPartiesAgree()) {
                // Send draft perjanjian bersama email
                \Log::info('Both parties agree, sending draft perjanjian bersama email');
                $this->sendDraftPerjanjianBersamaEmail($anjuran);
            } else {
                // Send final documents for rejected case
                \Log::info('Mixed or disagreed response, sending final documents email');
                $this->sendFinalDocumentsToParties($anjuran);
            }

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

        \Log::info('Checking laporan hasil mediasi for email', [
            'anjuran_id' => $anjuran->anjuran_id,
            'dokumen_hi_id' => $anjuran->dokumen_hi_id,
            'laporan_hasil_mediasi_found' => $laporanHasilMediasi ? true : false,
            'laporan_id' => $laporanHasilMediasi ? $laporanHasilMediasi->laporan_id : null
        ]);

        if ($laporanHasilMediasi) {
            $laporanPdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.pdf.laporan-hasil-mediasi', [
                'laporanHasilMediasi' => $laporanHasilMediasi
            ]);
            $laporanPdfContent = $laporanPdf->output();
            \Log::info('Laporan hasil mediasi PDF generated successfully');
        } else {
            \Log::warning('Laporan hasil mediasi not found, will send email without laporan PDF');
        }

        // Kirim ke pelapor
        $pelaporEmail = $anjuran->dokumenHI->pengaduan->pelapor->user->email;
        \Log::info('Sending final documents to pelapor', [
            'pelapor_email' => $pelaporEmail,
            'pelapor_id' => $anjuran->dokumenHI->pengaduan->pelapor->pelapor_id,
            'anjuran_id' => $anjuran->anjuran_id
        ]);

        if ($pelaporEmail) {
            \Mail::to($pelaporEmail)
                ->send(new \App\Mail\FinalCaseDocumentsMail($anjuran, $laporanPdfContent, $anjuranPdfContent));
            \Log::info('Final documents email sent to pelapor successfully');
        } else {
            \Log::warning('Pelapor email is empty, cannot send final documents email');
        }

        // Kirim ke terlapor
        $terlaporEmail = $anjuran->dokumenHI->pengaduan->terlapor->email_terlapor;
        \Log::info('Sending final documents to terlapor', [
            'terlapor_email' => $terlaporEmail,
            'terlapor_id' => $anjuran->dokumenHI->pengaduan->terlapor->terlapor_id,
            'anjuran_id' => $anjuran->anjuran_id
        ]);

        if ($terlaporEmail) {
            \Mail::to($terlaporEmail)
                ->send(new \App\Mail\FinalCaseDocumentsMail($anjuran, $laporanPdfContent, $anjuranPdfContent));
            \Log::info('Final documents email sent to terlapor successfully');
        } else {
            \Log::warning('Terlapor email is empty, cannot send final documents email');
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
        $pelaporEmail = $anjuran->dokumenHI->pengaduan->pelapor->user->email;
        \Log::info('Sending draft perjanjian bersama to pelapor', [
            'pelapor_email' => $pelaporEmail,
            'pelapor_id' => $anjuran->dokumenHI->pengaduan->pelapor->pelapor_id,
            'anjuran_id' => $anjuran->anjuran_id
        ]);

        if ($pelaporEmail) {
            Mail::to($pelaporEmail)
                ->send(new DraftPerjanjianBersamaMail($perjanjianBersama, 'pelapor', $perjanjianPdfContent));
            \Log::info('Draft perjanjian bersama email sent to pelapor successfully');
        } else {
            \Log::warning('Pelapor email is empty, cannot send draft perjanjian bersama email');
        }

        // Send to terlapor
        $terlaporEmail = $anjuran->dokumenHI->pengaduan->terlapor->email_terlapor;
        \Log::info('Sending draft perjanjian bersama to terlapor', [
            'terlapor_email' => $terlaporEmail,
            'terlapor_id' => $anjuran->dokumenHI->pengaduan->terlapor->terlapor_id,
            'anjuran_id' => $anjuran->anjuran_id
        ]);

        if ($terlaporEmail) {
            Mail::to($terlaporEmail)
                ->send(new DraftPerjanjianBersamaMail($perjanjianBersama, 'terlapor', $perjanjianPdfContent));
            \Log::info('Draft perjanjian bersama email sent to terlapor successfully');
        } else {
            \Log::warning('Terlapor email is empty, cannot send draft perjanjian bersama email');
        }
    }

    /**
     * Generate final reports
     */
    private function generateFinalReports($anjuran)
    {
        $pengaduan = $anjuran->dokumenHI->pengaduan;

        // Cek apakah sudah ada laporan hasil mediasi untuk dokumen HI ini
        $existingLaporan = \App\Models\LaporanHasilMediasi::where('dokumen_hi_id', $anjuran->dokumen_hi_id)->first();
        if ($existingLaporan) {
            \Log::info('Laporan hasil mediasi sudah ada, skipping creation', [
                'existing_laporan_id' => $existingLaporan->laporan_id,
                'anjuran_id' => $anjuran->anjuran_id
            ]);
            return;
        }

        // Hitung waktu penyelesaian dari tanggal mediator mengambil kasus hingga anjuran
        $waktuPenyelesaian = '-';
        if ($pengaduan->assigned_at) {
            $tanggalSelesai = $anjuran->created_at ?? now();
            // Gunakan abs() untuk memastikan nilai positif dan bulatkan
            $selisihHari = abs($pengaduan->assigned_at->diffInDays($tanggalSelesai));
            $waktuPenyelesaian = round($selisihHari) . ' hari';

            \Log::info('Waktu penyelesaian: ' . $pengaduan->assigned_at->format('Y-m-d') . ' hingga ' . $tanggalSelesai->format('Y-m-d') . ' = ' . round($selisihHari) . ' hari');
        }

        // Ambil data dari risalah untuk laporan hasil mediasi
        $risalah = $anjuran->dokumenHI->risalah()->latest()->first();

        // Ambil pendapat aktual dari response anjuran
        $pendapatPekerja = $this->getPendapatPekerja($anjuran);
        $pendapatPengusaha = $this->getPendapatPengusaha($anjuran);

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
            'pendapat_pekerja' => $pendapatPekerja,
            'pendapat_pengusaha' => $pendapatPengusaha,
            'upaya_penyelesaian' => $this->getUpayaPenyelesaian($anjuran),
        ]);

        \Log::info('Laporan hasil mediasi created', [
            'laporan_id' => $laporanHasilMediasi->laporan_id,
            'dokumen_hi_id' => $laporanHasilMediasi->dokumen_hi_id,
            'anjuran_id' => $anjuran->anjuran_id,
            'pendapat_pekerja' => $pendapatPekerja,
            'pendapat_pengusaha' => $pendapatPengusaha
        ]);
    }

    /**
     * Buat buku register otomatis ketika kasus selesai
     * Method ini akan menganalisis jenis penyelesaian dan mengisi buku register sesuai dengan completionType
     */
    private function createBukuRegisterOtomatis($pengaduan, string $completionType)
    {
        try {
            // Cek apakah sudah ada buku register untuk pengaduan ini
            $existingBukuRegister = \App\Models\BukuRegisterPerselisihan::whereHas('dokumenHI', function ($query) use ($pengaduan) {
                $query->where('pengaduan_id', $pengaduan->pengaduan_id);
            })->exists();

            if ($existingBukuRegister) {
                \Log::info('Buku register sudah ada untuk pengaduan: ' . $pengaduan->nomor_pengaduan);
                return;
            }

            $dokumenHI = $pengaduan->dokumenHI;
            if (!$dokumenHI) {
                \Log::error('Dokumen HI tidak ditemukan untuk pengaduan: ' . $pengaduan->nomor_pengaduan);
                return;
            }

            // Data dasar buku register
            $bukuRegisterData = [
                'dokumen_hi_id' => $dokumenHI->dokumen_hi_id,
                'tanggal_pencatatan' => $pengaduan->tanggal_laporan->format('Y-m-d'), // Ambil dari tanggal laporan pengaduan
                'pihak_mencatat' => $pengaduan->mediator->nama_mediator ?? 'Mediator',
                'keterangan' => 'Dibuat otomatis saat kasus selesai',
            ];

            // Ambil data pekerja dan pengusaha dari risalah terkait
            $risalah = $dokumenHI->risalah()->latest()->first();
            if ($risalah) {
                $bukuRegisterData['pihak_pekerja'] = $risalah->nama_pekerja ?? $pengaduan->pelapor->nama_pelapor ?? 'Pekerja';
                $bukuRegisterData['pihak_pengusaha'] = $risalah->nama_perusahaan ?? $pengaduan->terlapor->nama_terlapor ?? 'Pengusaha';
            } else {
                // Fallback jika tidak ada risalah
                $bukuRegisterData['pihak_pekerja'] = $pengaduan->pelapor->nama_pelapor ?? 'Pekerja';
                $bukuRegisterData['pihak_pengusaha'] = $pengaduan->terlapor->nama_terlapor ?? 'Pengusaha';
            }

            // Analisis jenis perselisihan berdasarkan perihal pengaduan
            $perihal = strtolower($pengaduan->perihal);
            $bukuRegisterData['perselisihan_hak'] = str_contains($perihal, 'hak') ? 'ya' : 'tidak';
            $bukuRegisterData['perselisihan_kepentingan'] = str_contains($perihal, 'kepentingan') ? 'ya' : 'tidak';
            $bukuRegisterData['perselisihan_phk'] = str_contains($perihal, 'phk') ? 'ya' : 'tidak';
            $bukuRegisterData['perselisihan_sp_sb'] = str_contains($perihal, 'serikat') ? 'ya' : 'tidak';

            // Analisis proses penyelesaian berdasarkan completionType
            switch ($completionType) {
                case 'klarifikasi_bipartit':
                    // Kasus selesai melalui klarifikasi dengan kesimpulan bipartit_lagi
                    $bukuRegisterData['penyelesaian_bipartit'] = 'ya'; // Selalu ya karena sudah masuk dinas
                    $bukuRegisterData['penyelesaian_klarifikasi'] = 'ya';
                    $bukuRegisterData['penyelesaian_mediasi'] = 'tidak';
                    $bukuRegisterData['penyelesaian_anjuran'] = 'tidak';
                    $bukuRegisterData['penyelesaian_pb'] = 'tidak';
                    $bukuRegisterData['penyelesaian_risalah'] = 'tidak';
                    $bukuRegisterData['tindak_lanjut_phi'] = 'tidak';
                    break;

                case 'mediasi_berhasil':
                    // Kasus selesai melalui mediasi berhasil dengan perjanjian bersama
                    $bukuRegisterData['penyelesaian_bipartit'] = 'ya';
                    $bukuRegisterData['penyelesaian_klarifikasi'] = 'ya';
                    $bukuRegisterData['penyelesaian_mediasi'] = 'ya';
                    $bukuRegisterData['penyelesaian_anjuran'] = 'tidak';
                    $bukuRegisterData['penyelesaian_pb'] = 'ya';
                    $bukuRegisterData['penyelesaian_risalah'] = 'ya';
                    $bukuRegisterData['tindak_lanjut_phi'] = 'tidak';
                    break;

                case 'anjuran_ditolak':
                    // Kasus selesai karena anjuran ditolak (kedua pihak tidak setuju atau mixed response)
                    $bukuRegisterData['penyelesaian_bipartit'] = 'ya';
                    $bukuRegisterData['penyelesaian_klarifikasi'] = 'ya';
                    $bukuRegisterData['penyelesaian_mediasi'] = 'ya';
                    $bukuRegisterData['penyelesaian_anjuran'] = 'ya';
                    $bukuRegisterData['penyelesaian_pb'] = 'tidak';
                    $bukuRegisterData['penyelesaian_risalah'] = 'ya';
                    $bukuRegisterData['tindak_lanjut_phi'] = 'ya'; // Ya karena anjuran ditolak
                    break;

                case 'anjuran_diterima':
                    // Kasus selesai karena anjuran diterima dan dibuat perjanjian bersama
                    $bukuRegisterData['penyelesaian_bipartit'] = 'ya';
                    $bukuRegisterData['penyelesaian_klarifikasi'] = 'ya';
                    $bukuRegisterData['penyelesaian_mediasi'] = 'ya';
                    $bukuRegisterData['penyelesaian_anjuran'] = 'ya';
                    $bukuRegisterData['penyelesaian_pb'] = 'ya';
                    $bukuRegisterData['penyelesaian_risalah'] = 'ya';
                    $bukuRegisterData['tindak_lanjut_phi'] = 'tidak';
                    break;
            }

            // Buat buku register
            \App\Models\BukuRegisterPerselisihan::create($bukuRegisterData);

            \Log::info('Buku register berhasil dibuat otomatis untuk pengaduan: ' . $pengaduan->nomor_pengaduan . ' dengan completionType: ' . $completionType);
        } catch (\Exception $e) {
            \Log::error('Error creating buku register otomatis: ' . $e->getMessage());
        }
    }

    /**
     * Get pendapat pekerja dari keterangan anjuran
     */
    private function getPendapatPekerja($anjuran)
    {
        return $anjuran->keterangan_pekerja ?: '-';
    }

    /**
     * Get pendapat pengusaha dari keterangan anjuran
     */
    private function getPendapatPengusaha($anjuran)
    {
        return $anjuran->keterangan_pengusaha ?: '-';
    }

    /**
     * Get upaya penyelesaian berdasarkan response
     */
    private function getUpayaPenyelesaian($anjuran)
    {
        if ($anjuran->isBothPartiesAgree()) {
            return 'Mediasi dengan anjuran yang disetujui oleh para pihak';
        } elseif ($anjuran->isBothPartiesDisagree()) {
            return 'Mediasi dengan anjuran yang ditolak oleh para pihak';
        } elseif ($anjuran->isMixedResponse()) {
            return 'Mediasi dengan anjuran yang ditolak oleh salah satu pihak';
        } else {
            return 'Mediasi dengan anjuran yang ditolak oleh para pihak';
        }
    }
}
