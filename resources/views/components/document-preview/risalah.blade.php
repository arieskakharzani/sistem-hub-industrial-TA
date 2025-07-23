@props(['risalah'])

<div class="bg-white p-8 text-black">
    <div class="text-center mb-6">
        <h1 class="text-xl font-bold">RISALAH {{ strtoupper($risalah->jenis_risalah) }} PERSELISIHAN<br>HUBUNGAN
            INDUSTRIAL</h1>
    </div>
    <table class="min-w-full table-auto mb-6 border-collapse">
        <tr>
            <td class="font-semibold align-top pr-2">1.</td>
            <td class="font-semibold align-top pr-2">Nama Perusahaan</td>
            <td class="align-top pr-2">:</td>
            <td>{{ $risalah->nama_perusahaan }}</td>
        </tr>
        <tr>
            <td class="font-semibold align-top pr-2">2.</td>
            <td class="font-semibold align-top pr-2">Jenis Usaha</td>
            <td class="align-top pr-2">:</td>
            <td>{{ $risalah->jenis_usaha }}</td>
        </tr>
        <tr>
            <td class="font-semibold align-top pr-2">3.</td>
            <td class="font-semibold align-top pr-2">Alamat Perusahaan</td>
            <td class="align-top pr-2">:</td>
            <td>{{ $risalah->alamat_perusahaan }}</td>
        </tr>
        <tr>
            <td class="font-semibold align-top pr-2">4.</td>
            <td class="font-semibold align-top pr-2">Nama Pekerja/Buruh/SP/SB</td>
            <td class="align-top pr-2">:</td>
            <td>{{ $risalah->nama_pekerja }}</td>
        </tr>
        <tr>
            <td class="font-semibold align-top pr-2">5.</td>
            <td class="font-semibold align-top pr-2">Alamat Pekerja/Buruh/SP/SB</td>
            <td class="align-top pr-2">:</td>
            <td>{{ $risalah->alamat_pekerja }}</td>
        </tr>
        <tr>
            <td class="font-semibold align-top pr-2">6.</td>
            <td class="font-semibold align-top pr-2">Tanggal dan Tempat Perundingan</td>
            <td class="align-top pr-2">:</td>
            <td>{{ $risalah->tanggal_perundingan ? \Carbon\Carbon::parse($risalah->tanggal_perundingan)->translatedFormat('d F Y') : '' }},
                {{ $risalah->tempat_perundingan }}</td>
        </tr>
        <tr>
            <td class="font-semibold align-top pr-2">7.</td>
            <td class="font-semibold align-top pr-2">Pokok Masalah/Alasan Perselisihan</td>
            <td class="align-top pr-2">:</td>
            <td>{{ $risalah->pokok_masalah }}</td>
        </tr>
        <tr>
            <td class="font-semibold align-top pr-2">8.</td>
            <td class="font-semibold align-top pr-2">Keterangan/Pendapat Pekerja/Buruh/SP/SB</td>
            <td class="align-top pr-2">:</td>
            <td>{{ $risalah->pendapat_pekerja }}</td>
        </tr>
        <tr>
            <td class="font-semibold align-top pr-2">9.</td>
            <td class="font-semibold align-top pr-2">Keterangan/Pendapat Pengusaha</td>
            <td class="align-top pr-2">:</td>
            <td>{{ $risalah->pendapat_pengusaha }}</td>
        </tr>
        @if ($risalah->jenis_risalah === 'klarifikasi')
            <tr>
                <td class="font-semibold align-top pr-2">10.</td>
                <td class="font-semibold align-top pr-2">Arahan Mediator</td>
                <td class="align-top pr-2">:</td>
                <td>{{ optional($risalah->detailKlarifikasi)->arahan_mediator }}</td>
            </tr>
            <tr>
                <td class="font-semibold align-top pr-2">11.</td>
                <td class="font-semibold align-top pr-2">Kesimpulan atau Hasil Klarifikasi</td>
                <td class="align-top pr-2">:</td>
                <td>
                    @if (optional($risalah->detailKlarifikasi)->kesimpulan_klarifikasi === 'bipartit_lagi')
                        Perundingan Bipartit
                    @elseif(optional($risalah->detailKlarifikasi)->kesimpulan_klarifikasi === 'lanjut_ke_tahap_mediasi')
                        Lanjut ke Tahap Mediasi
                    @else
                        {{ optional($risalah->detailKlarifikasi)->kesimpulan_klarifikasi }}
                    @endif
                    <div class="text-xs text-gray-500 italic mt-2">
                        Keterangan: dalam membuat Kesimpulan atau hasil klarifikasi agar ditegaskan penyelesaian
                        perselisihannya. Ada 3 alternatif, yaitu a) sepakat untuk melakukan perundingan bipartit; atau
                        b) sepakat akan melanjutkan penyelesaian melalui mediasi dengan hasil perjanjian bersama; atau
                        c) sepakat akan melanjutkan penyelesaian melalui mediasi dengan hasil anjuran.
                    </div>
                </td>
            </tr>
        @endif
    </table>
    <div class="mt-8 text-right">
        <div>Muara Bungo,
            {{ $risalah->tanggal_perundingan ? \Carbon\Carbon::parse($risalah->tanggal_perundingan)->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}
        </div>
        <div class="mt-2">Mediator Hubungan Industrial,</div>
        <div class="mt-8 mb-2">
            @if ($risalah->signature_mediator)
                <img src="{{ asset('storage/signatures/' . $risalah->signature_mediator) }}"
                    alt="Tanda Tangan Mediator" class="max-h-24 mx-auto mb-2">
            @else
                <br><br><br>
            @endif
        </div>
        <div class="font-bold">{{ optional(optional($risalah->jadwal)->mediator)->nama_mediator ?? '-' }}</div>
        <div class="text-sm">NIP. {{ optional(optional($risalah->jadwal)->mediator)->nip ?? '-' }}</div>
    </div>
</div>
