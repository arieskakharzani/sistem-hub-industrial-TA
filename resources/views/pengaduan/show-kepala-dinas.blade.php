<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detail Pengaduan - Kepala Dinas</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                    {{ __('Detail Pengaduan') }}
                </h2>
                <a href="{{ route('pengaduan.index-kepala-dinas') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Kembali ke Daftar
                </a>
            </div>
        </x-slot>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

                <!-- Success Alert -->
                @if (session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        <strong class="font-bold">Berhasil!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Error Alert -->
                @if (session('error'))
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Read-Only Mode Alert for Kepala Dinas -->
                <div class="mb-6 bg-purple-50 border border-purple-200 text-purple-800 px-4 py-3 rounded relative">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <strong class="font-bold">Mode Lihat Saja - Kepala Dinas</strong>
                            <span class="block sm:inline">Anda dapat melihat detail pengaduan ini, namun tidak dapat
                                melakukan aksi edit atau perubahan.</span>
                        </div>
                    </div>
                </div>

                <!-- Header Info -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">
                                    Detail Pengaduan
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 ml-2">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Kepala Dinas View
                                    </span>
                                </h3>
                                <p class="text-gray-600">{{ $pengaduan->nomor_pengaduan }}</p>
                            </div>
                            <div class="text-right">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'proses' => 'bg-blue-100 text-blue-800',
                                        'selesai' => 'bg-green-100 text-green-800',
                                    ];
                                    $statusColor = $statusColors[$pengaduan->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColor }}">
                                    {{ ucfirst($pengaduan->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h4 class="text-lg font-semibold text-gray-900">Informasi Dasar</h4>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h5 class="font-semibold text-gray-900 mb-3">Data Pelapor</h5>
                                <div class="space-y-2">
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Nama:</span>
                                        <span
                                            class="text-sm text-gray-900">{{ $pengaduan->pelapor->nama_pelapor ?? 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Email:</span>
                                        <span
                                            class="text-sm text-gray-900">{{ $pengaduan->pelapor->email ?? 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Telepon:</span>
                                        <span
                                            class="text-sm text-gray-900">{{ $pengaduan->pelapor->telepon ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h5 class="font-semibold text-gray-900 mb-3">Data Terlapor</h5>
                                <div class="space-y-2">
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Nama:</span>
                                        <span
                                            class="text-sm text-gray-900">{{ $pengaduan->terlapor->nama_terlapor ?? 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Email:</span>
                                        <span
                                            class="text-sm text-gray-900">{{ $pengaduan->terlapor->email_terlapor ?? 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Telepon:</span>
                                        <span
                                            class="text-sm text-gray-900">{{ $pengaduan->terlapor->telepon_terlapor ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Case Details -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h4 class="text-lg font-semibold text-gray-900">Detail Kasus</h4>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h5 class="font-semibold text-gray-900 mb-3">Informasi Kasus</h5>
                                <div class="space-y-2">
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Tanggal Laporan:</span>
                                        <span
                                            class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($pengaduan->tanggal_laporan)->format('d/m/Y') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Perihal:</span>
                                        <span class="text-sm text-gray-900">{{ $pengaduan->perihal }}</span>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Masa Kerja:</span>
                                        <span class="text-sm text-gray-900">{{ $pengaduan->masa_kerja }}</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h5 class="font-semibold text-gray-900 mb-3">Mediator</h5>
                                <div class="space-y-2">
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Nama:</span>
                                        <span
                                            class="text-sm text-gray-900">{{ $pengaduan->mediator->nama_mediator ?? 'Belum Ditugaskan' }}</span>
                                    </div>
                                    @if ($pengaduan->mediator)
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Email:</span>
                                            <span
                                                class="text-sm text-gray-900">{{ $pengaduan->mediator->user->email ?? 'N/A' }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Schedule Information -->
                @if ($pengaduan->jadwal && $pengaduan->jadwal->count() > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h4 class="text-lg font-semibold text-gray-900">Jadwal</h4>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach ($pengaduan->jadwal as $jadwal)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h5 class="font-semibold text-gray-900">
                                                    {{ ucfirst($jadwal->jenis_jadwal) }}</h5>
                                                <p class="text-sm text-gray-600">
                                                    {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d/m/Y') }}
                                                    {{ \Carbon\Carbon::parse($jadwal->waktu)->format('H:i') }}
                                                </p>
                                                <p class="text-sm text-gray-600">{{ $jadwal->tempat }}</p>
                                            </div>
                                            <div>
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'confirmed' => 'bg-green-100 text-green-800',
                                                        'completed' => 'bg-blue-100 text-blue-800',
                                                        'cancelled' => 'bg-red-100 text-red-800',
                                                    ];
                                                    $statusColor =
                                                        $statusColors[$jadwal->status_jadwal] ??
                                                        'bg-gray-100 text-gray-800';
                                                @endphp
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
                                                    {{ ucfirst($jadwal->status_jadwal) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Documents Information -->
                {{-- @if ($pengaduan->dokumenHI)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h4 class="text-lg font-semibold text-gray-900">Informasi Dokumen</h4>
                        </div>
                        <div class="p-6">
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Dokumen Tersedia</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Dokumen terkait pengaduan ini dapat diakses melalui menu Dokumen di sidebar.
                                </p>
                                <div class="mt-6">
                                    <a href="{{ route('dokumen.index') }}"
                                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                        Lihat Semua Dokumen
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif --}}

            </div>
        </div>
    </x-app-layout>
</body>

</html>
