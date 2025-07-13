<?php

namespace App\Http\Controllers\Risalah;

use App\Models\Risalah;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;

class RisalahController extends Controller
{
    // Tampilkan form buat risalah (klarifikasi/penyelesaian)
    public function create($jadwalId, $jenis_risalah)
    {
        $jadwal = Jadwal::findOrFail($jadwalId);
        return view('risalah.create', compact('jadwal', 'jenis_risalah'));
    }

    // Simpan risalah
    public function store(Request $request, $jadwalId, $jenis_risalah)
    {
        $jadwal = Jadwal::findOrFail($jadwalId);
        $data = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'jenis_usaha' => 'required|string|max:255',
            'alamat_perusahaan' => 'required|string|max:255',
            'nama_pekerja' => 'required|string|max:255',
            'alamat_pekerja' => 'required|string|max:255',
            'tanggal_perundingan' => 'required|date',
            'tempat_perundingan' => 'required|string|max:255',
            // Field khusus klarifikasi
            'pokok_masalah' => 'nullable|string',
            'arahan_mediator' => 'nullable|string',
            'kesimpulan_klarifikasi' => 'nullable|string',
            'pendapat_pekerja' => 'nullable|string',
            'pendapat_pengusaha' => 'nullable|string',
            // Field khusus penyelesaian
            'kesimpulan_penyelesaian' => 'nullable|string',
        ]);
        $data['jadwal_id'] = $jadwal->jadwal_id;
        $data['jenis_risalah'] = $jenis_risalah;
        $risalah = Risalah::create($data);
        // Jika risalah klarifikasi, update status jadwal menjadi selesai
        if ($jenis_risalah === 'klarifikasi') {
            $jadwal->status_jadwal = 'selesai';
            $jadwal->save();
        }
        return redirect()->route('risalah.show', $risalah)->with('success', 'Risalah berhasil dibuat');
    }

    // Tampilkan detail risalah
    public function show(Risalah $risalah)
    {
        return view('risalah.show', compact('risalah'));
    }

    public function edit(Risalah $risalah)
    {
        $jadwal = $risalah->jadwal;
        $jenis_risalah = $risalah->jenis_risalah;
        return view('risalah.edit', compact('risalah', 'jadwal', 'jenis_risalah'));
    }

    public function update(Request $request, Risalah $risalah)
    {
        $data = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'jenis_usaha' => 'required|string|max:255',
            'alamat_perusahaan' => 'required|string|max:255',
            'nama_pekerja' => 'required|string|max:255',
            'alamat_pekerja' => 'required|string|max:255',
            'tanggal_perundingan' => 'required|date',
            'tempat_perundingan' => 'required|string|max:255',
            'pokok_masalah' => 'nullable|string',
            'arahan_mediator' => 'nullable|string',
            'kesimpulan_klarifikasi' => 'nullable|string',
            'pendapat_pekerja' => 'nullable|string',
            'pendapat_pengusaha' => 'nullable|string',
            'kesimpulan_penyelesaian' => 'nullable|string',
        ]);
        $risalah->update($data);
        return redirect()->route('risalah.show', $risalah)->with('success', 'Risalah berhasil diperbarui');
    }

    public function exportPDF(Risalah $risalah)
    {
        $pdf = Pdf::loadView('risalah.pdf', compact('risalah'));
        $filename = 'Risalah-' . $risalah->jenis_risalah . '-' . $risalah->risalah_id . '.pdf';
        return $pdf->download($filename);
    }
}
