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
            'nomor_register' => 'required|string',
            'tanggal_register' => 'required|date',
            'catatan' => 'nullable|string',
        ]);
        BukuRegisterPerselisihan::create($data);
        return redirect()->route('dokumen.index')->with('success', 'Register berhasil disimpan');
    }
} 