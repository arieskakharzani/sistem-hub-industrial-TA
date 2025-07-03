<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Sistem Penyelesaian Hubungan Industrial</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #0000AB;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .content {
            background-color: #f9f9f9;
            padding: 30px;
        }

        .credentials-box {
            background-color: #fff;
            padding: 20px;
            border: 2px solid #0000AB;
            margin: 20px 0;
        }

        .footer {
            background-color: #e9e9e9;
            padding: 15px;
            text-align: center;
            font-size: 12px;
        }

        .button {
            display: inline-block;
            background-color: #0000AB;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }

        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Sistem Informasi Pengaduan dan Penyelesaian Hubungan Industrial</h1>
            <p>Pemberitahuan Akun & Kredensial Login</p>
        </div>

        <div class="content">
            <h2>Yth. {{ $nama_terlapor }},</h2>

            @if ($pengaduan_id)
                <p>Kami informasikan bahwa telah ada <strong>pengaduan hubungan industrial #{{ $pengaduan_id }}</strong>
                    yang melibatkan perusahaan/organisasi Anda sebagai pihak yang dilaporkan.</p>
            @endif

            <p>Akun Anda telah dibuat dalam sistem kami untuk mengakses informasi pengaduan dan berpartisipasi dalam
                proses penyelesaian melalui mediasi.</p>

            <div class="credentials-box">
                <h3 style="color: #0000AB; margin-top: 0;">🔐 Kredensial Login Anda</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">URL Login:</td>
                        <td style="padding: 8px 0;"><a href="{{ $login_url }}"
                                target="_blank">{{ $login_url }}</a></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">Email:</td>
                        <td style="padding: 8px 0; font-family: monospace;">{{ $email }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">Password Sementara:</td>
                        <td style="padding: 8px 0; font-family: monospace; background-color: #f0f0f0; padding: 5px;">
                            {{ $password }}</td>
                    </tr>
                </table>
            </div>

            <div class="warning">
                <h4 style="margin-top: 0; color: #856404;">⚠️ Penting untuk Diperhatikan:</h4>
                <ul style="margin: 0; padding-left: 20px;">
                    <li><strong>Segera ganti password</strong> setelah login pertama kali</li>
                    <li>Jangan bagikan kredensial ini kepada pihak lain</li>
                    <li>Simpan informasi login ini dengan aman</li>
                    <li>Hubungi mediator jika mengalami kesulitan login</li>
                </ul>
            </div>

            <h3 style="color: #0000AB;">📋 Yang Dapat Anda Lakukan di Sistem:</h3>
            <ul>
                <li>Melihat detail pengaduan yang melibatkan perusahaan/organisasi Anda</li>
                <li>Mengikuti perkembangan status penyelesaian secara real-time</li>
                <li>Berpartisipasi dalam jadwal mediasi yang ditetapkan</li>
                <li>Mengakses dokumen-dokumen terkait kasus</li>
            </ul>

            @if ($pengaduan_id)
                <p><strong>Langkah Selanjutnya:</strong></p>
                <ol>
                    <li>Login ke sistem menggunakan kredensial di atas</li>
                    <li>Lihat detail pengaduan #{{ $pengaduan_id }}</li>
                    <li>Tunggu penjadwalan mediasi dari mediator</li>
                    <li>Siapkan dokumen/bukti yang diperlukan</li>
                </ol>
            @endif

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $login_url }}" class="button">🔗 LOGIN KE SISTEM</a>
            </div>

            <p>Jika Anda memiliki pertanyaan atau membutuhkan bantuan, silakan hubungi tim mediator melalui sistem atau
                email resmi kami.</p>

            <p>Terima kasih atas kerjasamanya.</p>

            <hr style="border: none; border-top: 1px solid #ddd; margin: 30px 0;">

            <p style="margin-bottom: 0;"><strong>Tim Mediator</strong><br>
                Sistem Informasi Pengaduan dan Penyelesaian Hubungan Industrial Kab. Bungo</p>
        </div>

        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh sistem. Mohon tidak membalas email ini.</p>
            <p>© {{ date('Y') }} Sistem Informasi Pengaduan dan Penyelesaian Hubungan Industrial Kabupaten Bungo</p>
        </div>
    </div>
</body>

</html>
