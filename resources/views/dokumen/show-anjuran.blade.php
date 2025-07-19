<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Anjuran</title>
</head>

<body>
    <div class="max-w-2xl mx-auto py-8">
        <div class="mb-2 text-sm">Format 12 : Anjuran</div>
        <div class="text-center font-bold mb-2">KOP<br>DIREKTORAT JENDERAL/DINAS PROVINSI/DINAS KABUPATEN/KOTA</div>
        <div class="mb-2">(tempat), (tanggal)</div>
        <div class="mb-2">Nomor: ........................................</div>
        <div class="mb-2">Lampiran: ........................................</div>
        <div class="mb-2">Hal: Anjuran</div>
        <div class="mb-2">Yth. 1. Sdr. .................... (Pengusaha)<br>2. Sdr. ....................
            (Pekerja/Buruh/SP/SB)<br>di ........................................</div>
        <div class="mb-4">
            Sehubungan dengan penyelesaian perselisihan hubungan industrial antara PT. .......... dengan Sdr. ..........
            yang telah dilaksanakan melalui mediasi tidak tercapai kesepakatan dan sesuai ketentuan Pasal 13 ayat (2)
            Undang-Undang Nomor 2 Tahun 2004 tentang Penyelesaian Perselisihan Hubungan Industrial, maka Mediator
            Hubungan Industrial mengeluarkan anjuran.
        </div>
        <div class="mb-4">
            <b>A. Keterangan pihak Pekerja/Buruh/Serikat Pekerja/Serikat Buruh:</b><br>
            {!! nl2br(e($anjuran->keterangan_pekerja)) !!}
        </div>
        <div class="mb-4">
            <b>B. Keterangan pihak Pengusaha:</b><br>
            {!! nl2br(e($anjuran->keterangan_pengusaha)) !!}
        </div>
        <div class="mb-4">
            <b>C. Pertimbangan Hukum dan Kesimpulan Mediator:</b><br>
            {!! nl2br(e($anjuran->pertimbangan_hukum)) !!}
        </div>
        <div class="mb-4">
            <b>MENGANJURKAN:</b><br>
            {!! nl2br(e($anjuran->isi_anjuran)) !!}
        </div>
        <div class="mb-4">
            3. Agar kedua belah pihak memberikan jawaban atas anjuran tersebut selambat-lambatnya dalam jangka waktu 10
            (sepuluh) hari kerja setelah menerima surat anjuran ini.<br><br>
            Demikian untuk diketahui dan menjadi perhatian.
        </div>
        <div class="flex justify-between mt-8 mb-8">
            <div class="text-center">
                Mengetahui<br>
                Direktur Jenderal/Kepala Dinas*,<br><br><br>
                (........................................)<br>
                NIP. ........................................
            </div>
            <div class="text-center">
                Mediator Hubungan Industrial,<br><br><br>
                (........................................)<br>
                NIP. ........................................
            </div>
        </div>
        <div class="text-center">
            <a href="{{ route('dokumen.anjuran.pdf', $anjuran->anjuran_id) }}" target="_blank"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Cetak PDF</a>
        </div>
    </div>
</body>

</html>
