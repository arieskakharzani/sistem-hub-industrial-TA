<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anjuran Ditolak</title>
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
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
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
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
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
            content: "❌";
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

        .reason-box {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
        }

        .action-button {
            display: inline-block;
            background-color: #ef4444;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin-top: 20px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <h1>Anjuran Ditolak</h1>
            <p>Sistem Informasi Pengaduan & Penyelesaian Perselisihan Hubungan Industrial</p>
        </div>

        <div class="content">
            <div class="greeting">
                Yth. {{ $anjuran->mediator->nama_mediator }},
            </div>

            <div class="notification-card">
                <div class="notification-title">
                    Anjuran Ditolak Kepala Dinas
                </div>
                <p>Anjuran yang Anda buat telah ditolak oleh kepala dinas. Silakan review dan perbaiki sesuai dengan
                    catatan yang diberikan.</p>
            </div>

            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">Nomor Anjuran:</div>
                    <div class="detail-value">{{ $anjuran->nomor_anjuran }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Tanggal Rejection:</div>
                    <div class="detail-value">{{ $anjuran->rejected_by_kepala_dinas_at->format('d/m/Y H:i') }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Pengaduan:</div>
                    <div class="detail-value">{{ $anjuran->dokumenHI->pengaduan->nomor_pengaduan }}</div>
                </div>
            </div>

            <div class="reason-box">
                <h4 style="margin: 0 0 10px 0; color: #dc2626; font-weight: 600;">Alasan Penolakan:</h4>
                <p style="margin: 0; color: #333;">{{ $reason }}</p>
            </div>

            <p>Silakan login ke sistem untuk memperbaiki anjuran sesuai dengan catatan yang diberikan.</p>

            <a href="{{ url('/anjuran/' . $anjuran->anjuran_id . '/edit') }}" class="action-button">
                Edit Anjuran
            </a>
        </div>

        <div class="footer">
            <p>Email ini dikirim otomatis oleh sistem SIPPPHI Kabupaten Bungo</p>
            <p>Jika ada pertanyaan, silakan hubungi administrator sistem</p>
        </div>
    </div>
</body>

</html>
