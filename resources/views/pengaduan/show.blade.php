<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detail Pengaduan - Mediator</title>
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
                <a href="{{ route('pengaduan.kelola') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Kembali ke Kelola
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

                @php
                    $currentUser = auth()->user();
                    $currentMediator = $currentUser->role === 'mediator' ? $currentUser->mediator : null;
                    $isAssignedMediator = $currentMediator && $pengaduan->mediator_id === $currentMediator->mediator_id;
                    $isKepalaDinas = $currentUser->role === 'kepala_dinas';
                    $canManageActions = $isAssignedMediator || $isKepalaDinas;
                    $isViewOnlyMediator = $currentUser->role === 'mediator' && !$isAssignedMediator;
                @endphp

                <!-- View-Only Mode Alert for Non-Assigned Mediators -->
                @if ($isViewOnlyMediator)
                    <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded relative">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <strong class="font-bold">Mode Lihat Saja</strong>
                                <span class="block sm:inline">Anda dapat melihat detail pengaduan ini, namun tidak dapat
                                    melakukan aksi karena bukan mediator yang bertanggung jawab.</span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Header Info -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">
                                    Detail Pengaduan
                                    <!-- Responsibility Indicator -->
                                    @if ($isAssignedMediator)
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 ml-2">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Tanggung Jawab Anda
                                        </span>
                                    @elseif ($isViewOnlyMediator)
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 ml-2">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            Lihat Saja
                                        </span>
                                    @endif
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
                                            {{ $pengaduan->pelapor->nama_pelapor ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Email</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $pengaduan->pelapor->email ?? 'N/A' }}
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Masa Kerja</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $pengaduan->masa_kerja }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Pihak yang Dilaporkan -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pihak yang Dilaporkan
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Pihak yang
                                            Dilaporkan</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $pengaduan->nama_terlapor }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Email Terlapor</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $pengaduan->email_terlapor }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nomor Telepon
                                            Terlapor</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $pengaduan->no_hp_terlapor }}</p>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Alamat Kantor
                                            Cabang</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $pengaduan->alamat_kantor_cabang }}
                                        </p>
                                    </div>
                                    <div class="md:col-span-2">
                                        @php
                                            // Cek apakah terlapor sudah memiliki akun (menggunakan relasi terlapor_id)
                                            $terlaporAccount = $pengaduan->terlapor;
                                        @endphp

                                        @if ($terlaporAccount)
                                            <!-- Jika akun sudah ada, tampilkan info dan link ke detail akun -->
                                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                                <div class="flex items-center">
                                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span class="text-sm text-green-800 font-medium">Akun terlapor sudah
                                                        dibuat</span>
                                                </div>
                                                <div class="mt-3">
                                                    <!-- ✅ UPDATED: Hanya mediator yang bertanggung jawab yang bisa lihat detail akun -->
                                                    @if ($canManageActions)
                                                        <a href="{{ route('mediator.akun.show', $terlaporAccount->terlapor_id) }}"
                                                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                            <svg class="w-4 h-4 mr-2" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                                </path>
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                                </path>
                                                            </svg>
                                                            Lihat Detail Akun
                                                        </a>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-500 uppercase tracking-widest cursor-not-allowed">
                                                            <svg class="w-4 h-4 mr-2" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                                </path>
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                                </path>
                                                            </svg>
                                                            Akun Sudah Dibuat
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <!-- Jika akun belum ada, tampilkan button untuk membuat akun -->
                                            <!-- Hanya mediator yang bertanggung jawab atau kepala dinas yang bisa buat akun -->
                                            @if ($canManageActions)
                                                <a href="{{ route('mediator.akun.create', ['pengaduan_id' => $pengaduan->pengaduan_id]) }}"
                                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dark focus:bg-primary-dark active:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition ease-in-out duration-150">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                    </svg>
                                                    Buat Akun Pihak yang Dilaporkan
                                                </a>
                                            @else
                                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                                    <p class="text-sm text-gray-600 text-center">
                                                        <!-- Pesan yang lebih spesifik -->
                                                        @if ($isViewOnlyMediator)
                                                            Akun belum dibuat. Hanya mediator yang bertanggung jawab
                                                            atas kasus ini yang dapat membuat akun terlapor.
                                                        @else
                                                            Akun belum dibuat. Silakan hubungi mediator yang menangani
                                                            kasus ini.
                                                        @endif
                                                    </p>
                                                </div>
                                            @endif
                                        @endif
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
                                    <div class="flex items-center p-4 bg-green-50 border border-green-200 rounded-lg">
                                        <svg class="w-8 h-8 text-green-600 mr-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-green-900">
                                                {{ basename($pengaduan->risalah_bipartit) }}</p>
                                            <p class="text-xs text-green-700">Bukti upaya penyelesaian bipartit -
                                                Sesuai UU No. 2/2004</p>
                                        </div>
                                        <a href="{{ asset('storage/' . $pengaduan->risalah_bipartit) }}"
                                            target="_blank" class="text-green-600 hover:text-green-700 ml-4">
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

                                    <!-- Validation Info for Mediator -->
                                    <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-3">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-blue-600 mr-2" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                            <p class="text-xs text-blue-800">
                                                <strong>Catatan:</strong> Dokumen ini merupakan bukti bahwa pelapor
                                                telah melakukan upaya penyelesaian secara bipartit sebelum mengajukan
                                                pengaduan ke Dinas, sesuai dengan ketentuan Pasal 3 UU No. 2 Tahun 2004.
                                            </p>
                                        </div>
                                    </div>
                                @else
                                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-red-600 mr-2" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm text-red-800 font-medium">Tidak ada risalah bipartit!
                                                </p>
                                                <p class="text-xs text-red-600 mt-1">Pengaduan ini tidak memenuhi
                                                    syarat karena tidak melampirkan bukti upaya penyelesaian bipartit.
                                                </p>
                                            </div>
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

                    <!-- Right Column - Actions & Info -->
                    <div class="space-y-6">

                        <!-- Status Management -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Kelola Status</h4>

                                <!-- ✅ UPDATED: Assign to Self (only if not assigned and user is mediator) -->
                                @if (!$pengaduan->mediator_id && $currentUser->role === 'mediator')
                                    <form method="POST"
                                        action="{{ route('pengaduan.assign', $pengaduan->pengaduan_id) }}"
                                        class="mb-4">
                                        @csrf
                                        <button type="submit" style="background-color: #3cbb6b; color: white;"
                                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                                            onclick="return confirm('Ambil pengaduan ini untuk ditangani?')">
                                            Ambil Pengaduan
                                        </button>
                                    </form>
                                @endif

                                <!-- ✅ UPDATED: Show assigned info with different messages -->
                                @if ($pengaduan->mediator_id && !$canManageActions && $currentUser->role === 'mediator')
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                            <span class="text-sm text-blue-800 font-medium">
                                                Pengaduan ini ditangani oleh
                                                {{ $pengaduan->mediator->nama_mediator ?? 'mediator lain' }}
                                            </span>
                                        </div>
                                    </div>
                                @endif

                                <!-- ✅ UPDATED: Update Status Form - Only for authorized users -->
                                @if ($canManageActions)
                                    <form method="POST"
                                        action="{{ route('pengaduan.updateStatus', $pengaduan->pengaduan_id) }}">
                                        @csrf
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                            <select name="status" required
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                                                <option value="pending"
                                                    {{ $pengaduan->status == 'pending' ? 'selected' : '' }}>Pending
                                                </option>
                                                <option value="proses"
                                                    {{ $pengaduan->status == 'proses' ? 'selected' : '' }}>Dalam Proses
                                                </option>
                                                <option value="selesai"
                                                    {{ $pengaduan->status == 'selesai' ? 'selected' : '' }}>Selesai
                                                </option>
                                            </select>
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan
                                                Mediator</label>
                                            <textarea name="catatan_mediator" rows="4"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                                                placeholder="Tambahkan catatan untuk pengaduan ini...">{{ $pengaduan->catatan_mediator }}</textarea>
                                        </div>
                                        <button type="submit" style="background-color: #1D4ED8; color: white;"
                                            class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded">
                                            Update Status
                                        </button>
                                    </form>
                                @else
                                    <!-- ✅ UPDATED: Show read-only form with better messaging -->
                                    <div class="opacity-60">
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                            <select disabled
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 cursor-not-allowed">
                                                <option>{{ ucfirst($pengaduan->status) }}</option>
                                            </select>
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan
                                                Mediator</label>
                                            <textarea disabled rows="4"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 cursor-not-allowed"
                                                placeholder="Tidak ada catatan">{{ $pengaduan->catatan_mediator }}</textarea>
                                        </div>
                                        <button disabled
                                            class="w-full bg-gray-300 text-gray-500 font-bold py-2 px-4 rounded cursor-not-allowed">
                                            @if ($isViewOnlyMediator)
                                                Hanya untuk Mediator yang Bertanggung Jawab
                                            @else
                                                Tidak Dapat Mengelola Status
                                            @endif
                                        </button>
                                    </div>
                                @endif
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
                                            <!-- ✅ NEW: Indicator if this is current user -->
                                            @if ($isAssignedMediator)
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 ml-1">
                                                    Anda
                                                </span>
                                            @endif
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ $pengaduan->mediator->user->email ?? 'No Email' }}</p>

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

                                        <!-- Button untuk kepala dinas only -->
                                        @if ($isKepalaDinas && $pengaduan->mediator_id)
                                            <form method="POST"
                                                action="{{ route('pengaduan.release', $pengaduan->pengaduan_id) }}"
                                                class="mt-3">
                                                @csrf
                                                <button type="submit"
                                                    class="w-full bg-orange-500 hover:bg-orange-600 text-white text-xs py-1 px-2 rounded"
                                                    onclick="return confirm('Lepas pengaduan ini dari mediator?')">
                                                    Lepas dari Mediator
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @else
                                    <div class="text-center">
                                        <p class="text-gray-500 italic mb-3">Belum ditugaskan ke mediator</p>

                                        <!-- Auto assign untuk kepala dinas only -->
                                        @if ($isKepalaDinas)
                                            <form method="POST"
                                                action="{{ route('pengaduan.autoAssign', $pengaduan->pengaduan_id) }}">
                                                @csrf
                                                <button type="submit"
                                                    class="w-full bg-blue-500 hover:bg-blue-600 text-white text-xs py-2 px-3 rounded"
                                                    onclick="return confirm('Auto assign ke mediator dengan beban kerja teringan?')">
                                                    Auto Assign Mediator
                                                </button>
                                            </form>
                                        @endif
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
                                                {{ $pengaduan->created_at->format('d M Y H:i') }}</p>
                                        </div>
                                    </div>
                                    @if ($pengaduan->assigned_at)
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-blue-400 rounded-full mr-3"></div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Ditugaskan ke Mediator</p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $pengaduan->assigned_at->format('d M Y H:i') }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-gray-300 rounded-full mr-3"></div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Terakhir Diupdate</p>
                                            <p class="text-xs text-gray-500">
                                                {{ $pengaduan->updated_at->format('d M Y H:i') }}</p>
                                        </div>
                                    </div>
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
