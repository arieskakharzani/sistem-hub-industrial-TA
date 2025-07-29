@props(['anjuran'])

<div class="bg-white p-6 rounded-lg border">
    <h3 class="text-lg font-semibold mb-4">Preview Anjuran</h3>

    <div class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nomor Anjuran</label>
                <p class="mt-1 text-sm text-gray-900">{{ $anjuran->nomor_anjuran ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Anjuran</label>
                <p class="mt-1 text-sm text-gray-900">
                    {{ $anjuran->tanggal_anjuran ? $anjuran->tanggal_anjuran->format('d/m/Y') : '-' }}</p>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Nama Pengusaha</label>
            <p class="mt-1 text-sm text-gray-900">{{ $anjuran->nama_pengusaha }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Jabatan Pengusaha</label>
            <p class="mt-1 text-sm text-gray-900">{{ $anjuran->jabatan_pengusaha }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Perusahaan Pengusaha</label>
            <p class="mt-1 text-sm text-gray-900">{{ $anjuran->perusahaan_pengusaha }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Alamat Pengusaha</label>
            <p class="mt-1 text-sm text-gray-900">{{ $anjuran->alamat_pengusaha }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Nama Pekerja</label>
            <p class="mt-1 text-sm text-gray-900">{{ $anjuran->nama_pekerja }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Jabatan Pekerja</label>
            <p class="mt-1 text-sm text-gray-900">{{ $anjuran->jabatan_pekerja }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Perusahaan Pekerja</label>
            <p class="mt-1 text-sm text-gray-900">{{ $anjuran->perusahaan_pekerja }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Alamat Pekerja</label>
            <p class="mt-1 text-sm text-gray-900">{{ $anjuran->alamat_pekerja }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Keterangan Pekerja</label>
            <p class="mt-1 text-sm text-gray-900">{{ $anjuran->keterangan_pekerja ?? '-' }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Keterangan Pengusaha</label>
            <p class="mt-1 text-sm text-gray-900">{{ $anjuran->keterangan_pengusaha ?? '-' }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Pertimbangan Hukum</label>
            <p class="mt-1 text-sm text-gray-900">{{ $anjuran->pertimbangan_hukum ?? '-' }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Isi Anjuran</label>
            <p class="mt-1 text-sm text-gray-900">{{ $anjuran->isi_anjuran }}</p>
        </div>
    </div>
</div>
