<?php

namespace App\Http\Controllers\Risalah;

use App\Models\Risalah;
use App\Models\Jadwal;
use App\Models\Pengaduan;
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

        // Load relasi yang diperlukan
        $jadwal->load(['pengaduan.pelapor', 'pengaduan.terlapor']);

        // Data default untuk form
        $defaultData = [
            'nama_perusahaan' => $jadwal->pengaduan->terlapor->nama_terlapor ?? '',
            'jenis_usaha' => '', // Kosong agar mediator harus mengisi
            'alamat_perusahaan' => $jadwal->pengaduan->terlapor->alamat_kantor_cabang ?? '',
            'nama_pekerja' => $jadwal->pengaduan->pelapor->nama_pelapor ?? '',
            'alamat_pekerja' => $jadwal->pengaduan->pelapor->alamat_pelapor ?? '',
            'pokok_masalah' => $jadwal->pengaduan->narasi_kasus ?? '',
        ];

        return view('risalah.create', compact('jadwal', 'jenis_risalah', 'defaultData'));
    }

    /**
     * Handle hasil klarifikasi dan update status pengaduan
     */
    private function handleKlarifikasiResult(Jadwal $jadwal, $kesimpulan_klarifikasi)
    {
        $pengaduan = $jadwal->pengaduan;

        if ($kesimpulan_klarifikasi === 'bipartit_lagi') {
            // Update status pengaduan menjadi selesai
            $pengaduan->status = 'selesai';
            $pengaduan->save();

            // Update status jadwal menjadi selesai
            $jadwal->status_jadwal = 'selesai';
            $jadwal->save();

            // Kirim email dan notifikasi risalah klarifikasi ke pelapor dan terlapor
            $this->sendKlarifikasiNotifications($jadwal, $pengaduan);

            return redirect()->route('pengaduan.show', $pengaduan)
                ->with('success', 'Kasus selesai dan akan dilanjutkan dengan perundingan Bipartit. Risalah klarifikasi telah dikirim ke para pihak.');
        } else {
            // Jika hasil klarifikasi adalah mediasi, status pengaduan tetap proses
            $pengaduan->status = 'proses'; // Tetap proses karena akan lanjut ke mediasi
            $pengaduan->save();
            // Set jadwal klarifikasi sebagai selesai
            $jadwal->status_jadwal = 'selesai';
            $jadwal->save();
            return redirect()->route('dokumen.index')
                ->with('success', 'Klarifikasi selesai. Silahkan buat jadwal Mediasi untuk melanjutkan proses.');
        }
    }

    /**
     * Kirim notifikasi risalah klarifikasi ke pelapor dan terlapor
     */
    private function sendKlarifikasiNotifications(Jadwal $jadwal, $pengaduan)
    {
        $risalah = $jadwal->risalah()->latest()->first();
        if (!$risalah) {
            return;
        }

        // Kirim ke pelapor
        if ($pengaduan->pelapor && $pengaduan->pelapor->user) {
            $pengaduan->pelapor->user->notify(new \App\Notifications\RisalahKlarifikasiNotification($risalah, $pengaduan));
        }

        // Kirim ke terlapor
        if ($pengaduan->terlapor && $pengaduan->terlapor->user) {
            $pengaduan->terlapor->user->notify(new \App\Notifications\RisalahKlarifikasiNotification($risalah, $pengaduan));
        }

        // TRIGGER OTOMATIS: Jika kesimpulan klarifikasi adalah bipartit_lagi, selesaikan kasus
        $detailKlarifikasi = $risalah->detailKlarifikasi;
        if ($detailKlarifikasi && $detailKlarifikasi->kesimpulan_klarifikasi === 'bipartit_lagi') {
            // Update status pengaduan menjadi selesai
            $pengaduan->update(['status' => 'selesai']);

            // Buat buku register otomatis
            $this->createBukuRegisterOtomatis($pengaduan, 'klarifikasi_bipartit');

            // Kirim email kasus selesai + risalah klarifikasi
            $this->kirimEmailKasusSelesaiKlarifikasi($pengaduan, $risalah);

            \Log::info('Kasus selesai otomatis melalui klarifikasi bipartit_lagi untuk pengaduan: ' . $pengaduan->nomor_pengaduan);
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

        // Set dokumen_hi_id for semua jenis risalah (klarifikasi, mediasi, penyelesaian)
        $dokumenHI = $jadwal->pengaduan->dokumenHI()->first();
        if (!$dokumenHI) {
            $dokumenHI = new \App\Models\DokumenHubunganIndustrial();
            $dokumenHI->dokumen_hi_id = \Illuminate\Support\Str::uuid();
            $dokumenHI->pengaduan_id = $jadwal->pengaduan->pengaduan_id;
            $dokumenHI->save();
        }
        $data['dokumen_hi_id'] = $dokumenHI->dokumen_hi_id;

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

            // Update status sidang mediasi menjadi 'selesai' ketika risalah penyelesaian dibuat
            $this->updateMediasiStatusToSelesai($jadwal);
        }

        return redirect()->route('dokumen.index')->with('success', 'Risalah berhasil dibuat');
    }

    // Tampilkan detail risalah
    public function show($id)
    {
        $risalah = Risalah::findOrFail($id);
        // Load relasi yang diperlukan
        $risalah->load(['jadwal.pengaduan.dokumenHI']);

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
            // DokumenHI sudah pasti ada karena risalah penyelesaian sudah dibuat
            $dokumen_hi_id = $risalah->dokumen_hi_id;

            // Check for existing PB or Anjuran
            if ($dokumen_hi_id) {
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

    public function edit($id)
    {
        $risalah = Risalah::findOrFail($id);
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

    public function update(Request $request, $id)
    {
        $risalah = Risalah::findOrFail($id);
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

    public function exportPDF($id)
    {
        $risalah = Risalah::findOrFail($id);
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

    /**
     * Buat buku register otomatis ketika kasus selesai
     * Method ini akan menganalisis jenis penyelesaian dan mengisi buku register sesuai dengan completionType
     */
    private function createBukuRegisterOtomatis(Pengaduan $pengaduan, string $completionType)
    {
        try {
            // Cek apakah sudah ada buku register untuk pengaduan ini
            $existingBukuRegister = \App\Models\BukuRegisterPerselisihan::whereHas('dokumenHI', function ($query) use ($pengaduan) {
                $query->where('pengaduan_id', $pengaduan->pengaduan_id);
            })->exists();

            if ($existingBukuRegister) {
                \Log::info('Buku register sudah ada untuk pengaduan: ' . $pengaduan->nomor_pengaduan);
                return;
            }

            $dokumenHI = $pengaduan->dokumenHI;
            if (!$dokumenHI) {
                \Log::error('Dokumen HI tidak ditemukan untuk pengaduan: ' . $pengaduan->nomor_pengaduan);
                return;
            }

            // Data dasar buku register
            $bukuRegisterData = [
                'dokumen_hi_id' => $dokumenHI->dokumen_hi_id,
                'tanggal_pencatatan' => $pengaduan->tanggal_laporan->format('Y-m-d'), // Ambil dari tanggal laporan pengaduan
                'pihak_mencatat' => $pengaduan->mediator->nama_mediator ?? 'Mediator',
                'keterangan' => 'Dibuat otomatis saat kasus selesai',
            ];

            // Ambil data pekerja dan pengusaha dari risalah terkait
            $risalah = $dokumenHI->risalah()->latest()->first();
            if ($risalah) {
                $bukuRegisterData['pihak_pekerja'] = $risalah->nama_pekerja ?? $pengaduan->pelapor->nama_pelapor ?? 'Pekerja';
                $bukuRegisterData['pihak_pengusaha'] = $risalah->nama_perusahaan ?? $pengaduan->terlapor->nama_terlapor ?? 'Pengusaha';
            } else {
                // Fallback jika tidak ada risalah
                $bukuRegisterData['pihak_pekerja'] = $pengaduan->pelapor->nama_pelapor ?? 'Pekerja';
                $bukuRegisterData['pihak_pengusaha'] = $pengaduan->terlapor->nama_terlapor ?? 'Pengusaha';
            }

            // Analisis jenis perselisihan berdasarkan perihal pengaduan
            $perihal = strtolower($pengaduan->perihal);
            $bukuRegisterData['perselisihan_hak'] = str_contains($perihal, 'hak') ? 'ya' : 'tidak';
            $bukuRegisterData['perselisihan_kepentingan'] = str_contains($perihal, 'kepentingan') ? 'ya' : 'tidak';
            $bukuRegisterData['perselisihan_phk'] = str_contains($perihal, 'phk') ? 'ya' : 'tidak';
            $bukuRegisterData['perselisihan_sp_sb'] = str_contains($perihal, 'serikat') ? 'ya' : 'tidak';

            // Analisis proses penyelesaian berdasarkan completionType
            switch ($completionType) {
                case 'klarifikasi_bipartit':
                    // Kasus selesai melalui klarifikasi dengan kesimpulan bipartit_lagi
                    $bukuRegisterData['penyelesaian_bipartit'] = 'ya'; // Selalu ya karena sudah masuk dinas
                    $bukuRegisterData['penyelesaian_klarifikasi'] = 'ya';
                    $bukuRegisterData['penyelesaian_mediasi'] = 'tidak';
                    $bukuRegisterData['penyelesaian_anjuran'] = 'tidak';
                    $bukuRegisterData['penyelesaian_pb'] = 'tidak';
                    $bukuRegisterData['penyelesaian_risalah'] = 'ya'; // Ya karena ada risalah klarifikasi
                    $bukuRegisterData['tindak_lanjut_phi'] = 'tidak';
                    break;
            }

            // Buat buku register
            \App\Models\BukuRegisterPerselisihan::create($bukuRegisterData);

            \Log::info('Buku register berhasil dibuat otomatis untuk pengaduan: ' . $pengaduan->nomor_pengaduan . ' dengan completionType: ' . $completionType);
        } catch (\Exception $e) {
            \Log::error('Error creating buku register otomatis: ' . $e->getMessage());
        }
    }

    /**
     * Update status sidang mediasi menjadi 'selesai' ketika risalah penyelesaian dibuat
     */
    private function updateMediasiStatusToSelesai(Jadwal $jadwal)
    {
        // Cari semua risalah mediasi yang terkait dengan pengaduan ini
        $risalahMediasi = Risalah::whereHas('jadwal', function ($query) use ($jadwal) {
            $query->where('pengaduan_id', $jadwal->pengaduan_id)
                ->where('jenis_jadwal', 'mediasi');
        })->where('jenis_risalah', 'mediasi')->get();

        // Update status sidang menjadi 'selesai' untuk semua risalah mediasi
        foreach ($risalahMediasi as $risalah) {
            if ($risalah->detailMediasi) {
                $risalah->detailMediasi->update(['status_sidang' => 'selesai']);
            }
        }
    }

    /**
     * Kirim email kasus selesai dengan risalah klarifikasi
     */
    private function kirimEmailKasusSelesaiKlarifikasi(Pengaduan $pengaduan, Risalah $risalah)
    {
        try {
            // Kirim ke pelapor
            if ($pengaduan->pelapor && $pengaduan->pelapor->user) {
                \Illuminate\Support\Facades\Mail::to($pengaduan->pelapor->user->email)
                    ->send(new \App\Mail\KasusSelesaiKlarifikasiMail($pengaduan, $risalah));
            }

            // Kirim ke terlapor
            if ($pengaduan->terlapor) {
                \Illuminate\Support\Facades\Mail::to($pengaduan->terlapor->email_terlapor)
                    ->send(new \App\Mail\KasusSelesaiKlarifikasiMail($pengaduan, $risalah));
            }

            \Log::info('Email kasus selesai klarifikasi berhasil dikirim untuk pengaduan: ' . $pengaduan->nomor_pengaduan);
        } catch (\Exception $e) {
            \Log::error('Error mengirim email kasus selesai klarifikasi: ' . $e->getMessage());
        }
    }
}
