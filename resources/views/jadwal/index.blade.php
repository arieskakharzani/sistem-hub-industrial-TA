<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Atur Jadwal</title>
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
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Kelola Jadwal
                </h2>
                <a href="{{ route('jadwal.create') }}"
                    class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah jadwal
                </a>
            </div>
        </x-slot>

        <div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                {{-- Pesan sukses/error --}}
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Kartu Statistik --}}
                <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-3">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-100 mr-4">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                                    <p class="text-gray-600">Total Jadwal</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-yellow-100 mr-4">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-gray-900">{{ $stats['hari_ini'] }}</p>
                                    <p class="text-gray-600">Hari Ini</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-100 mr-4">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-gray-900">{{ $stats['dijadwalkan'] }}</p>
                                    <p class="text-gray-600">Dijadwalkan</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100 mr-4">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-gray-900">{{ $stats['selesai'] }}</p>
                                    <p class="text-gray-600">Selesai</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>

                {{-- Filter --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <form method="GET" action="{{ route('jadwal.index') }}" class="flex flex-wrap gap-4">
                            <div class="flex-1 min-w-32">
                                <select name="jenis_jadwal" class="w-full rounded-md border-gray-300">
                                    <option value="">Semua Jenis Jadwal</option>
                                    @foreach (\App\Models\Jadwal::getJenisJadwalOptions() as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ request('jenis_jadwal') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-1 min-w-32">
                                <select name="status" class="w-full rounded-md border-gray-300">
                                    <option value="">Semua Status</option>
                                    @foreach (\App\Models\Jadwal::getStatusOptions() as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ request('status') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-1 min-w-32">
                                <select name="bulan" class="w-full rounded-md border-gray-300">
                                    <option value="">Semua Bulan</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}"
                                            {{ request('bulan') == $i ? 'selected' : '' }}>
                                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <button type="submit" style="background-color: #1D4ED8; color: white;"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                                Filter
                            </button>
                            <a href="{{ route('jadwal.index') }}"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">
                                Reset
                            </a>
                        </form>
                    </div>
                </div>

                {{-- Daftar Jadwal --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        @if ($jadwalList->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Nomor Jadwal
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Pengaduan
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Tanggal & Waktu
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Tempat
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Status
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Jenis Jadwal
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Sidang Ke-
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Mediator
                                            </th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                                Aksi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($jadwalList as $jadwal)
                                            @php
                                                $isMine = $jadwal->mediator_id === $mediator->mediator_id;
                                            @endphp
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">
                                                        {{ $jadwal->nomor_jadwal ?? $jadwal->jadwal_id }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        Pengaduan oleh :
                                                        {{ $jadwal->pengaduan->pelapor->nama_pelapor }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $jadwal->pengaduan->perihal }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">
                                                        {{ $jadwal->tanggal->format('d M Y') }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $jadwal->waktu->format('H:i') }} WIB
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm text-gray-900">
                                                        {{ Str::limit($jadwal->tempat, 30) }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $jadwal->getStatusBadgeClass() }}">
                                                        {!! $jadwal->getStatusIcon() !!}
                                                        <span
                                                            class="ml-1">{{ ucfirst($jadwal->status_jadwal) }}</span>
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">
                                                        {{ ucfirst($jadwal->jenis_jadwal) }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">
                                                        {{ $jadwal->sidang_ke ?? '-' }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $jadwal->mediator->nama_mediator ?? '-' }}
                                                        @if ($isMine)
                                                            <span
                                                                class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Anda</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <div class="flex justify-end space-x-2">
                                                        @if ($isMine)
                                                            <a href="{{ route('jadwal.show', $jadwal) }}"
                                                                class="text-blue-600 hover:text-blue-900">
                                                                Lihat
                                                            </a>
                                                            @if (!in_array($jadwal->status_jadwal, ['selesai', 'dibatalkan']))
                                                                <a href="{{ route('jadwal.edit', $jadwal) }}"
                                                                    class="text-yellow-600 hover:text-yellow-900">
                                                                    Edit
                                                                </a>
                                                            @endif
                                                            @if (in_array($jadwal->status_jadwal, ['dijadwalkan', 'ditunda']))
                                                                <form method="POST"
                                                                    action="{{ route('jadwal.destroy', $jadwal) }}"
                                                                    class="inline"
                                                                    onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="text-red-600 hover:text-red-900">
                                                                        Hapus
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        @else
                                                            <span class="text-gray-400 text-xs italic">Mode lihat
                                                                saja</span>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination --}}
                            <div class="mt-6">
                                {{ $jadwalList->withQueryString()->links() }}
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada jadwal</h3>
                                <p class="text-gray-600 mb-4">
                                    Mulai buat jadwal untuk mengelola pengaduan yang ditugaskan kepada Anda.
                                </p>
                                <a href="{{ route('jadwal.create') }}"
                                    class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                                    Buat Jadwal Pertama
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>

</body>

</html>
