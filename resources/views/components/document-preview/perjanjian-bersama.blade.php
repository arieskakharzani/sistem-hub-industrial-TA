@props(['perjanjian'])

<div class="bg-white p-6 rounded-lg border">
    <h3 class="text-lg font-semibold mb-4">Preview Perjanjian Bersama</h3>

    <div class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nomor Perjanjian</label>
                <p class="mt-1 text-sm text-gray-900">{{ $perjanjian->nomor_perjanjian ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Perjanjian</label>
                <p class="mt-1 text-sm text-gray-900">
                    {{ $perjanjian->tanggal_perjanjian ? $perjanjian->tanggal_perjanjian->format('d/m/Y') : '-' }}</p>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Nama Pengusaha</label>
            <p class="mt-1 text-sm text-gray-900">{{ $perjanjian->nama_pengusaha }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Jabatan Pengusaha</label>
            <p class="mt-1 text-sm text-gray-900">{{ $perjanjian->jabatan_pengusaha }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Perusahaan Pengusaha</label>
            <p class="mt-1 text-sm text-gray-900">{{ $perjanjian->perusahaan_pengusaha }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Alamat Pengusaha</label>
            <p class="mt-1 text-sm text-gray-900">{{ $perjanjian->alamat_pengusaha }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Nama Pekerja</label>
            <p class="mt-1 text-sm text-gray-900">{{ $perjanjian->nama_pekerja }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Jabatan Pekerja</label>
            <p class="mt-1 text-sm text-gray-900">{{ $perjanjian->jabatan_pekerja }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Perusahaan Pekerja</label>
            <p class="mt-1 text-sm text-gray-900">{{ $perjanjian->perusahaan_pekerja }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Alamat Pekerja</label>
            <p class="mt-1 text-sm text-gray-900">{{ $perjanjian->alamat_pekerja }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Isi Kesepakatan</label>
            <p class="mt-1 text-sm text-gray-900">{{ $perjanjian->isi_kesepakatan }}</p>
        </div>
    </div>
</div>
