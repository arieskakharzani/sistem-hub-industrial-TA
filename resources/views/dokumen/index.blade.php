<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kelola Dokumen Hubungan Industrial</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#0000AB',
                        'primary-light': '#3333CC',
                        'primary-lighter': '#6666DD',
                        'primary-dark': '#000088'
                    }
                }
            }
        }
    </script>
</head>

<body>
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Dokumen dan Surat-Surat Perselisihan Hubungan Industrial') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                {{-- Performance Metrics --}}
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-lg font-semibold">Daftar Dokumen</h4>
                        <div class="mb-6">
                            <form method="GET" action="" class="flex items-center gap-4">
                                <label for="jenis_dokumen" class="font-medium">Filter Jenis Dokumen:</label>
                                <select name="jenis_dokumen" id="jenis_dokumen" class="border rounded p-2"
                                    onchange="this.form.submit()">
                                    <option value="Semua"{{ empty($filter) || $filter == 'Semua' ? ' selected' : '' }}>
                                        Semua
                                    </option>
                                    @foreach ($jenisDokumenList as $jenis)
                                        <option value="{{ $jenis }}"{{ $filter == $jenis ? ' selected' : '' }}>
                                            {{ $jenis }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                        <!--<button
                            class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                            + Tambah Dokumen
                        </button> -->
                    </div>


                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nomor Pengaduan</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jenis Dokumen</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Perusahaan</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Pekerja</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Dibuat Oleh</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($pagedDokumenList as $index => $dokumen)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ ($pagedDokumenList->currentPage() - 1) * $pagedDokumenList->perPage() + $index + 1 }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if (
                                                $dokumen->jenis_dokumen == 'Risalah Klarifikasi' ||
                                                    $dokumen->jenis_dokumen == 'Risalah Mediasi' ||
                                                    $dokumen->jenis_dokumen == 'Risalah Penyelesaian')
                                                {{ optional(optional($dokumen->jadwal)->pengaduan)->nomor_pengaduan ?? '-' }}
                                            @elseif($dokumen->jenis_dokumen == 'Perjanjian Bersama' || $dokumen->jenis_dokumen == 'Anjuran')
                                                @php
                                                    $pengaduan = null;
                                                    if ($dokumen->dokumenHI && $dokumen->dokumenHI->risalah) {
                                                        $risalah = $dokumen->dokumenHI->risalah->first();
                                                        if (
                                                            $risalah &&
                                                            $risalah->jadwal &&
                                                            $risalah->jadwal->pengaduan
                                                        ) {
                                                            $pengaduan = $risalah->jadwal->pengaduan;
                                                        }
                                                    }
                                                @endphp
                                                {{ $pengaduan ? $pengaduan->nomor_pengaduan : '-' }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <span
                                                class="px-3 py-1 text-xs font-semibold rounded-full
                                                @if ($dokumen->jenis_dokumen == 'Risalah Klarifikasi') bg-purple-100 text-purple-800
                                                @elseif($dokumen->jenis_dokumen == 'Risalah Mediasi') bg-orange-100 text-orange-800
                                                @elseif($dokumen->jenis_dokumen == 'Risalah Penyelesaian') bg-pink-100 text-pink-800
                                                @elseif($dokumen->jenis_dokumen == 'Perjanjian Bersama') bg-blue-100 text-blue-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ $dokumen->jenis_dokumen }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $dokumen->tanggal_dokumen ? \Carbon\Carbon::parse($dokumen->tanggal_dokumen)->format('d M Y') : '-' }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $dokumen->pihak_pengusaha }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $dokumen->pihak_pekerja }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if ($dokumen->jenis_dokumen == 'Perjanjian Bersama' || $dokumen->jenis_dokumen == 'Anjuran')
                                                @php
                                                    $mediator = null;
                                                    if ($dokumen->dokumenHI && $dokumen->dokumenHI->risalah) {
                                                        $risalah = $dokumen->dokumenHI->risalah->first();
                                                        if (
                                                            $risalah &&
                                                            $risalah->jadwal &&
                                                            $risalah->jadwal->mediator
                                                        ) {
                                                            $mediator = $risalah->jadwal->mediator;
                                                        }
                                                    }
                                                @endphp
                                                {{ $mediator ? $mediator->nama_mediator : '-' }}
                                            @elseif(
                                                $dokumen->jenis_dokumen == 'Risalah Klarifikasi' ||
                                                    $dokumen->jenis_dokumen == 'Risalah Mediasi' ||
                                                    $dokumen->jenis_dokumen == 'Risalah Penyelesaian')
                                                {{ optional(optional($dokumen->jadwal)->mediator)->nama_mediator ?? '-' }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            @if (
                                                $dokumen->jenis_dokumen == 'Risalah Klarifikasi' ||
                                                    $dokumen->jenis_dokumen == 'Risalah Mediasi' ||
                                                    $dokumen->jenis_dokumen == 'Risalah Penyelesaian')
                                                @php
                                                    try {
                                                        $risalahUrl = route('risalah.show', $dokumen->id);
                                                    } catch (\Exception $e) {
                                                        $risalahUrl = '#';
                                                    }
                                                @endphp
                                                <a href="{{ $risalahUrl }}"
                                                    class="text-blue-600 hover:text-blue-900 mr-2">Lihat</a>
                                                @php
                                                    try {
                                                        $destroyUrl = route('risalah.destroy', $dokumen->id);
                                                    } catch (\Exception $e) {
                                                        $destroyUrl = '#';
                                                    }
                                                @endphp
                                                <form action="{{ $destroyUrl }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus risalah ini?')">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @elseif ($dokumen->jenis_dokumen == 'Perjanjian Bersama')
                                                <a href="{{ route('dokumen.perjanjian-bersama.show', $dokumen->id) }}"
                                                    class="text-blue-600 hover:text-blue-900 mr-2">Lihat</a>
                                                <form
                                                    action="{{ route('dokumen.perjanjian-bersama.destroy', $dokumen->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus perjanjian bersama ini?')">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @elseif ($dokumen->jenis_dokumen == 'Anjuran')
                                                <a href="{{ route('dokumen.anjuran.show', $dokumen->id) }}"
                                                    class="text-blue-600 hover:text-blue-900 mr-2">Lihat</a>
                                                <form action="{{ route('dokumen.anjuran.destroy', $dokumen->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus anjuran ini?')">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-8 text-gray-500">Belum ada dokumen.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $pagedDokumenList->withQueryString()->links() }}</div>
                </div>
                <br>

                {{-- Tabel Buku Register Perselisihan --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold">Buku Register Perselisihan</h4>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nomor Register</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal Register</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Pengaduan</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Catatan</th>
                                        <th
                                            class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($registerList as $index => $register)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ ($registerList->currentPage() - 1) * $registerList->perPage() + $index + 1 }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $register->nomor_register }}</td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $register->tanggal_register ? \Carbon\Carbon::parse($register->tanggal_register)->format('d M Y') : '-' }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $register->pengaduan->perihal ?? '-' }}</td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $register->catatan ?? '-' }}</td>
                                            <td class="px-4 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <a href="#"
                                                    class="text-blue-600 hover:text-blue-900 mr-2">Lihat</a>
                                                <a href="#" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-8 text-gray-500">Belum ada
                                                register.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-4">{{ $registerList->withQueryString()->links() }}</div>
                        </div>
                    </div>
                </div>
                {{-- Tabel Laporan Hasil Mediasi (placeholder) --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold">Laporan Hasil Mediasi</h4>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nomor Laporan</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Pengaduan</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Catatan</th>
                                        <th
                                            class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($laporanList as $index => $laporan)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ ($laporanList->currentPage() - 1) * $laporanList->perPage() + $index + 1 }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $laporan->nomor_laporan }}</td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $laporan->tanggal ? \Carbon\Carbon::parse($laporan->tanggal)->format('d M Y') : '-' }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $laporan->pengaduan->perihal ?? '-' }}</td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $laporan->catatan ?? '-' }}</td>
                                            <td class="px-4 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <a href="#"
                                                    class="text-blue-600 hover:text-blue-900 mr-2">Lihat</a>
                                                <a href="#"
                                                    class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-8 text-gray-500">Belum ada
                                                laporan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-4">{{ $laporanList->withQueryString()->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
</body>

</html>
