<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Anjuran</title>
    <style>
        body {
            font-family: Times New Roman, serif;
            line-height: 1.5;
            font-size: 12pt;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
            font-weight: bold;
        }

        .content {
            margin: 0 30px;
        }

        .title {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .section {
            margin: 20px 0;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .numbered-list {
            margin-left: 20px;
            text-align: justify;
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

        .dotted-line {
            display: inline-block;
            border-bottom: 1px dotted #000;
            width: 250px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="text-center text-bold">ANJURAN</div>
    </div>

    <div class="content">
        <div style="text-align: right; margin-bottom: 0px;">
            Muara Bungo,
            {{ \Carbon\Carbon::parse($anjuran->created_at)->translatedFormat('d F Y') }}
        </div>

        <div style="margin-bottom: 20px;">
            <p>Yth.</p>
            <div style="margin-left: 20px;">
                <p>1. Sdr. {{ $anjuran->nama_pengusaha }} (Pengusaha)</p>
                <p>2. Sdr. {{ $anjuran->nama_pekerja }} (Pekerja/Buruh/SP/SB)</p>
            </div>
            <p>di tempat</p>
        </div>

        <p style="text-align: justify;">
            Sehubungan dengan penyelesaian perselisihan hubungan industrial antara Pengusaha dengan
            Pekerja/Buruh/SP/SB
            yang telah dilaksanakan melalui mediasi tidak tercapai kesepakatan dan sesuai ketentuan Pasal 13 ayat (2)
            Undang-Undang Nomor 2 Tahun 2004 tentang Penyelesaian Perselisihan Hubungan Industrial, maka Mediator
            Hubungan Industrial mengeluarkan anjuran.
        </p>

        <p>Sebagai bahan pertimbangan, Mediator perlu mendengar keterangan kedua belah pihak yang berselisih sebagai
            berikut:</p>

        <div class="section">
            <div class="section-title">A. Keterangan pihak Pekerja/Buruh/Serikat Pekerja/Serikat Buruh:</div>
            <div class="numbered-list" style="text-align: justify;">
                {!! nl2br(e($anjuran->keterangan_pekerja)) !!}
                <br>
                <p>dan seterusnya.</p>
            </div>

        </div>

        <div class="section">
            <div class="section-title">B. Keterangan pihak Pengusaha:</div>
            <div class="numbered-list">
                {!! nl2br(e($anjuran->keterangan_pengusaha)) !!}
                <br>
                <p>dan seterusnya.</p>
            </div>

        </div>

        <div class="section">
            <div class="section-title">C. Pertimbangan Hukum dan Kesimpulan Mediator:</div>
            <div class="numbered-list">
                {!! nl2br(e($anjuran->pertimbangan_hukum)) !!}
            </div>
        </div>

        <p>Berdasarkan hal-hal tersebut diatas dan guna menyelesaikan masalah dimaksud, dengan ini Mediator:</p>

        <div class="section text-center">
            <div class="section-title text-center">MENGANJURKAN:</div>
            <div class="numbered-list">
                {!! nl2br(e($anjuran->isi_anjuran)) !!}
                <br>
                <p>Dan agar kedua belah pihak memberikan jawaban atas anjuran tersebut selambat-lambatnya dalam jangka
                    waktu
                    10 (sepuluh) hari kerja setelah menerima surat anjuran ini.</p>
            </div>
        </div>


        <p>Demikian untuk diketahui dan menjadi perhatian.</p>

        <div class="signature-container">
            <div class="signature-row">
                <div class="signature-box">
                    <p>Mengetahui</p>
                    <p>Kepala Dinas,</p>
                    <div class="signature-line"></div>
                    <p>(...................)</p>
                    <p>NIP. ---------------------</p>
                </div>
                <div class="signature-box">
                    <p>Mediator Hubungan Industrial,</p>
                    <div class="signature-line"></div>
                    <p>({{ $anjuran->dokumenHI->risalah->first()->jadwal->mediator->nama_mediator }})</p>
                    <p>NIP. {{ $anjuran->dokumenHI->risalah->first()->jadwal->mediator->nip }}</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
