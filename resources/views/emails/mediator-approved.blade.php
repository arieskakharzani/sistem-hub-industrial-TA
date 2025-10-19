<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Mediator Disetujui</title>
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

        .success-icon {
            color: #10B981;
            font-size: 48px;
            margin-bottom: 20px;
        }

        .credentials-box {
            background-color: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .credentials-box h3 {
            color: #495057;
            margin-top: 0;
        }

        .credential-item {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 10px;
            background-color: white;
            border-radius: 5px;
            border-left: 4px solid #3B82F6;
        }

        .credential-label {
            font-weight: bold;
            color: #495057;
        }

        .credential-value {
            font-family: monospace;
            color: #3B82F6;
            font-weight: bold;
        }

        .warning-box {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }

        .warning-box h4 {
            color: #856404;
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
            <div class="success-icon">✅</div>
            <h1 style="color: #10B981; margin: 0;">Akun Mediator Disetujui!</h1>
            <p style="color: #6c757d; margin: 10px 0 0 0;">Sistem Informasi Pengaduan dan Penyelesaian Hubungan
                Industrial Kab. Bungo</p>
        </div>

        <p>Selamat <strong>{{ $notifiable->name }}</strong>!</p>

        <p>Registrasi mediator Anda telah <strong>disetujui</strong> oleh Kepala Dinas. Akun Anda sekarang sudah aktif
            dan dapat digunakan untuk mengakses sistem SIPPPHI.</p>

        <div class="credentials-box">
            <h3>🔐 Kredensial Login Anda</h3>
            <div class="credential-item">
                <span class="credential-label">Email:</span>
                <span class="credential-value">{{ $notifiable->email }}</span>
            </div>
            <div class="credential-item">
                <span class="credential-label">Password:</span>
                <span class="credential-value">{{ $password }}</span>
            </div>
        </div>

        <div class="warning-box">
            <h4>⚠️ PENTING!</h4>
            <p>Untuk keamanan akun Anda, silakan <strong>ganti password</strong> setelah login pertama kali. Gunakan
                password yang kuat dan mudah diingat.</p>
        </div>

        <div style="text-align: center;">
            <a href="{{ $actionUrl }}" class="btn">🚀 Login ke Sistem</a>
        </div>

        <h3>📋 Informasi Mediator:</h3>
        <ul>
            <li><strong>Nama:</strong> {{ $mediator->nama_mediator }}</li>
            <li><strong>NIP:</strong> {{ $mediator->nip }}</li>
            <li><strong>Status:</strong> <span style="color: #10B981; font-weight: bold;">Aktif</span></li>
            <li><strong>Disetujui pada:</strong> {{ $mediator->approved_at->format('d F Y, H:i') }}</li>
        </ul>

        <p>Terima kasih telah bergabung dengan sistem SIPPPHI. Jika Anda mengalami masalah atau memerlukan bantuan,
            silakan hubungi admin sistem.</p>

        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh sistem SIPPPHI.</p>
            <p>Jangan balas email ini karena tidak akan diproses.</p>
        </div>
    </div>
</body>

</html>
