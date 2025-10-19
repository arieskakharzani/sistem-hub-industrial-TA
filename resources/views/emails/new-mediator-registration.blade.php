<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mediator Baru Mendaftar - Perlu Approval</title>
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

        .notification-icon {
            color: #ffc107;
            font-size: 48px;
            margin-bottom: 20px;
        }

        .mediator-info {
            background-color: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .mediator-info h3 {
            color: #495057;
            margin-top: 0;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 10px;
            background-color: white;
            border-radius: 5px;
            border-left: 4px solid #3B82F6;
        }

        .info-label {
            font-weight: bold;
            color: #495057;
        }

        .info-value {
            color: #3B82F6;
            font-weight: bold;
        }

        .action-box {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }

        .action-box h4 {
            color: #0c5460;
            margin-top: 0;
        }

        .steps-box {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }

        .steps-box h4 {
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
            <div class="notification-icon">🔔</div>
            <h1 style="color: #ffc107; margin: 0;">Mediator Baru Mendaftar!</h1>
            <p style="color: #6c757d; margin: 10px 0 0 0;">Sistem Informasi Pengaduan dan Penyelesaian Hubungan
                Industrial Kab. Bungo</p>
        </div>

        <p>Kepala Dinas yang terhormat,</p>

        <p>Seorang mediator baru telah mendaftar ke sistem SIPPPHI dan <strong>memerlukan persetujuan</strong> dari Anda
            untuk mengaktifkan akunnya.</p>

        <div class="mediator-info">
            <h3>👤 Detail Mediator yang Mendaftar</h3>
            <div class="info-item">
                <span class="info-label">Nama Lengkap:</span>
                <span class="info-value">{{ $mediator->nama_mediator }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">NIP:</span>
                <span class="info-value">{{ $mediator->nip }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $mediator->user->email }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Tanggal Registrasi:</span>
                <span class="info-value">{{ $mediator->created_at->format('d F Y, H:i') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">File SK:</span>
                <span class="info-value">{{ $mediator->sk_file_name }}</span>
            </div>
        </div>

        <div class="action-box">
            <h4>📋 Langkah yang Perlu Dilakukan:</h4>
            <ol>
                <li><strong>Login ke sistem</strong> sebagai Kepala Dinas</li>
                <li><strong>Akses menu "Approval Mediator"</strong></li>
                <li><strong>Review dokumen SK</strong> yang diupload oleh mediator</li>
                <li><strong>Verifikasi keaslian</strong> dokumen dan data mediator</li>
                <li><strong>Approve atau Reject</strong> registrasi mediator</li>
            </ol>
        </div>

        <div class="steps-box">
            <h4>⚠️ PENTING!</h4>
            <p>Pastikan untuk <strong>memverifikasi keaslian dokumen SK</strong> sebelum memberikan persetujuan. Dokumen
                SK harus merupakan dokumen resmi yang ditandatangani oleh Menteri.</p>
        </div>

        <div style="text-align: center;">
            <a href="{{ $actionUrl }}" class="btn">🔍 Review Registrasi</a>
        </div>

        <p>Terima kasih atas perhatian dan kerjasama Anda dalam menjaga kualitas mediator di sistem SIPPPHI.</p>

        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh sistem SIPPPHI.</p>
            <p>Jangan balas email ini karena tidak akan diproses.</p>
        </div>
    </div>
</body>

</html>
