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
            margin: 0;
            padding: 0;
        }

        .page {
            padding: 40px 60px;
            margin-bottom: 50px;
            /* Memberikan ruang untuk footer fixed */
            position: relative;
            min-height: auto;
        }



        /* Header */
        .header {
            text-align: center;
            margin: 100px 0 10px 0;
            /* Kembali ke margin normal */
            font-weight: bold;
            font-size: 16px;
            position: relative;
        }

        /* Content */
        .content {
            margin: 0;
            text-align: justify;
            position: relative;
        }



        .document-info {
            margin: 5px 0;
        }

        .document-info p {
            margin: 3px 0;
            font-size: 12px;
        }

        .salutation {
            margin: 5px 0;
        }

        .recipients {
            margin-left: 20px;
            margin-bottom: 20px;
        }

        .recipients p {
            margin: 3px 0;
            font-size: 12px;
        }

        .section {
            margin: 5px 0;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 12px;
        }

        .numbered-list {
            margin-left: 20px;
            text-align: justify;
            font-size: 12px;
        }

        .text-center {
            text-align: center;
        }

        .text-bold {
            font-weight: bold;
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
            <div class="text-center text-bold">ANJURAN</div>
        </div>

        <div class="content">
            <div class="document-info">
                <p style="text-align: right;">Muara Bungo,
                    {{ \Carbon\Carbon::parse($anjuran->created_at)->translatedFormat('d F Y') }}</p>
                <p>Nomor :
                    {{ $anjuran->nomor_anjuran ?? 'A-' . date('Y') . '-' . str_pad($anjuran->id ?? '001', 3, '0', STR_PAD_LEFT) }}
                </p>
                <p>Lampiran : .............................</p>
                <p>Hal : Anjuran</p>
            </div>

            <div class="salutation">
                <p style="font-size: 12px; margin-bottom: 5px;">Yth.</p>
            </div>

            <div class="recipients">
                <p style="font-size: 12px; margin-bottom: 3px;">1. Sdr. {{ $anjuran->nama_pengusaha }} (Pengusaha)</p>
                <p style="font-size: 12px; margin-bottom: 3px;">2. Sdr. {{ $anjuran->nama_pekerja }}
                    (Pekerja/Buruh/SP/SB)</p>
            </div>

            <p style="text-align: justify; margin-bottom: 10px; font-size: 12px;">
                Sehubungan dengan penyelesaian perselisihan hubungan industrial antara Pengusaha dengan
                Pekerja/Buruh/SP/SB
                yang telah dilaksanakan melalui mediasi tidak tercapai kesepakatan dan sesuai ketentuan Pasal 13 ayat
                (2)
                Undang-Undang Nomor 2 Tahun 2004 tentang Penyelesaian Perselisihan Hubungan Industrial, maka Mediator
                Hubungan Industrial mengeluarkan anjuran.
            </p>
            <p style="text-align: justify; margin-bottom: 10px; font-size: 12px;">Sebagai bahan pertimbangan, Mediator
                perlu mendengar
                keterangan kedua belah pihak yang berselisih sebagai
                berikut:</p>

            <div class="section">
                <div class="section-title">A. Keterangan pihak Pekerja/Buruh/Serikat Pekerja/Serikat Buruh:</div>
                <div class="numbered-list" style="text-align: justify; margin-bottom: 10px;">
                    {!! nl2br(e($anjuran->keterangan_pekerja)) !!}
                    <p>dan seterusnya.</p>
                </div>
            </div>

            <div class="section">
                <div class="section-title">B. Keterangan pihak Pengusaha:</div>
                <div class="numbered-list" style="margin-bottom: 10px;">
                    {!! nl2br(e($anjuran->keterangan_pengusaha)) !!}
                    <p>dan seterusnya.</p>
                </div>
            </div>

            <div class="section">
                <div class="section-title">C. Pertimbangan Hukum dan Kesimpulan Mediator:</div>
                <div class="numbered-list" style="margin-bottom: 10px;">
                    {!! nl2br(e($anjuran->pertimbangan_hukum)) !!}
                </div>
            </div>

            <p style="text-align: justify; margin-bottom: 10px; font-size: 12px;">Berdasarkan hal-hal tersebut diatas
                dan
                guna menyelesaikan masalah dimaksud, dengan ini Mediator:</p>

            <div class="section text-center" style="margin-bottom: 10px;">
                <div class="section-title text-center">MENGANJURKAN:</div>
                <div class="numbered-list">
                    {!! nl2br(e($anjuran->isi_anjuran)) !!}
                    <br>
                    <p>Dan agar kedua belah pihak memberikan jawaban atas anjuran tersebut selambat-lambatnya dalam
                        jangka
                        waktu
                        10 (sepuluh) hari kerja setelah menerima surat anjuran ini.</p>
                </div>
            </div>

            <p style="text-align: justify; margin-bottom: 10px; font-size: 12px;">Demikian untuk diketahui dan menjadi
                perhatian.</p>
        </div>

        <!-- Footer menggunakan komponen -->
        @include('components.pdf.footer', [
            'footerText' =>
                'Anjuran ini dikeluarkan oleh Mediator Hubungan Industrial dan disetujui oleh Kepala Dinas Tenaga Kerja dan Transmigrasi Kabupaten Bungo pada',
            'approvalDate' =>
                $anjuran->status_approval === 'published'
                    ? \Carbon\Carbon::parse($anjuran->published_at)->translatedFormat('d F Y')
                    : \Carbon\Carbon::parse($anjuran->created_at)->translatedFormat('d F Y'),
            'approvalTime' =>
                $anjuran->status_approval === 'published'
                    ? \Carbon\Carbon::parse($anjuran->published_at)->format('H:i')
                    : \Carbon\Carbon::parse($anjuran->created_at)->format('H:i'),
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
