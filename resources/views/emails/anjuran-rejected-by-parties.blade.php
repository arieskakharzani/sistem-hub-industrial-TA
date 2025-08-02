<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anjuran Ditolak</title>
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

        .alert {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
        }

        .info-box {
            background-color: white;
            border: 1px solid #e5e7eb;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
        }

        .button {
            display: inline-block;
            background-color: #dc2626;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 0;
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
        <h1>🚨 Anjuran Ditolak</h1>
    </div>

    <div class="content">
        <p>Halo <strong>{{ $mediator->name }}</strong>,</p>

        <div class="alert">
            <strong>Anjuran yang Anda keluarkan telah ditolak oleh {{ $rejectedByText }}.</strong>
        </div>

        <div class="info-box">
            <h3>Detail Pengaduan:</h3>
            <ul>
                <li><strong>Nomor Pengaduan:</strong> {{ $pengaduan->nomor_pengaduan }}</li>
                <li><strong>Nomor Anjuran:</strong> {{ $anjuran->nomor_anjuran }}</li>
                <li><strong>Pelapor:</strong> {{ $pengaduan->pelapor->nama_pelapor }}</li>
                <li><strong>Terlapor:</strong> {{ $pengaduan->terlapor->nama_terlapor }}</li>
                <li><strong>Ditolak oleh:</strong> {{ $rejectedByText }}</li>
                <li><strong>Tanggal Penolakan:</strong> {{ now()->format('d/m/Y H:i') }}</li>
            </ul>
        </div>

        <div class="info-box">
            <h3>Langkah Selanjutnya:</h3>
            <p>Berdasarkan penolakan ini, Anda dapat:</p>
            <ol>
                <li>Mengakses halaman detail anjuran untuk melihat respon lengkap</li>
                <li>Menyelesaikan kasus dengan membuat laporan hasil penyelesaian</li>
                <li>Mengirim dokumen yang diperlukan ke para pihak</li>
            </ol>
        </div>

        <div style="text-align: center; margin: 20px 0;">
            <a href="{{ route('dokumen.anjuran.show', $anjuran->anjuran_id) }}" class="button">
                Lihat Detail Anjuran
            </a>
        </div>

        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh sistem SIPPPHI.</p>
            <p>Jika ada pertanyaan, silakan hubungi administrator sistem.</p>
        </div>
    </div>
</body>

</html>
