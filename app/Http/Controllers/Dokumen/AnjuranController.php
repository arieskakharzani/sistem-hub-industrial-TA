<?php

namespace App\Http\Controllers\Dokumen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Anjuran;
use Barryvdh\DomPDF\Facade\Pdf;

class AnjuranController extends Controller
{
    public function create($dokumen_hi_id)
    {
        return view('dokumen.create-anjuran', compact('dokumen_hi_id'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'dokumen_hi_id' => 'required|uuid',
            'keterangan_pekerja' => 'required|string',
            'keterangan_pengusaha' => 'required|string',
            'pertimbangan_hukum' => 'required|string',
            'isi_anjuran' => 'required|string',
        ]);
        $anjuran = Anjuran::create($data);
        return redirect()->route('risalah.show', ['risalah' => $request->input('risalah_id')])->with('success', 'Anjuran berhasil dibuat.');
    }

    public function show($id)
    {
        $anjuran = Anjuran::findOrFail($id);
        return view('dokumen.show-anjuran', compact('anjuran'));
    }

    public function cetakPdf($id)
    {
        $anjuran = Anjuran::findOrFail($id);
        $pdf = Pdf::loadView('dokumen.show-anjuran', compact('anjuran'));
        return $pdf->download('anjuran.pdf');
    }
} 