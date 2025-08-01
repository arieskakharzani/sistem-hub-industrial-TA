<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kelola Pengaduan</title>
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
                    {{ __('Kelola Pengaduan') }}
                </h2>
                <!-- ✅ NEW: Mode indicator untuk mediator -->
                @if (auth()->user()->role === 'mediator')
                    <div class="text-sm text-gray-600">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Semua Pengaduan Dapat Dilihat
                        </span>
                    </div>
                @endif
            </div>
        </x-slot>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Statistics Cards with different data for mediator vs kepala dinas -->
                <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                    @if (auth()->user()->role === 'mediator')
                        <!-- Stats untuk Mediator -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="p-3 bg-blue-100 rounded-lg">
                                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                            <path fill-rule="evenodd"
                                                d="M4 5a2 2 0 012-2v1a1 1 0 001 1h6a1 1 0 001-1V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-gray-600 text-sm">Total Semua Pengaduan</p>
                                        <p class="text-2xl font-bold text-gray-900">
                                            {{ $stats['total_semua_pengaduan'] ?? ($stats['total_kasus'] ?? 0) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="p-3 bg-yellow-100 rounded-lg">
                                        <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-gray-600 text-sm">Kasus Aktif</p>
                                        <p class="text-2xl font-bold text-gray-900">
                                            {{ $stats['kasus_aktif'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="p-3 bg-green-100 rounded-lg">
                                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-gray-600 text-sm">Kasus Selesai</p>
                                        <p class="text-2xl font-bold text-gray-900">
                                            {{ $stats['kasus_selesai'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="p-3 bg-purple-100 rounded-lg">
                                        <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-gray-600 text-sm">Tersedia Diambil</p>
                                        <p class="text-2xl font-bold text-gray-900">
                                            {{ $stats['pengaduan_tersedia'] ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Stats untuk Kepala Dinas (tidak berubah) -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="p-3 bg-blue-100 rounded-lg">
                                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                            <path fill-rule="evenodd"
                                                d="M4 5a2 2 0 012-2v1a1 1 0 001 1h6a1 1 0 001-1V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-gray-600 text-sm">Total Pengaduan</p>
                                        <p class="text-2xl font-bold text-gray-900">
                                            {{ $stats['total_kasus'] ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="p-3 bg-yellow-100 rounded-lg">
                                        <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-gray-600 text-sm">Kasus Aktif</p>
                                        <p class="text-2xl font-bold text-gray-900">{{ $stats['kasus_aktif'] ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="p-3 bg-green-100 rounded-lg">
                                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-gray-600 text-sm">Kasus Selesai</p>
                                        <p class="text-2xl font-bold text-gray-900">{{ $stats['kasus_selesai'] ?? 0 }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <br>

                <!-- Kolom Pencarian -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <form method="GET" action="" class="flex flex-wrap gap-4">
                            <div class="flex-1 min-w-32">
                                <input type="text" name="q" value="{{ request('q') }}"
                                    placeholder="Cari pengaduan..."
                                    class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring" />
                            </div>
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">Cari</button>
                            @if (request('q'))
                                <a href="{{ route('pengaduan.kelola') }}"
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">Reset</a>
                            @endif
                        </form>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                    @if (isset($pengaduans) && $pengaduans->count() > 0)
                        <!-- Table with new Status Akun Terlapor column -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nomor Pengaduan</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Pelapor</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Perihal</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Pihak Terlapor</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status Akun Terlapor</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Mediator</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal Laporan</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($pengaduans as $index => $pengaduan)
                                        @php
                                            $currentUser = auth()->user();
                                            $currentMediator =
                                                $currentUser->active_role === 'mediator'
                                                    ? $currentUser->mediator
                                                    : null;
                                            $isAssignedToCurrentUser =
                                                $currentMediator &&
                                                $pengaduan->mediator_id === $currentMediator->mediator_id;
                                            $isUnassigned = !$pengaduan->mediator_id;
                                            $canTakeAction =
                                                $isAssignedToCurrentUser ||
                                                $currentUser->active_role === 'kepala_dinas';

                                            $existingTerlapor = \App\Models\Terlapor::where(
                                                'email_terlapor',
                                                $pengaduan->email_terlapor,
                                            )
                                                ->whereHas('user')
                                                ->first();
                                        @endphp

                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ ($pengaduans->currentPage() - 1) * $pengaduans->perPage() + $index + 1 }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $pengaduan->nomor_pengaduan ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $pengaduan->pelapor->nama_pelapor ?? 'N/A' }}</div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $pengaduan->pelapor->email ?? 'N/A' }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900">{{ $pengaduan->perihal }}</div>
                                                <div class="text-xs text-gray-500">Masa Kerja:
                                                    {{ $pengaduan->masa_kerja }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900 max-w-xs">
                                                    {{ $pengaduan->nama_terlapor ?? 'N/A' }}</div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $pengaduan->email_terlapor ?? 'N/A' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($existingTerlapor)
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Sudah Terdaftar
                                                    </span>
                                                @else
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        Belum Ada Akun
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($pengaduan->mediator)
                                                    <div class="flex items-center">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $pengaduan->mediator->nama_mediator }}
                                                        </div>
                                                        @if ($isAssignedToCurrentUser)
                                                            <span
                                                                class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                                Anda
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $pengaduan->assigned_at ? $pengaduan->assigned_at->format('d/m/Y') : '' }}
                                                    </div>
                                                @else
                                                    <div class="flex items-center">
                                                        <span class="text-sm text-gray-500 italic">Belum
                                                            ditugaskan</span>
                                                        @if ($currentUser->active_role === 'mediator')
                                                            <span
                                                                class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                Dapat Diambil
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusClass = match ($pengaduan->status) {
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'proses' => 'bg-blue-100 text-blue-800',
                                                        'selesai' => 'bg-green-100 text-green-800',
                                                        default => 'bg-gray-100 text-gray-800',
                                                    };
                                                @endphp
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                                    {{ ucfirst($pengaduan->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $pengaduan->tanggal_laporan ? $pengaduan->tanggal_laporan->format('d/m/Y') : 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <!-- Detail button - semua bisa lihat -->
                                                    <a href="{{ route('pengaduan.show', $pengaduan->pengaduan_id) }}"
                                                        class="text-primary hover:text-primary-dark transition-colors">
                                                        <svg class="w-4 h-4 inline mr-1" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                            </path>
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                            </path>
                                                        </svg>
                                                        {{ $canTakeAction ? 'Kelola' : 'Lihat' }}
                                                    </a>

                                                    <!-- Aksi untuk terlapor yang sudah terdaftar -->
                                                    @if ($existingTerlapor)
                                                        @php
                                                            $notificationSent =
                                                                $pengaduan->terlapor_id ===
                                                                $existingTerlapor->terlapor_id;
                                                        @endphp
                                                        @if ($notificationSent)
                                                            <span class="inline-flex items-center text-gray-500">
                                                                <svg class="w-4 h-4 mr-1" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                                Notifikasi Terkirim
                                                            </span>
                                                        @else
                                                            <form method="POST"
                                                                action="{{ route('pengaduan.notify-existing-terlapor', $pengaduan) }}"
                                                                class="inline">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="text-blue-600 hover:text-blue-900">
                                                                    <svg class="w-4 h-4 inline mr-1" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                                        </path>
                                                                    </svg>
                                                                    Kirim Notifikasi
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endif

                                                    <!-- Ambil button untuk pengaduan yang belum diambil -->
                                                    @if ($isUnassigned && $currentUser->active_role === 'mediator')
                                                        <form method="POST"
                                                            action="{{ route('pengaduan.assign', $pengaduan->pengaduan_id) }}"
                                                            class="inline">
                                                            @csrf
                                                            <button type="submit"
                                                                class="text-green-600 hover:text-green-900 transition-colors"
                                                                onclick="return confirm('Ambil pengaduan ini untuk ditangani?')">
                                                                <svg class="w-4 h-4 inline mr-1" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                                </svg>
                                                                Ambil
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <!-- Quick status buttons hanya untuk yang authorized -->
                                                    @if ($canTakeAction)
                                                        @if ($pengaduan->status === 'pending')
                                                            <button
                                                                onclick="updateStatus({{ $pengaduan->pengaduan_id }}, 'proses')"
                                                                class="text-blue-600 hover:text-blue-900 transition-colors">
                                                                <svg class="w-4 h-4 inline mr-1" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                    </path>
                                                                </svg>
                                                                Proses
                                                            </button>
                                                        @endif

                                                        @if ($pengaduan->status === 'proses')
                                                            <button
                                                                onclick="updateStatus({{ $pengaduan->pengaduan_id }}, 'selesai')"
                                                                class="text-green-600 hover:text-green-900 transition-colors">
                                                                <svg class="w-4 h-4 inline mr-1" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                                Selesai
                                                            </button>
                                                            <button
                                                                onclick="updateStatus({{ $pengaduan->pengaduan_id }}, 'ditunda')"
                                                                class="text-orange-600 hover:text-orange-900 transition-colors ml-2">
                                                                <svg class="w-4 h-4 inline mr-1" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                    </path>
                                                                </svg>
                                                                Tunda
                                                            </button>
                                                            <button
                                                                onclick="updateStatus({{ $pengaduan->pengaduan_id }}, 'dibatalkan')"
                                                                class="text-red-600 hover:text-red-900 transition-colors ml-2">
                                                                <svg class="w-4 h-4 inline mr-1" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M6 18L18 6M6 6l12 12">
                                                                    </path>
                                                                </svg>
                                                                Batalkan
                                                            </button>
                                                        @endif
                                                    @elseif ($currentUser->active_role === 'mediator' && !$isUnassigned)
                                                        <!-- Show disabled message for mediator non-assigned -->
                                                        <span class="text-gray-400 text-xs italic">
                                                            Lihat saja
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if (method_exists($pengaduans, 'links'))
                            <div class="px-6 py-4 border-t border-gray-200">
                                {{ $pengaduans->links() }}
                            </div>
                        @endif
                    @else
                        <!-- Empty State -->
                        <div class="p-12">
                            <div class="text-center">
                                <div
                                    class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>

                                <div class="max-w-md mx-auto">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum ada pengaduan</h3>
                                    <p class="text-gray-600 mb-8 leading-relaxed">
                                        Saat ini belum ada pengaduan yang perlu dikelola. Pengaduan baru akan muncul di
                                        sini.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- JavaScript for actions and filtering -->
        <script>
            function updateStatus(jadwalId, newStatus) {
                const statusText = {
                    'dijadwalkan': 'Dijadwalkan',
                    'berlangsung': 'Berlangsung',
                    'selesai': 'Selesai',
                    'ditunda': 'Ditunda',
                    'dibatalkan': 'Dibatalkan'
                };

                if (confirm(`Apakah Anda yakin ingin mengubah status menjadi "${statusText[newStatus]}"?`)) {
                    // Create form data
                    const formData = new FormData();
                    formData.append('status_jadwal', newStatus);
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                    // Send AJAX request
                    fetch(`/jadwal/${jadwalId}/update-status`, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Show success message
                                alert(data.message);
                                // Reload page to show updated status
                                window.location.reload();
                            } else {
                                // Show error message
                                alert(data.message || 'Terjadi kesalahan saat mengupdate status');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat mengupdate status');
                        });
                }
            }
        </script>
    </x-app-layout>
</body>

</html>
