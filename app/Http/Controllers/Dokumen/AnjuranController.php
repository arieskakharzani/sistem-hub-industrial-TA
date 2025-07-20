<?php

namespace App\Http\Controllers\Dokumen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Anjuran;
use App\Models\DokumenHubunganIndustrial;
use Barryvdh\DomPDF\Facade\Pdf;

class AnjuranController extends Controller
{
    public function create($dokumen_hi_id)
    {
        $dokumenHI = DokumenHubunganIndustrial::with(['risalah' => function($query) {
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
            'alamat_pekerja' => 'required|string',
            'keterangan_pekerja' => 'required|string',
            'keterangan_pengusaha' => 'required|string',
            'pertimbangan_hukum' => 'required|string',
            'isi_anjuran' => 'required|string',
        ]);
        
        $anjuran = Anjuran::create($data);
        
        return redirect()->route('dokumen.anjuran.show', ['id' => $anjuran->anjuran_id])
            ->with('success', 'Anjuran berhasil dibuat.');
    }

    public function show($id)
    {
        $anjuran = Anjuran::findOrFail($id);
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
            'alamat_pekerja' => 'required|string',
            'keterangan_pekerja' => 'required|string',
            'keterangan_pengusaha' => 'required|string',
            'pertimbangan_hukum' => 'required|string',
            'isi_anjuran' => 'required|string',
        ]);

        $anjuran->update($data);
        
        return redirect()->route('dokumen.anjuran.show', ['id' => $anjuran->anjuran_id])
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
        $anjuran = Anjuran::findOrFail($id);
        $pdf = Pdf::loadView('dokumen.pdf.anjuran', compact('anjuran'));
        return $pdf->stream('anjuran.pdf');
    }
} 