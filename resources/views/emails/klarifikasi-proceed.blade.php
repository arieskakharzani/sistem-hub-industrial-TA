<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klarifikasi Akan Dilanjutkan</title>
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

        .container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }

        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 16px;
        }

        .content {
            padding: 30px;
        }

        .info-box {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }

        .info-box h3 {
            margin: 0 0 15px 0;
            color: #495057;
            font-size: 18px;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .details-table th,
        .details-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }

        .details-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
            width: 30%;
        }

        .details-table td {
            color: #6c757d;
        }

        .alert {
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }

        .alert-info {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }

        .alert-warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }

        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
        }

        .button:hover {
            opacity: 0.9;
        }

        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }

        .highlight {
            background-color: #fff3cd;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>📋 Klarifikasi Akan Dilanjutkan</h1>
            <p>Notifikasi untuk Mediator</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Halo <strong>{{ $notifiable->name }}</strong>,</p>

            <p>Jadwal klarifikasi akan dilanjutkan meskipun ada pihak yang tidak dapat hadir.</p>

            <div class="info-box">
                <h3>📋 Detail Jadwal Klarifikasi</h3>
                <table class="details-table">
                    <tr>
                        <th>Nomor Jadwal</th>
                        <td><strong>{{ $jadwal->nomor_jadwal }}</strong></td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>{{ $jadwal->tanggal->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th>Waktu</th>
                        <td>{{ $jadwal->waktu->format('H:i') }} WIB</td>
                    </tr>
                    <tr>
                        <th>Tempat</th>
                        <td>{{ $jadwal->tempat }}</td>
                    </tr>
                    <tr>
                        <th>Pihak yang tidak hadir</th>
                        <td><span class="highlight">{{ $absentPartyLabel }}</span></td>
                    </tr>
                    @if ($reason)
                        <tr>
                            <th>Alasan</th>
                            <td>{{ $reason }}</td>
                        </tr>
                    @endif
                </table>
            </div>

            <div class="alert alert-info">
                <strong>📋 Informasi Penting:</strong><br>
                • Proses klarifikasi tetap akan dilanjutkan sesuai jadwal<br>
                • Setelah klarifikasi selesai, Anda dapat melanjutkan ke tahap mediasi<br>
                • Tidak perlu melakukan reschedule untuk jadwal ini<br>
                • Status jadwal tetap <span class="highlight">Dijadwalkan</span>
            </div>

            <div class="alert alert-warning">
                <strong>⚠️ Catatan untuk Mediator:</strong><br>
                Anda dapat melanjutkan proses klarifikasi meskipun ada pihak yang tidak hadir.
                Setelah klarifikasi selesai, lanjutkan ke tahap mediasi sesuai prosedur.
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/jadwal/' . $jadwal->jadwal_id) }}" class="button">
                    Lihat Detail Jadwal
                </a>
            </div>

            <p>Terima kasih atas perhatian Anda.</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh sistem SIPPPHI.</p>
            <p>Jika Anda memiliki pertanyaan, silakan hubungi administrator sistem.</p>
        </div>
    </div>
</body>

</html>
