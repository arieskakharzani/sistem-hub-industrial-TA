<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Informasi Jadwal {{ $jadwal->getJenisJadwalLabel() }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #0000AB;
            color: white;
            padding: 20px;
            margin: -30px -30px 30px -30px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .section {
            margin: 25px 0;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #0000AB;
        }

        .section h3 {
            margin-top: 0;
            color: #0000AB;
            font-size: 18px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 15px 0;
        }

        .info-item {
            margin: 8px 0;
        }

        .info-label {
            font-weight: bold;
            color: #555;
            margin-bottom: 3px;
        }

        .info-value {
            color: #333;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-dijadwalkan {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-berlangsung {
            background-color: #fef3c7;
            color: #d97706;
        }

        .status-selesai {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-ditunda {
            background-color: #fed7aa;
            color: #ea580c;
        }

        .status-dibatalkan {
            background-color: #fecaca;
            color: #dc2626;
        }

        .jenis-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 5px;
        }

        .jenis-klarifikasi {
            background-color: #e3f2fd;
            color: #0d47a1;
        }

        .jenis-mediasi {
            background-color: #f3e5f5;
            color: #7b1fa2;
        }

        .changes-list {
            background-color: #fffbeb;
            border: 1px solid #f59e0b;
            border-radius: 5px;
            padding: 15px;
            margin: 15px 0;
        }

        .change-item {
            margin: 8px 0;
            padding: 8px;
            background-color: white;
            border-radius: 3px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e5e5;
            text-align: center;
            color: #666;
            font-size: 14px;
        }

        .button {
            display: inline-block;
            background-color: #0000AB;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
            font-weight: bold;
        }

        .alert {
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }

        .alert-info {
            background-color: #e0f2fe;
            border: 1px solid #0288d1;
            color: #01579b;
        }

        .alert-warning {
            background-color: #fff3e0;
            border: 1px solid #f57c00;
            color: #e65100;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>
                Informasi Jadwal {{ $jadwal->getJenisJadwalLabel() }}
                <span class="jenis-badge jenis-{{ $jadwal->jenis_jadwal }}">
                    {{ $jadwal->getJenisJadwalLabel() }}
                </span>
            </h1>
            <p style="margin: 5px 0 0 0;">Sistem Informasi Pengaduan dan Penyelesaian Hubungan Industrial Kab. Bungo</p>
        </div>

        <p>Yth. {{ $recipient['name'] }},</p>

        @if ($eventType === 'created')
            <div class="alert alert-info">
                <strong>Informasi:</strong> Jadwal {{ $jadwal->getJenisJadwalLabel() }} baru telah dibuat.
            </div>
        @elseif($eventType === 'updated')
            <div class="alert alert-warning">
                <strong>Perubahan:</strong> Terdapat perubahan pada jadwal {{ $jadwal->getJenisJadwalLabel() }}.
            </div>
        @elseif($eventType === 'status_updated')
            <div class="alert alert-info">
                <strong>Update Status:</strong> Status jadwal {{ $jadwal->getJenisJadwalLabel() }} telah diperbarui.
            </div>
        @endif

        <div class="section">
            <h3>📅 Detail Jadwal</h3>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Jenis Jadwal</div>
                    <div class="info-value">
                        {{ $jadwal->getJenisJadwalLabel() }}
                        @if ($jadwal->sidang_ke)
                            <span style="color: #666; margin-left: 5px;">
                                (Sidang ke-{{ $jadwal->sidang_ke }})
                            </span>
                        @endif
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        <span class="status-badge status-{{ $jadwal->status_jadwal }}">
                            {{ ucfirst($jadwal->status_jadwal) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Tanggal</div>
                    <div class="info-value">
                        {{ \Carbon\Carbon::parse($jadwal->tanggal)->isoFormat('dddd, D MMMM Y') }}
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Waktu</div>
                    <div class="info-value">
                        {{ \Carbon\Carbon::parse($jadwal->waktu)->format('H:i') }} WIB
                    </div>
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">Tempat</div>
                <div class="info-value">{{ $jadwal->tempat }}</div>
            </div>

            @if ($jadwal->catatan_jadwal)
                <div class="info-item">
                    <div class="info-label">Catatan</div>
                    <div class="info-value">{{ $jadwal->catatan_jadwal }}</div>
                </div>
            @endif
        </div>

        @if ($eventType === 'updated' && !empty($additionalData['old_data']))
            <div class="section">
                <h3>📝 Detail Perubahan</h3>
                <div class="changes-list">
                    @if ($additionalData['old_data']['tanggal'] !== $jadwal->tanggal)
                        <div class="change-item">
                            <strong>Tanggal:</strong><br>
                            <span style="color: #dc2626;">Sebelum:
                                {{ \Carbon\Carbon::parse($additionalData['old_data']['tanggal'])->isoFormat('D MMMM Y') }}</span><br>
                            <span style="color: #16a34a;">Sesudah:
                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->isoFormat('D MMMM Y') }}</span>
                        </div>
                    @endif
                    @if ($additionalData['old_data']['waktu'] !== $jadwal->waktu)
                        <div class="change-item">
                            <strong>Waktu:</strong><br>
                            <span style="color: #dc2626;">Sebelum:
                                {{ \Carbon\Carbon::parse($additionalData['old_data']['waktu'])->format('H:i') }}</span><br>
                            <span style="color: #16a34a;">Sesudah:
                                {{ \Carbon\Carbon::parse($jadwal->waktu)->format('H:i') }}</span>
                        </div>
                    @endif
                    @if ($additionalData['old_data']['tempat'] !== $jadwal->tempat)
                        <div class="change-item">
                            <strong>Tempat:</strong><br>
                            <span style="color: #dc2626;">Sebelum:
                                {{ $additionalData['old_data']['tempat'] }}</span><br>
                            <span style="color: #16a34a;">Sesudah: {{ $jadwal->tempat }}</span>
                        </div>
                    @endif
                    @if ($additionalData['old_data']['status_jadwal'] !== $jadwal->status_jadwal)
                        <div class="change-item">
                            <strong>Status:</strong><br>
                            <span style="color: #dc2626;">Sebelum:
                                {{ ucfirst($additionalData['old_data']['status_jadwal']) }}</span><br>
                            <span style="color: #16a34a;">Sesudah: {{ ucfirst($jadwal->status_jadwal) }}</span>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <div class="section">
            <h3>👤 Mediator yang Menangani</h3>
            <div class="info-item">
                <div class="info-label">Nama Mediator</div>
                <div class="info-value">{{ $jadwal->mediator->nama_mediator }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">NIP</div>
                <div class="info-value">{{ $jadwal->mediator->nip }}</div>
            </div>
        </div>

        @if ($jadwal->status_jadwal === 'dijadwalkan')
            <div class="alert alert-info">
                <strong>📌 Catatan Penting:</strong><br>
                Harap datang tepat waktu sesuai jadwal yang telah ditentukan.
                @if ($jadwal->jenis_jadwal === 'klarifikasi')
                    Bawa dokumen-dokumen yang diperlukan untuk proses klarifikasi.
                    <br><br>
                    <strong>📋 Informasi Khusus Klarifikasi:</strong><br>
                    Jika Anda tidak dapat hadir, proses klarifikasi tetap akan dilanjutkan dan mediator akan melanjutkan
                    ke tahap mediasi setelah klarifikasi selesai.
                @elseif ($jadwal->jenis_jadwal === 'mediasi')
                    Bawa dokumen-dokumen yang diperlukan untuk proses mediasi.
                @elseif ($jadwal->jenis_jadwal === 'ttd_perjanjian_bersama')
                    Bawa dokumen-dokumen yang diperlukan untuk penandatanganan perjanjian bersama.
                @endif
            </div>
            <div style="text-align: center; margin: 30px 0;">
                <p style="margin-top: 30px; color: #555;">
                    Terima kasih atas perhatian dan kerja sama Anda.
                    Silakan login ke sistem untuk melakukan konfirmasi kehadiran Anda.
                </p>
                <a href="{{ url('/dashboard') }}" class="button">
                    Konfirmasi Sekarang
                </a>
            </div>
        @elseif($jadwal->status_jadwal === 'ditunda')
            <div class="alert alert-warning">
                <strong>⚠️ Perhatian:</strong><br>
                Jadwal {{ $jadwal->getJenisJadwalLabel() }} ditunda. Anda akan mendapat pemberitahuan jadwal baru
                segera.
            </div>
            <div style="text-align: center; margin: 30px 0;">
                <p style="margin-top: 30px; color: #555;">
                    Terima kasih atas perhatian dan kerja sama Anda.
                </p>
                <a href="{{ url('/dashboard') }}" class="button">
                    Cek Sekarang
                </a>
            </div>
        @elseif($jadwal->status_jadwal === 'dibatalkan')
            <div class="alert alert-warning">
                <strong>❌ Informasi:</strong><br>
                Jadwal {{ $jadwal->getJenisJadwalLabel() }} dibatalkan. Silakan hubungi mediator untuk informasi lebih
                lanjut.
            </div>
            <div style="text-align: center; margin: 30px 0;">
                <p style="margin-top: 30px; color: #555;">
                    Terima kasih atas perhatian dan kerja sama Anda.
                </p>
                <a href="{{ url('/dashboard') }}" class="button">
                    Cek Sekarang
                </a>
            </div>
        @elseif($jadwal->status_jadwal === 'berlangsung')
            <div class="alert alert-warning">
                <strong>Informasi:</strong><br>
                Jadwal {{ $jadwal->getJenisJadwalLabel() }} sedang berlangsung.
            </div>
            <div style="text-align: center; margin: 30px 0;">
                <p style="margin-top: 30px; color: #555;">
                    Terima kasih atas perhatian dan kerja sama Anda.
                </p>
                <a href="{{ url('/dashboard') }}" class="button">
                    Cek Sekarang
                </a>
            </div>
        @elseif($jadwal->status_jadwal === 'selesai')
            <div class="alert alert-warning">
                <strong>Informasi:</strong><br>
                Jadwal {{ $jadwal->getJenisJadwalLabel() }} telah selesai.
            </div>
            <div style="text-align: center; margin: 30px 0;">
                <p style="margin-top: 30px; color: #555;">
                    Terima kasih atas perhatian dan kerja sama Anda.
                </p>
                <a href="{{ url('/dashboard') }}" class="button">
                    Cek Sekarang
                </a>
            </div>
        @endif

        {{-- <div style="text-align: center; margin: 30px 0;">
            <p style="margin-top: 30px; color: #555;">
                Terima kasih atas perhatian dan kerja sama Anda.
                Silakan login ke sistem untuk melakukan konfirmasi kehadiran Anda.
            </p>
            <a href="{{ url('/dashboard') }}" class="button">
                Masuk ke Sistem
            </a>
        </div> --}}

        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh sistem.<br>
                Jika ada pertanyaan, silakan hubungi mediator yang menangani kasus Anda.</p>
            <p style="margin-top: 15px;">
                <strong>Dinas Tenaga Kerja dan Transmigrasi Kab. Bungo</strong><br>
                Sistem Informasi Pengaduan dan Penyelesaian Hubungan Industrial Kab. Bungo
            </p>
        </div>
    </div>
</body>

</html>
