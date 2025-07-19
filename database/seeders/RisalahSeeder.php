<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\Risalah;
use App\Models\DetailKlarifikasi;
use App\Models\DetailPenyelesaian;
use App\Models\DokumenHubunganIndustrial;
use App\Models\Jadwal;
use App\Models\Pengaduan;

class RisalahSeeder extends Seeder
{
    public function run()
    {
        // Kasus 1: Ecak Harzani - Upah Lembur (Sampai Penyelesaian)
        $pengaduan1 = Pengaduan::where('narasi_kasus', 'like', '%Upah lembur selama 3 bulan%')->first();
        if ($pengaduan1) {
            $dokumenHI1 = DokumenHubunganIndustrial::create([
                'dokumen_hi_id' => Str::uuid(),
                'pengaduan_id' => $pengaduan1->pengaduan_id,
            ]);

            $jadwalKlarifikasi1 = Jadwal::where('pengaduan_id', $pengaduan1->pengaduan_id)
                ->where('jenis_jadwal', 'klarifikasi')
                ->first();

            if ($jadwalKlarifikasi1) {
                $risalahKlarifikasi1 = Risalah::create([
                    'risalah_id' => Str::uuid(),
                    'jadwal_id' => $jadwalKlarifikasi1->jadwal_id,
                    'dokumen_hi_id' => $dokumenHI1->dokumen_hi_id,
                    'jenis_risalah' => 'klarifikasi',
                    'nama_perusahaan' => 'PT ABC Technology',
                    'jenis_usaha' => 'Teknologi Informasi',
                    'alamat_perusahaan' => 'Jl. Teknologi No. 123, Jakarta',
                    'nama_pekerja' => 'Ecak Harzani',
                    'alamat_pekerja' => 'Jl. Pekerja No. 45, Jakarta',
                    'tanggal_perundingan' => $jadwalKlarifikasi1->tanggal,
                    'tempat_perundingan' => $jadwalKlarifikasi1->tempat,
                    'pokok_masalah' => 'Perselisihan mengenai pembayaran upah lembur selama 3 bulan terakhir (Maret-Mei 2024) dengan total 120 jam lembur yang belum dibayarkan sesuai ketentuan.',
                    'pendapat_pekerja' => 'Upah lembur selama 3 bulan terakhir belum dibayarkan sesuai ketentuan. Total jam lembur 120 jam dengan rincian: Maret (45 jam), April (35 jam), Mei (40 jam). Perhitungan seharusnya mengikuti Kepmen 102/2004.',
                    'pendapat_pengusaha' => 'Perusahaan sedang melakukan perhitungan ulang upah lembur. Ada perbedaan pencatatan jam lembur antara sistem dengan laporan supervisor. Akan dilakukan verifikasi data.',
                ]);

                DetailKlarifikasi::create([
                    'detail_klarifikasi_id' => Str::uuid(),
                    'risalah_id' => $risalahKlarifikasi1->risalah_id,
                    'arahan_mediator' => 'Berdasarkan pemaparan kedua belah pihak, perlu dilakukan mediasi untuk:
1. Verifikasi data jam lembur
2. Perhitungan ulang upah lembur sesuai Kepmen 102/2004
3. Pembahasan mekanisme pembayaran',
                    'kesimpulan_klarifikasi' => 'lanjut_ke_tahap_mediasi',
                ]);
            }

            $jadwalMediasi1 = Jadwal::where('pengaduan_id', $pengaduan1->pengaduan_id)
                ->where('jenis_jadwal', 'mediasi')
                ->first();

            if ($jadwalMediasi1) {
                $risalahPenyelesaian1 = Risalah::create([
                    'risalah_id' => Str::uuid(),
                    'jadwal_id' => $jadwalMediasi1->jadwal_id,
                    'dokumen_hi_id' => $dokumenHI1->dokumen_hi_id,
                    'jenis_risalah' => 'penyelesaian',
                    'nama_perusahaan' => 'PT ABC Technology',
                    'jenis_usaha' => 'Teknologi Informasi',
                    'alamat_perusahaan' => 'Jl. Teknologi No. 123, Jakarta',
                    'nama_pekerja' => 'Ecak Harzani',
                    'alamat_pekerja' => 'Jl. Pekerja No. 45, Jakarta',
                    'tanggal_perundingan' => $jadwalMediasi1->tanggal,
                    'tempat_perundingan' => $jadwalMediasi1->tempat,
                    'pokok_masalah' => 'Perselisihan mengenai pembayaran upah lembur selama 3 bulan terakhir (Maret-Mei 2024) dengan total 120 jam lembur yang belum dibayarkan sesuai ketentuan.',
                    'pendapat_pekerja' => 'Berdasarkan perhitungan sesuai Kepmen 102/2004, total upah lembur yang harus dibayarkan adalah Rp 7.250.000. Mohon dapat dibayarkan sekaligus.',
                    'pendapat_pengusaha' => 'Hasil verifikasi menunjukkan kebenaran data 120 jam lembur. Perusahaan setuju dengan perhitungan Rp 7.250.000 dan akan membayar dalam 2 tahap.',
                ]);

                DetailPenyelesaian::create([
                    'detail_penyelesaian_id' => Str::uuid(),
                    'risalah_id' => $risalahPenyelesaian1->risalah_id,
                    'kesimpulan_penyelesaian' => 'Kedua belah pihak sepakat:
1. Total upah lembur yang harus dibayarkan Rp 7.250.000
2. Pembayaran dilakukan dalam 2 tahap:
   - Tahap 1: Rp 4.000.000 (15 Juni 2024)
   - Tahap 2: Rp 3.250.000 (30 Juni 2024)
3. Pembayaran melalui rekening yang sama dengan gaji bulanan
4. Slip rincian pembayaran akan diberikan untuk setiap tahap',
                ]);
            }
        }

        // Kasus 2: Budi - THR (Berhenti di Klarifikasi - Bipartit)
        $pengaduan2 = Pengaduan::where('narasi_kasus', 'like', '%THR yang diberikan tidak sesuai%')->first();
        if ($pengaduan2) {
            $dokumenHI2 = DokumenHubunganIndustrial::create([
                'dokumen_hi_id' => Str::uuid(),
                'pengaduan_id' => $pengaduan2->pengaduan_id,
            ]);

            $jadwalKlarifikasi2 = Jadwal::where('pengaduan_id', $pengaduan2->pengaduan_id)
                ->where('jenis_jadwal', 'klarifikasi')
                ->first();

            if ($jadwalKlarifikasi2) {
                $risalahKlarifikasi2 = Risalah::create([
                    'risalah_id' => Str::uuid(),
                    'jadwal_id' => $jadwalKlarifikasi2->jadwal_id,
                    'dokumen_hi_id' => $dokumenHI2->dokumen_hi_id,
                    'jenis_risalah' => 'klarifikasi',
                    'nama_perusahaan' => 'PT XYZ Manufacturing',
                    'jenis_usaha' => 'Manufaktur',
                    'alamat_perusahaan' => 'Jl. Industri No. 789, Jakarta',
                    'nama_pekerja' => 'Budi Santoso',
                    'alamat_pekerja' => 'Jl. Damai No. 12, Jakarta',
                    'tanggal_perundingan' => $jadwalKlarifikasi2->tanggal,
                    'tempat_perundingan' => $jadwalKlarifikasi2->tempat,
                    'pokok_masalah' => 'Perselisihan mengenai pembayaran THR yang hanya 50% dari ketentuan. Seharusnya menerima 1 bulan gaji penuh karena masa kerja lebih dari 1 tahun.',
                    'pendapat_pekerja' => 'THR yang diberikan hanya 50% dari gaji pokok, padahal masa kerja sudah 1 tahun 6 bulan. Sesuai PP 36/2021 seharusnya menerima 1 bulan gaji penuh.',
                    'pendapat_pengusaha' => 'Pengurangan THR dilakukan karena kondisi perusahaan yang masih pemulihan pasca pandemi. Akan tetapi perusahaan bersedia melakukan perundingan ulang.',
                ]);

                DetailKlarifikasi::create([
                    'detail_klarifikasi_id' => Str::uuid(),
                    'risalah_id' => $risalahKlarifikasi2->risalah_id,
                    'arahan_mediator' => 'Mengingat itikad baik perusahaan untuk berunding dan kesediaan pekerja, disarankan untuk:
1. Melakukan perundingan bipartit kembali
2. Membahas skema pembayaran kekurangan THR
3. Membuat kesepakatan tertulis hasil perundingan',
                    'kesimpulan_klarifikasi' => 'bipartit_lagi',
                ]);
            }
        }

        // Kasus 3: Ahmad - PHK (Proses Mediasi)
        $pengaduan3 = Pengaduan::where('narasi_kasus', 'like', '%PHK sepihak tanpa alasan%')->first();
        if ($pengaduan3) {
            $dokumenHI3 = DokumenHubunganIndustrial::create([
                'dokumen_hi_id' => Str::uuid(),
                'pengaduan_id' => $pengaduan3->pengaduan_id,
            ]);

            $jadwalKlarifikasi3 = Jadwal::where('pengaduan_id', $pengaduan3->pengaduan_id)
                ->where('jenis_jadwal', 'klarifikasi')
                ->first();

            if ($jadwalKlarifikasi3) {
                $risalahKlarifikasi3 = Risalah::create([
                    'risalah_id' => Str::uuid(),
                    'jadwal_id' => $jadwalKlarifikasi3->jadwal_id,
                    'dokumen_hi_id' => $dokumenHI3->dokumen_hi_id,
                    'jenis_risalah' => 'klarifikasi',
                    'nama_perusahaan' => 'PT DEF Trading',
                    'jenis_usaha' => 'Perdagangan',
                    'alamat_perusahaan' => 'Jl. Bisnis No. 456, Jakarta',
                    'nama_pekerja' => 'Ahmad',
                    'alamat_pekerja' => 'Jl. Harmoni No. 78, Jakarta',
                    'tanggal_perundingan' => $jadwalKlarifikasi3->tanggal,
                    'tempat_perundingan' => $jadwalKlarifikasi3->tempat,
                    'pokok_masalah' => 'PHK sepihak dengan alasan efisiensi namun tanpa prosedur yang benar dan pesangon tidak sesuai UU Cipta Kerja.',
                    'pendapat_pekerja' => 'PHK dilakukan secara mendadak tanpa pemberitahuan dan musyawarah terlebih dahulu. Pesangon yang ditawarkan hanya 50% dari ketentuan UU.',
                    'pendapat_pengusaha' => 'PHK dilakukan karena reorganisasi dan efisiensi perusahaan. Besaran pesangon disesuaikan dengan kemampuan perusahaan saat ini.',
                ]);

                DetailKlarifikasi::create([
                    'detail_klarifikasi_id' => Str::uuid(),
                    'risalah_id' => $risalahKlarifikasi3->risalah_id,
                    'arahan_mediator' => 'Perlu dilakukan mediasi untuk:
1. Pembahasan prosedur PHK
2. Verifikasi perhitungan pesangon sesuai UU
3. Pembahasan skema pembayaran
4. Hak-hak lain yang belum dipenuhi',
                    'kesimpulan_klarifikasi' => 'lanjut_ke_tahap_mediasi',
                ]);
            }
        }

        // Kasus 4: Sarah - Perjanjian Kerja (Proses Mediasi)
        $pengaduan4 = Pengaduan::where('narasi_kasus', 'like', '%Perubahan status kerja%')->first();
        if ($pengaduan4) {
            $dokumenHI4 = DokumenHubunganIndustrial::create([
                'dokumen_hi_id' => Str::uuid(),
                'pengaduan_id' => $pengaduan4->pengaduan_id,
            ]);

            $jadwalKlarifikasi4 = Jadwal::where('pengaduan_id', $pengaduan4->pengaduan_id)
                ->where('jenis_jadwal', 'klarifikasi')
                ->first();

            if ($jadwalKlarifikasi4) {
                $risalahKlarifikasi4 = Risalah::create([
                    'risalah_id' => Str::uuid(),
                    'jadwal_id' => $jadwalKlarifikasi4->jadwal_id,
                    'dokumen_hi_id' => $dokumenHI4->dokumen_hi_id,
                    'jenis_risalah' => 'klarifikasi',
                    'nama_perusahaan' => 'PT GHI Services',
                    'jenis_usaha' => 'Jasa',
                    'alamat_perusahaan' => 'Jl. Layanan No. 234, Jakarta',
                    'nama_pekerja' => 'Sarah',
                    'alamat_pekerja' => 'Jl. Sejahtera No. 56, Jakarta',
                    'tanggal_perundingan' => $jadwalKlarifikasi4->tanggal,
                    'tempat_perundingan' => $jadwalKlarifikasi4->tempat,
                    'pokok_masalah' => 'Perubahan status kerja dari PKWTT menjadi PKWT secara sepihak tanpa persetujuan pekerja.',
                    'pendapat_pekerja' => 'Perubahan status dilakukan secara sepihak dan merugikan karena menghilangkan hak-hak yang sudah diperoleh selama ini.',
                    'pendapat_pengusaha' => 'Perubahan diperlukan untuk efisiensi dan penyesuaian model bisnis perusahaan.',
                ]);

                DetailKlarifikasi::create([
                    'detail_klarifikasi_id' => Str::uuid(),
                    'risalah_id' => $risalahKlarifikasi4->risalah_id,
                    'arahan_mediator' => 'Perlu dilakukan mediasi untuk:
1. Pembahasan legalitas perubahan status kerja
2. Kajian dampak terhadap hak-hak pekerja
3. Mencari solusi yang tidak merugikan kedua belah pihak',
                    'kesimpulan_klarifikasi' => 'lanjut_ke_tahap_mediasi',
                ]);
            }
        }

        // Kasus 5: Rini - Fasilitas Kerja (Baru Tahap Klarifikasi)
        $pengaduan5 = Pengaduan::where('narasi_kasus', 'like', '%Pengurangan fasilitas kerja%')->first();
        if ($pengaduan5) {
            $dokumenHI5 = DokumenHubunganIndustrial::create([
                'dokumen_hi_id' => Str::uuid(),
                'pengaduan_id' => $pengaduan5->pengaduan_id,
            ]);

            $jadwalKlarifikasi5 = Jadwal::where('pengaduan_id', $pengaduan5->pengaduan_id)
                ->where('jenis_jadwal', 'klarifikasi')
                ->first();

            if ($jadwalKlarifikasi5) {
                $risalahKlarifikasi5 = Risalah::create([
                    'risalah_id' => Str::uuid(),
                    'jadwal_id' => $jadwalKlarifikasi5->jadwal_id,
                    'dokumen_hi_id' => $dokumenHI5->dokumen_hi_id,
                    'jenis_risalah' => 'klarifikasi',
                    'nama_perusahaan' => 'PT JKL Consulting',
                    'jenis_usaha' => 'Konsultan',
                    'alamat_perusahaan' => 'Jl. Konsultasi No. 567, Jakarta',
                    'nama_pekerja' => 'Rini',
                    'alamat_pekerja' => 'Jl. Bahagia No. 89, Jakarta',
                    'tanggal_perundingan' => $jadwalKlarifikasi5->tanggal,
                    'tempat_perundingan' => $jadwalKlarifikasi5->tempat,
                    'pokok_masalah' => 'Pengurangan fasilitas kerja dan tunjangan operasional tanpa pemberitahuan dan kesepakatan dengan pekerja.',
                    'pendapat_pekerja' => 'Pengurangan fasilitas dan tunjangan dilakukan secara sepihak dan mengganggu kinerja karena beberapa fasilitas penting untuk operasional dihapus.',
                    'pendapat_pengusaha' => 'Pengurangan dilakukan karena kondisi keuangan perusahaan yang sedang tidak stabil.',
                ]);

                DetailKlarifikasi::create([
                    'detail_klarifikasi_id' => Str::uuid(),
                    'risalah_id' => $risalahKlarifikasi5->risalah_id,
                    'arahan_mediator' => 'Perlu dilakukan mediasi untuk:
1. Pembahasan detail fasilitas yang dikurangi
2. Dampak terhadap operasional kerja
3. Mencari alternatif solusi yang dapat diterima kedua pihak',
                    'kesimpulan_klarifikasi' => 'lanjut_ke_tahap_mediasi',
                ]);
            }
        }

        $this->command->info('Risalah data seeded successfully!');
    }
} 