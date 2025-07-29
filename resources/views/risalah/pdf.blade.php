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
            padding: 40px;
            color: #333;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .content {
            margin-bottom: 30px;
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

        .divider {
            border-top: 1px solid #000;
            margin: 40px 0;
        }

        .signature-section {
            margin-top: 60px;
            text-align: right;
        }

        .signature-text {
            font-weight: bold;
        }

        .signature-name {
            font-weight: bold;
            font-size: 12px;
        }

        .signature-nip {
            font-size: 12px;
            color: #666;
        }

        .page-break {
            page-break-before: always;
        }

        @media print {
            body {
                margin: 0;
                padding: 30px;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>
            @if ($risalah->jenis_risalah === 'klarifikasi')
                RISALAH KLARIFIKASI PERSELISIHAN HUBUNGAN INDUSTRIAL
            @elseif ($risalah->jenis_risalah === 'mediasi')
                RISALAH MEDIASI PERSELISIHAN HUBUNGAN INDUSTRIAL
            @elseif ($risalah->jenis_risalah === 'penyelesaian')
                RISALAH PENYELESAIAN PERSELISIHAN HUBUNGAN INDUSTRIAL
            @else
                RISALAH {{ strtoupper($risalah->jenis_risalah) }}
            @endif
        </h1>
    </div>
    <div class="divider"></div>

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
                                    perundingan bipartit; atau b) sepakat akan melanjutkan penyelesaian melalui mediasi
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

    @if ($risalah->jenis_risalah !== 'mediasi')
        <div class="signature-section">
            <div class="signature-text">Mediator Hubungan Industrial,</div>
            <br><br><br>
            <div class="signature-name">
                {{ $risalah->jadwal->mediator->nama_mediator }}</div>
            <div class="signature-nip">
                @php
                    $mediator = null;
                    if ($risalah->jadwal && $risalah->jadwal->mediator) {
                        $mediator = $risalah->jadwal->mediator;
                    }
                @endphp
                NIP. {{ $mediator ? $mediator->nip : '-' }}
            </div>
        </div>
    @endif
</body>

</html>
