<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaduan Baru Masuk</title>
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
            content: "🚨";
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

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            background-color: #ffeaa7;
            color: #d63031;
        }

        .priority-high {
            background-color: #ff7675;
            color: white;
        }

        .button-container {
            text-align: center;
            margin: 30px 0;
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

        .summary-box {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 20px;
            border-radius: 6px;
            margin: 25px 0;
        }

        .summary-title {
            font-weight: 600;
            color: #856404;
            margin-bottom: 10px;
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

        .system-info {
            background-color: #e3f2fd;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            font-size: 14px;
            color: #1565c0;
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
            <h1>📋 Pengaduan Baru</h1>
            <p>Sistem Informasi Pengaduan & Penyelesaian Perselisihan Hubungan Industrial</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Halo <strong>{{ $mediator->getName() }}</strong>,
            </div>

            <div class="notification-card">
                <div class="notification-title">
                    Pengaduan Baru Masuk untuk Ditangani
                </div>
                <p style="margin: 0; color: #555;">
                    Ada pengaduan baru yang memerlukan perhatian dan tindak lanjut dari mediator.
                    Silakan review dan ambil tindakan yang diperlukan.
                </p>
            </div>

            <!-- Detail Pengaduan -->
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">📂 Perihal: </div>
                    <div class="detail-value"><strong>{{ $pengaduan->perihal }}</strong></div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">👤 Pelapor: </div>
                    <div class="detail-value">{{ $pengaduan->pelapor->nama_pelapor }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">👨‍💼 Terlapor: </div>
                    <div class="detail-value">{{ $pengaduan->nama_terlapor }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">📅 Tanggal Laporan: </div>
                    <div class="detail-value">{{ $pengaduan->tanggal_laporan->format('d F Y') }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">⚡ Status: </div>
                    <div class="detail-value">
                        <span class="status-badge">{{ strtoupper($pengaduan->status) }}</span>
                    </div>
                </div>
            </div>

            <!-- Summary Box -->
            <div class="summary-box">
                <div class="summary-title">📝 Ringkasan Kasus</div>
                <p style="margin: 0;">{{ Str::limit($pengaduan->narasi_kasus, 200) }}</p>
                @if (strlen($pengaduan->narasi_kasus) > 200)
                    <small style="color: #856404; font-style: italic;">...lihat detail lengkap di sistem</small>
                @endif
            </div>

            <!-- Action Button -->
            <div class="button-container">
                <a href="{{ $actionUrl }}" class="cta-button">
                    Lihat Detail Pengaduan
                </a>
            </div>

            <!-- Informasi Sistem -->
            <div class="system-info">
                <strong>ℹ️ Informasi Sistem:</strong><br>
                • Pengaduan ini telah masuk ke sistem pada {{ $pengaduan->created_at->format('d F Y, H:i') }} WIB<br>
                • Status saat ini: <strong>{{ $pengaduan->status_text }}</strong><br>
                • Perlu segera ditindaklanjuti untuk memastikan penyelesaian yang optimal
            </div>

            <p style="margin-top: 30px; color: #555;">
                Terima kasih atas perhatian dan kerja sama Anda dalam menangani pengaduan ini.
                Silakan login ke sistem untuk melihat detail lengkap dan mengambil tindakan yang diperlukan.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Sistem Informasi Penyelesaian Perselisihan Hubungan Industrial</strong></p>
            <p>Email ini dikirim secara otomatis oleh sistem.</p>
            <p style="font-size: 12px; color: #999;">
                Diterima pada {{ now()->format('d F Y, H:i') }} WIB
            </p>
        </div>
    </div>
</body>

</html>
