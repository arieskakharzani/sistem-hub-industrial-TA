<?php

namespace App\Http\Controllers\Dokumen;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PerjanjianBersama;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\DokumenHubunganIndustrial;

class PerjanjianBersamaController extends Controller
{
    public function create($dokumen_hi_id)
    {
        $dokumenHI = DokumenHubunganIndustrial::with(['risalah' => function ($query) {
            $query->where('jenis_risalah', 'penyelesaian');
        }, 'anjuran' => function ($query) {
            $query->where('status_approval', 'published');
        }])->findOrFail($dokumen_hi_id);

        $risalah = $dokumenHI->risalah->first();
        $anjuran = $dokumenHI->anjuran->first();

        if (!$risalah && !$anjuran) {
            return redirect()->back()->with('error', 'Data risalah penyelesaian atau anjuran tidak ditemukan.');
        }

        return view('dokumen.create-perjanjian-bersama', compact('dokumen_hi_id', 'risalah', 'anjuran'));
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
            'isi_kesepakatan' => 'required|string',
        ]);

        $perjanjian = PerjanjianBersama::create($data);

        // Redirect ke halaman detail perjanjian bersama
        return redirect()->route('dokumen.perjanjian-bersama.show', ['id' => $perjanjian->perjanjian_bersama_id])
            ->with('success', 'Perjanjian Bersama berhasil dibuat.');
    }

    public function show($id)
    {
        $perjanjian = PerjanjianBersama::findOrFail($id);
        $user = Auth::user();

        // Load relasi yang diperlukan
        $perjanjian->load(['dokumenHI.pengaduan.pelapor.user', 'dokumenHI.pengaduan.terlapor']);

        // Authorization check
        if ($user->active_role === 'pelapor') {
            $pelapor = $user->pelapor;
            if (!$pelapor || $perjanjian->dokumenHI->pengaduan->pelapor_id !== $pelapor->pelapor_id) {
                abort(403, 'Anda tidak memiliki akses ke perjanjian bersama ini.');
            }
        } elseif ($user->active_role === 'terlapor') {
            $terlapor = $user->terlapor;
            if (!$terlapor || $perjanjian->dokumenHI->pengaduan->terlapor_id !== $terlapor->terlapor_id) {
                abort(403, 'Anda tidak memiliki akses ke perjanjian bersama ini.');
            }
        } elseif (!in_array($user->active_role, ['mediator', 'kepala_dinas'])) {
            abort(403, 'Anda tidak memiliki akses ke perjanjian bersama ini.');
        }

        return view('dokumen.show-perjanjian-bersama', compact('perjanjian'));
    }

    public function edit($id)
    {
        $perjanjian = PerjanjianBersama::findOrFail($id);
        return view('dokumen.edit-perjanjian-bersama', compact('perjanjian'));
    }

    public function update(Request $request, $id)
    {
        $perjanjian = PerjanjianBersama::findOrFail($id);

        $data = $request->validate([
            'nama_pengusaha' => 'required|string',
            'jabatan_pengusaha' => 'required|string',
            'perusahaan_pengusaha' => 'required|string',
            'alamat_pengusaha' => 'required|string',
            'nama_pekerja' => 'required|string',
            'jabatan_pekerja' => 'required|string',
            'perusahaan_pekerja' => 'required|string',
            'alamat_pekerja' => 'required|string',
            'isi_kesepakatan' => 'required|string',
        ]);

        $perjanjian->update($data);

        return redirect()->route('dokumen.perjanjian-bersama.show', ['id' => $perjanjian->perjanjian_bersama_id])
            ->with('success', 'Perjanjian Bersama berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $perjanjian = PerjanjianBersama::findOrFail($id);
        $dokumenHiId = $perjanjian->dokumen_hi_id;

        $perjanjian->delete();

        return redirect()->route('dokumen.index')
            ->with('success', 'Perjanjian Bersama berhasil dihapus.');
    }

    public function cetakPdf($id)
    {
        $perjanjian = PerjanjianBersama::findOrFail($id);
        $pdf = Pdf::loadView('dokumen.pdf.perjanjian-bersama', compact('perjanjian'));
        return $pdf->stream('perjanjian-bersama.pdf');
    }

    public function complete($id)
    {
        $perjanjian = PerjanjianBersama::findOrFail($id);
        $pengaduan = $perjanjian->dokumenHI->pengaduan;

        // Ubah status pengaduan menjadi 'selesai'
        $pengaduan->update(['status' => 'selesai']);

        // Generate laporan otomatis
        $this->generateFinalReports($perjanjian);

        // Kirim draft perjanjian bersama ke para pihak
        $this->kirimDraftPerjanjianBersama($pengaduan);

        return redirect()->back()->with('success', 'Kasus telah selesai, laporan telah dibuat, dan draft perjanjian bersama telah dikirim ke para pihak.');
    }

    /**
     * Kirim draft Perjanjian Bersama ke email para pihak
     */
    private function kirimDraftPerjanjianBersama($pengaduan)
    {
        try {
            \Log::info('Memulai pengiriman email draft Perjanjian Bersama untuk pengaduan: ' . $pengaduan->nomor_pengaduan);

            // Load relasi yang diperlukan
            $pengaduan->load([
                'pelapor.user',
                'terlapor',
                'mediator.user',
                'dokumenHI.perjanjianBersama'
            ]);

            // Ambil Perjanjian Bersama
            $perjanjianBersama = $pengaduan->dokumenHI->first()?->perjanjianBersama->first();

            if (!$perjanjianBersama) {
                \Log::error('Perjanjian Bersama tidak ditemukan untuk pengaduan: ' . $pengaduan->nomor_pengaduan);
                return;
            }

            \Log::info('Perjanjian Bersama ditemukan: ' . $perjanjianBersama->perjanjian_bersama_id);

            // Email ke Pelapor
            if ($pengaduan->pelapor && $pengaduan->pelapor->user) {
                $pelaporEmail = $pengaduan->pelapor->user->email;
                \Log::info('Mengirim email ke pelapor: ' . $pelaporEmail);

                \Illuminate\Support\Facades\Mail::to($pelaporEmail)
                    ->send(new \App\Mail\DraftPerjanjianBersamaMail($pengaduan, $perjanjianBersama, 'pelapor'));

                \Log::info('Email berhasil dikirim ke pelapor: ' . $pelaporEmail);
            } else {
                \Log::warning('Pelapor atau user pelapor tidak ditemukan');
            }

            // Email ke Terlapor
            if ($pengaduan->terlapor) {
                $terlaporEmail = $pengaduan->terlapor->email_terlapor;
                \Log::info('Mengirim email ke terlapor: ' . $terlaporEmail);

                \Illuminate\Support\Facades\Mail::to($terlaporEmail)
                    ->send(new \App\Mail\DraftPerjanjianBersamaMail($pengaduan, $perjanjianBersama, 'terlapor'));

                \Log::info('Email berhasil dikirim ke terlapor: ' . $terlaporEmail);
            } else {
                \Log::warning('Terlapor tidak ditemukan');
            }

            \Log::info('Draft Perjanjian Bersama berhasil dikirim untuk pengaduan: ' . $pengaduan->nomor_pengaduan);
        } catch (\Exception $e) {
            \Log::error('Error mengirim draft Perjanjian Bersama: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
        }
    }

    /**
     * Generate final reports untuk perjanjian bersama
     */
    private function generateFinalReports($perjanjian)
    {
        $pengaduan = $perjanjian->dokumenHI->pengaduan;
        $anjuran = $perjanjian->dokumenHI->anjuran->first();

        // Hitung waktu penyelesaian dari jadwal mediasi pertama hingga perjanjian bersama
        $jadwalMediasiPertama = $pengaduan->jadwal()->where('jenis_jadwal', 'mediasi')->orderBy('tanggal')->first();
        $waktuPenyelesaian = $jadwalMediasiPertama ? now()->diffInDays($jadwalMediasiPertama->tanggal) . ' hari' : '-';

        // Generate laporan hasil mediasi
        $laporanHasilMediasi = \App\Models\LaporanHasilMediasi::create([
            'laporan_id' => (string) \Illuminate\Support\Str::uuid(),
            'dokumen_hi_id' => $perjanjian->dokumen_hi_id,
            'tanggal_penerimaan_pengaduan' => $pengaduan->tanggal_laporan,
            'nama_pekerja' => $perjanjian->nama_pekerja,
            'alamat_pekerja' => $perjanjian->alamat_pekerja,
            'masa_kerja' => $pengaduan->masa_kerja ?? '-',
            'nama_perusahaan' => $perjanjian->perusahaan_pengusaha,
            'alamat_perusahaan' => $perjanjian->alamat_pengusaha,
            'jenis_usaha' => $anjuran ? $anjuran->jenis_usaha : '-',
            'waktu_penyelesaian_mediasi' => $waktuPenyelesaian,
            'permasalahan' => $pengaduan->perihal,
            'pendapat_pekerja' => $anjuran ? $anjuran->keterangan_pekerja : '-',
            'pendapat_pengusaha' => $anjuran ? $anjuran->keterangan_pengusaha : '-',
            'upaya_penyelesaian' => 'Mediasi dengan perjanjian bersama yang disetujui oleh para pihak',
        ]);

        // Generate buku register perselisihan
        $bukuRegister = \App\Models\BukuRegisterPerselisihan::create([
            'buku_register_perselisihan_id' => (string) \Illuminate\Support\Str::uuid(),
            'dokumen_hi_id' => $perjanjian->dokumen_hi_id,
            'tanggal_pencatatan' => $pengaduan->tanggal_laporan,
            'pihak_mencatat' => $pengaduan->mediator->nama_mediator,
            'pihak_pekerja' => $perjanjian->nama_pekerja,
            'pihak_pengusaha' => $perjanjian->nama_pengusaha,
            'perselisihan_phk' => 'ya',
            'penyelesaian_mediasi' => 'ya',
            'penyelesaian_pb' => 'ya',
            'keterangan' => 'Kasus diselesaikan dengan perjanjian bersama yang disetujui oleh para pihak',
        ]);
    }
}
