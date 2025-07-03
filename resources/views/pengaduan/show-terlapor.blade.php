<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detail Pengaduan - Terlapor</title>
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
                <a href="{{ route('pengaduan.index-terlapor') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Kembali ke Daftar
                </a>
            </div>
        </x-slot>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

                <!-- Important Notice for Terlapor -->
                <div class="mb-6 bg-orange-50 border border-orange-200 text-orange-800 px-4 py-3 rounded relative">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <strong class="font-bold">Informasi Penting</strong>
                            <span class="block sm:inline">Berikut adalah detail pengaduan yang melibatkan
                                perusahaan/instansi Anda.
                                Silakan tunggu informasi selanjutnya untuk jadwal mediasi dari mediator yang menangani
                                kasus ini.</span>
                        </div>
                    </div>
                </div>

                <!-- Header Info -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">
                                    Pengaduan #{{ $pengaduan->pengaduan_id }}
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 ml-2">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Pihak yang Dilaporkan
                                    </span>
                                </h3>
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span>📅 {{ $pengaduan->tanggal_laporan->format('d F Y') }}</span>
                                    <span>•</span>
                                    <span>📂 {{ $pengaduan->perihal }}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                @php
                                    $statusClass = match ($pengaduan->status) {
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'proses' => 'bg-blue-100 text-blue-800',
                                        'selesai' => 'bg-green-100 text-green-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                                    {{ ucfirst($pengaduan->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <!-- Left Column - Detail Pengaduan -->
                    <div class="lg:col-span-2 space-y-6">

                        <!-- Informasi Pelapor -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pelapor</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nama Pelapor</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ $pengaduan->pelapor->nama_pelapor ?? 'N/A' }}
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Email</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ $pengaduan->pelapor->email ?? 'N/A' }}
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Masa Kerja</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $pengaduan->masa_kerja }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Pihak yang Dilaporkan (Diri Sendiri) -->
                        <div class="bg-orange-50 border border-orange-200 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h4 class="text-lg font-semibold text-orange-900 mb-4">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    Informasi Perusahaan/Instansi Anda
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-orange-700">Nama
                                            Perusahaan/Instansi</label>
                                        <p class="mt-1 text-sm text-orange-900 font-medium">
                                            {{ $pengaduan->nama_terlapor }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-orange-700">Email</label>
                                        <p class="mt-1 text-sm text-orange-900">{{ $pengaduan->email_terlapor }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-orange-700">Nomor Telepon</label>
                                        <p class="mt-1 text-sm text-orange-900">{{ $pengaduan->no_hp_terlapor }}</p>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-orange-700">Alamat
                                            Kantor/Cabang</label>
                                        <p class="mt-1 text-sm text-orange-900">{{ $pengaduan->alamat_kantor_cabang }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Kasus -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Detail Kasus</h4>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Perihal</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $pengaduan->perihal }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Narasi Kasus</label>
                                        <div class="mt-1 text-sm text-gray-900 bg-gray-50 p-4 rounded-lg">
                                            {{ $pengaduan->narasi_kasus }}
                                        </div>
                                    </div>
                                    @if ($pengaduan->catatan_tambahan)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Catatan
                                                Tambahan</label>
                                            <div class="mt-1 text-sm text-gray-900 bg-gray-50 p-4 rounded-lg">
                                                {{ $pengaduan->catatan_tambahan }}
                                            </div>
                                        </div>
                                    @endif
                                    @if ($pengaduan->catatan_mediator)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Catatan
                                                Mediator</label>
                                            <div
                                                class="mt-1 text-sm text-gray-900 bg-blue-50 p-4 rounded-lg border-l-4 border-blue-400">
                                                {{ $pengaduan->catatan_mediator }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Risalah Bipartit -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    Risalah Bipartit
                                </h4>
                                @if ($pengaduan->risalah_bipartit)
                                    <div class="flex items-center p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                        <svg class="w-8 h-8 text-blue-600 mr-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-blue-900">
                                                {{ basename($pengaduan->risalah_bipartit) }}</p>
                                            <p class="text-xs text-blue-700">Dokumen risalah perundingan bipartit yang
                                                diajukan oleh pelapor</p>
                                        </div>
                                        <a href="{{ asset('storage/' . $pengaduan->risalah_bipartit) }}"
                                            target="_blank" class="text-blue-600 hover:text-blue-700 ml-4">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>
                                    </div>
                                @else
                                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.232 13.5c-.77.833.192 2.5 1.732 2.5z">
                                                </path>
                                            </svg>
                                            <p class="text-sm text-yellow-800">Tidak ada risalah bipartit yang diupload
                                                oleh pelapor.</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Lampiran -->
                        @if ($pengaduan->lampiran && count($pengaduan->lampiran) > 0)
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Lampiran</h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        @foreach ($pengaduan->lampiran as $lampiran)
                                            <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                                                <svg class="w-6 h-6 text-gray-400 mr-3" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ basename($lampiran) }}</p>
                                                    <p class="text-xs text-gray-500">Lampiran</p>
                                                </div>
                                                <a href="{{ asset('storage/' . $lampiran) }}" target="_blank"
                                                    class="text-primary hover:text-primary-dark">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>

                    <!-- Right Column - Info & Status -->
                    <div class="space-y-6">

                        <!-- Status Information (Read-Only) -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Status Pengaduan</h4>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Saat Ini</label>
                                    <div class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                                        <span class="text-sm font-medium">{{ ucfirst($pengaduan->status) }}</span>
                                    </div>
                                </div>

                                @if ($pengaduan->catatan_mediator)
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan
                                            Mediator</label>
                                        <div
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-sm">
                                            {{ $pengaduan->catatan_mediator }}
                                        </div>
                                    </div>
                                @endif

                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm text-blue-800 font-medium">
                                                @if ($pengaduan->status === 'pending')
                                                    Pengaduan sedang menunggu untuk ditugaskan ke mediator.
                                                @elseif ($pengaduan->status === 'proses')
                                                    Pengaduan sedang ditangani oleh mediator. Mohon tunggu informasi
                                                    jadwal mediasi.
                                                @else
                                                    Pengaduan telah selesai diproses.
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mediator Info -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Informasi Mediator</h4>
                                @if ($pengaduan->mediator)
                                    <div class="text-center">
                                        <div
                                            class="w-16 h-16 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <span class="text-lg font-medium text-primary">
                                                {{ substr($pengaduan->mediator->nama_mediator ?? 'M', 0, 2) }}
                                            </span>
                                        </div>
                                        <p class="font-medium text-gray-900">
                                            {{ $pengaduan->mediator->nama_mediator ?? 'Mediator' }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ $pengaduan->mediator->user->email ?? 'No Email' }}
                                        </p>

                                        @if ($pengaduan->mediator->nip)
                                            <p class="text-xs text-gray-400 mt-1">
                                                NIP: {{ $pengaduan->mediator->nip }}
                                            </p>
                                        @endif

                                        @if ($pengaduan->assigned_at)
                                            <p class="text-xs text-gray-400 mt-2">
                                                Ditugaskan: {{ $pengaduan->assigned_at->format('d M Y H:i') }}
                                            </p>
                                        @endif

                                        <div class="mt-3 bg-green-50 border border-green-200 rounded-lg p-3">
                                            <p class="text-xs text-green-800">
                                                Mediator yang menangani kasus Anda. Tunggu informasi lebih lanjut
                                                mengenai jadwal mediasi.
                                            </p>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <div
                                            class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                </path>
                                            </svg>
                                        </div>
                                        <p class="text-gray-500 italic mb-3">Belum ditugaskan ke mediator</p>
                                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                            <p class="text-xs text-yellow-800">
                                                Pengaduan akan segera ditugaskan ke mediator yang akan menangani kasus
                                                ini.
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Timeline -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Timeline</h4>
                                <div class="space-y-3">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-green-400 rounded-full mr-3"></div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Pengaduan Dibuat</p>
                                            <p class="text-xs text-gray-500">
                                                {{ $pengaduan->created_at->format('d M Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                    @if ($pengaduan->assigned_at)
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-blue-400 rounded-full mr-3"></div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Ditugaskan ke Mediator</p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $pengaduan->assigned_at->format('d M Y H:i') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-gray-300 rounded-full mr-3"></div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Terakhir Diupdate</p>
                                            <p class="text-xs text-gray-500">
                                                {{ $pengaduan->updated_at->format('d M Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Next Steps -->
                        <div class="bg-orange-50 border border-orange-200 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h4 class="text-lg font-semibold text-orange-900 mb-4">Langkah Selanjutnya</h4>
                                <div class="space-y-3 text-sm text-orange-800">
                                    @if ($pengaduan->status === 'pending')
                                        <div class="flex items-start">
                                            <span
                                                class="inline-block w-6 h-6 bg-orange-200 text-orange-800 rounded-full text-xs text-center leading-6 mr-3 flex-shrink-0">1</span>
                                            <p>Pengaduan akan ditugaskan ke mediator oleh kepala dinas</p>
                                        </div>
                                        <div class="flex items-start">
                                            <span
                                                class="inline-block w-6 h-6 bg-orange-200 text-orange-800 rounded-full text-xs text-center leading-6 mr-3 flex-shrink-0">2</span>
                                            <p>Mediator akan menghubungi Anda untuk mengatur jadwal mediasi</p>
                                        </div>
                                    @elseif ($pengaduan->status === 'proses')
                                        <div class="flex items-start">
                                            <span
                                                class="inline-block w-6 h-6 bg-orange-200 text-orange-800 rounded-full text-xs text-center leading-6 mr-3 flex-shrink-0">1</span>
                                            <p>Tunggu informasi jadwal mediasi dari mediator</p>
                                        </div>
                                        <div class="flex items-start">
                                            <span
                                                class="inline-block w-6 h-6 bg-orange-200 text-orange-800 rounded-full text-xs text-center leading-6 mr-3 flex-shrink-0">2</span>
                                            <p>Siapkan dokumen dan informasi yang diperlukan</p>
                                        </div>
                                        <div class="flex items-start">
                                            <span
                                                class="inline-block w-6 h-6 bg-orange-200 text-orange-800 rounded-full text-xs text-center leading-6 mr-3 flex-shrink-0">3</span>
                                            <p>Hadiri sesi mediasi sesuai jadwal yang ditetapkan</p>
                                        </div>
                                    @else
                                        <div class="flex items-start">
                                            <span
                                                class="inline-block w-6 h-6 bg-green-200 text-green-800 rounded-full text-xs text-center leading-6 mr-3 flex-shrink-0">✓</span>
                                            <p>Pengaduan telah selesai diproses</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
</body>

</html>
