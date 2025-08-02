<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Kehadiran {{ $jadwal->getJenisJadwalLabel() }}</title>
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

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            margin: 10px 0;
        }

        .status-hadir {
            background: #d1fae5;
            color: #065f46;
        }

        .status-tidak-hadir {
            background: #fef2f2;
            color: #991b1b;
        }

        .info-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #0000AB;
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
        <h1>🗓️ Konfirmasi Kehadiran {{ $jadwal->getJenisJadwalLabel() }}</h1>
        <p>Sistem Informasi Pengaduan dan Penyelesaian Hubungan Industrial</p>
        <p>Dinas Tenaga Kerja dan Transmigrasi Kabupaten Bungo</p>
    </div>

    <div class="content">
        <h2>Kepada Yth. {{ $mediator->nama_mediator ?? 'Mediator' }},</h2>

        <p>Anda mendapat notifikasi konfirmasi kehadiran untuk jadwal dengan detail sebagai berikut:</p>

        <div class="info-box">
            <h3>📋 Detail Pengaduan</h3>
            <table class="details-table">
                <tr>
                    <th>No. Pengaduan</th>
                    <td>{{ $pengaduan->nomor_pengaduan }}</td>
                </tr>
                <tr>
                    <th>Perihal</th>
                    <td>{{ $pengaduan->perihal }}</td>
                </tr>
                <tr>
                    <th>Pelapor</th>
                    <td>{{ $pengaduan->pelapor->nama_pelapor ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Terlapor</th>
                    <td>{{ $pengaduan->terlapor->nama_terlapor ?? '-' }}</td>
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
            <h3>✅ Konfirmasi Kehadiran</h3>
            <p><strong>{{ $roleText }}</strong> telah memberikan konfirmasi kehadiran:</p>

            <div class="status-badge {{ $konfirmasi === 'hadir' ? 'status-hadir' : 'status-tidak-hadir' }}">
                {{ $konfirmasiText }}
            </div>

            @if ($konfirmasi === 'hadir')
                <p>✅ <strong>Kabar Baik!</strong> {{ $roleText }} akan hadir pada jadwal yang telah
                    ditetapkan.</p>
            @else
                <p>⚠️ <strong>Perhatian!</strong> {{ $roleText }} tidak dapat hadir pada jadwal yang telah
                    ditetapkan. Anda mungkin perlu melakukan penjadwalan ulang.</p>
            @endif

            @if ($userRole === 'pelapor' && $jadwal->catatan_konfirmasi_pelapor)
                <p><strong>Catatan dari Pelapor:</strong><br>
                    {{ $jadwal->catatan_konfirmasi_pelapor }}</p>
            @elseif($userRole === 'terlapor' && $jadwal->catatan_konfirmasi_terlapor)
                <p><strong>Catatan dari Terlapor:</strong><br>
                    {{ $jadwal->catatan_konfirmasi_terlapor }}</p>
            @endif
        </div>

        <div class="info-box">
            <h3>📊 Status Konfirmasi Lengkap</h3>
            <table class="details-table">
                <tr>
                    <th>Pelapor</th>
                    <td>
                        <span
                            class="status-badge {{ $jadwal->konfirmasi_pelapor === 'hadir' ? 'status-hadir' : ($jadwal->konfirmasi_pelapor === 'tidak_hadir' ? 'status-tidak-hadir' : '') }}">
                            {{ ucfirst(str_replace('_', ' ', $jadwal->konfirmasi_pelapor)) }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Terlapor</th>
                    <td>
                        <span
                            class="status-badge {{ $jadwal->konfirmasi_terlapor === 'hadir' ? 'status-hadir' : ($jadwal->konfirmasi_terlapor === 'tidak_hadir' ? 'status-tidak-hadir' : '') }}">
                            {{ ucfirst(str_replace('_', ' ', $jadwal->konfirmasi_terlapor)) }}
                        </span>
                    </td>
                </tr>
            </table>

            @if ($jadwal->sudahDikonfirmasiSemua())
                @if ($jadwal->adaYangTidakHadir())
                    <p>⚠️ <strong>Tindakan Diperlukan:</strong> Ada pihak yang tidak dapat hadir. Status jadwal telah
                        diubah menjadi "Ditunda". Silakan lakukan koordinasi untuk penjadwalan ulang.</p>
                @else
                    <p>✅ <strong>Semua Pihak Siap:</strong> Kedua belah pihak telah mengkonfirmasi kehadiran.
                        {{ $jadwal->getJenisJadwalLabel() }}
                        dapat dilaksanakan sesuai jadwal.</p>
                @endif
            @else
                <p>⏳ <strong>Menunggu Konfirmasi:</strong> Masih menunggu konfirmasi dari pihak lain.</p>
            @endif
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/dashboard') }}" class="button">
                Akses Dashboard Mediator
            </a>
        </div>
    </div>

    <div class="footer">
        <p>Email ini dikirim secara otomatis oleh sistem. Harap tidak membalas email ini.</p>
        <p>&copy; {{ date('Y') }} Dinas Tenaga Kerja dan Transmigrasi Kabupaten Bungo. Semua hak dilindungi.</p>
        <p>
            <small>
                Email dikirim pada: {{ now()->format('d F Y, H:i') }} WIB<br>
                Ref: {{ $userRole }}-{{ $konfirmasi }}
            </small>
        </p>
    </div>
</body>

</html>
