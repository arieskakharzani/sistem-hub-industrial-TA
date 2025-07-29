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
use App\Models\DetailMediasi;

class RisalahController extends Controller
{
    // Tampilkan form buat risalah (klarifikasi/mediasi/penyelesaian)
    public function create($jadwalId, $jenis_risalah)
    {
        $jadwal = Jadwal::findOrFail($jadwalId);
        return view('risalah.create', compact('jadwal', 'jenis_risalah'));
    }

    /**
     * Handle hasil klarifikasi dan update status pengaduan
     */
    private function handleKlarifikasiResult(Jadwal $jadwal, $kesimpulan_klarifikasi)
    {
        $pengaduan = $jadwal->pengaduan;

        if ($kesimpulan_klarifikasi === 'bipartit_lagi') {
            // DULU: Status langsung selesai
            // $pengaduan->status = 'selesai';
            // $pengaduan->save();
            // $jadwal->status_jadwal = 'selesai';
            // $jadwal->save();
            // return redirect()->route('pengaduan.show', $pengaduan)
            //     ->with('success', 'Kasus selesai dan akan dilanjutkan dengan perundingan Bipartit');

            // SEKARANG: Status tetap 'proses', update catatan jika perlu
            // Status akan diubah ke 'selesai' setelah dokumen ditandatangani dan didistribusikan
            // Bisa tambahkan catatan internal jika ingin menandai kasus bipartit_lagi
            // Contoh:
            // $pengaduan->catatan_mediator = 'Kesimpulan klarifikasi: bipartit_lagi';
            // $pengaduan->save();
        } else {
            // Jika hasil klarifikasi adalah mediasi, status pengaduan tetap proses
            $pengaduan->status = 'proses'; // Tetap proses karena akan lanjut ke mediasi
            $pengaduan->save();
            // Set jadwal klarifikasi sebagai selesai
            $jadwal->status_jadwal = 'selesai';
            $jadwal->save();
            return redirect()->route('risalah.show', ['pengaduan' => $pengaduan->pengaduan_id, 'jenis' => 'mediasi'])
                ->with('success', 'Silahkan buat jadwal Mediasi');
        }
        // Untuk bipartit_lagi, tetap redirect ke detail risalah/halaman pengaduan tanpa mengubah status
        $latestRisalah = $jadwal->risalah()->latest()->first();
        if ($latestRisalah) {
            return redirect()->route('risalah.show', $latestRisalah->risalah_id)
                ->with('success', 'Kesimpulan klarifikasi: Bipartit Lagi. Status akan selesai setelah dokumen ditandatangani dan didistribusikan.');
        } else {
            return redirect()->route('jadwal.show', $jadwal)
                ->with('error', 'Tidak ada risalah yang ditemukan');
        }
    }

