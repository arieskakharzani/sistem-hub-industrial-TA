<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risalah {{ ucfirst($risalah->jenis_risalah) }}</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            color: #333;
            font-size: 12px;
        }

        .page {
            padding: 40px 60px;
            margin-bottom: 50px;
            /* Memberikan ruang untuk footer fixed */
            position: relative;
            min-height: auto;
        }

        .header {
            text-align: center;
            margin: 100px 0 10px 0;
            /* Memberikan ruang untuk kop surat absolute */
            font-weight: bold;
            font-size: 16px;
            position: relative;
        }

        .content {
            margin: 0;
            text-align: justify;
            position: relative;
        }

        .info-section {
            margin-bottom: 30px;
        }

        .info-row {
            margin-bottom: 10px;
            min-height: 15px;
        }

        .info-row table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-row td {
            vertical-align: top;
            padding: 0;
        }

        .info-number {
            width: 40px;
            font-weight: bold;
            text-align: right;
            padding-right: 10px;
            font-size: 12px;
        }

        .info-label {
            width: 280px;
            font-weight: bold;
            text-align: left;
            padding-right: 15px;
            font-size: 12px;
        }

        .info-colon {
            width: 20px;
            font-weight: bold;
            text-align: left;
            padding-right: 15px;
            font-size: 12px;
        }

        .info-value {
            text-align: left;
            min-height: 25px;
            font-size: 12px;
        }

        .info-value-long {
            text-align: left;
            min-height: 80px;
        }

        .keterangan-text {
            font-style: italic;
            color: #666;
            text-align: justify;
            font-size: 11px;
            margin-top: 10px;
            line-height: 1.4;
        }

        .page-break {
            page-break-before: always;
        }

        /* Print styles */
        @media print {
            @page {
                margin: 3cm 3cm 4cm 3cm;
                size: A4;
            }

            @page :first {
                margin: 3cm 3cm 4cm 3cm;
            }

            @page :left {
                margin: 3cm 3cm 4cm 3cm;
            }

            @page :right {
                margin: 3cm 3cm 4cm 3cm;
            }

            /* Memastikan kop surat muncul di setiap halaman */
            .kop-surat {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
                page-break-after: avoid;
            }

            /* Memastikan header tidak terlalu mepet di halaman selanjutnya */
            .header {
                margin-top: 150px;
                /* Memberikan ruang untuk kop surat absolute */
            }

            .page {
                padding: 40px 60px;
                margin: 0;
                margin-bottom: 100px;
                page-break-inside: avoid;
            }

            .footer {
                position: fixed !important;
                bottom: 0 !important;
                left: 0 !important;
                right: 0 !important;
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
            }

            .section {
                page-break-inside: avoid;
            }

            .content {
                page-break-inside: auto;
            }

            .header {
                page-break-after: avoid;
            }

            .kop-surat {
                page-break-after: avoid;
            }
        }

        /* Responsive adjustments */
        @media screen and (max-width: 768px) {
            .page {
                padding: 20px 30px;
            }
        }
    </style>
</head>

