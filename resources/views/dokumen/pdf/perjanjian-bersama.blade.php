<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Perjanjian Bersama</title>
    <style>
        body {
            font-family: Times New Roman, serif;
            line-height: 1.5;
            font-size: 12px;
            margin: 0;
            padding: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .content {
            margin: 0;
        }

        .title {
            font-weight: bold;
            font-size: 14px;
        }

        .party-info {
            margin-bottom: 10px;
        }

        .party-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .party-info td {
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
            width: 120px;
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
            font-size: 12px;
        }

        .party-title {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 12px;
        }

        .party-description {
            font-style: italic;
            font-size: 12px;
            margin-top: 10px;
        }

        .legal-basis {
            text-align: justify;
            font-size: 12px;
        }

        .agreement-content {
            text-align: justify;
            margin: 20px 0;
            font-size: 12px;
        }

        .conclusion {
            text-align: justify;
            font-size: 12px;
        }

        .signature-container {
            width: 100%;
            margin-top: 10px;
        }

        .signature-row {
            width: 100%;
            display: table;
            table-layout: fixed;
        }

        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 20px;
        }

        .signature-line {
            margin: 0 auto;
            margin-top: 50px;
            margin-bottom: 5px;
            width: 200px;
            border-bottom: 1px solid #000;
        }

        .mediator-signature {
            margin-top: 10px;
            text-align: center;

        }

        .mediator-signature .signature-line {
            margin-top: 80px;
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
        <div class="title">PERJANJIAN BERSAMA</div>
    </div>

    <div class="content">
        <p>
            Pada hari {{ \Carbon\Carbon::parse($perjanjian->created_at)->translatedFormat('l') }}
            tanggal {{ \Carbon\Carbon::parse($perjanjian->created_at)->translatedFormat('d') }}
            bulan {{ \Carbon\Carbon::parse($perjanjian->created_at)->translatedFormat('F') }}
            tahun {{ \Carbon\Carbon::parse($perjanjian->created_at)->translatedFormat('Y') }}
            kami yang bertanda tangan di bawah ini:
        </p>

        <div class="party-info">
            <div class="party-title">1. Pihak Pengusaha</div>
            <table>
                <tr>
                    <td class="info-label">Nama</td>
                    <td class="info-colon">:</td>
                    <td class="info-value">{{ $perjanjian->nama_pengusaha }}</td>
                </tr>
                <tr>
                    <td class="info-label">Jabatan</td>
                    <td class="info-colon">:</td>
                    <td class="info-value">{{ $perjanjian->jabatan_pengusaha }}</td>
                </tr>
                <tr>
                    <td class="info-label">Perusahaan</td>
                    <td class="info-colon">:</td>
                    <td class="info-value">{{ $perjanjian->perusahaan_pengusaha }}</td>
                </tr>
                <tr>
                    <td class="info-label">Alamat</td>
                    <td class="info-colon">:</td>
                    <td class="info-value">{{ $perjanjian->alamat_pengusaha }}</td>
                </tr>
            </table>
            <div class="party-description">Yang selanjutnya disebut Pihak Pengusaha.</div>
        </div>

        <div class="party-info">
            <div class="party-title">2. Pihak Pekerja/Buruh/SP/SB</div>
            <table>
                <tr>
                    <td class="info-label">Nama</td>
                    <td class="info-colon">:</td>
                    <td class="info-value">{{ $perjanjian->nama_pekerja }}</td>
                </tr>
                <tr>
                    <td class="info-label">Jabatan</td>
                    <td class="info-colon">:</td>
                    <td class="info-value">{{ $perjanjian->jabatan_pekerja }}</td>
                </tr>
                <tr>
                    <td class="info-label">Perusahaan</td>
                    <td class="info-colon">:</td>
                    <td class="info-value">{{ $perjanjian->perusahaan_pekerja }}</td>
                </tr>
                <tr>
                    <td class="info-label">Alamat</td>
                    <td class="info-colon">:</td>
                    <td class="info-value">{{ $perjanjian->alamat_pekerja }}</td>
                </tr>
            </table>
            <div class="party-description">Yang selanjutnya disebut Pihak Pekerja/Buruh/SP/SB*)</div>
        </div>

        <div class="legal-basis">
            <p>
                Berdasarkan ketentuan Pasal 13 ayat (1) Undang-Undang Nomor 2 Tahun 2004 tentang Penyelesaian
                Perselisihan
                Hubungan Industrial, antara Pihak Pengusaha dan Pihak Pekerja/Buruh/SP/SB*) telah tercapai kesepakatan
                penyelesaian perselisihan hubungan industrial melalui Mediasi sebagai berikut:
            </p>
        </div>

        <div class="agreement-content">
            {!! nl2br(e($perjanjian->isi_kesepakatan)) !!}
        </div>

        <div class="conclusion">
            <p>
                Kesepakatan ini merupakan perjanjian bersama yang berlaku sejak ditandatangani pihak-pihak berselisih.
            </p>

            <p>
                Demikian Perjanjian Bersama ini dibuat dalam keadaan sadar tanpa paksaan dari pihak manapun, dan
                dilaksanakan dengan penuh rasa tanggung jawab yang didasari itikad baik.
            </p>
        </div>

        <div class="signature-container">
            <div class="signature-row">
                <div class="signature-box">
                    <p>Pihak Pengusaha,</p>
                    <p>({{ $perjanjian->nama_pengusaha }})</p>
                </div>
                <div class="signature-box">
                    <p>Pihak Pekerja/Buruh/SP/SB,</p>
                    <p>({{ $perjanjian->nama_pekerja }})</p>
                </div>
            </div>
        </div>

        <div class="mediator-signature">
            <p>Menyaksikan</p>
            <p>Mediator Hubungan Industrial,</p>
            <p>({{ $perjanjian->dokumenHI->pengaduan->mediator->nama_mediator ?? '-' }})</p>
            <p>NIP. {{ $perjanjian->dokumenHI->pengaduan->mediator->nip ?? '-' }}</p>
        </div>
    </div>
</body>

</html>
