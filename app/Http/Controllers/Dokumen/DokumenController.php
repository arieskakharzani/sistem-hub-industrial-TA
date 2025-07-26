<?php

namespace App\Http\Controllers\Dokumen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Risalah;
use App\Models\PerjanjianBersama;
use App\Models\Anjuran;
use App\Models\BukuRegisterPerselisihan;
use App\Models\LaporanHasilMediasi;
use Illuminate\Pagination\LengthAwarePaginator;

class DokumenController extends Controller
{
    public function dokumenIndex(Request $request)
    {
        $risalahList = Risalah::orderBy('created_at', 'desc')->get()->map(function ($item) {
            $item->jenis_dokumen = $item->jenis_risalah === 'klarifikasi' ? 'Risalah Klarifikasi' : 'Risalah Penyelesaian';
            $item->tanggal_dokumen = $item->created_at;
            $item->pihak_pengusaha = $item->nama_perusahaan ?? '-';
            $item->pihak_pekerja = $item->nama_pekerja ?? '-';
            $item->id = $item->risalah_id;
            return $item;
        });
        $perjanjianList = PerjanjianBersama::orderBy('created_at', 'desc')->get()->map(function ($item) {
            $item->jenis_dokumen = 'Perjanjian Bersama';
            $item->tanggal_dokumen = $item->created_at;
            $item->pihak_pengusaha = $item->nama_pengusaha ?? '-';
            $item->pihak_pekerja = $item->nama_pekerja ?? '-';
            $item->id = $item->perjanjian_bersama_id;
            return $item;
        });
        $anjuranList = Anjuran::orderBy('created_at', 'desc')->get()->map(function ($item) {
            $item->jenis_dokumen = 'Anjuran';
            $item->tanggal_dokumen = $item->created_at;
            $item->pihak_pengusaha = '-';
            $item->pihak_pekerja = '-';
            $item->id = $item->anjuran_id;
            return $item;
        });
        $dokumenList = $risalahList->concat($perjanjianList)->concat($anjuranList);
        $dokumenList = $dokumenList->sortByDesc('tanggal_dokumen')->values();
        // Pagination manual untuk dokumenList (karena collection)
        $page = $request->get('dokumen_page', 1);
        $perPage = 10;
        $pagedDokumenList = new LengthAwarePaginator(
            $dokumenList->forPage($page, $perPage),
            $dokumenList->count(),
            $perPage,
            $page,
            ['pageName' => 'dokumen_page']
        );
        $jenisDokumenList = collect(['Risalah Klarifikasi', 'Risalah Penyelesaian', 'Perjanjian Bersama', 'Anjuran']);
        $filter = $request->get('jenis_dokumen');
        if ($filter && $filter !== 'Semua') {
            $dokumenList = $dokumenList->where('jenis_dokumen', $filter)->values();
        }
        $registerList = BukuRegisterPerselisihan::with('pengaduan')->orderBy('tanggal_register', 'desc')->paginate(10, ['*'], 'register_page');
        $laporanList = LaporanHasilMediasi::with('pengaduan')->orderBy('tanggal', 'desc')->paginate(10, ['*'], 'laporan_page');
        return view('dokumen.index', compact('pagedDokumenList', 'jenisDokumenList', 'filter', 'registerList', 'laporanList'));
    }
}
