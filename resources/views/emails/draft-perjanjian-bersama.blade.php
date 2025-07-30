<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Draft Perjanjian Bersama</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #0000AB;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }

        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
        }

        .footer {
            background-color: #f5f5f5;
            padding: 15px;
            text-align: center;
            border-radius: 0 0 5px 5px;
            font-size: 12px;
            color: #666;
        }

        .info-box {
            background-color: #e3f2fd;
            border: 1px solid #2196f3;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }

        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }

        .button {
            display: inline-block;
            background-color: #0000AB;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }

        .button:hover {
            background-color: #000088;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Draft Perjanjian Bersama - Sistem Informasi Pengaduan dan Penyelesaian Hubungan Industrial Kab. Bungo</h1>
    </div>

    <div class="content">
        <h2>Draft Perjanjian Bersama</h2>

        <p>Kepada Yth.<br>
            <strong>{{ $pihak === 'pelapor' ? $pengaduan->pelapor->nama_pelapor : $pengaduan->terlapor->nama_terlapor }}</strong>
        </p>

        <div class="info-box">
            <h3>Informasi Pengaduan:</h3>
            <ul>
                <li><strong>Nomor Pengaduan:</strong> {{ $pengaduan->nomor_pengaduan }}</li>
                <li><strong>Perihal:</strong> {{ $pengaduan->perihal }}</li>
                <li><strong>Tanggal Laporan:</strong> {{ $pengaduan->tanggal_laporan->format('d/m/Y') }}</li>
                <li><strong>Mediator:</strong> {{ $pengaduan->mediator->nama_mediator ?? 'Belum ditugaskan' }}</li>
            </ul>
        </div>

        <p>Dengan hormat,</p>

        <p>Berdasarkan proses mediasi yang telah dilaksanakan, telah tercapai kesepakatan antara para pihak.
            Berikut adalah <strong>Draft Perjanjian Bersama</strong> yang telah disusun oleh mediator.</p>

        <div class="info-box">
            <h3>Akses Draft Perjanjian Bersama:</h3>
            <p>Untuk melihat dan mengunduh draft Perjanjian Bersama, silakan akses link berikut :</p>
            <a href="{{ route('dokumen.show-perjanjian-bersama', $perjanjianBersama->perjanjian_bersama_id) }}"
                class="button">
                Lihat Draft di Sistem
            </a>
        </div>

        <div class="warning">
            <h4>⚠️ Penting:</h4>
            <ul>
                <li>Draft Perjanjian Bersama ini adalah versi awal yang telah disusun oleh mediator</li>
                <li>Dokumen asli yang sudah ditandatangani di atas materai dipegang oleh para pihak</li>
            </ul>
        </div>

        <p>Apabila ada pertanyaan atau masukan, silakan menghubungi mediator yang menangani kasus ini.</p>

        <p>Terima kasih atas perhatian dan kerjasamanya.</p>

        <p>Salam,<br>
            <strong>Dinas Tenaga Kerja dan Transmigrasi Kabupaten Bungo</strong>
        </p>
    </div>

    <div class="footer">
        <p>Email ini dikirim otomatis oleh sistem</p>
        <p>Jangan membalas email ini. Untuk pertanyaan, silakan hubungi mediator yang menangani kasus.</p>
    </div>
</body>

</html>
