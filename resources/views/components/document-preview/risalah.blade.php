@props(['risalah'])

<div class="bg-white p-8 text-black">
    <!-- Header -->
    <div class="text-center mb-6">
        <h1 class="text-xl font-bold">RISALAH {{ strtoupper($risalah->jenis_risalah) }}</h1>
        <h2 class="text-lg">Nomor: {{ $risalah->nomor_risalah ?? '-' }}</h2>
    </div>

    <!-- Content -->
    <div class="space-y-4">
        <div>
            <p>Pada hari ini telah dilakukan {{ $risalah->jenis_risalah }} antara:</p>

            <div class="mt-4 ml-4">
                <p class="font-bold">PIHAK PERTAMA (PENGUSAHA):</p>
                <div class="ml-4">
                    <p>Nama Perusahaan: {{ $risalah->nama_perusahaan }}</p>
                    <p>Jenis Usaha: {{ $risalah->jenis_usaha }}</p>
                    <p>Alamat: {{ $risalah->alamat_perusahaan }}</p>
                </div>
            </div>

            <div class="mt-4 ml-4">
                <p class="font-bold">PIHAK KEDUA (PEKERJA):</p>
                <div class="ml-4">
                    <p>Nama: {{ $risalah->nama_pekerja }}</p>
                    <p>Alamat: {{ $risalah->alamat_pekerja }}</p>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <p class="font-bold">POKOK PERMASALAHAN:</p>
            <p class="mt-2">{{ $risalah->pokok_masalah }}</p>
        </div>

        <div class="mt-6">
            <p class="font-bold">PENDAPAT PIHAK PEKERJA:</p>
            <p class="mt-2">{{ $risalah->pendapat_pekerja }}</p>
        </div>

        <div class="mt-6">
            <p class="font-bold">PENDAPAT PIHAK PENGUSAHA:</p>
            <p class="mt-2">{{ $risalah->pendapat_pengusaha }}</p>
        </div>

        <!-- Signature Section -->
        <div class="mt-12 flex justify-between">
            <div class="w-1/3">
                <p class="text-center">Pihak Pekerja,</p>
                <div class="h-32 flex items-center justify-center">
                    @if ($risalah->signature_pekerja)
                        <img src="{{ Storage::url('signatures/' . $risalah->signature_pekerja) }}"
                            alt="Tanda Tangan Pekerja" class="max-h-24">
                    @endif
                </div>
                <p class="text-center font-bold">{{ $risalah->nama_pekerja }}</p>
            </div>

            <div class="w-1/3">
                <p class="text-center">Pihak Pengusaha,</p>
                <div class="h-32 flex items-center justify-center">
                    @if ($risalah->signature_pengusaha)
                        <img src="{{ Storage::url('signatures/' . $risalah->signature_pengusaha) }}"
                            alt="Tanda Tangan Pengusaha" class="max-h-24">
                    @endif
                </div>
                <p class="text-center font-bold">{{ $risalah->nama_pengusaha }}</p>
            </div>

            <div class="w-1/3">
                <p class="text-center">Mediator Hubungan Industrial,</p>
                <div class="h-32 flex items-center justify-center">
                    @if ($risalah->signature_mediator)
                        <img src="{{ Storage::url('signatures/' . $risalah->signature_mediator) }}"
                            alt="Tanda Tangan Mediator" class="max-h-24">
                    @endif
                </div>
                <p class="text-center font-bold">{{ $risalah->jadwal->mediator->nama_mediator }}</p>
                <p class="text-center">NIP. {{ $risalah->jadwal->mediator->nip }}</p>
            </div>
        </div>
    </div>
</div>
