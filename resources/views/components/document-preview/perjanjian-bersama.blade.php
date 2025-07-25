@props(['perjanjian'])

<div class="bg-white p-8 text-black">
    <!-- Header -->
    <div class="text-center mb-6">
        <h1 class="text-xl font-bold">PERJANJIAN BERSAMA</h1>
        {{-- <h2 class="text-lg">Nomor: {{ $perjanjian->nomor_perjanjian }}</h2> --}}
    </div>

    <!-- Content -->
    <div class="space-y-4 text-justify">
        <div>
            <p>Pada hari ini
                {{ $perjanjian->tanggal_perjanjian ? $perjanjian->tanggal_perjanjian->isoFormat('dddd, D MMMM Y') : '' }}
                telah tercapai kesepakatan antara:</p>

            <div class="mt-6">
                <p class="font-bold">PIHAK PERTAMA:</p>
                <div class="mt-2">
                    <p>Nama: {{ $perjanjian->nama_pengusaha }}</p>
                    <p>Jabatan: {{ $perjanjian->jabatan_pengusaha }}</p>
                    <p>Perusahaan: {{ $perjanjian->perusahaan_pengusaha }}</p>
                    <p>Alamat: {{ $perjanjian->alamat_pengusaha }}</p>
                </div>
            </div>

            <div class="mt-6">
                <p class="font-bold">PIHAK KEDUA:</p>
                <div class="mt-2">
                    <p>Nama: {{ $perjanjian->nama_pekerja }}</p>
                    <p>Jabatan: {{ $perjanjian->jabatan_pekerja }}</p>
                    <p>Perusahaan: {{ $perjanjian->perusahaan_pekerja }}</p>
                    <p>Alamat: {{ $perjanjian->alamat_pekerja }}</p>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <p class="font-bold">ISI KESEPAKATAN:</p>
            <p class="mt-2 whitespace-pre-line">{{ $perjanjian->isi_kesepakatan }}</p>
        </div>

        <!-- Signature Section -->
        <div class="mt-12">
            <p class="text-center mb-6">Demikian Perjanjian Bersama ini dibuat dan ditandatangani oleh kedua belah
                pihak
                untuk dilaksanakan sebagaimana mestinya.</p>

            <div class="flex justify-between">
                <div class="w-1/3">
                    <p class="text-center">PIHAK KEDUA,</p>
                    <div class="h-32 flex items-center justify-center">
                        @if ($perjanjian->signature_pekerja)
                            <img src="{{ asset('storage/signatures/' . $perjanjian->signature_pekerja) }}"
                                alt="Tanda Tangan Pekerja" class="max-h-24">
                        @endif
                    </div>
                    <p class="text-center font-bold">{{ $perjanjian->nama_pekerja }}</p>
                </div>

                <div class="w-1/3">
                    <p class="text-center">PIHAK PERTAMA,</p>
                    <div class="h-32 flex items-center justify-center">
                        @if ($perjanjian->signature_pengusaha)
                            <img src="{{ asset('storage/signatures/' . $perjanjian->signature_pengusaha) }}"
                                alt="Tanda Tangan Pengusaha" class="max-h-24">
                        @endif
                    </div>
                    <p class="text-center font-bold">{{ $perjanjian->nama_pengusaha }}</p>
                </div>
            </div>

            <div class="mt-12 flex justify-end">
                <div class="flex flex-col items-end" style="width: 350px;">
                    <div class="mb-2">Muara Bungo,
                        {{ $perjanjian->tanggal_perjanjian ? $perjanjian->tanggal_perjanjian->isoFormat('D MMMM Y') : now()->isoFormat('D MMMM Y') }}
                    </div>
                    <div class="font-semibold">Mediator Hubungan Industrial,</div>
                    <div class="mb-2 flex flex-col items-end w-full">
                        @if ($perjanjian->signature_mediator)
                            <img src="{{ asset('storage/signatures/' . $perjanjian->signature_mediator) }}"
                                alt="Tanda Tangan Mediator" class="max-h-24 mb-2">
                        @else
                            <br><br><br>
                        @endif
                    </div>
                    <div class="font-bold">
                        {{ optional(optional(optional($perjanjian->dokumenHI)->pengaduan)->mediator)->nama_mediator ?? '-' }}
                    </div>
                    <div class="text-sm">NIP.
                        {{ optional(optional(optional($perjanjian->dokumenHI)->pengaduan)->mediator)->nip ?? '-' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
