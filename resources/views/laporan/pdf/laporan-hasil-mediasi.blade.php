<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hasil Mediasi</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }

        .content {
            margin-top: 120px;
        }

        .letter-info {
            margin: 20px 0;
        }

        .letter-info table {
            width: 100%;
        }

        .letter-info td {
            vertical-align: top;
            padding: 2px 0;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        .data-table td {
            border: 1px solid #ffffff;
            padding: 8px;
            vertical-align: top;
        }

        .data-table td:first-child {
            font-weight: bold;
            width: 40%;
        }

        .footer {
            margin-top: 40px;
            text-align: left;
        }
    </style>
</head>

<body>
    <!-- Kop Surat -->
    @include('components.pdf.kop-surat')

    <!-- Content -->
    <div class="content">
        <!-- Informasi Surat -->
        <div class="letter-info">
            <table>
                <tr>
                    <td style="width: 15%;">Nomor</td>
                    <td style="width: 5%;">:</td>
                    <td style="width: 80%;">....................................</td>
                </tr>
                <tr>
                    <td>Lampiran</td>
                    <td>:</td>
                    <td>....................................</td>
                </tr>
                <tr>
                    <td>Hal</td>
                    <td>:</td>
                    <td><strong>Laporan Hasil Mediasi</strong></td>
                </tr>
            </table>
        </div>

        <!-- Alamat Tujuan -->
        <div style="margin: 10px 0;">
            <p>Yth. Direktur Jenderal PHI dan Jamsos</p>
            <p>di Tempat</p>
        </div>

        <!-- Pembuka -->
        <div style="margin: 10px 0;">
            <p>Sehubungan dengan penyelesaian perselisihan hubungan industrial melalui Mediasi, maka kami laporkan
                hasilnya sebagai berikut:</p>
        </div>

        <!-- Data Laporan -->
        <table class="data-table">
            <tr>
                <td>1. Tanggal Penerimaan Pengaduan Tertulis</td>
                <td>: {{ \Carbon\Carbon::parse($laporanHasilMediasi->tanggal_penerimaan_pengaduan)->format('d/m/Y') }}
                </td>
            </tr>
            <tr>
                <td>2. Nama Pekerja/Buruh/SP/SB</td>
                <td>: {{ $laporanHasilMediasi->nama_pekerja }}</td>
            </tr>
            <tr>
                <td>3. Alamat Pekerja/Buruh/SP/SB</td>
                <td>: {{ $laporanHasilMediasi->alamat_pekerja }}</td>
            </tr>
            <tr>
                <td>4. Masa Kerja</td>
                <td>: {{ $laporanHasilMediasi->masa_kerja }}</td>
            </tr>
            <tr>
                <td>5. Nama Perusahaan</td>
                <td>: {{ $laporanHasilMediasi->nama_perusahaan }}</td>
            </tr>
            <tr>
                <td>6. Alamat Perusahaan</td>
                <td>: {{ $laporanHasilMediasi->alamat_perusahaan }}</td>
            </tr>
            <tr>
                <td>7. Jenis Usaha</td>
                <td>: {{ $laporanHasilMediasi->jenis_usaha }}</td>
            </tr>
            <tr>
                <td>8. Waktu Penyelesaian Mediasi</td>
                <td>: {{ $laporanHasilMediasi->waktu_penyelesaian_mediasi }}</td>
            </tr>
            <tr>
                <td>9. Permasalahan</td>
                <td>: {{ $laporanHasilMediasi->permasalahan }}</td>
            </tr>
            <tr>
                <td>10. Pendapat Pekerja/Buruh/SP/SB</td>
                <td>: {{ $laporanHasilMediasi->pendapat_pekerja }}</td>
            </tr>
            <tr>
                <td>11. Pendapat Pengusaha</td>
                <td>: {{ $laporanHasilMediasi->pendapat_pengusaha }}</td>
            </tr>
            <tr>
                <td>12. Upaya Penyelesaian</td>
                <td>: {{ $laporanHasilMediasi->upaya_penyelesaian }}</td>
            </tr>
        </table>

        <!-- Penutup -->
        <div style="margin: 10px 0;">
            <p>Demikian kami sampaikan dan diucapkan terima kasih.</p>
        </div>

        <!-- Footer -->
        @include('components.pdf.footer', [
            'footerText' =>
                'Laporan Hasil Mediasi ini dikeluarkan oleh Mediator Hubungan Industrial Dinas Tenaga Kerja dan Transmigrasi Kabupaten Bungo pada ' .
                \Carbon\Carbon::parse($laporanHasilMediasi->created_at)->translatedFormat('d F Y') .
                ' pukul ' .
                \Carbon\Carbon::parse($laporanHasilMediasi->created_at)->format('H:i') .
                ' WIB.',
        ])
    </div>
</body>

</html>
