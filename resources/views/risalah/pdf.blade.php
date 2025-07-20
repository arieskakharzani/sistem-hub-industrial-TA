<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Risalah {{ $risalah->jenis_risalah }} Perselisihan Hubungan Industrial</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            margin: 40px;
        }

        .judul {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .subjudul {
            font-size: 14px;
            text-align: center;
            margin-bottom: 18px;
        }

        .table-risalah {
            width: 100%;
            border-collapse: collapse;
        }

        .table-risalah td {
            padding: 8px 5px;
            vertical-align: top;
        }

        .table-risalah .nomor {
            width: 5%;
            font-size: 12pt;
        }

        .table-risalah .label {
            width: 25%;
            font-size: 12pt;
        }

        .table-risalah .colon {
            width: 3%;
            font-size: 12pt;
        }

        .keterangan-text {
            color: #666;
            font-size: 11pt;
            font-style: italic;
            margin-top: 10px;
            display: block;
            line-height: 1.4;
        }

        .ttd-section {
            margin-top: 10px;
            margin-right: 30px;
            text-align: right;
        }

        .ttd-section p {
            margin-top: 0px;
        }

        .ttd-content {
            display: inline-block;
            text-align: right;
        }

        .ttd-section p {
            margin-top: 0px;
        }

        .ttd-nama {
            font-weight: bold;
            margin-top: 10px;
            /* Sesuaikan jarak untuk tanda tangan */
            font-size: 12pt;
        }

        .ttd-nip {
            font-size: 11pt;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="judul">RISALAH {{ strtoupper($risalah->jenis_risalah) }} PERSELISIHAN<br>HUBUNGAN INDUSTRIAL</div>
    <table class="table-risalah">
        <tr>
            <td class="nomor">1.</td>
            <td class="label">Nama Perusahaan</td>
            <td class="colon">:</td>
            <td>{{ $risalah->nama_perusahaan }}</td>
        </tr>
        <tr>
            <td class="nomor">2.</td>
            <td class="label">Jenis Usaha</td>
            <td class="colon">:</td>
            <td>{{ $risalah->jenis_usaha }}</td>
        </tr>
        <tr>
            <td class="nomor">3.</td>
            <td class="label">Alamat Perusahaan</td>
            <td class="colon">:</td>
            <td>{{ $risalah->alamat_perusahaan }}</td>
        </tr>
        <tr>
            <td class="nomor">4.</td>
            <td class="label">Nama Pekerja/Buruh/SP/SB</td>
            <td class="colon">:</td>
            <td>{{ $risalah->nama_pekerja }}</td>
        </tr>
        <tr>
            <td class="nomor">5.</td>
            <td class="label">Alamat Pekerja/Buruh/SP/SB</td>
            <td class="colon">:</td>
            <td>{{ $risalah->alamat_pekerja }}</td>
        </tr>
        <tr>
            <td class="nomor">6.</td>
            <td class="label">Tanggal dan Tempat Perundingan</td>
            <td class="colon">:</td>
            <td>{{ $risalah->tanggal_perundingan ? \Carbon\Carbon::parse($risalah->tanggal_perundingan)->translatedFormat('d F Y') : '' }},
                {{ $risalah->tempat_perundingan }}</td>
        </tr>
        <tr>
            <td class="nomor">7.</td>
            <td class="label">Pokok Masalah/Alasan Perselisihan</td>
            <td class="colon">:</td>
            <td>{{ $risalah->pokok_masalah }}</td>
        </tr>
        <tr>
            <td class="nomor">8.</td>
            <td class="label">Keterangan/Pendapat Pekerja/Buruh/SP/SB</td>
            <td class="colon">:</td>
            <td>{{ $risalah->pendapat_pekerja }}</td>
        </tr>
        <tr>
            <td class="nomor">9.</td>
            <td class="label">Keterangan/Pendapat Pengusaha</td>
            <td class="colon">:</td>
            <td>{{ $risalah->pendapat_pengusaha }}</td>
        </tr>
        @if ($risalah->jenis_risalah === 'klarifikasi')
            <tr>
                <td class="nomor">10.</td>
                <td class="label">Arahan Mediator</td>
                <td class="colon">:</td>
                <td>{{ $risalah->detailKlarifikasi->arahan_mediator }}</td>
            </tr>
            <tr>
                <td class="nomor">11.</td>
                <td class="label">Kesimpulan atau Hasil Klarifikasi</td>
                <td class="colon">:</td>
                <td>
                    @if ($risalah->detailKlarifikasi->kesimpulan_klarifikasi === 'bipartit_lagi')
                        Perundingan Bipartit
                    @elseif($risalah->detailKlarifikasi->kesimpulan_klarifikasi === 'lanjut_ke_tahap_mediasi')
                        Lanjut ke Tahap Mediasi
                    @else
                        {{ $risalah->detailKlarifikasi->kesimpulan_klarifikasi }}
                    @endif
                    <span class="keterangan-text">
                        Keterangan: dalam membuat Kesimpulan atau hasil klarifikasi agar ditegaskan penyelesaian
                        perselisihannya. Ada 3 alternatif, yaitu a) sepakat untuk melakukan perundingan bipartit; atau
                        b)
                        sepakat akan melanjutkan penyelesaian melalui mediasi dengan hasil perjanjian bersama; atau c)
                        sepakat akan melanjutkan penyelesaian melalui mediasi dengan hasil anjuran.
                    </span>
                </td>
            </tr>
        @endif
    </table>

    <div class="ttd-section">
        <p>Muara Bungo,
            {{ $risalah->tanggal_perundingan ? \Carbon\Carbon::parse($risalah->tanggal_perundingan)->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}
        </p>
        <div class="ttd-content">
            <p>Mediator Hubungan Industrial,</p>
            <br><br><br>
            <p class="ttd-nama">{{ $risalah->jadwal->mediator->nama_mediator ?? '-' }}</p>
            <p class="ttd-nip">NIP. {{ $risalah->jadwal->mediator->nip ?? '-' }}</p>
        </div>
    </div>
</body>

</html>