    // Modifikasi method store untuk menggunakan handleKlarifikasiResult
    public function store(Request $request, $jadwalId, $jenis_risalah)
    {
        \Log::info('RISALAH STORE: request data', $request->all());
        if (!in_array($jenis_risalah, ['klarifikasi', 'mediasi', 'penyelesaian'])) {
            abort(404);
        }
        $jadwal = Jadwal::findOrFail($jadwalId);
        $data = $request->validate([
            'jenis_risalah' => 'required|in:klarifikasi,mediasi,penyelesaian',
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
            // detail fields untuk klarifikasi
            'arahan_mediator' => 'nullable|string',
            'kesimpulan_klarifikasi' => 'nullable|in:bipartit_lagi,lanjut_ke_tahap_mediasi',
            // detail fields untuk penyelesaian
            'kesimpulan_penyelesaian' => 'nullable|string',
            // detail fields untuk mediasi
            'ringkasan_pembahasan' => 'nullable|string',
            'kesepakatan_sementara' => 'nullable|string',
            'ketidaksepakatan_sementara' => 'nullable|string',
            'catatan_khusus' => 'nullable|string',
            'rekomendasi_mediator' => 'nullable|string',
            'status_sidang' => 'nullable|in:selesai,lanjut_sidang_berikutnya',
            'sidang_ke' => 'nullable|integer|min:1|max:3',
        ]);
        \Log::info('RISALAH STORE: validated data', $data);
        $data['jadwal_id'] = $jadwal->jadwal_id;
        $data['jenis_risalah'] = $jenis_risalah;

        // Set dokumen_hi_id for penyelesaian risalah
        if ($jenis_risalah === 'penyelesaian') {
            $dokumenHI = $jadwal->pengaduan->dokumenHI()->first();
            if (!$dokumenHI) {
                $dokumenHI = new \App\Models\DokumenHubunganIndustrial();
                $dokumenHI->dokumen_hi_id = \Illuminate\Support\Str::uuid();
                $dokumenHI->pengaduan_id = $jadwal->pengaduan->pengaduan_id;
                $dokumenHI->save();
            }
            $data['dokumen_hi_id'] = $dokumenHI->dokumen_hi_id;
        }

        // Simpan risalah utama
        $risalah = Risalah::create($data);
        \Log::info('RISALAH STORE: risalah created', $risalah->toArray());

        // Update status jadwal menjadi 'selesai' setiap kali risalah disimpan
        $jadwal->update(['status_jadwal' => 'selesai']);

        // Simpan detail sesuai jenis
        if ($jenis_risalah === 'klarifikasi') {
            $detailKlarifikasi = DetailKlarifikasi::create([
                'detail_klarifikasi_id' => (string) Str::uuid(),
                'risalah_id' => $risalah->risalah_id,
                'arahan_mediator' => $data['arahan_mediator'] ?? null,
                'kesimpulan_klarifikasi' => $data['kesimpulan_klarifikasi'] ?? null,
            ]);
            \Log::info('RISALAH STORE: detail klarifikasi created', $detailKlarifikasi->toArray());

            // Handle hasil klarifikasi
            if (isset($data['kesimpulan_klarifikasi'])) {
                $redirectResponse = $this->handleKlarifikasiResult($jadwal, $data['kesimpulan_klarifikasi']);
                if ($redirectResponse) {
                    return $redirectResponse;
                }
            }
        } elseif ($jenis_risalah === 'mediasi') {
            $detailMediasi = DetailMediasi::create([
                'detail_mediasi_id' => (string) Str::uuid(),
                'risalah_id' => $risalah->risalah_id,
                'ringkasan_pembahasan' => $data['ringkasan_pembahasan'] ?? null,
                'kesepakatan_sementara' => $data['kesepakatan_sementara'] ?? null,
                'ketidaksepakatan_sementara' => $data['ketidaksepakatan_sementara'] ?? null,
                'catatan_khusus' => $data['catatan_khusus'] ?? null,
                'rekomendasi_mediator' => $data['rekomendasi_mediator'] ?? null,
                'status_sidang' => $data['status_sidang'] ?? 'lanjut_sidang_berikutnya',
                'sidang_ke' => $data['sidang_ke'] ?? 1,
            ]);
            \Log::info('RISALAH STORE: detail mediasi created', $detailMediasi->toArray());
        } else {
            $detailPenyelesaian = DetailPenyelesaian::create([
                'detail_penyelesaian_id' => (string) Str::uuid(),
                'risalah_id' => $risalah->risalah_id,
                'kesimpulan_penyelesaian' => $data['kesimpulan_penyelesaian'] ?? null,
            ]);
            \Log::info('RISALAH STORE: detail penyelesaian created', $detailPenyelesaian->toArray());
        }

        return redirect()->route('dokumen.index')->with('success', 'Risalah berhasil dibuat');
    }

    // Tampilkan detail risalah
    public function show(Risalah $risalah)
    {
        $detail = null;
        if ($risalah->jenis_risalah === 'klarifikasi') {
            $detail = $risalah->detailKlarifikasi;
        } elseif ($risalah->jenis_risalah === 'mediasi') {
            $detail = $risalah->detailMediasi;
        } else {
            $detail = $risalah->detailPenyelesaian;
        }

        $dokumen_hi_id = null;
        $perjanjianBersama = null;
        $anjuran = null;
        if ($risalah->jenis_risalah === 'penyelesaian') {
            // Get or create dokumen_hi for this pengaduan
            $jadwal = $risalah->jadwal;
            if ($jadwal && $jadwal->pengaduan) {
                $dokumenHI = $jadwal->pengaduan->dokumenHI()->first();
                if (!$dokumenHI) {
                    // Create new DokumenHI if not exists
                    $dokumenHI = new \App\Models\DokumenHubunganIndustrial();
                    $dokumenHI->dokumen_hi_id = \Illuminate\Support\Str::uuid();
                    $dokumenHI->pengaduan_id = $jadwal->pengaduan->pengaduan_id;
                    $dokumenHI->save();
                }
                $dokumen_hi_id = $dokumenHI->dokumen_hi_id;

                // Check for existing PB or Anjuran
                $perjanjianBersama = \App\Models\PerjanjianBersama::where('dokumen_hi_id', $dokumen_hi_id)->first();
                $anjuran = \App\Models\Anjuran::where('dokumen_hi_id', $dokumen_hi_id)->first();
            }
        }

        return view('risalah.show', [
            'risalah' => $risalah,
            'detail' => $detail,
            'dokumen_hi_id' => $dokumen_hi_id,
            'perjanjianBersama' => $perjanjianBersama,
            'anjuran' => $anjuran
        ]);
    }

