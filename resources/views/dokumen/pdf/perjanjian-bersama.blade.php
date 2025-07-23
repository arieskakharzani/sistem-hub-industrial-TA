<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Perjanjian Bersama</title>
    <style>
        body {
            font-family: Times New Roman, serif;
            line-height: 1.5;
            font-size: 12pt;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .content {
            margin: 0 30px;
        }

        .title {
            font-weight: bold;
            font-size: 14pt;
            margin-bottom: 20px;
        }

        .party-info {
            margin-left: 20px;
            margin-bottom: 20px;
        }

        .party-info p {
            margin: 5px 0;
        }

        .label {
            display: inline-block;
            width: 100px;
        }

        .colon {
            display: inline-block;
            width: 20px;
        }

        .dotted-line {
            display: inline-block;
            border-bottom: 1px dotted #000;
            width: 250px;
        }

        .signature-container {
            width: 100%;
            margin-top: 50px;
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
        }

        .signature-line {
            margin: 0 auto;
            margin-top: 80px;
            margin-bottom: 5px;
            width: 200px;
            border-bottom: 1px dotted #000;
        }

        .mediator-signature {
            margin-top: 50px;
            text-align: center;
        }

        .mediator-signature .signature-line {
            margin-top: 80px;
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
            <p>1. <span class="label">Nama</span><span class="colon">:</span>{{ $perjanjian->nama_pengusaha }}</p>
            <p><span class="label">Jabatan</span><span class="colon">:</span>{{ $perjanjian->jabatan_pengusaha }}</p>
            <p><span class="label">Perusahaan</span><span class="colon">:</span>{{ $perjanjian->perusahaan_pengusaha }}
            </p>
            <p><span class="label">Alamat</span><span class="colon">:</span>{{ $perjanjian->alamat_pengusaha }}</p>
            <p>Yang selanjutnya disebut Pihak Pengusaha.</p>
        </div>

        <div class="party-info">
            <p>2. <span class="label">Nama</span><span class="colon">:</span>{{ $perjanjian->nama_pekerja }}</p>
            <p><span class="label">Jabatan</span><span class="colon">:</span>{{ $perjanjian->jabatan_pekerja }}</p>
            <p><span class="label">Perusahaan</span><span class="colon">:</span>{{ $perjanjian->perusahaan_pekerja }}
            </p>
            <p><span class="label">Alamat</span><span class="colon">:</span>{{ $perjanjian->alamat_pekerja }}</p>
            <p>Yang selanjutnya disebut Pihak Pekerja/Buruh/SP/SB*)</p>
        </div>

        <p style="text-align: justify;">
            Berdasarkan ketentuan Pasal 13 ayat (1) Undang-Undang Nomor 2 Tahun 2004 tentang Penyelesaian Perselisihan
            Hubungan Industrial, antara Pihak Pengusaha dan Pihak Pekerja/Buruh/SP/SB*) telah tercapai kesepakatan
            penyelesaian perselisihan hubungan industrial melalui Mediasi sebagai berikut:
        </p>

        <div style="margin: 20px 0;">
            {!! nl2br(e($perjanjian->isi_kesepakatan)) !!}
        </div>

        <p style="text-align: justify;">
            Kesepakatan ini merupakan perjanjian bersama yang berlaku sejak ditandatangani diatas materai cukup.
        </p>

        <p style="text-align: justify;">
            Demikian Perjanjian Bersama ini dibuat dalam keadaan sadar tanpa paksaan dari pihak manapun, dan
            dilaksanakan dengan penuh rasa tanggung jawab yang didasari itikad baik.
        </p>

        <div class="signature-container">
            <div class="signature-row">
                <div class="signature-box">
                    <p>Pihak Pengusaha,</p>
                    @if ($perjanjian->signature_pengusaha)
                        <img src="{{ public_path('storage/signatures/' . $perjanjian->signature_pengusaha) }}"
                            alt="Tanda Tangan Pengusaha"
                            style="max-height: 80px; max-width: 200px; display: block; margin: 0 0 10px auto;">
                    @endif
                    <p>({{ $perjanjian->nama_pengusaha }})</p>
                </div>
                <div class="signature-box">
                    <p>Pihak Pekerja/Buruh/SP/SB,</p>
                    @if ($perjanjian->signature_pekerja)
                        <img src="{{ public_path('storage/signatures/' . $perjanjian->signature_pekerja) }}"
                            alt="Tanda Tangan Pekerja"
                            style="max-height: 80px; max-width: 200px; display: block; margin: 0 0 10px auto;">
                    @endif
                    <p>({{ $perjanjian->nama_pekerja }})</p>
                </div>
            </div>
        </div>

        <div class="mediator-signature">
            <p>Menyaksikan</p>
            <p>Mediator Hubungan Industrial,</p>
            @if ($perjanjian->signature_mediator)
                <img src="{{ public_path('storage/signatures/' . $perjanjian->signature_mediator) }}"
                    alt="Tanda Tangan Mediator"
                    style="max-height: 80px; max-width: 200px; display: block; margin: 0 0 10px auto;">
            @endif
            <p>({{ optional(optional($perjanjian->dokumenHI->risalah->first())->jadwal)->mediator->nama_mediator ?? '-' }})
            </p>
            <p>NIP. {{ optional(optional($perjanjian->dokumenHI->risalah->first())->jadwal)->mediator->nip ?? '-' }}
            </p>
        </div>
    </div>
</body>

</html>
