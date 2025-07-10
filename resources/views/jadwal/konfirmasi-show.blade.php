<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Konfirmasi Kehadiran - {{ $jadwal->pengaduan->perihal }}</title>
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
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Konfirmasi Kehadiran Mediasi
                </h2>
                <a href="{{ route('konfirmasi.index') }}"
                    class="inline-flex items-center gap-2 bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Kembali</span>
                </a>
            </div>
        </x-slot>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                {{-- Flash Messages --}}
                @if (session('success'))
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Header Card --}}
                <div class="bg-gradient-to-br from-primary to-primary-light rounded-xl p-8 text-white mb-8">
                    <div class="flex items-start justify-between">
                        <div>
                            <h1 class="text-3xl font-bold mb-2">🗓️ Konfirmasi Kehadiran</h1>
                            <p class="text-primary-lighter mb-4">{{ $jadwal->pengaduan->perihal }}</p>
                            <div class="flex items-center gap-4 text-sm">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <span>{{ $jadwal->tanggal_mediasi->format('d F Y') }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <span>{{ $jadwal->waktu_mediasi->format('H:i') }} WIB</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <span>{{ $jadwal->tempat_mediasi }}</span>
                                </div>
                            </div>
                        </div>
                        @php
                            $userKonfirmasi =
                                $user->role === 'pelapor' ? $jadwal->konfirmasi_pelapor : $jadwal->konfirmasi_terlapor;
                        @endphp
                        <div class="text-right">
                            <span
                                class="inline-block px-4 py-2 rounded-full text-sm font-medium {{ $jadwal->getKonfirmasiBadgeClass($user->role) }} bg-white">
                                {{ ucfirst(str_replace('_', ' ', $userKonfirmasi)) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 space-y-6">
                        {{-- Detail Pengaduan --}}
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Detail Pengaduan</h3>
                            </div>
                            <div class="p-6">
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <p class="text-sm text-gray-500 mb-1">No. Pengaduan</p>
                                        <p class="font-medium">{{ $jadwal->pengaduan->pengaduan_id }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 mb-1">Tanggal Laporan</p>
                                        <p class="font-medium">
                                            {{ $jadwal->pengaduan->tanggal_laporan->format('d F Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 mb-1">Pelapor</p>
                                        <p class="font-medium">{{ $jadwal->pengaduan->pelapor->nama_pelapor ?? '-' }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 mb-1">Terlapor</p>
                                        <p class="font-medium">{{ $jadwal->pengaduan->terlapor->nama_terlapor ?? '-' }}
                                        </p>
                                    </div>
                                    <div class="md:col-span-2">
                                        <p class="text-sm text-gray-500 mb-1">Perihal</p>
                                        <p class="font-medium">{{ $jadwal->pengaduan->perihal }}</p>
                                    </div>
                                    @if ($jadwal->pengaduan->deskripsi_masalah)
                                        <div class="md:col-span-2">
                                            <p class="text-sm text-gray-500 mb-1">Deskripsi Masalah</p>
                                            <p class="text-gray-700">{{ $jadwal->pengaduan->deskripsi_masalah }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Detail Jadwal Mediasi --}}
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Detail Jadwal Mediasi</h3>
                            </div>
                            <div class="p-6">
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <p class="text-sm text-gray-500 mb-1">Mediator</p>
                                        <p class="font-medium">{{ $jadwal->mediator->nama_mediator ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 mb-1">Status Jadwal</p>
                                        <span
                                            class="inline-block px-3 py-1 text-sm rounded-full {{ $jadwal->getStatusBadgeClass() }}">
                                            {{ ucfirst($jadwal->status_jadwal) }}
                                        </span>
                                    </div>
                                    @if ($jadwal->catatan_jadwal)
                                        <div class="md:col-span-2">
                                            <p class="text-sm text-gray-500 mb-1">Catatan Jadwal</p>
                                            <p class="text-gray-700">{{ $jadwal->catatan_jadwal }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Status Konfirmasi --}}
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Status Konfirmasi Kedua Belah Pihak
                                </h3>
                            </div>
                            <div class="p-6">
                                <div class="grid md:grid-cols-2 gap-6">
                                    {{-- Konfirmasi Pelapor --}}
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <h4 class="font-semibold">Pelapor
                                                {{ $user->role === 'pelapor' ? '(Anda)' : '' }}</h4>
                                            <span
                                                class="px-3 py-1 text-sm rounded-full {{ $jadwal->getKonfirmasiBadgeClass('pelapor') }}">
                                                {{ ucfirst(str_replace('_', ' ', $jadwal->konfirmasi_pelapor)) }}
                                            </span>
                                        </div>
                                        @if ($jadwal->konfirmasi_pelapor !== 'pending')
                                            <p class="text-sm text-gray-600 mb-1">Dikonfirmasi pada:</p>
                                            <p class="text-sm font-medium mb-2">
                                                {{ $jadwal->tanggal_konfirmasi_pelapor ? $jadwal->tanggal_konfirmasi_pelapor->format('d F Y, H:i') : '-' }}
                                            </p>
                                            @if ($jadwal->catatan_konfirmasi_pelapor)
                                                <p class="text-sm text-gray-600 mb-1">Catatan:</p>
                                                <p class="text-sm text-gray-800">
                                                    {{ $jadwal->catatan_konfirmasi_pelapor }}</p>
                                            @endif
                                        @endif
                                    </div>

                                    {{-- Konfirmasi Terlapor --}}
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <h4 class="font-semibold">Terlapor
                                                {{ $user->role === 'terlapor' ? '(Anda)' : '' }}</h4>
                                            <span
                                                class="px-3 py-1 text-sm rounded-full {{ $jadwal->getKonfirmasiBadgeClass('terlapor') }}">
                                                {{ ucfirst(str_replace('_', ' ', $jadwal->konfirmasi_terlapor)) }}
                                            </span>
                                        </div>
                                        @if ($jadwal->konfirmasi_terlapor !== 'pending')
                                            <p class="text-sm text-gray-600 mb-1">Dikonfirmasi pada:</p>
                                            <p class="text-sm font-medium mb-2">
                                                {{ $jadwal->tanggal_konfirmasi_terlapor ? $jadwal->tanggal_konfirmasi_terlapor->format('d F Y, H:i') : '-' }}
                                            </p>
                                            @if ($jadwal->catatan_konfirmasi_terlapor)
                                                <p class="text-sm text-gray-600 mb-1">Catatan:</p>
                                                <p class="text-sm text-gray-800">
                                                    {{ $jadwal->catatan_konfirmasi_terlapor }}</p>
                                            @endif
                                        @endif
                                    </div>
                                </div>

                                {{-- Overall Status --}}
                                <div
                                    class="mt-6 p-4 rounded-lg {{ $jadwal->sudahDikonfirmasiSemua() ? ($jadwal->adaYangTidakHadir() ? 'bg-red-50 border border-red-200' : 'bg-green-50 border border-green-200') : 'bg-yellow-50 border border-yellow-200' }}">
                                    @if ($jadwal->sudahDikonfirmasiSemua())
                                        @if ($jadwal->adaYangTidakHadir())
                                            <div class="flex items-center gap-2 text-red-800">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="font-semibold">Perlu Penjadwalan Ulang</span>
                                            </div>
                                            <p class="text-red-700 text-sm mt-1">
                                                Ada pihak yang tidak dapat hadir. Mediator akan menghubungi untuk
                                                penjadwalan ulang.
                                            </p>
                                        @else
                                            <div class="flex items-center gap-2 text-green-800">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="font-semibold">Siap Dilaksanakan</span>
                                            </div>
                                            <p class="text-green-700 text-sm mt-1">
                                                Kedua belah pihak telah mengkonfirmasi kehadiran. Mediasi dapat
                                                dilaksanakan sesuai jadwal.
                                            </p>
                                        @endif
                                    @else
                                        <div class="flex items-center gap-2 text-yellow-800">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="font-semibold">Menunggu Konfirmasi</span>
                                        </div>
                                        <p class="text-yellow-700 text-sm mt-1">
                                            Masih menunggu konfirmasi dari
                                            {{ $jadwal->konfirmasi_pelapor === 'pending' && $jadwal->konfirmasi_terlapor === 'pending' ? 'kedua belah pihak' : ($jadwal->konfirmasi_pelapor === 'pending' ? 'pelapor' : 'terlapor') }}.
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Sidebar --}}
                    <div class="space-y-6">
                        {{-- Form Konfirmasi atau Status --}}
                        @if ($userKonfirmasi === 'pending')
                            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                                <div class="px-6 py-4 bg-primary text-white">
                                    <h3 class="text-lg font-semibold">Konfirmasi Kehadiran</h3>
                                    <p class="text-primary-lighter text-sm">Berikan konfirmasi kehadiran Anda</p>
                                </div>
                                <form action="{{ route('konfirmasi.konfirmasi', $jadwal->jadwal_id) }}"
                                    method="POST" class="p-6">
                                    @csrf
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                                Status Kehadiran <span class="text-red-500">*</span>
                                            </label>
                                            <div class="space-y-3">
                                                <label
                                                    class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-green-50 hover:border-green-300 cursor-pointer">
                                                    <input type="radio" name="konfirmasi" value="hadir"
                                                        class="mr-3 text-green-600" required
                                                        onchange="toggleCatatanField()">
                                                    <div>
                                                        <div class="font-medium text-green-800">✅ Saya akan hadir</div>
                                                        <div class="text-sm text-green-600">Saya akan menghadiri sesi
                                                            mediasi sesuai jadwal</div>
                                                    </div>
                                                </label>
                                                <label
                                                    class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-red-50 hover:border-red-300 cursor-pointer">
                                                    <input type="radio" name="konfirmasi" value="tidak_hadir"
                                                        class="mr-3 text-red-600" required
                                                        onchange="toggleCatatanField()">
                                                    <div>
                                                        <div class="font-medium text-red-800">❌ Saya tidak dapat hadir
                                                        </div>
                                                        <div class="text-sm text-red-600">Saya berhalangan hadir pada
                                                            jadwal yang ditetapkan</div>
                                                    </div>
                                                </label>
                                            </div>
                                            @error('konfirmasi')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div id="catatanField" class="hidden">
                                            <label for="catatan"
                                                class="block text-sm font-medium text-gray-700 mb-2">
                                                <span id="catatanLabel">Catatan</span> <span id="catatanRequired"
                                                    class="text-red-500">*</span>
                                            </label>
                                            <textarea name="catatan" id="catatan" rows="3"
                                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                                placeholder="Mohon jelaskan alasan Anda tidak dapat hadir...">{{ old('catatan') }}</textarea>
                                            @error('catatan')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <button type="submit"
                                            class="w-full bg-blue-800 text-white py-3 rounded-lg hover:bg-primary-dark transition-colors font-medium">
                                            Kirim Konfirmasi
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                                <div class="px-6 py-4 bg-gray-600 text-white">
                                    <h3 class="text-lg font-semibold">Status Konfirmasi Anda</h3>
                                </div>
                                <div class="p-6">
                                    <div class="text-center mb-4">
                                        <span
                                            class="inline-block px-4 py-2 rounded-full text-lg font-semibold {{ $jadwal->getKonfirmasiBadgeClass($user->role) }}">
                                            {{ ucfirst(str_replace('_', ' ', $userKonfirmasi)) }}
                                        </span>
                                    </div>
                                    @php
                                        $userTanggalKonfirmasi =
                                            $user->role === 'pelapor'
                                                ? $jadwal->tanggal_konfirmasi_pelapor
                                                : $jadwal->tanggal_konfirmasi_terlapor;
                                        $userCatatanKonfirmasi =
                                            $user->role === 'pelapor'
                                                ? $jadwal->catatan_konfirmasi_pelapor
                                                : $jadwal->catatan_konfirmasi_terlapor;
                                    @endphp
                                    <div class="text-sm text-gray-600">
                                        <p class="mb-2"><strong>Dikonfirmasi pada:</strong><br>
                                            {{ $userTanggalKonfirmasi ? $userTanggalKonfirmasi->format('d F Y, H:i') : '-' }}
                                        </p>
                                        @if ($userCatatanKonfirmasi)
                                            <p><strong>Catatan Anda:</strong><br>
                                                {{ $userCatatanKonfirmasi }}</p>
                                        @endif
                                    </div>

                                    <form action="{{ route('konfirmasi.cancel', $jadwal->jadwal_id) }}"
                                        method="POST" class="mt-4">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Apakah Anda yakin ingin membatalkan konfirmasi ini?')"
                                            class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition-colors text-sm">
                                            Batalkan Konfirmasi
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        {{-- Quick Actions --}}
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Aksi Lainnya</h3>
                            </div>
                            <div class="p-6 space-y-3">
                                <a href="{{ route('pengaduan.show', $jadwal->pengaduan_id) }}"
                                    class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <span class="text-sm font-medium">Lihat Detail Pengaduan</span>
                                </a>

                                <a href="{{ route('dashboard') }}"
                                    class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                        </path>
                                    </svg>
                                    <span class="text-sm font-medium">Kembali ke Dashboard</span>
                                </a>
                            </div>
                        </div>

                        {{-- Contact Information --}}
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                            <h4 class="font-semibold text-blue-800 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z">
                                    </path>
                                </svg>
                                <span>Butuh Bantuan?</span>
                            </h4>
                            <div class="space-y-2 text-sm text-blue-700">
                                <p>📧 <strong>Email:</strong> mediasi@disnaker.bungo.go.id</p>
                                <p>📱 <strong>Telepon:</strong> (0746) 21234</p>
                                <p>🕒 <strong>Jam Layanan:</strong> Senin - Jumat, 08:00 - 16:00 WIB</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>

    <script>
        function toggleCatatanField() {
            const konfirmasiRadios = document.querySelectorAll('input[name="konfirmasi"]');
            const catatanField = document.getElementById('catatanField');
            const catatanTextarea = document.getElementById('catatan');
            const catatanRequired = document.getElementById('catatanRequired');

            let selectedValue = '';
            konfirmasiRadios.forEach(radio => {
                if (radio.checked) {
                    selectedValue = radio.value;
                }
            });

            if (selectedValue === 'tidak_hadir') {
                // Show catatan field and make it required
                catatanField.classList.remove('hidden');
                catatanTextarea.setAttribute('required', 'required');
                catatanRequired.classList.remove('hidden');
                catatanTextarea.placeholder = 'Mohon jelaskan alasan Anda tidak dapat hadir dan kapan bisa hadir';
            } else if (selectedValue === 'hadir') {
                // Hide catatan field and remove required attribute
                catatanField.classList.add('hidden');
                catatanTextarea.removeAttribute('required');
                catatanRequired.classList.add('hidden');
                catatanTextarea.value = ''; // Clear the field
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleCatatanField();
        });
    </script>
</body>

</html>
