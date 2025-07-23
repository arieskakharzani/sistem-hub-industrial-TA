<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Tanda Tangan Dokumen</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }

        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 40px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }

        .header p {
            margin: 8px 0 0 0;
            opacity: 0.9;
            font-size: 16px;
        }

        .content {
            padding: 40px;
        }

        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .notification-card {
            background-color: #f8f9ff;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 25px 0;
            border-radius: 0 6px 6px 0;
        }

        .notification-title {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .notification-title::before {
            content: "✍️";
            margin-right: 8px;
            font-size: 18px;
        }

        .detail-grid {
            display: grid;
            gap: 12px;
            margin: 20px 0;
        }

        .detail-item {
            display: flex;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .detail-label {
            font-weight: 600;
            color: #555;
            min-width: 140px;
            flex-shrink: 0;
        }

        .detail-value {
            color: #333;
            flex: 1;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            transition: transform 0.2s ease;
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .footer {
            background-color: #f8f9fa;
            padding: 25px 40px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }

        .footer p {
            margin: 5px 0;
            color: #6c757d;
            font-size: 14px;
        }

        @media (max-width: 600px) {
            body {
                padding: 10px;
            }

            .content,
            .header {
                padding: 20px;
            }

            .detail-item {
                flex-direction: column;
            }

            .detail-label {
                min-width: auto;
                margin-bottom: 4px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>✍️ Permintaan Tanda Tangan Dokumen</h1>
            <p>Sistem Informasi Pengaduan & Penyelesaian Perselisihan Hubungan Industrial</p>
        </div>
        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Halo <strong>{{ $user->getName() }}</strong>,
            </div>
            <div class="notification-card">
                <div class="notification-title">
                    Dokumen Memerlukan Tanda Tangan Anda
                </div>
                <p style="margin: 0; color: #555;">
                    Terdapat dokumen <strong>{{ $documentTypeLabel }}</strong> yang memerlukan tanda tangan Anda.
                    Silakan login ke sistem dan lakukan penandatanganan secara digital.
                </p>
            </div>
            <!-- Detail Dokumen -->
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">Jenis Dokumen:</div>
                    <div class="detail-value">{{ $documentTypeLabel }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Perihal:</div>
                    <div class="detail-value">{{ $perihal }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Nama Pekerja:</div>
                    <div class="detail-value">{{ $namaPekerja }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Nama Pengusaha:</div>
                    <div class="detail-value">{{ $namaPengusaha }}</div>
                </div>
            </div>
            <!-- Action Button -->
            <div class="button-container">
                <a href="{{ $actionUrl }}" class="cta-button">
                    Tanda Tangani Sekarang
                </a>
            </div>
            <p style="margin-top: 30px; color: #555;">
                Terima kasih atas perhatian dan kerja sama Anda.
            </p>
        </div>
        <!-- Footer -->
        <div class="footer">
            <p><strong>Sistem Informasi Pengaduan dan Penyelesaian Perselisihan Hubungan Industrial</strong></p>
            <p>Email ini dikirim secara otomatis oleh sistem.</p>
            <p style="font-size: 12px; color: #999;">
                Diterima pada {{ now()->format('d F Y, H:i') }} WIB
            </p>
        </div>
    </div>
</body>

</html>
