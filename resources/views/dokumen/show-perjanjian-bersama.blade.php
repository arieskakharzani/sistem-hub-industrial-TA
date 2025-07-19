<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Perjanjian Bersama</title>
</head>

<body>
    <div class="max-w-2xl mx-auto py-8">
        <h2 class="text-xl font-bold mb-6 text-center">PERJANJIAN BERSAMA</h2>
        <div class="mb-4">Pada hari ini ............tanggal ............ bulan ............ tahun ............<br>kami
            yang bertanda tangan di bawah ini:</div>
        <ol class="mb-4">
            <li>
                Nama: {{ $perjanjian->nama_pengusaha }}<br>
                Jabatan: {{ $perjanjian->jabatan_pengusaha }}<br>
                Perusahaan: {{ $perjanjian->perusahaan_pengusaha }}<br>
                Alamat: {{ $perjanjian->alamat_pengusaha }}<br>
                Yang selanjutnya disebut Pihak Pengusaha.
            </li>
            <li class="mt-2">
                Nama: {{ $perjanjian->nama_pekerja }}<br>
                Jabatan: {{ $perjanjian->jabatan_pekerja }}<br>
                Perusahaan: {{ $perjanjian->perusahaan_pekerja }}<br>
                Alamat: {{ $perjanjian->alamat_pekerja }}<br>
                Yang selanjutnya disebut Pihak Pekerja/Buruh/SP/SB
            </li>
        </ol>
        <div class="mb-4">
            Berdasarkan ketentuan Pasal 13 ayat (1) Undang-Undang Nomor 2 Tahun 2004 tentang Penyelesaian Perselisihan
            Hubungan Industrial, antara Pihak Pengusaha dan Pihak Pekerja/Buruh/SP/SB telah tercapai kesepakatan
            penyelesaian perselisihan hubungan industrial melalui Mediasi sebagai berikut:
        </div>
        <div class="mb-4 border p-2">{!! nl2br(e($perjanjian->isi_kesepakatan)) !!}</div>
        <div class="mb-4">
            Kesepakatan ini merupakan perjanjian bersama yang berlaku sejak ditandatangani diatas materai cukup.<br><br>
            Demikian Perjanjian Bersama ini dibuat dalam keadaan sadar tanpa paksaan dari pihak manapun, dan
            dilaksanakan dengan penuh rasa tanggung jawab yang didasari itikad baik.
        </div>
        <div class="flex justify-between mt-8 mb-8">
            <div class="text-center">
                Pihak Pengusaha,<br><br><br><br>
                (........................................)
            </div>
            <div class="text-center">
                Pihak Pekerja/Buruh/SP/SB,<br><br><br><br>
                (........................................)
            </div>
        </div>
        <div class="text-center mb-8">
            Menyaksikan<br>
            Mediator Hubungan Industrial,<br><br><br>
            (........................................)<br>
            NIP. ........................................
        </div>
        <div class="text-center">
            <a href="{{ route('dokumen.perjanjian-bersama.pdf', $perjanjian->perjanjian_bersama_id) }}" target="_blank"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Cetak PDF</a>
        </div>
    </div>
</body>

</html>
