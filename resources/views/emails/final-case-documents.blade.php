<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumen Final Kasus</title>
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
            background-color: #dc2626;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .content {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 0 0 8px 8px;
        }

        .info-box {
            background-color: white;
            border: 1px solid #e5e7eb;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
        }

        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 14px;
            color: #6b7280;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>📋 Dokumen Final Kasus</h1>
    </div>

    <div class="content">
        <p>Halo,</p>

        <div class="info-box">
            <h3>Kasus telah diselesaikan</h3>
            <p>Kasus perselisihan hubungan industrial telah diselesaikan dengan anjuran yang ditolak oleh para pihak.
            </p>
        </div>

        <div class="info-box">
            <h3>Detail Pengaduan:</h3>
            <ul>
                <li><strong>Nomor Pengaduan:</strong> {{ $pengaduan->nomor_pengaduan }}</li>
                <li><strong>Nomor Anjuran:</strong> {{ $anjuran->nomor_anjuran }}</li>
                <li><strong>Pelapor:</strong> {{ $pengaduan->pelapor->nama_pelapor }}</li>
                <li><strong>Terlapor:</strong> {{ $pengaduan->terlapor->nama_terlapor }}</li>
                <li><strong>Status:</strong> Selesai - Anjuran Ditolak</li>
            </ul>
        </div>

        <div class="info-box">
            <h3>Dokumen yang Dilampirkan:</h3>
            <ul>
                <li>Laporan Hasil Mediasi (PDF)</li>
                <li>Anjuran (PDF)</li>
            </ul>
            <p><strong>Catatan:</strong> Dokumen-dokumen ini dapat digunakan untuk melanjutkan ke tahap pengadilan
                hubungan industrial.</p>
        </div>

        <div class="info-box">
            <h3>Langkah Selanjutnya:</h3>
            <p>Berdasarkan penolakan anjuran oleh para pihak, kasus ini dapat dilanjutkan ke:</p>
            <ol>
                <li>Pengadilan Hubungan Industrial</li>
                <li>Mahkamah Agung (jika diperlukan)</li>
            </ol>
        </div>

        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh sistem.</p>
            <p>Jika ada pertanyaan, silakan hubungi mediator.</p>
        </div>
    </div>
</body>

</html>
