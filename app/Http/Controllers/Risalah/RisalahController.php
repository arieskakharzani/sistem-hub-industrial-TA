<?php

namespace App\Http\Controllers\Risalah;

use App\Models\Risalah;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\DetailKlarifikasi;
use App\Models\DetailPenyelesaian;

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
        if (!in_array($jenis_risalah, ['klarifikasi', 'penyelesaian'])) {
            abort(404);
        }
        $jadwal = Jadwal::findOrFail($jadwalId);
        $data = $request->validate([
            'jenis_risalah' => 'required|in:klarifikasi,penyelesaian',
            'nama_perusahaan' => 'required|string|max:255',
            'jenis_usaha' => 'required|string|max:255',
            'alamat_perusahaan' => 'required|string|max:255',
            'nama_pekerja' => 'required|string|max:255',
            'alamat_pekerja' => 'required|string|max:255',
            'tanggal_perundingan' => 'required|date',
            'tempat_perundingan' => 'required|string|max:255',
            'pokok_masalah' => 'nullable|string',
            'pendapat_pekerja' => 'nullable|string',
            'pendapat_pengusaha' => 'nullable|string',
            // detail fields
            'arahan_mediator' => 'nullable|string',
            'kesimpulan_klarifikasi' => 'nullable|in:bipartit_lagi,lanjut_ke_tahap_mediasi',
            'kesimpulan_penyelesaian' => 'nullable|string',
        ]);
        $data['jadwal_id'] = $jadwal->jadwal_id;
        $data['jenis_risalah'] = $jenis_risalah;
        // Simpan risalah utama
        $risalah = Risalah::create($data);
        // Simpan detail sesuai jenis
        if ($jenis_risalah === 'klarifikasi') {
            DetailKlarifikasi::create([
                'detail_klarifikasi_id' => (string) Str::uuid(),
                'risalah_id' => $risalah->risalah_id,
                'arahan_mediator' => $data['arahan_mediator'] ?? null,
                'kesimpulan_klarifikasi' => $data['kesimpulan_klarifikasi'] ?? null,
            ]);
        } else {
            DetailPenyelesaian::create([
                'detail_penyelesaian_id' => (string) Str::uuid(),
                'risalah_id' => $risalah->risalah_id,
                'kesimpulan_penyelesaian' => $data['kesimpulan_penyelesaian'] ?? null,
            ]);
        }
        if ($jenis_risalah === 'klarifikasi') {
            $jadwal->status_jadwal = 'selesai';
            $jadwal->save();
        }
        return redirect()->route('risalah.show', $risalah)->with('success', 'Risalah berhasil dibuat');
    }

    // Tampilkan detail risalah
    public function show(Risalah $risalah)
    {
        $detail = null;
        if ($risalah->jenis_risalah === 'klarifikasi') {
            $detail = $risalah->detailKlarifikasi;
        } else {
            $detail = $risalah->detailPenyelesaian;
        }
        return view('risalah.show', compact('risalah', 'detail'));
    }

    public function edit(Risalah $risalah)
    {
        $jadwal = $risalah->jadwal;
        $jenis_risalah = $risalah->jenis_risalah;
        $detail = null;
        if ($jenis_risalah === 'klarifikasi') {
            $detail = $risalah->detailKlarifikasi;
        } else {
            $detail = $risalah->detailPenyelesaian;
        }
        return view('risalah.edit', compact('risalah', 'jadwal', 'jenis_risalah', 'detail'));
    }

    public function update(Request $request, Risalah $risalah)
    {
        if (!in_array($risalah->jenis_risalah, ['klarifikasi', 'penyelesaian'])) {
            abort(404);
        }
        $data = $request->validate([
            'jenis_risalah' => 'required|in:klarifikasi,penyelesaian',
            'nama_perusahaan' => 'required|string|max:255',
            'jenis_usaha' => 'required|string|max:255',
            'alamat_perusahaan' => 'required|string|max:255',
            'nama_pekerja' => 'required|string|max:255',
            'alamat_pekerja' => 'required|string|max:255',
            'tanggal_perundingan' => 'required|date',
            'tempat_perundingan' => 'required|string|max:255',
            'pokok_masalah' => 'nullable|string',
            'pendapat_pekerja' => 'nullable|string',
            'pendapat_pengusaha' => 'nullable|string',
            'arahan_mediator' => 'nullable|string',
            'kesimpulan_klarifikasi' => 'nullable|in:bipartit_lagi,lanjut_ke_tahap_mediasi',
            'kesimpulan_penyelesaian' => 'nullable|string',
        ]);
        $risalah->update($data);
        // Update detail
        if ($risalah->jenis_risalah === 'klarifikasi') {
            $detail = $risalah->detailKlarifikasi;
            if ($detail) {
                $detail->update([
                    'arahan_mediator' => $data['arahan_mediator'] ?? null,
                    'kesimpulan_klarifikasi' => $data['kesimpulan_klarifikasi'] ?? null,
                ]);
            } else {
                DetailKlarifikasi::create([
                    'detail_klarifikasi_id' => (string) Str::uuid(),
                    'risalah_id' => $risalah->risalah_id,
                    'arahan_mediator' => $data['arahan_mediator'] ?? null,
                    'kesimpulan_klarifikasi' => $data['kesimpulan_klarifikasi'] ?? null,
                ]);
            }
        } else {
            $detail = $risalah->detailPenyelesaian;
            if ($detail) {
                $detail->update([
                    'kesimpulan_penyelesaian' => $data['kesimpulan_penyelesaian'] ?? null,
                ]);
            } else {
                DetailPenyelesaian::create([
                    'detail_penyelesaian_id' => (string) Str::uuid(),
                    'risalah_id' => $risalah->risalah_id,
                    'kesimpulan_penyelesaian' => $data['kesimpulan_penyelesaian'] ?? null,
                ]);
            }
        }
        return redirect()->route('risalah.show', $risalah)->with('success', 'Risalah berhasil diperbarui');
    }

    public function exportPDF(Risalah $risalah)
    {
        $detail = null;
        if ($risalah->jenis_risalah === 'klarifikasi') {
            $detail = $risalah->detailKlarifikasi;
        } else {
            $detail = $risalah->detailPenyelesaian;
        }
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('risalah.pdf', compact('risalah', 'detail'));
        return $pdf->stream('Risalah-'.$risalah->jenis_risalah.'-'.$risalah->risalah_id.'.pdf');
    }
}
