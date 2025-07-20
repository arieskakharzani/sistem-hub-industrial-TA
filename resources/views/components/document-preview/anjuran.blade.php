@props(['anjuran'])

<div class="bg-white p-8 text-black">
    <!-- Header -->
    <div class="text-center mb-6">
        <h1 class="text-xl font-bold">ANJURAN TERTULIS</h1>
        <h2 class="text-lg">Nomor: {{ $anjuran->nomor_anjuran }}</h2>
    </div>

    <!-- Content -->
    <div class="space-y-4">
        <div>
            <p>Memperhatikan hasil mediasi antara:</p>

            <div class="mt-4 ml-4">
                <p class="font-bold">PIHAK PERTAMA (PENGUSAHA):</p>
                <div class="ml-4">
                    <p>Nama: {{ $anjuran->nama_pengusaha }}</p>
                    <p>Jabatan: {{ $anjuran->jabatan_pengusaha }}</p>
                    <p>Perusahaan: {{ $anjuran->perusahaan_pengusaha }}</p>
                    <p>Alamat: {{ $anjuran->alamat_pengusaha }}</p>
                </div>
            </div>

            <div class="mt-4 ml-4">
                <p class="font-bold">PIHAK KEDUA (PEKERJA):</p>
                <div class="ml-4">
                    <p>Nama: {{ $anjuran->nama_pekerja }}</p>
                    <p>Jabatan: {{ $anjuran->jabatan_pekerja }}</p>
                    <p>Perusahaan: {{ $anjuran->perusahaan_pekerja }}</p>
                    <p>Alamat: {{ $anjuran->alamat_pekerja }}</p>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <p class="font-bold">KETERANGAN PIHAK PEKERJA:</p>
            <p class="mt-2 whitespace-pre-line">{{ $anjuran->keterangan_pekerja }}</p>
        </div>

        <div class="mt-6">
            <p class="font-bold">KETERANGAN PIHAK PENGUSAHA:</p>
            <p class="mt-2 whitespace-pre-line">{{ $anjuran->keterangan_pengusaha }}</p>
        </div>

        <div class="mt-6">
            <p class="font-bold">PERTIMBANGAN HUKUM:</p>
            <p class="mt-2 whitespace-pre-line">{{ $anjuran->pertimbangan_hukum }}</p>
        </div>

        <div class="mt-6">
            <p class="font-bold">ANJURAN:</p>
            <p class="mt-2 whitespace-pre-line">{{ $anjuran->isi_anjuran }}</p>
        </div>

        <!-- Signature Section -->
        <div class="mt-12">
            <p class="text-center mb-6">Demikian anjuran ini dibuat untuk dapat dilaksanakan sebagaimana mestinya.</p>

            <div class="flex justify-between">
                <div class="w-1/2">
                    <p class="text-center">Mediator Hubungan Industrial,</p>
                    <div class="h-32 flex items-center justify-center">
                        @if ($anjuran->signature_mediator)
                            <img src="{{ Storage::url('signatures/' . $anjuran->signature_mediator) }}"
                                alt="Tanda Tangan Mediator" class="max-h-24">
                        @endif
                    </div>
                    <p class="text-center font-bold">
                        {{ $anjuran->dokumenHI->risalah->jadwal->mediator->nama_mediator }}</p>
                    <p class="text-center">NIP. {{ $anjuran->dokumenHI->risalah->jadwal->mediator->nip }}</p>
                </div>

                <div class="w-1/2">
                    <p class="text-center">Kepala Dinas,</p>
                    <div class="h-32 flex items-center justify-center">
                        @if ($anjuran->signature_kepala_dinas)
                            <img src="{{ Storage::url('signatures/' . $anjuran->signature_kepala_dinas) }}"
                                alt="Tanda Tangan Kepala Dinas" class="max-h-24">
                        @endif
                    </div>
                    <p class="text-center font-bold">{{ $anjuran->kepalaDinas->nama_kepala_dinas }}</p>
                    <p class="text-center">NIP. {{ $anjuran->kepalaDinas->nip }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
