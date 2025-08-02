<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Respon Anjuran</title>
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
            content: "📝";
            margin-right: 8px;
            font-size: 18px;
        }

        .response-box {
            background-color: {{ $response === 'setuju' ? '#d1fae5' : '#fee2e2' }};
            border: 1px solid {{ $response === 'setuju' ? '#10b981' : '#ef4444' }};
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
            text-align: center;
        }

        .response-text {
            font-size: 18px;
            font-weight: 600;
            color: {{ $response === 'setuju' ? '#065f46' : '#991b1b' }};
            margin: 0;
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
            min-width: 120px;
        }

        .detail-value {
            color: #333;
            flex: 1;
        }

        .action-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
            transition: all 0.3s ease;
        }

        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .footer {
            background-color: #f8f9fa;
            padding: 20px 40px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }

        .footer p {
            margin: 5px 0;
        }

        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }

        @media (max-width: 600px) {
            .content {
                padding: 20px;
            }

            .header {
                padding: 20px;
            }

            .header h1 {
                font-size: 24px;
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
        <div class="header">
            <h1>📝 Respon Anjuran</h1>
            <p>Sistem Informasi Pengelolaan Perselisihan Hubungan Industrial</p>
        </div>

        <div class="content">
            <div class="greeting">
                Halo <strong>{{ $user->getName() }}</strong>!
            </div>

            <div class="notification-card">
                <div class="notification-title">
                    Respon Anjuran dari {{ $roleLabel }}
                </div>

                <p>Anda telah menerima respon anjuran dari <strong>{{ $roleLabel }}</strong> untuk pengaduan yang
                    sedang Anda tangani.</p>

                <div class="response-box">
                    <p class="response-text">
                        {{ $response === 'setuju' ? '✅ SETUJU' : '❌ TIDAK SETUJU' }}
                    </p>
                </div>

                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="detail-label">Nomor Anjuran:</span>
                        <span class="detail-value">{{ $anjuran->nomor_anjuran ?? 'A-' . $anjuran->anjuran_id }}</span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Nomor Pengaduan:</span>
                        <span class="detail-value">{{ $anjuran->dokumenHI->pengaduan->nomor_pengaduan }}</span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Pelapor:</span>
                        <span class="detail-value">{{ $anjuran->dokumenHI->pengaduan->pelapor->nama }}</span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Terlapor:</span>
                        <span class="detail-value">{{ $anjuran->dokumenHI->pengaduan->terlapor->nama }}</span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Tanggal Respon:</span>
                        <span class="detail-value">{{ now()->format('d/m/Y H:i') }}</span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Status Respon:</span>
                        <span class="detail-value">
                            @if ($anjuran->pelapor_response && $anjuran->terlapor_response)
                                @if ($anjuran->pelapor_response === 'setuju' && $anjuran->terlapor_response === 'setuju')
                                    <span style="color: #10b981; font-weight: 600;">Kedua Pihak Setuju</span>
                                @elseif($anjuran->pelapor_response === 'tidak_setuju' && $anjuran->terlapor_response === 'tidak_setuju')
                                    <span style="color: #ef4444; font-weight: 600;">Kedua Pihak Tidak Setuju</span>
                                @else
                                    <span style="color: #f59e0b; font-weight: 600;">Respon Berbeda</span>
                                @endif
                            @else
                                <span style="color: #6b7280; font-weight: 600;">Menunggu Respon Lengkap</span>
                            @endif
                        </span>
                    </div>
                </div>

                <div style="text-align: center; margin-top: 30px;">
                    <a href="{{ route('dokumen.anjuran.show', $anjuran->anjuran_id) }}" class="action-button">
                        Lihat Detail Anjuran
                    </a>
                </div>
            </div>

            <div
                style="margin-top: 30px; padding: 20px; background-color: #f0f9ff; border-radius: 6px; border-left: 4px solid #0ea5e9;">
                <h4 style="margin: 0 0 10px 0; color: #0c4a6e; font-size: 16px;">📋 Langkah Selanjutnya</h4>
                <p style="margin: 0; color: #0c4a6e; font-size: 14px;">
                    @if ($anjuran->pelapor_response && $anjuran->terlapor_response)
                        @if ($anjuran->pelapor_response === 'setuju' && $anjuran->terlapor_response === 'setuju')
                            Kedua pihak telah menyetujui anjuran. Anda dapat membuat jadwal pertemuan untuk
                            penandatanganan perjanjian bersama.
                        @elseif($anjuran->pelapor_response === 'tidak_setuju' && $anjuran->terlapor_response === 'tidak_setuju')
                            Kedua pihak telah menolak anjuran. Anda dapat menyelesaikan kasus dan mengirimkan dokumen
                            final kepada para pihak.
                        @else
                            Para pihak memberikan respon yang berbeda. Anda dapat memilih untuk membuat jadwal pertemuan
                            atau menyelesaikan kasus.
                        @endif
                    @else
                        Menunggu respon dari pihak lainnya untuk menentukan langkah selanjutnya.
                    @endif
                </p>
            </div>
        </div>

        <div class="footer">
            <p><strong>SIPPPHI - Sistem Informasi Pengelolaan Perselisihan Hubungan Industrial</strong></p>
            <p>Dinas Tenaga Kerja dan Transmigrasi</p>
            <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
            <p style="margin-top: 15px; font-size: 12px; color: #999;">
                © {{ date('Y') }} SIPPPHI. All rights reserved.
            </p>
        </div>
    </div>
</body>

</html>
