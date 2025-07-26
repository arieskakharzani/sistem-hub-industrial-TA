<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Pengaduan Baru</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #1d4ed8;
            color: white;
            padding: 20px;
            margin: -30px -30px 30px -30px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .section {
            margin: 25px 0;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #1d4ed8;
        }

        .section h3 {
            margin-top: 0;
            color: #1d4ed8;
            font-size: 18px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 15px 0;
        }

        .info-item {
            margin: 8px 0;
        }

        .info-label {
            font-weight: bold;
            color: #555;
            margin-bottom: 3px;
        }

        .info-value {
            color: #333;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #d97706;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e5e5;
            text-align: center;
            color: #666;
            font-size: 14px;
        }

        .button {
            display: inline-block;
            background-color: #1d4ed8;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
            font-weight: bold;
        }

        .alert {
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }

        .alert-info {
            background-color: #e0f2fe;
            border: 1px solid #0288d1;
            color: #01579b;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Notifikasi Pengaduan Baru</h1>
            <p style="margin: 5px 0 0 0;">Sistem Informasi Pengaduan dan Penyelesaian Hubungan Industrial Kab. Bungo</p>
        </div>

        {{-- Greeting --}}
        <p>Kepada Yth. <strong>{{ $pengaduan->nama_terlapor }}</strong>,</p>

        <div class="alert alert-info">
            <strong>Informasi:</strong> Anda telah dilaporkan oleh {{ $pengaduan->pelapor->nama_pelapor }} terkait
            perselisihan hubungan industrial.
        </div>

        {{-- Pengaduan Information --}}
        <div class="section">
            <h3>📝 Detail Pengaduan</h3>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nomor Pengaduan:</div>
                    <div class="info-value">#{{ $pengaduan->nomor_pengaduan }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Status:</div>
                    <div class="info-value">
                        <span class="status-badge status-pending">
                            PENDING
                        </span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tanggal Laporan:</div>
                    <div class="info-value">{{ $pengaduan->tanggal_laporan->format('d F Y') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Jenis Perselisihan:</div>
                    <div class="info-value">{{ $pengaduan->perihal }}</div>
                </div>
            </div>

            <div class="info-item" style="margin-top: 20px;">
                <div class="info-label">Pelapor:</div>
                <div class="info-value">{{ $pengaduan->pelapor->nama_pelapor }}</div>
            </div>
        </div>

        {{-- Action Button --}}
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('pengaduan.show-terlapor', $pengaduan) }}" class="button" style="color: white;">
                Lihat Detail Pengaduan
            </a>
        </div>

        <div class="section">
            <h3>ℹ️ Informasi Penting</h3>
            <p>Langkah selanjutnya:</p>
            <ol style="margin: 10px 0; padding-left: 20px;">
                <li>Login ke sistem menggunakan akun yang telah diberikan</li>
                <li>Periksa detail pengaduan secara lengkap</li>
                <li>Tunggu jadwal mediasi yang akan ditetapkan oleh mediator</li>
                <li>Siapkan dokumen-dokumen pendukung jika diperlukan</li>
            </ol>
        </div>

        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh sistem. Mohon tidak membalas email ini.</p>
            <p>Dinas Tenaga Kerja dan Transmigrasi Kabupaten Bungo<br>
                Telp: (0747) 21013 | Email: disnakertrans@bungokab.go.id</p>
        </div>
    </div>
</body>

</html>
