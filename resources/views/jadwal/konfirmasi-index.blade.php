<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Jadwal - Konfirmasi Kehadiran</title>
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
                Jadwal - Konfirmasi Kehadiran
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @php
                    // Cari jadwal yang status konfirmasi user-nya masih 'pending'
                    $pendingJadwal = $jadwal->first(function ($item) use ($user) {
                        return ($user->active_role === 'pelapor'
                            ? $item->konfirmasi_pelapor
                            : $item->konfirmasi_terlapor) === 'pending';
                    });
                @endphp
                <!-- Info Card: Penjelasan Konfirmasi -->
                <div class="mb-8 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 text-blue-400 mr-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
                        </svg>
                        <div class="text-blue-800 text-sm">
                            @if ($jadwal->count() === 1 && $pendingJadwal)
                                Jadwal yang akan Anda konfirmasi saat ini adalah <span class="font-semibold">Jadwal
                                    {{ $pendingJadwal->getJenisJadwalLabel() }}</span>
                                yang telah ditetapkan oleh
                                mediator. Silakan klik tombol <span class="font-semibold">Konfirmasi Kehadiran</span>
                                untuk melanjutkan.

                                @if ($pendingJadwal->jenis_jadwal === 'klarifikasi')
                                    <br><br>
                                    <strong>📋 Catatan Khusus Klarifikasi:</strong><br>
                                    Jika Anda tidak dapat hadir, proses klarifikasi tetap akan dilanjutkan dan mediator
                                    akan melanjutkan ke tahap mediasi setelah klarifikasi selesai.
                                @endif
                            @else
                                Halaman ini digunakan oleh <span class="font-semibold">pelapor</span> dan <span
                                    class="font-semibold">terlapor</span> untuk <span
                                    class="font-semibold">mengonfirmasi jadwal</span>
                                yang telah ditetapkan oleh mediator. Silakan klik tombol <span
                                    class="font-semibold">Konfirmasi Kehadiran</span> pada jadwal yang tersedia.
                            @endif
                        </div>
                    </div>
                </div>
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

                {{-- Header Section --}}
                <div class="bg-gradient-to-br from-primary to-primary-light rounded-xl p-8 text-white mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold mb-2">🗓️ Jadwal </h1>
                            <p class="text-white">Konfirmasi kehadiran Anda untuk jadwal yang telah dijadwalkan</p>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold">{{ $jadwal->count() }}</div>
                            <div class="text-sm text-white">Total Jadwal</div>
                        </div>
                    </div>
                </div>

                {{-- Statistics Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    @php
                        $pendingKonfirmasi = $jadwal
                            ->where(
                                $user->active_role === 'pelapor' ? 'konfirmasi_pelapor' : 'konfirmasi_terlapor',
                                'pending',
                            )
                            ->count();
                        $sudahKonfirmasi = $jadwal
                            ->where(
                                $user->active_role === 'pelapor' ? 'konfirmasi_pelapor' : 'konfirmasi_terlapor',
                                '!=',
                                'pending',
                            )
                            ->count();
                        $akanHadir = $jadwal
                            ->where(
                                $user->active_role === 'pelapor' ? 'konfirmasi_pelapor' : 'konfirmasi_terlapor',
                                'hadir',
                            )
                            ->count();
                        $tidakHadir = $jadwal
                            ->where(
                                $user->active_role === 'pelapor' ? 'konfirmasi_pelapor' : 'konfirmasi_terlapor',
                                'tidak_hadir',
                            )
                            ->count();
                    @endphp

                    <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-yellow-500">
                        <div class="flex items-center">
                            <div class="p-3 bg-yellow-100 rounded-lg">
                                <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-600 text-sm">Pending</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $pendingKonfirmasi }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-600 text-sm">Hadir</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $akanHadir }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-red-500">
                        <div class="flex items-center">
                            <div class="p-3 bg-red-100 rounded-lg">
                                <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-600 text-sm">Tidak Dapat Hadir</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $tidakHadir }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-600 text-sm">Total Jadwal</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $jadwal->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Jadwal yang Perlu Dikonfirmasi Section --}}
                @php
                    // Jadwal yang perlu dikonfirmasi: status dijadwalkan, konfirmasi user masih pending,
                    // dan untuk mediasi tidak boleh jadwal yang sudah lewat hari H
                    $jadwalPending = $jadwal
                        ->where('status_jadwal', 'dijadwalkan')
                        ->filter(function ($item) use ($user) {
                            $isUserPending =
                                $user->active_role === 'pelapor'
                                    ? $item->konfirmasi_pelapor === 'pending'
                                    : $item->konfirmasi_terlapor === 'pending';

                            if (!$isUserPending) {
                                return false;
                            }

                            if ($item->jenis_jadwal === 'mediasi') {
                                return $item->tanggal >= now()->toDateString();
                            }

                            return true;
                        });
                @endphp

                @if ($jadwalPending->count() > 0)
                    <div class="mt-8 bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 px-6 py-5 border-b border-yellow-200">
                            <h3 class="text-lg font-semibold text-yellow-800">Jadwal yang Perlu Dikonfirmasi</h3>
                            <p class="text-yellow-700 text-sm mt-1">Silakan konfirmasi kehadiran Anda untuk jadwal
                                berikut</p>
                        </div>
                        <div class="p-6">
                            @foreach ($jadwalPending as $item)
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center mb-2">
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mr-3">
                                                    {{ $item->getJenisJadwalLabel() }}
                                                </span>
                                                <span class="text-sm text-gray-600">
                                                    {{ $item->pengaduan->nomor_pengaduan }}
                                                </span>
                                            </div>
                                            <h4 class="text-lg font-semibold text-gray-900 mb-2">
                                                {{ $item->pengaduan->perihal }}
                                            </h4>
                                            <div
                                                class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600 mb-4">
                                                <div>
                                                    <span class="font-medium">Tanggal:</span>
                                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                                                </div>
                                                <div>
                                                    <span class="font-medium">Waktu:</span>
                                                    {{ \Carbon\Carbon::parse($item->waktu)->format('H:i') }}
                                                </div>
                                                <div>
                                                    <span class="font-medium">Tempat:</span>
                                                    {{ $item->tempat }}
                                                </div>
                                            </div>
                                            @if ($item->catatan_jadwal)
                                                <div class="bg-white p-3 rounded border-l-4 border-blue-400 mb-4">
                                                    <p class="text-sm text-gray-700">
                                                        <span class="font-medium">Catatan:</span>
                                                        {{ $item->catatan_jadwal }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <a href="{{ route('konfirmasi.show', $item->jadwal_id) }}"
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Konfirmasi Kehadiran
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    {{-- Tidak ada jadwal yang perlu dikonfirmasi --}}
                    <div class="mt-8 bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-5 border-b border-green-200">
                            <h3 class="text-lg font-semibold text-green-800">Tidak Ada Jadwal yang Perlu Dikonfirmasi
                            </h3>
                            <p class="text-green-700 text-sm mt-1">Semua jadwal Anda sudah selesai atau tidak
                                memerlukan konfirmasi kehadiran</p>
                        </div>
                        <div class="p-6">
                            <div class="text-center py-8">
                                <div class="text-4xl mb-4 opacity-50">✅</div>
                                <h4 class="text-lg font-semibold text-gray-800 mb-2">Semua Jadwal Sudah Selesai</h4>
                                <p class="text-gray-600">Tidak ada jadwal yang memerlukan konfirmasi kehadiran saat
                                    ini.</p>
                                <p class="text-gray-500 text-sm mt-2">Jadwal baru akan muncul di sini setelah mediator
                                    membuat jadwal untuk Anda.</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Riwayat Jadwal Section --}}
                <div class="mt-8 bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-5 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Riwayat Jadwal</h3>
                        <p class="text-gray-600 text-sm mt-1">Daftar jadwal yang telah Anda lalui</p>
                    </div>
                    <div class="p-6">
                        @php
                            // Ambil semua jadwal yang sudah selesai, dibatalkan, atau ditunda
                            // Tambahkan ke riwayat untuk jadwal yang sudah lewat waktu meski status masih dijadwalkan
                            $riwayatJadwal = $jadwal
                                ->filter(function ($item) {
                                    if (in_array($item->status_jadwal, ['selesai', 'dibatalkan', 'ditunda'])) {
                                        return true;
                                    }

                                    // Klarifikasi atau mediasi yang sudah lewat hari H tetap tampil sebagai riwayat
                                    if (
                                        $item->status_jadwal === 'dijadwalkan' &&
                                        $item->tanggal < now()->toDateString()
                                    ) {
                                        return in_array($item->jenis_jadwal, ['klarifikasi', 'mediasi']);
                                    }

                                    return false;
                                })
                                ->sortByDesc('tanggal');
                        @endphp

                        @if ($riwayatJadwal->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                No. Jadwal
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                No. Pengaduan
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Perihal
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Pelapor
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Terlapor
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Jenis Jadwal
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status Kehadiran
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status Jadwal
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($riwayatJadwal as $item)
                                            <tr class="hover:bg-gray-50">
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $item->nomor_jadwal ?? $item->jadwal_id }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $item->pengaduan->nomor_pengaduan ?? $item->pengaduan_id }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    <div class="max-w-xs truncate"
                                                        title="{{ $item->pengaduan->perihal }}">
                                                        {{ $item->pengaduan->perihal }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $item->pengaduan->pelapor->nama_pelapor ?? '-' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $item->pengaduan->nama_terlapor }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    @php
                                                        $jenisClass = match ($item->jenis_jadwal) {
                                                            'mediasi' => 'bg-purple-100 text-purple-800',
                                                            'klarifikasi' => 'bg-orange-100 text-orange-800',
                                                            default => 'bg-blue-100 text-blue-800',
                                                        };
                                                    @endphp
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $jenisClass }}">
                                                        {{ $item->getJenisJadwalLabel() }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    @php
                                                        $kehadiranClass = match (
                                                            $user->active_role === 'pelapor'
                                                                ? $item->konfirmasi_pelapor
                                                                : $item->konfirmasi_terlapor
                                                        ) {
                                                            'hadir' => 'bg-green-100 text-green-800',
                                                            'tidak_hadir' => 'bg-red-100 text-red-800',
                                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                                            default => 'bg-gray-100 text-gray-800',
                                                        };
                                                        $kehadiranText = match (
                                                            $user->active_role === 'pelapor'
                                                                ? $item->konfirmasi_pelapor
                                                                : $item->konfirmasi_terlapor
                                                        ) {
                                                            'hadir' => 'Hadir',
                                                            'tidak_hadir' => 'Tidak Hadir',
                                                            'pending' => 'Pending',
                                                            default => 'Tidak Diketahui',
                                                        };
                                                    @endphp
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $kehadiranClass }}">
                                                        {{ $kehadiranText }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    @php
                                                        // Tentukan status dan class untuk tampilan
                                                        $displayStatus = $item->status_jadwal;
                                                        $statusClass = match ($item->status_jadwal) {
                                                            'selesai' => 'bg-green-100 text-green-800',
                                                            'dibatalkan' => 'bg-red-100 text-red-800',
                                                            'ditunda' => 'bg-yellow-100 text-yellow-800',
                                                            default => 'bg-gray-100 text-gray-800',
                                                        };

                                                        // Khusus untuk jadwal yang sudah lewat waktu meski status masih dijadwalkan
                                                        if (
                                                            in_array($item->jenis_jadwal, ['klarifikasi', 'mediasi']) &&
                                                            $item->status_jadwal === 'dijadwalkan' &&
                                                            $item->tanggal < now()->toDateString()
                                                        ) {
                                                            $displayStatus = 'selesai';
                                                            $statusClass = 'bg-green-100 text-green-800';
                                                        }
                                                    @endphp
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                                        {{ ucfirst($displayStatus) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="text-4xl mb-4 opacity-50">📅</div>
                                <h4 class="text-lg font-semibold text-gray-800 mb-2">Belum Ada Riwayat Jadwal</h4>
                                <p class="text-gray-600">Riwayat jadwal akan muncul setelah Anda mengikuti jadwal yang
                                    telah ditetapkan.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Help Section --}}
                <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h4 class="font-semibold text-blue-800 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span>Bantuan & Informasi</span>
                    </h4>
                    <div class="grid md:grid-cols-2 gap-4 text-sm text-blue-700">
                        <div>
                            <p class="font-medium mb-2">🕒 Batas Waktu Konfirmasi</p>
                            <p>Harap konfirmasi kehadiran Anda paling lambat 1 hari sebelum jadwal.</p>
                        </div>
                        <div>
                            <p class="font-medium mb-2">📞 Butuh Bantuan?</p>
                            <p>Hubungi: (0747) 21013 atau email: nakertrans@bungokab.go.id</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
</body>

</html>