<body>
    <div class="page">
        <!-- Kop Surat menggunakan komponen -->
        @include('components.pdf.kop-surat')

        <div class="header">
            <h5 class="text-center">
                @if ($risalah->jenis_risalah === 'klarifikasi')
                    RISALAH KLARIFIKASI PERSELISIHAN HUBUNGAN INDUSTRIAL
                @elseif ($risalah->jenis_risalah === 'mediasi')
                    RISALAH MEDIASI PERSELISIHAN HUBUNGAN INDUSTRIAL
                @elseif ($risalah->jenis_risalah === 'penyelesaian')
                    RISALAH PENYELESAIAN PERSELISIHAN HUBUNGAN INDUSTRIAL
                @else
                    RISALAH {{ strtoupper($risalah->jenis_risalah) }}
                @endif
            </h5>
        </div>

        <div class="content">
            <div class="info-section">
                <div class="info-row">
                    <table>
                        <tr>
                            <td class="info-number">1.</td>
                            <td class="info-label">Nama Perusahaan</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">{{ $risalah->nama_perusahaan }}</td>
                        </tr>
                    </table>
                </div>

                <div class="info-row">
                    <table>
                        <tr>
                            <td class="info-number">2.</td>
                            <td class="info-label">Jenis Usaha</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">{{ $risalah->jenis_usaha }}</td>
                        </tr>
                    </table>
                </div>

                <div class="info-row">
                    <table>
                        <tr>
                            <td class="info-number">3.</td>
                            <td class="info-label">Alamat Perusahaan</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">{{ $risalah->alamat_perusahaan }}</td>
                        </tr>
                    </table>
                </div>

                <div class="info-row">
                    <table>
                        <tr>
                            <td class="info-number">4.</td>
                            <td class="info-label">Nama Pekerja/Buruh/SP/SB</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">{{ $risalah->nama_pekerja }}</td>
                        </tr>
                    </table>
                </div>

                <div class="info-row">
                    <table>
                        <tr>
                            <td class="info-number">5.</td>
                            <td class="info-label">Alamat Pekerja/Buruh/SP/SB</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">{{ $risalah->alamat_pekerja }}</td>
                        </tr>
                    </table>
                </div>

                <div class="info-row">
                    <table>
                        <tr>
                            <td class="info-number">6.</td>
                            <td class="info-label">Tanggal dan Tempat Perundingan</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">
                                {{ $risalah->tanggal_perundingan ? $risalah->tanggal_perundingan->format('d/m/Y') : '-' }}
                                di {{ $risalah->tempat_perundingan }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="info-section">
                <div class="info-row">
                    <table>
                        <tr>
                            <td class="info-number">7.</td>
                            <td class="info-label">Pokok Masalah/Alasan Perselisihan</td>
                            <td class="info-colon">:</td>
                            <td class="info-value-long">{{ $risalah->pokok_masalah ?? '-' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="info-row">
                    <table>
                        <tr>
                            <td class="info-number">8.</td>
                            <td class="info-label">Keterangan/Pendapat Pekerja/Buruh/SP/SB</td>
                            <td class="info-colon">:</td>
                            <td class="info-value-long">{{ $risalah->pendapat_pekerja }}</td>
                        </tr>
                    </table>
                </div>

                <div class="info-row">
                    <table>
                        <tr>
                            <td class="info-number">9.</td>
                            <td class="info-label">Keterangan/Pendapat Pengusaha</td>
                            <td class="info-colon">:</td>
                            <td class="info-value-long">{{ $risalah->pendapat_pengusaha }}</td>
                        </tr>
                    </table>
                </div>

                @if ($risalah->jenis_risalah === 'klarifikasi' && $detail)
                    <div class="info-row">
                        <table>
                            <tr>
                                <td class="info-number">10.</td>
                                <td class="info-label">Arahan Mediator</td>
                                <td class="info-colon">:</td>
                                <td class="info-value-long">{{ $detail->arahan_mediator ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="info-row">
                        <table>
                            <tr>
                                <td class="info-number">11.</td>
                                <td class="info-label">Kesimpulan atau Hasil Klarifikasi</td>
                                <td class="info-colon">:</td>
                                <td class="info-value">
                                    @if ($detail->kesimpulan_klarifikasi === 'bipartit_lagi')
                                        Sepakat untuk melakukan perundingan bipartit
                                    @elseif($detail->kesimpulan_klarifikasi === 'lanjut_ke_tahap_mediasi')
                                        Sepakat akan melanjutkan penyelesaian melalui mediasi
                                    @else
                                        {{ $detail->kesimpulan_klarifikasi ?? '-' }}
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                @elseif ($risalah->jenis_risalah === 'mediasi' && $detail)
                    <div class="info-row">
                        <table>
                            <tr>
                                <td class="info-number">10.</td>
                                <td class="info-label">Sidang Ke</td>
                                <td class="info-colon">:</td>
                                <td class="info-value">{{ $detail->sidang_ke ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="info-row">
                        <table>
                            <tr>
                                <td class="info-number">11.</td>
                                <td class="info-label">Ringkasan Pembahasan</td>
                                <td class="info-colon">:</td>
                                <td class="info-value-long">{{ $detail->ringkasan_pembahasan ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="info-row">
                        <table>
                            <tr>
                                <td class="info-number">12.</td>
                                <td class="info-label">Kesepakatan Sementara</td>
                                <td class="info-colon">:</td>
                                <td class="info-value-long">{{ $detail->kesepakatan_sementara ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="info-row">
                        <table>
                            <tr>
                                <td class="info-number">13.</td>
                                <td class="info-label">Ketidaksepakatan Sementara</td>
                                <td class="info-colon">:</td>
                                <td class="info-value-long">{{ $detail->ketidaksepakatan_sementara ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="info-row">
                        <table>
                            <tr>
                                <td class="info-number">14.</td>
                                <td class="info-label">Catatan Khusus</td>
                                <td class="info-colon">:</td>
                                <td class="info-value-long">{{ $detail->catatan_khusus ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="info-row">
                        <table>
                            <tr>
                                <td class="info-number">15.</td>
                                <td class="info-label">Rekomendasi Mediator</td>
                                <td class="info-colon">:</td>
                                <td class="info-value-long">{{ $detail->rekomendasi_mediator ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="info-row">
                        <table>
                            <tr>
                                <td class="info-number">16.</td>
                                <td class="info-label">Status Sidang</td>
                                <td class="info-colon">:</td>
                                <td class="info-value">
                                    @if ($detail->status_sidang === 'selesai')
                                        Selesai
                                    @elseif($detail->status_sidang === 'lanjut_sidang_berikutnya')
                                        Lanjut Sidang Berikutnya
                                    @else
                                        {{ $detail->status_sidang ?? '-' }}
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                @elseif ($risalah->jenis_risalah === 'penyelesaian' && $detail)
                    <div class="info-row">
                        <table>
                            <tr>
                                <td class="info-number">10.</td>
                                <td class="info-label">Kesimpulan atau Hasil Perundingan</td>
                                <td class="info-colon">:</td>
                                <td class="info-value-long">
                                    {{ $detail->kesimpulan_penyelesaian ?? '-' }}
                                    <div class="keterangan-text">
                                        Keterangan: dalam membuat Kesimpulan atau hasil perundingan agar ditegaskan
                                        penyelesaian perselisihannya. Ada 3 alternatif, yaitu a) sepakat untuk melakukan
                                        perundingan bipartit; atau b) sepakat akan melanjutkan penyelesaian melalui
                                        mediasi
                                        dengan hasil perjanjian bersama; atau c) sepakat akan melanjutkan penyelesaian
                                        melalui mediasi dengan hasil anjuran.
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer menggunakan komponen -->
        @include('components.pdf.footer', [
            'footerText' =>
                'Risalah ' .
                ucfirst($risalah->jenis_risalah) .
                ' ini dikeluarkan oleh Mediator Hubungan Industrial Dinas Tenaga Kerja dan Transmigrasi Kabupaten Bungo',
            'approvalDate' => \Carbon\Carbon::parse($risalah->created_at)->translatedFormat('d F Y'),
            'approvalTime' => \Carbon\Carbon::parse($risalah->created_at)->format('H:i'),
        ])
    </div>

    <script>
        // Memastikan kop surat muncul di setiap halaman
        function ensureKopSuratVisible() {
            const kopSurat = document.querySelector('.kop-surat');
            if (kopSurat) {
                kopSurat.style.display = 'block';
                kopSurat.style.visibility = 'visible';
                kopSurat.style.opacity = '1';
            }
        }

        // Panggil fungsi setelah halaman load
        window.addEventListener('load', function() {
            setTimeout(ensureKopSuratVisible, 100);
        });

        // Panggil lagi saat scroll atau resize
        window.addEventListener('scroll', ensureKopSuratVisible);
        window.addEventListener('resize', ensureKopSuratVisible);
    </script>
</body>

</html>
