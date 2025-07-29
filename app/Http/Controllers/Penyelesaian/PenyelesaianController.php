<?php

namespace App\Http\Controllers\Penyelesaian;

use App\Http\Controllers\Controller;
use App\Models\Risalah;
use App\Models\PerjanjianBersama;
use App\Models\Anjuran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class PenyelesaianController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $jenisDokumenList = ['Semua', 'Risalah Penyelesaian', 'Perjanjian Bersama', 'Anjuran'];
        $filter = request('filter', 'Semua');

        // Query untuk semua dokumen
        $risalahList = Risalah::with(['jadwal.pengaduan'])
            ->where('jenis_risalah', '!=', 'mediasi')
            ->where('jenis_risalah', 'penyelesaian')
            ->get();

        $perjanjianList = PerjanjianBersama::with(['dokumenHI.pengaduan'])
            ->get();

        $anjuranList = Anjuran::with(['dokumenHI.pengaduan'])
            ->get();

        // Filter berdasarkan jenis dokumen
        if ($filter === 'Risalah Penyelesaian') {
            $perjanjianList = collect();
            $anjuranList = collect();
        } elseif ($filter === 'Perjanjian Bersama') {
            $risalahList = collect();
            $anjuranList = collect();
        } elseif ($filter === 'Anjuran') {
            $risalahList = collect();
            $perjanjianList = collect();
        }

        // Gabungkan semua dokumen
        $dokumenPending = $risalahList->concat($perjanjianList)->concat($anjuranList);
        $dokumenSigned = collect(); // Tidak ada lagi konsep signed/unsigned
        $dokumenSignedByUser = collect(); // Tidak ada lagi konsep signed/unsigned

        return view('penyelesaian.index', compact('dokumenPending', 'dokumenSigned', 'dokumenSignedByUser', 'jenisDokumenList', 'filter'));
    }
}
