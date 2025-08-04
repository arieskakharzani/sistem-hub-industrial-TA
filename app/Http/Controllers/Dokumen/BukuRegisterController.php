<?php

namespace App\Http\Controllers\Dokumen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BukuRegisterPerselisihan;
use App\Models\Pengaduan;

class BukuRegisterController extends Controller
{
    public function create()
    {
        // Ambil pengaduan yang semua dokumen HI-nya belum punya buku register
        $pengaduanList = \App\Models\Pengaduan::whereDoesntHave('dokumenHI.bukuRegister')->get();
        return view('dokumen.create-buku-register', compact('pengaduanList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pengaduan_id' => 'required|exists:pengaduans,pengaduan_id',
            'tanggal_pencatatan' => 'required|date',
            'pihak_mencatat' => 'required|string',
            'pihak_pekerja' => 'required|string',
            'pihak_pengusaha' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        // Ambil pengaduan dan dokumen HI
        $pengaduan = Pengaduan::findOrFail($data['pengaduan_id']);
        $dokumenHI = $pengaduan->dokumenHI;

        if (!$dokumenHI) {
            return redirect()->back()->with('error', 'Dokumen Hubungan Industrial tidak ditemukan');
        }

        // LOGIKA BISNIS: Analisis data pengaduan untuk mengisi buku register
        $bukuRegisterData = [
            'dokumen_hi_id' => $dokumenHI->dokumen_hi_id,
            'tanggal_pencatatan' => $data['tanggal_pencatatan'],
            'pihak_mencatat' => $data['pihak_mencatat'],
            'pihak_pekerja' => $data['pihak_pekerja'],
            'pihak_pengusaha' => $data['pihak_pengusaha'],
            'keterangan' => $data['keterangan'],
        ];

        // Analisis jenis perselisihan berdasarkan perihal pengaduan
        $perihal = strtolower($pengaduan->perihal);
        $bukuRegisterData['perselisihan_hak'] = str_contains($perihal, 'hak') ? 'ya' : 'tidak';
        $bukuRegisterData['perselisihan_kepentingan'] = str_contains($perihal, 'kepentingan') ? 'ya' : 'tidak';
        $bukuRegisterData['perselisihan_phk'] = str_contains($perihal, 'phk') ? 'ya' : 'tidak';
        $bukuRegisterData['perselisihan_sp_sb'] = str_contains($perihal, 'serikat') ? 'ya' : 'tidak';

        // Analisis proses penyelesaian berdasarkan dokumen yang ada
        $bukuRegisterData['penyelesaian_klarifikasi'] = $dokumenHI->risalah()->where('jenis_risalah', 'klarifikasi')->exists() ? 'ya' : 'tidak';
        $bukuRegisterData['penyelesaian_mediasi'] = $dokumenHI->risalah()->where('jenis_risalah', 'mediasi')->exists() ? 'ya' : 'tidak';
        $bukuRegisterData['penyelesaian_anjuran'] = $dokumenHI->anjuran()->exists() ? 'ya' : 'tidak';
        $bukuRegisterData['penyelesaian_pb'] = $dokumenHI->perjanjianBersama()->exists() ? 'ya' : 'tidak';
        $bukuRegisterData['penyelesaian_risalah'] = $dokumenHI->risalah()->where('jenis_risalah', 'penyelesaian')->exists() ? 'ya' : 'tidak';

        // LOGIKA TINDAK LANJUT PHI
        // "Ya" jika ada anjuran tapi tidak ada perjanjian bersama (anjuran ditolak)
        $hasAnjuran = $dokumenHI->anjuran()->exists();
        $hasPerjanjianBersama = $dokumenHI->perjanjianBersama()->exists();

        if ($hasAnjuran && !$hasPerjanjianBersama) {
            $bukuRegisterData['tindak_lanjut_phi'] = 'ya'; // Anjuran ditolak
        } else {
            $bukuRegisterData['tindak_lanjut_phi'] = 'tidak'; // Anjuran diterima atau tidak ada anjuran
        }

        // Buat buku register
        BukuRegisterPerselisihan::create($bukuRegisterData);

        return redirect()->route('dokumen.index')->with('success', 'Buku Register Perselisihan berhasil disimpan');
    }
}
