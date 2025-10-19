{{-- resources/views/emails/mediasi-proceed.blade.php --}}
<x-mail::message>
    # Mediasi Akan Dilanjutkan

    Halo Mediator {{ $notifiable->name }},

    Kami ingin memberitahukan bahwa jadwal mediasi dengan nomor **{{ $jadwal->nomor_jadwal }}** akan tetap dilanjutkan
    meskipun ada pihak yang tidak dapat hadir.

    **Detail Jadwal Mediasi:**
    - **Nomor Pengaduan:** {{ $jadwal->pengaduan->nomor_pengaduan }}
    - **Sidang Ke:** {{ $jadwal->sidang_ke }}
    - **Tanggal:** {{ $jadwal->tanggal->format('d F Y') }}
    - **Waktu:** {{ $jadwal->waktu->format('H:i') }} WIB
    - **Tempat:** {{ $jadwal->tempat }}
    - **Pihak yang Tidak Hadir:** {{ $absentPartyLabel }}
    @if ($reason)
        - **Catatan:** {{ $reason }}
    @endif

    **Tindakan Selanjutnya:**
    Anda dapat melanjutkan proses mediasi sesuai jadwal. Setelah mediasi selesai, Anda dapat membuat risalah
    penyelesaian.

    <x-mail::button :url="route('jadwal.show', $jadwal->jadwal_id)">
        Lihat Detail Jadwal
    </x-mail::button>

    Terima kasih atas perhatian Anda.

    Hormat kami,<br>
    {{ config('app.name') }}
</x-mail::message>

