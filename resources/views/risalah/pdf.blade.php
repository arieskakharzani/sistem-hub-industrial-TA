<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Risalah Klarifikasi Perselisihan Hubungan Industrial</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12px;
            background: #fff;
            color: #222;
        }

        .judul {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 8px;
            letter-spacing: 1px;
            text-transform: uppercase;
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
            vertical-align: top;
            padding: 2px 4px;
        }

        .nomor {
            width: 22px;
        }

        .label {
            width: 220px;
        }

        .colon {
            width: 10px;
        }

        .keterangan {
            font-size: 10px;
            color: #444;
            margin-top: 6px;
        }

        .ttd {
            margin-top: 40px;
            text-align: right;
        }

        .ttd .nama {
            margin-top: 60px;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="judul">RISALAH KLARIFIKASI PERSELISIHAN<br>HUBUNGAN INDUSTRIAL</div>
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
            <td>{{ $risalah->tanggal_perundingan }}, {{ $risalah->tempat_perundingan }}</td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
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
        <tr>
            <td class="nomor">10.</td>
            <td class="label">Arahan Mediator</td>
            <td class="colon">:</td>
            <td>{{ $risalah->arahan_mediator }}</td>
        </tr>
        <tr>
            <td class="nomor">11.</td>
            <td class="label">Kesimpulan atau Hasil Klarifikasi</td>
            <td class="colon">:</td>
            <td>
                @if ($risalah->kesimpulan_klarifikasi === 'bipartit_lagi')
                    Perundingan Bipartit
                @elseif($risalah->kesimpulan_klarifikasi === 'lanjut_ke_tahap_mediasi')
                    Lanjut ke Tahap Mediasi
                @else
                    {{ $risalah->kesimpulan_klarifikasi }}
                @endif
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" class="keterangan">
                Keterangan: dalam membuat Kesimpulan atau hasil klarifikasi agar ditegaskan penyelesaian
                perselisihannya. Ada 3 alternatif, yaitu a) sepakat untuk melakukan perundingan bipartit; atau b)
                sepakat akan melanjutkan penyelesaian melalui mediasi dengan hasil perjanjian bersama; atau c) sepakat
                akan melanjutkan penyelesaian melalui mediasi dengan hasil anjuran.
            </td>
        </tr>
    </table>
    <div class="ttd">
        <div>Muara Bungo, {{ \Carbon\Carbon::parse($risalah->tanggal_perundingan)->translatedFormat('d F Y') }}</div>
        <div class="mt-4">Mediator Hubungan Industrial,</div>
        <div class="nama">{{ $risalah->jadwal->mediator->nama_mediator ?? '-' }}</div>
        <div class="text-sm">NIP: {{ $risalah->jadwal->mediator->nip ?? '-' }}</div>
    </div>
</body>

</html>
