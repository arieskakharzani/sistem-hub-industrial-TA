{{-- resources/views/emails/jadwal-mediation.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data['event_label'] }}</title>
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
            background-color: #1d4ed8;
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
            border-left: 4px solid #1d4ed8;
        }

        .section h3 {
            margin-top: 0;
            color: #1d4ed8;
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
            background-color: #1d4ed8;
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

        .alert-success {
            background-color: #e8f5e8;
            border: 1px solid #4caf50;
            color: #2e7d32;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>{{ $data['event_label'] }}</h1>
            <p style="margin: 5px 0 0 0;">Sistem Informasi Pengaduan dan Penyelesaian Hubungan Industrial Kab. Bungo</p>
        </div>

        {{-- Greeting --}}
        <p>Kepada Yth. <strong>{{ $recipient['name'] }}</strong>,</p>

        {{-- Event specific intro --}}
        @if ($eventType === 'created')
            <div class="alert alert-info">
                <strong>Informasi:</strong> jadwal baru telah dibuat untuk pengaduan Anda.
            </div>
        @elseif($eventType === 'updated')
            <div class="alert alert-warning">
                <strong>Perubahan:</strong> Terdapat perubahan pada jadwal Anda.
            </div>
        @elseif($eventType === 'status_updated')
            <div class="alert alert-info">
                <strong>Update Status:</strong> Status jadwal Anda telah diperbarui.
            </div>
        @endif

        {{-- Jadwal Information --}}
        <div class="section">
            <h3>📅 Informasi Jadwal</h3>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">ID Jadwal:</div>
                    <div class="info-value">#{{ $data['jadwal']['id'] }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Status:</div>
                    <div class="info-value">
                        <span class="status-badge status-{{ $data['jadwal']['status'] }}">
                            {{ $data['jadwal']['status_label'] }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Tanggal:</div>
                    <div class="info-value">{{ $data['jadwal']['tanggal'] }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Waktu:</div>
                    <div class="info-value">{{ $data['jadwal']['waktu'] }} WIB</div>
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">Tempat:</div>
                <div class="info-value">{{ $data['jadwal']['tempat'] }}</div>
            </div>

            @if ($data['jadwal']['catatan'])
                <div class="info-item">
                    <div class="info-label">Catatan:</div>
                    <div class="info-value">{{ $data['jadwal']['catatan'] }}</div>
                </div>
            @endif
        </div>

        {{-- Status Change Information --}}
        @if ($eventType === 'status_updated' && isset($data['old_status']))
            <div class="section">
                <h3>🔄 Perubahan Status</h3>
                <p>Status jadwal telah berubah:</p>
                <div style="margin: 10px 0;">
                    <span class="status-badge status-{{ $data['old_status'] }}">{{ $data['old_status_label'] }}</span>
                    <span style="margin: 0 10px;">→</span>
                    <span
                        class="status-badge status-{{ $data['jadwal']['status'] }}">{{ $data['jadwal']['status_label'] }}</span>
                </div>
            </div>
        @endif

        {{-- Changes Information --}}
        @if ($eventType === 'updated' && isset($data['changes']) && count($data['changes']) > 0)
            <div class="section">
                <h3>📝 Detail Perubahan</h3>
                <div class="changes-list">
                    @foreach ($data['changes'] as $change)
                        <div class="change-item">
                            <strong>{{ $change['field'] }}:</strong><br>
                            <span style="color: #dc2626;">Sebelum: {{ $change['old'] }}</span><br>
                            <span style="color: #16a34a;">Sesudah: {{ $change['new'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Pengaduan Information --}}
        <div class="section">
            <h3>📋 Informasi Pengaduan</h3>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">ID Pengaduan:</div>
                    <div class="info-value">#{{ $data['pengaduan']['id'] }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tanggal Laporan:</div>
                    <div class="info-value">{{ $data['pengaduan']['tanggal_laporan'] }}</div>
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">Perihal:</div>
                <div class="info-value">{{ $data['pengaduan']['perihal'] }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Nama Perusahaan:</div>
                <div class="info-value">{{ $data['pengaduan']['nama_terlapor'] }}</div>
            </div>
        </div>

        {{-- Mediator Information --}}
        <div class="section">
            <h3>👤 Mediator yang Menangani</h3>
            <div class="info-item">
                <div class="info-label">Nama Mediator:</div>
                <div class="info-value">{{ $data['mediator']['nama'] }}</div>
            </div>
            @if ($data['mediator']['nip'])
                <div class="info-item">
                    <div class="info-label">NIP:</div>
                    <div class="info-value">{{ $data['mediator']['nip'] }}</div>
                </div>
            @endif
        </div>

        {{-- Action based on status --}}
        @if ($data['jadwal']['status'] === 'dijadwalkan')
            <div class="alert alert-success">
                <strong>📌 Catatan Penting:</strong><br>
                Harap datang tepat waktu sesuai jadwal yang telah ditentukan.
                Bawa dokumen-dokumen yang diperlukan terkait pengaduan Anda.
            </div>
        @elseif($data['jadwal']['status'] === 'ditunda')
            <div class="alert alert-warning">
                <strong>⚠️ Perhatian:</strong><br>
                jadwal ditunda. Anda akan mendapat pemberitahuan jadwal baru segera.
            </div>
        @elseif($data['jadwal']['status'] === 'dibatalkan')
            <div class="alert alert-warning">
                <strong>❌ Informasi:</strong><br>
                jadwal dibatalkan. Silakan hubungi mediator untuk informasi lebih lanjut.
            </div>
        @endif

        <div style="text-align: center; margin: 30px 0;">
            <p style="margin-top: 30px; color: #555;">
                Terima kasih atas perhatian dan kerja sama Anda dalam menangani pengaduan ini.
                Silakan login ke sistem untuk melakukan konfirmasi terhadap kehadiran Anda di Panggilan Mediasi berikut.
            </p>
            <a href="{{ url('/dashboard') }}" class="button">
                Konfirmasi Sekarang
            </a>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh sistem.<br>
                Jika ada pertanyaan, silakan hubungi mediator yang menangani kasus Anda.</p>
            <p style="margin-top: 15px;">
                <strong>Dinas Tenaga Kerja dan Transmigrasi Kab. Bungo</strong><br>
                Sistem Informasi Pengaduan dan Penyelesaian Hubungan Industrial Kab. bungo
            </p>
        </div>
    </div>
</body>

</html>
