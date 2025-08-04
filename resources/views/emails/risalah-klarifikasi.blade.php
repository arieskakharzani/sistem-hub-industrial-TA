<x-mail::message>
    # Risalah Klarifikasi - Kasus Selesai

    Halo {{ $userName }},

    Kasus perselisihan hubungan industrial dengan nomor pengaduan **{{ $pengaduan->nomor_pengaduan }}** telah selesai.

    Berdasarkan hasil klarifikasi, kasus ini akan dilanjutkan dengan perundingan bipartit di luar ranah dinas.

    Risalah klarifikasi telah dibuat dan dapat diunduh melalui sistem.

    <x-mail::button :url="url('/pengaduan/' . $pengaduan->pengaduan_id)">
        Lihat Detail Kasus
    </x-mail::button>

    Terima kasih telah menggunakan layanan kami.

    Salam,<br>
    Tim SIPPPHI
</x-mail::message>
