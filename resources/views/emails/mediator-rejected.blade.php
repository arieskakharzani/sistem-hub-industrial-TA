<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Mediator Ditolak</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #3B82F6, #10B981);
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }

        .rejection-icon {
            color: #dc3545;
            font-size: 48px;
            margin-bottom: 20px;
        }

        .rejection-box {
            background-color: #f8d7da;
            border: 2px solid #f5c6cb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .rejection-box h3 {
            color: #721c24;
            margin-top: 0;
        }

        .reason-box {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }

        .reason-box h4 {
            color: #856404;
            margin-top: 0;
        }

        .steps-box {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }

        .steps-box h4 {
            color: #0c5460;
            margin-top: 0;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #3B82F6, #2563EB);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin: 20px 0;
        }

        .btn:hover {
            background: linear-gradient(135deg, #2563EB, #1D4ED8);
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo">SIPPPHI</div>
            <div class="rejection-icon">❌</div>
            <h1 style="color: #dc3545; margin: 0;">Registrasi Mediator Ditolak</h1>
            <p style="color: #6c757d; margin: 10px 0 0 0;">Sistem Informasi Pengaduan dan Penyelesaian Hubungan
                Industrial Kab. Bungo</p>
        </div>

        <p>Mohon maaf <strong>{{ $mediator->nama_mediator }}</strong>,</p>

        <div class="rejection-box">
            <h3>📋 Status Registrasi</h3>
            <p>Registrasi mediator Anda telah <strong>ditolak</strong> oleh Kepala Dinas setelah melakukan review
                terhadap dokumen yang Anda upload.</p>
        </div>

        <div class="reason-box">
            <h4>📝 Alasan Penolakan:</h4>
            <p><strong>{{ $mediator->rejection_reason ?? 'Tidak ada alasan yang diberikan.' }}</strong></p>
        </div>

        <div class="steps-box">
            <h4>🔄 Langkah Selanjutnya:</h4>
            <ol>
                <li><strong>Perbaiki dokumen</strong> sesuai dengan alasan penolakan yang diberikan</li>
                <li><strong>Pastikan SK yang diupload</strong> adalah dokumen resmi yang ditandatangani oleh Menteri
                </li>
                <li><strong>Registrasi ulang</strong> dengan dokumen yang sudah diperbaiki</li>
                <li><strong>Hubungi admin</strong> jika memerlukan bantuan lebih lanjut</li>
            </ol>
        </div>

        <div style="text-align: center;">
            <a href="{{ $registerUrl }}" class="btn">🔄 Registrasi Ulang</a>
        </div>

        <h3>📋 Detail Registrasi:</h3>
        <ul>
            <li><strong>Nama:</strong> {{ $mediator->nama_mediator }}</li>
            <li><strong>NIP:</strong> {{ $mediator->nip }}</li>
            <li><strong>Email:</strong> {{ $mediator->user->email }}</li>
            <li><strong>Status:</strong> <span style="color: #dc3545; font-weight: bold;">Ditolak</span></li>
            <li><strong>Ditolak pada:</strong>
                {{ $mediator->rejection_date ? $mediator->rejection_date->format('d F Y, H:i') : now()->format('d F Y, H:i') }}
            </li>
        </ul>

        <p>Terima kasih atas pengertian Anda. Kami berharap Anda dapat memperbaiki dokumen dan melakukan registrasi
            ulang.</p>

        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh sistem SIPPPHI.</p>
            <p>Jika Anda memerlukan bantuan, silakan hubungi admin sistem.</p>
        </div>
    </div>
</body>

</html>
