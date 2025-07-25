@props(['anjuran'])

<div class="bg-white p-8 text-black">
    <!-- Header -->
    <div class="text-center mb-6">
        <h1 class="text-xl font-bold">ANJURAN</h1>
    </div>

    <!-- Content -->
    <div class="space-y-4 text-justify">
        <div>
            <p>Memperhatikan hasil mediasi antara:</p>

            <div class="mt-6">
                <p class="font-bold">PIHAK PERTAMA (PENGUSAHA):</p>
                <div class="mt-2">
                    <p>Nama: {{ $anjuran->nama_pengusaha }}</p>
                    <p>Jabatan: {{ $anjuran->jabatan_pengusaha }}</p>
                    <p>Perusahaan: {{ $anjuran->perusahaan_pengusaha }}</p>
                    <p>Alamat: {{ $anjuran->alamat_pengusaha }}</p>
                </div>
            </div>

            <div class="mt-6">
                <p class="font-bold">PIHAK KEDUA (PEKERJA):</p>
                <div class="mt-2">
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
        <div class="mt-12 flex justify-end">
            <div class="flex flex-col items-end" style="width: 350px;">
                <div class="mb-2">Muara Bungo,
                    {{ $anjuran->created_at ? \Carbon\Carbon::parse($anjuran->created_at)->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}
                </div>
                <div class="font-semibold">Mediator Hubungan Industrial,</div>
                <div class="mb-2 flex flex-col items-end w-full">
                    @if ($anjuran->signature_mediator)
                        <img src="{{ asset('storage/signatures/' . $anjuran->signature_mediator) }}"
                            alt="Tanda Tangan Mediator" class="max-h-24 mb-2">
                    @else
                        <br><br><br>
                    @endif
                </div>
                <div class="font-bold">
                    {{ optional(optional(optional(optional(optional($anjuran)->dokumenHI)->risalah)->jadwal)->mediator)->nama_mediator ?? '-' }}
                </div>
                <div class="text-sm">NIP.
                    {{ optional(optional(optional(optional(optional($anjuran)->dokumenHI)->risalah)->jadwal)->mediator)->nip ?? '-' }}
                </div>
            </div>
        </div>
    </div>
</div>
