<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Terlapor
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('akun.terlapor.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Kembali
                </a>
                @if (!$terlapor->has_account)
                    <a href="{{ route('akun.terlapor.create-account', $terlapor) }}"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Buat Akun
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Informasi Terlapor -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Perusahaan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nama Perusahaan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $terlapor->nama_terlapor }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Alamat</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $terlapor->alamat_kantor_cabang }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $terlapor->email_terlapor }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">No. HP</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $terlapor->no_hp_terlapor ?? '-' }}</dd>
                                </div>
                            </dl>
                        </div>
                        <div>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status Akun</dt>
                                    <dd class="mt-1">
                                        @if ($terlapor->has_account)
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $terlapor->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $terlapor->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        @else
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Belum Ada Akun
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                @if ($terlapor->has_account)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Tanggal Pembuatan Akun</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ $terlapor->account_created_at->format('d/m/Y H:i') }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Login Terakhir</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ $terlapor->last_login_at ? $terlapor->last_login_at->format('d/m/Y H:i') : 'Belum Pernah Login' }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Dibuat Oleh</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ $terlapor->mediator->nama_mediator ?? 'Unknown' }}
                                        </dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Pengaduan -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Daftar Pengaduan
                        <span class="text-sm text-gray-500">(Total: {{ $terlapor->total_pengaduan }})</span>
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nomor Pengaduan
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pelapor
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Perihal
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($terlapor->pengaduans as $pengaduan)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $pengaduan->nomor_pengaduan ?? $pengaduan->pengaduan_id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $pengaduan->tanggal_laporan->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $pengaduan->pelapor->nama_pelapor }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $pengaduan->pelapor->email }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $pengaduan->perihal }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @switch($pengaduan->status)
                                                    @case('pending')
                                                        bg-yellow-100 text-yellow-800
                                                        @break
                                                    @case('proses')
                                                        bg-blue-100 text-blue-800
                                                        @break
                                                    @case('selesai')
                                                        bg-green-100 text-green-800
                                                        @break
                                                @endswitch
                                            ">
                                                {{ ucfirst($pengaduan->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('pengaduan.show', $pengaduan) }}"
                                                class="text-blue-600 hover:text-blue-900">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6"
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Belum ada pengaduan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
