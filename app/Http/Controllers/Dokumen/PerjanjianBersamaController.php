<?php

namespace App\Http\Controllers\Dokumen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PerjanjianBersama;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DokumenHubunganIndustrial;

class PerjanjianBersamaController extends Controller
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

        return view('dokumen.create-perjanjian-bersama', compact('dokumen_hi_id', 'risalah'));
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

        // Kirim notifikasi ke terlapor untuk tanda tangan
        $pengaduan = $perjanjian->dokumenHI->pengaduan;
        $terlaporUser = $pengaduan && $pengaduan->terlapor ? $pengaduan->terlapor->user : null;
        if ($terlaporUser) {
            $terlaporUser->notify(new \App\Notifications\PerjanjianBersamaNeedsTerlaporSignatureNotification($perjanjian));
        }
        // Redirect ke halaman detail perjanjian bersama
        return redirect()->route('dokumen.perjanjian-bersama.show', ['id' => $perjanjian->perjanjian_bersama_id])
            ->with('success', 'Perjanjian Bersama berhasil dibuat.');
    }

    public function show($id)
    {
        $perjanjian = PerjanjianBersama::findOrFail($id);
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
}
