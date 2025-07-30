<?php

namespace App\Services;

use App\Models\Pengaduan;
use App\Models\LaporanHasilMediasi;
use App\Models\LaporanPengadilanHI;
use App\Models\BukuRegisterPerselisihan;
use App\Models\DokumenHubunganIndustrial;
use App\Models\Anjuran;
use App\Models\PerjanjianBersama;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LaporanService
{
    /**
     * Generate laporan otomatis ketika kasus selesai
     */
    public function generateLaporanOtomatis(Pengaduan $pengaduan)
    {
        try {
            // Load relasi yang diperlukan
            $pengaduan->load([
                'pelapor',
                'terlapor',
                'mediator',
                'dokumenHI.anjuran',
                'dokumenHI.perjanjianBersama',
                'dokumenHI.laporanHasilMediasi'
            ]);

            // Generate Laporan Hasil Mediasi
            $this->generateLaporanHasilMediasi($pengaduan);

            // Jika ada anjuran (tidak sepakat), generate laporan pengadilan HI
            if ($pengaduan->dokumenHI->first()?->anjuran) {
                $this->generateLaporanPengadilanHI($pengaduan);
            }

            // Update buku register
            $this->updateBukuRegister($pengaduan);

            Log::info("Laporan otomatis berhasil digenerate untuk pengaduan: {$pengaduan->nomor_pengaduan}");

            return true;
        } catch (\Exception $e) {
            Log::error("Error generating laporan otomatis: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate Laporan Hasil Mediasi
     */
    private function generateLaporanHasilMediasi(Pengaduan $pengaduan)
    {
        // Cek apakah sudah ada laporan hasil mediasi
        $existingLaporan = LaporanHasilMediasi::where('dokumen_hi_id', $pengaduan->dokumenHI->first()?->dokumen_hi_id)->first();

        if ($existingLaporan) {
            return $existingLaporan;
        }

        // Ambil dokumen HI
        $dokumenHI = $pengaduan->dokumenHI->first();
        if (!$dokumenHI) {
            return null;
        }

        // Generate laporan hasil mediasi
        $laporan = LaporanHasilMediasi::create([
            'dokumen_hi_id' => $dokumenHI->dokumen_hi_id,
            'tanggal_penerimaan_pengaduan' => $pengaduan->tanggal_laporan,
            'nama_pekerja' => $pengaduan->pelapor->nama_pelapor ?? '-',
            'alamat_pekerja' => $pengaduan->pelapor->alamat_pelapor ?? '-',
            'upah_terakhir' => '-', // Bisa diisi dari data pengaduan jika ada
            'masa_kerja' => $pengaduan->masa_kerja,
            'nama_perusahaan' => $pengaduan->terlapor->nama_terlapor ?? '-',
            'alamat_perusahaan' => $pengaduan->terlapor->alamat_terlapor ?? '-',
            'jenis_usaha' => '-', // Bisa diisi dari data terlapor jika ada
            'waktu_penyelesaian_mediasi' => $pengaduan->updated_at->format('Y-m-d'),
            'permasalahan' => $pengaduan->narasi_kasus,
            'pendapat_pekerja' => '-', // Bisa diisi dari risalah jika ada
            'pendapat_pengusaha' => '-', // Bisa diisi dari risalah jika ada
            'pendapat_saksi' => '-', // Bisa diisi dari risalah jika ada
            'upaya_penyelesaian' => $this->getUpayaPenyelesaian($pengaduan),
        ]);

        return $laporan;
    }

    /**
     * Generate Laporan Pengadilan HI (untuk kasus tidak sepakat)
     */
    private function generateLaporanPengadilanHI(Pengaduan $pengaduan)
    {
        // Cek apakah sudah ada laporan pengadilan HI
        $existingLaporan = LaporanPengadilanHI::where('pengaduan_id', $pengaduan->pengaduan_id)->first();

        if ($existingLaporan) {
            return $existingLaporan;
        }

        // Ambil anjuran
        $anjuran = $pengaduan->dokumenHI->first()?->anjuran;
        if (!$anjuran) {
            return null;
        }

        // Generate laporan pengadilan HI
        $laporan = LaporanPengadilanHI::create([
            'pengaduan_id' => $pengaduan->pengaduan_id,
            'tanggal_laporan' => now()->toDateString(),
            'nama_pelapor' => $pengaduan->pelapor->nama_pelapor ?? '-',
            'alamat_pelapor' => $pengaduan->pelapor->alamat_pelapor ?? '-',
            'nama_terlapor' => $pengaduan->terlapor->nama_terlapor ?? '-',
            'alamat_terlapor' => $pengaduan->terlapor->alamat_terlapor ?? '-',
            'perihal_perselisihan' => $pengaduan->perihal,
            'pokok_permasalahan' => $pengaduan->narasi_kasus,
            'upaya_penyelesaian' => $this->getUpayaPenyelesaian($pengaduan),
            'hasil_mediasi' => 'Tidak tercapai kesepakatan',
            'alasan_tidak_sepakat' => $anjuran->isi_anjuran ?? '-',
            'rekomendasi_pengadilan' => $anjuran->pertimbangan_hukum ?? '-',
            'status_laporan' => 'draft',
            'catatan_tambahan' => 'Laporan otomatis digenerate oleh sistem'
        ]);

        return $laporan;
    }

    /**
     * Update buku register perselisihan
     */
    private function updateBukuRegister(Pengaduan $pengaduan)
    {
        $dokumenHI = $pengaduan->dokumenHI->first();
        if (!$dokumenHI) {
            return;
        }

        // Cek apakah sudah ada buku register
        $existingRegister = BukuRegisterPerselisihan::where('dokumen_hi_id', $dokumenHI->dokumen_hi_id)->first();

        if ($existingRegister) {
            // Update existing register
            $existingRegister->update([
                'penyelesaian_risalah' => 'ya',
                'penyelesaian_pb' => $pengaduan->dokumenHI->first()?->perjanjianBersama ? 'ya' : 'tidak',
                'penyelesaian_anjuran' => $pengaduan->dokumenHI->first()?->anjuran ? 'ya' : 'tidak',
                'tindak_lanjut_phi' => $pengaduan->dokumenHI->first()?->anjuran ? 'ya' : 'tidak',
                'updated_at' => now()
            ]);
        } else {
            // Create new register
            BukuRegisterPerselisihan::create([
                'dokumen_hi_id' => $dokumenHI->dokumen_hi_id,
                'tanggal_pencatatan' => $pengaduan->tanggal_laporan,
                'pihak_mencatat' => $pengaduan->mediator->nama_mediator ?? '-',
                'pihak_pekerja' => $pengaduan->pelapor->nama_pelapor ?? '-',
                'pihak_pengusaha' => $pengaduan->terlapor->nama_terlapor ?? '-',
                'perselisihan_hak' => $pengaduan->perihal === 'Perselisihan Hak' ? 'ya' : 'tidak',
                'perselisihan_kepentingan' => $pengaduan->perihal === 'Perselisihan Kepentingan' ? 'ya' : 'tidak',
                'perselisihan_phk' => $pengaduan->perihal === 'Perselisihan PHK' ? 'ya' : 'tidak',
                'perselisihan_sp_sb' => $pengaduan->perihal === 'Perselisihan antar SP/SB' ? 'ya' : 'tidak',
                'penyelesaian_bipartit' => 'tidak',
                'penyelesaian_klarifikasi' => 'tidak',
                'penyelesaian_mediasi' => 'tidak',
                'penyelesaian_pb' => $pengaduan->dokumenHI->first()?->perjanjianBersama ? 'ya' : 'tidak',
                'penyelesaian_anjuran' => $pengaduan->dokumenHI->first()?->anjuran ? 'ya' : 'tidak',
                'penyelesaian_risalah' => 'ya',
                'tindak_lanjut_phi' => $pengaduan->dokumenHI->first()?->anjuran ? 'ya' : 'tidak',
                'tindak_lanjut_ma' => 'tidak',
                'keterangan' => 'Otomatis digenerate oleh sistem'
            ]);
        }
    }

    /**
     * Get upaya penyelesaian berdasarkan dokumen yang ada
     */
    private function getUpayaPenyelesaian(Pengaduan $pengaduan): string
    {
        $upaya = [];

        if ($pengaduan->dokumenHI->first()?->perjanjianBersama) {
            $upaya[] = 'Perjanjian Bersama';
        }

        if ($pengaduan->dokumenHI->first()?->anjuran) {
            $upaya[] = 'Anjuran Kepala Dinas';
        }

        if ($pengaduan->dokumenHI->first()?->risalah) {
            $upaya[] = 'Risalah Mediasi';
        }

        return !empty($upaya) ? implode(', ', $upaya) : 'Mediasi langsung';
    }

    /**
     * Send laporan ke pengadilan HI
     */
    public function sendLaporanKePengadilan(LaporanPengadilanHI $laporan)
    {
        try {
            $laporan->update([
                'status_laporan' => 'submitted',
                'tanggal_kirim' => now()
            ]);

            // Di sini bisa ditambahkan logika untuk mengirim laporan ke pengadilan
            // Misalnya: email, API call, atau upload ke sistem pengadilan

            Log::info("Laporan pengadilan HI berhasil dikirim: {$laporan->nomor_laporan}");

            return true;
        } catch (\Exception $e) {
            Log::error("Error sending laporan ke pengadilan: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get statistik laporan berdasarkan role
     */
    public function getStatistikLaporan($user)
    {
        $baseQuery = Pengaduan::where('status', 'selesai');

        // Filter berdasarkan role
        if ($user->active_role === 'pelapor') {
            $pelapor = $user->pelapor;
            $baseQuery->where('pelapor_id', $pelapor->pelapor_id);
        } elseif ($user->active_role === 'terlapor') {
            $terlapor = $user->terlapor;
            $baseQuery->where('terlapor_id', $terlapor->terlapor_id);
        } elseif ($user->active_role === 'mediator') {
            $mediator = $user->mediator;
            $baseQuery->where('mediator_id', $mediator->mediator_id);
        }

        return [
            'total_selesai' => $baseQuery->count(),
            'sepakat' => $baseQuery->whereHas('dokumenHI.perjanjianBersama')->count(),
            'tidak_sepakat' => $baseQuery->whereHas('dokumenHI.anjuran')->count(),
            'bulan_ini' => $baseQuery->whereMonth('updated_at', now()->month)->count(),
            'tahun_ini' => $baseQuery->whereYear('updated_at', now()->year)->count(),
        ];
    }
}
