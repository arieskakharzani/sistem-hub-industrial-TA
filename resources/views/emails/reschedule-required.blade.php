<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🚨 Penjadwalan Ulang Diperlukan</title>
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
            background: linear-gradient(135deg, #DC2626, #EF4444);
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .urgent-badge {
            background: #FEE2E2;
            color: #991B1B;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 15px;
            display: inline-block;
        }

        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }

        .alert-box {
            background: #FEF2F2;
            border: 2px solid #FECACA;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-left: 6px solid #DC2626;
        }

        .info-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #0000AB;
        }

        .action-box {
            background: #FEF3C7;
            border: 2px solid #FCD34D;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }

        .button {
            display: inline-block;
            background: #DC2626;
            color: white;
            padding: 14px 28px;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 0;
            font-weight: bold;
        }

        .button:hover {
            background: #B91C1C;
        }

        .secondary-button {
            display: inline-block;
            background: #0000AB;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 5px;
            font-size: 14px;
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
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .details-table th {
            background: #f4f4f4;
            font-weight: bold;
        }

        .status-absent {
            background: #FEE2E2;
            color: #991B1B;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-pending {
            background: #FEF3C7;
            color: #92400E;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .urgent-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="urgent-badge">🚨 URGENT - TINDAKAN SEGERA DIPERLUKAN</div>
        <div class="urgent-icon">⚠️</div>
        <h1>Penjadwalan Ulang Mediasi Diperlukan</h1>
        <p>Sistem Informasi Pengaduan dan Penyelesaian Hubungan Industrial</p>
        <p>Dinas Tenaga Kerja Kabupaten Bungo</p>
    </div>

    <div class="content">
        <h2>Kepada Yth. {{ $mediator->nama_mediator ?? 'Mediator' }},</h2>

        <div class="alert-box">
            <h3>🚨 PEMBERITAHUAN URGENT</h3>
            <p><strong>{{ $absentPartyText }}</strong> telah mengkonfirmasi <strong>TIDAK DAPAT HADIR</strong> pada
                jadwal mediasi yang telah ditetapkan.</p>
            <p><strong>Status Jadwal:</strong> Automatically changed to <span
                    style="color: #DC2626; font-weight: bold;">"DITUNDA"</span></p>
            <p><strong>Tindakan Diperlukan:</strong> Penjadwalan ulang segera diperlukan.</p>
        </div>

        <div class="info-box">
            <h3>📋 Detail Pengaduan</h3>
            <table class="details-table">
                <tr>
                    <th>No. Pengaduan</th>
                    <td>{{ $pengaduan->pengaduan_id }}</td>
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
            <h3>🗓️ Jadwal Mediasi Yang Ditunda</h3>
            <table class="details-table">
                <tr>
                    <th>Tanggal Original</th>
                    <td>{{ $jadwal->tanggal_mediasi->format('d F Y') }}</td>
                </tr>
                <tr>
                    <th>Waktu Original</th>
                    <td>{{ $jadwal->waktu_mediasi->format('H:i') }} WIB</td>
                </tr>
                <tr>
                    <th>Tempat</th>
                    <td>{{ $jadwal->tempat_mediasi }}</td>
                </tr>
                <tr>
                    <th>Status Saat Ini</th>
                    <td><span style="color: #DC2626; font-weight: bold;">{{ strtoupper($jadwal->status_jadwal) }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="info-box">
            <h3>👥 Status Konfirmasi Kehadiran</h3>
            <table class="details-table">
                <tr>
                    <th>Pelapor</th>
                    <td>
                        @if ($jadwal->konfirmasi_pelapor === 'tidak_hadir')
                            <span class="status-absent">TIDAK HADIR</span>
                        @elseif($jadwal->konfirmasi_pelapor === 'hadir')
                            <span
                                style="background: #D1FAE5; color: #065F46; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">HADIR</span>
                        @else
                            <span class="status-pending">PENDING</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Terlapor</th>
                    <td>
                        @if ($jadwal->konfirmasi_terlapor === 'tidak_hadir')
                            <span class="status-absent">TIDAK HADIR</span>
                        @elseif($jadwal->konfirmasi_terlapor === 'hadir')
                            <span
                                style="background: #D1FAE5; color: #065F46; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">HADIR</span>
                        @else
                            <span class="status-pending">PENDING</span>
                        @endif
                    </td>
                </tr>
            </table>

            @if ($absentParty === 'pelapor' && $jadwal->catatan_konfirmasi_pelapor)
                <div
                    style="margin-top: 15px; padding: 12px; background: #FEE2E2; border-radius: 6px; border-left: 4px solid #DC2626;">
                    <p><strong>Alasan Pelapor Tidak Hadir:</strong></p>
                    <p style="font-style: italic;">{{ $jadwal->catatan_konfirmasi_pelapor }}</p>
                </div>
            @elseif($absentParty === 'terlapor' && $jadwal->catatan_konfirmasi_terlapor)
                <div
                    style="margin-top: 15px; padding: 12px; background: #FEE2E2; border-radius: 6px; border-left: 4px solid #DC2626;">
                    <p><strong>Alasan Terlapor Tidak Hadir:</strong></p>
                    <p style="font-style: italic;">{{ $jadwal->catatan_konfirmasi_terlapor }}</p>
                </div>
            @elseif($absentParty === 'both')
                @if ($jadwal->catatan_konfirmasi_pelapor)
                    <div
                        style="margin-top: 15px; padding: 12px; background: #FEE2E2; border-radius: 6px; border-left: 4px solid #DC2626;">
                        <p><strong>Alasan Pelapor:</strong></p>
                        <p style="font-style: italic;">{{ $jadwal->catatan_konfirmasi_pelapor }}</p>
                    </div>
                @endif
                @if ($jadwal->catatan_konfirmasi_terlapor)
                    <div
                        style="margin-top: 15px; padding: 12px; background: #FEE2E2; border-radius: 6px; border-left: 4px solid #DC2626;">
                        <p><strong>Alasan Terlapor:</strong></p>
                        <p style="font-style: italic;">{{ $jadwal->catatan_konfirmasi_terlapor }}</p>
                    </div>
                @endif
            @endif
        </div>

        <div class="action-box">
            <h3>⚡ Tindakan Yang Perlu Dilakukan</h3>
            <p><strong>Segera lakukan penjadwalan ulang untuk mediasi ini.</strong></p>
            <p>Koordinasikan dengan kedua belah pihak untuk menentukan jadwal baru yang sesuai.</p>

            <div style="margin: 20px 0;">
                <a href="{{ url('/jadwal/' . $jadwal->jadwal_id . '/edit') }}" class="button">
                    🗓️ Jadwalkan Ulang Sekarang
                </a>
            </div>

            <div>
                <a href="{{ url('/jadwal/' . $jadwal->jadwal_id) }}" class="secondary-button">
                    📋 Lihat Detail Lengkap
                </a>
                <a href="{{ url('/dashboard') }}" class="secondary-button">
                    🏠 Dashboard Mediator
                </a>
            </div>
        </div>

        <div class="info-box">
            <h3>📞 Kontak untuk Koordinasi</h3>
            <p>Untuk koordinasi penjadwalan ulang, Anda dapat menghubungi:</p>
            <div style="margin: 15px 0;">
                <p><strong>📧 Email Pelapor:</strong> {{ $pengaduan->pelapor->user->email ?? 'Tidak tersedia' }}</p>
                <p><strong>📧 Email Terlapor:</strong> {{ $pengaduan->terlapor->user->email ?? 'Tidak tersedia' }}</p>
                <p><strong>📱 Call Center:</strong> (0746) 21234</p>
            </div>
        </div>

        <div style="background: #DBEAFE; border: 1px solid #93C5FD; border-radius: 8px; padding: 15px; margin: 20px 0;">
            <p><strong>💡 Tips Penjadwalan Ulang:</strong></p>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>Berikan waktu minimal 3-5 hari kerja untuk pemberitahuan</li>
                <li>Pertimbangkan fleksibilitas waktu yang lebih luas</li>
                <li>Konfirmasi ulang ketersediaan kedua belah pihak</li>
                <li>Pastikan tempat mediasi masih tersedia</li>
            </ul>
        </div>
    </div>

    <div class="footer">
        <p>Email ini dikirim secara otomatis oleh sistem dan memerlukan tindakan segera.</p>
        <p>&copy; {{ date('Y') }} Dinas Tenaga Kerja Kabupaten Bungo. Semua hak dilindungi.</p>
        <p>
            <small>
                Email dikirim pada: {{ now()->format('d F Y, H:i') }} WIB<br>
                Ref: URGENT-{{ $jadwal->jadwal_id }}-{{ $absentParty }}-reschedule<br>
                Priority: HIGH - Immediate Action Required
            </small>
        </p>
    </div>
</body>

</html>
