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
        <div style="font-size: 10pt; margin-top: 5px;">
            Nomor:
            {{ $anjuran->nomor_anjuran ?? 'A-' . date('Y') . '-' . str_pad($anjuran->id ?? '001', 3, '0', STR_PAD_LEFT) }}
        </div>
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

        <!-- Informasi Approval -->
        @if ($anjuran->status_approval === 'approved' || $anjuran->status_approval === 'published')
            <div style="margin: 20px 0; padding: 10px; border: 1px solid #000; background-color: #f9f9f9;">
                <p style="font-weight: bold; margin-bottom: 5px;">INFORMASI APPROVAL:</p>
                <p style="margin: 0; font-size: 11pt;">
                    Anjuran ini telah disetujui oleh Kepala Dinas Tenaga Kerja dan Transmigrasi
                    pada tanggal
                    {{ $anjuran->approved_by_kepala_dinas_at ? \Carbon\Carbon::parse($anjuran->approved_by_kepala_dinas_at)->translatedFormat('d F Y') : '-' }}
                    @if ($anjuran->notes_kepala_dinas)
                        dengan catatan: {{ $anjuran->notes_kepala_dinas }}
                    @endif
                </p>
            </div>
        @endif

        <!-- Pernyataan Resmi -->
        <div style="margin: 20px 0; padding: 10px; border: 1px solid #ccc; background-color: #f9f9f9;">
            <p style="font-weight: bold; margin-bottom: 5px; text-align: center; color: #666; font-style: italic;">
                PERNYATAAN RESMI</p>
            <p style="margin: 0; font-size: 10pt; text-align: justify; color: #666; font-style: italic;">
                Dokumen anjuran ini dikeluarkan secara resmi oleh Mediator Hubungan Industrial yang ditunjuk
                oleh Dinas Tenaga Kerja dan Transmigrasi Kabupaten Bungo. Anjuran ini telah melalui proses
                approval dan disetujui oleh Kepala Dinas Tenaga Kerja dan Transmigrasi, sehingga memiliki
                kekuatan hukum sesuai dengan ketentuan Undang-Undang Nomor 2 Tahun 2004 tentang Penyelesaian
                Perselisihan Hubungan Industrial.
            </p>
        </div>

        <!-- Legalisasi Digital -->
        @if ($anjuran->status_approval === 'approved' || $anjuran->status_approval === 'published')
            <div
                style="margin: 20px 0; padding: 15px; border: 2px solid #28a745; background-color: #d4edda; text-align: center;">
                <div style="font-size: 14pt; font-weight: bold; color: #155724; margin-bottom: 10px;">
                    ✓ DOKUMEN RESMI & TERAPPROVE
                </div>
                <div style="font-size: 10pt; color: #155724;">
                    <p style="margin: 2px 0;">Disetujui oleh:
                        {{ $anjuran->kepalaDinas->nama_kepala_dinas ?? 'Kepala Dinas' }}</p>
                    <p style="margin: 2px 0;">Tanggal:
                        {{ $anjuran->approved_by_kepala_dinas_at ? \Carbon\Carbon::parse($anjuran->approved_by_kepala_dinas_at)->translatedFormat('d F Y') : '-' }}
                    </p>
                    <p style="margin: 2px 0;">Dikeluarkan oleh:
                        {{ $anjuran->dokumenHI->pengaduan->mediator->nama_mediator ?? 'Mediator' }}</p>
                    <p style="margin: 2px 0; font-weight: bold;">Dinas Tenaga Kerja dan Transmigrasi Kabupaten Bungo</p>
                </div>
            </div>
        @endif
    </div>
</body>

</html>
