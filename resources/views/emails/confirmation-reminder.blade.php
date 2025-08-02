<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reminder Konfirmasi Kehadiran</title>
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
            background: linear-gradient(135deg, #0000AB, #3333CC);
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }

        .urgent-badge {
            background: #fef3c7;
            color: #92400e;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            margin: 10px 0;
            text-align: center;
        }

        .info-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #f59e0b;
        }

        .button {
            display: inline-block;
            background: #0000AB;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 0;
        }

        .footer {
            text-align: center;
            color: #666;
            font-size: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .details-table th,
        .details-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .details-table th {
            background: #f4f4f4;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="urgent-badge">⏰ URGENT - REMINDER KONFIRMASI</div>
        <h1>Reminder Konfirmasi Kehadiran</h1>
        <p>Sistem Informasi Pengaduan dan Penyelesaian Hubungan Industrial</p>
        <p>Dinas Tenaga Kerja dan Transmigrasi Kabupaten Bungo</p>
    </div>

    <div class="content">
        <h2>Kepada Yth. {{ $user->getName() ?? 'Pengguna' }},</h2>

        <div class="info-box">
            <h3>⚠️ PENTING: Deadline Konfirmasi Mendekati</h3>
            <p>Anda belum mengkonfirmasi kehadiran untuk jadwal berikut:</p>
        </div>

        <div class="info-box">
            <h3>📋 Detail Pengaduan</h3>
            <table class="details-table">
                <tr>
                    <th>No. Pengaduan</th>
                    <td>{{ $jadwal->pengaduan->nomor_pengaduan }}</td>
                </tr>
                <tr>
                    <th>Perihal</th>
                    <td>{{ $jadwal->pengaduan->perihal }}</td>
                </tr>
                <tr>
                    <th>Pelapor</th>
                    <td>{{ $jadwal->pengaduan->pelapor->nama_pelapor ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Terlapor</th>
                    <td>{{ $jadwal->pengaduan->terlapor->nama_terlapor ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <div class="info-box">
            <h3>🗓️ Detail Jadwal {{ $jadwal->getJenisJadwalLabel() }}</h3>
            <table class="details-table">
                <tr>
                    <th>Jenis Jadwal</th>
                    <td><strong>{{ $jadwal->getJenisJadwalLabel() }}</strong></td>
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
            </table>
        </div>

        <div class="info-box">
            <h3>⏰ Deadline Konfirmasi</h3>
            <p><strong>Batas waktu konfirmasi:</strong> {{ $deadline->format('d F Y, H:i') }} WIB</p>
            <p><strong>Status konfirmasi Anda saat ini:</strong>
                <span style="color: #dc2626; font-weight: bold;">BELUM DIKONFIRMASI</span>
            </p>

            <div
                style="background: #fef3c7; border: 1px solid #f59e0b; padding: 15px; border-radius: 6px; margin: 15px 0;">
                <p style="margin: 0; color: #92400e;">
                    <strong>⚠️ PERHATIAN:</strong> Jika Anda tidak mengkonfirmasi kehadiran sebelum deadline,
                    jadwal akan otomatis dibatalkan dan mediator akan menghubungi Anda untuk penjadwalan ulang.
                </p>
            </div>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/konfirmasi') }}" class="button">
                Konfirmasi Sekarang
            </a>
        </div>

        <div class="info-box">
            <h3>📞 Butuh Bantuan?</h3>
            <p>Jika Anda mengalami kesulitan atau memiliki pertanyaan, silakan hubungi:</p>
            <ul>
                <li><strong>Telepon:</strong> (0747) 21013</li>
                <li><strong>Email:</strong> nakertrans@bungokab.go.id</li>
            </ul>
        </div>
    </div>

    <div class="footer">
        <p>Email ini dikirim secara otomatis oleh sistem. Harap tidak membalas email ini.</p>
        <p>&copy; {{ date('Y') }} Dinas Tenaga Kerja dan Transmigrasi Kabupaten Bungo. Semua hak dilindungi.</p>
        <p>
            <small>
                Email dikirim pada: {{ now()->format('d F Y, H:i') }} WIB<br>
                Ref: reminder-{{ $userRole }}-{{ $jadwal->jadwal_id }}
            </small>
        </p>
    </div>
</body>

</html>