    public function edit(Risalah $risalah)
    {
        $jadwal = $risalah->jadwal;
        $jenis_risalah = $risalah->jenis_risalah;
        $detail = null;
        if ($jenis_risalah === 'klarifikasi') {
            $detail = $risalah->detailKlarifikasi;
        } elseif ($jenis_risalah === 'mediasi') {
            $detail = $risalah->detailMediasi;
        } else {
            $detail = $risalah->detailPenyelesaian;
        }
        return view('risalah.edit', compact('risalah', 'jadwal', 'jenis_risalah', 'detail'));
    }

    public function update(Request $request, Risalah $risalah)
    {
        if (!in_array($risalah->jenis_risalah, ['klarifikasi', 'mediasi', 'penyelesaian'])) {
            abort(404);
        }
        $data = $request->validate([
            'jenis_risalah' => 'required|in:klarifikasi,mediasi,penyelesaian',
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
            // detail fields untuk klarifikasi
            'arahan_mediator' => 'nullable|string',
            'kesimpulan_klarifikasi' => 'nullable|in:bipartit_lagi,lanjut_ke_tahap_mediasi',
            // detail fields untuk penyelesaian
            'kesimpulan_penyelesaian' => 'nullable|string',
            // detail fields untuk mediasi
            'ringkasan_pembahasan' => 'nullable|string',
            'kesepakatan_sementara' => 'nullable|string',
            'ketidaksepakatan_sementara' => 'nullable|string',
            'catatan_khusus' => 'nullable|string',
            'rekomendasi_mediator' => 'nullable|string',
            'status_sidang' => 'nullable|in:selesai,lanjut_sidang_berikutnya',
            'sidang_ke' => 'nullable|integer|min:1|max:3',
        ]);
        $risalah->update($data);

        // Update status jadwal menjadi 'selesai' setiap kali risalah disimpan
        $jadwal = $risalah->jadwal;
        if ($jadwal) {
            $jadwal->update(['status_jadwal' => 'selesai']);
        }

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
        } elseif ($risalah->jenis_risalah === 'mediasi') {
            $detail = $risalah->detailMediasi;
            if ($detail) {
                $detail->update([
                    'ringkasan_pembahasan' => $data['ringkasan_pembahasan'] ?? null,
                    'kesepakatan_sementara' => $data['kesepakatan_sementara'] ?? null,
                    'ketidaksepakatan_sementara' => $data['ketidaksepakatan_sementara'] ?? null,
                    'catatan_khusus' => $data['catatan_khusus'] ?? null,
                    'rekomendasi_mediator' => $data['rekomendasi_mediator'] ?? null,
                    'status_sidang' => $data['status_sidang'] ?? 'lanjut_sidang_berikutnya',
                    'sidang_ke' => $data['sidang_ke'] ?? 1,
                ]);
            } else {
                DetailMediasi::create([
                    'detail_mediasi_id' => (string) Str::uuid(),
                    'risalah_id' => $risalah->risalah_id,
                    'ringkasan_pembahasan' => $data['ringkasan_pembahasan'] ?? null,
                    'kesepakatan_sementara' => $data['kesepakatan_sementara'] ?? null,
                    'ketidaksepakatan_sementara' => $data['ketidaksepakatan_sementara'] ?? null,
                    'catatan_khusus' => $data['catatan_khusus'] ?? null,
                    'rekomendasi_mediator' => $data['rekomendasi_mediator'] ?? null,
                    'status_sidang' => $data['status_sidang'] ?? 'lanjut_sidang_berikutnya',
                    'sidang_ke' => $data['sidang_ke'] ?? 1,
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
        return redirect()->route('dokumen.index')->with('success', 'Risalah berhasil diperbarui');
    }

    public function exportPDF(Risalah $risalah)
    {
        $detail = null;
        if ($risalah->jenis_risalah === 'klarifikasi') {
            $detail = $risalah->detailKlarifikasi;
        } elseif ($risalah->jenis_risalah === 'mediasi') {
            $detail = $risalah->detailMediasi;
        } else {
            $detail = $risalah->detailPenyelesaian;
        }
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('risalah.pdf', compact('risalah', 'detail'));
        return $pdf->stream('Risalah-' . $risalah->jenis_risalah . '-' . $risalah->risalah_id . '.pdf');
    }

    public function destroy($id)
    {
        $risalah = Risalah::findOrFail($id);
        $dokumenHiId = $risalah->dokumen_hi_id;

        // Delete the risalah
        $risalah->delete();

        return redirect()->route('dokumen.index')
            ->with('success', 'Risalah berhasil dihapus.');
    }
}
