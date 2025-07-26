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
                                    {{ ucfirst($pendingJadwal->jenis_jadwal) }}</span> yang telah ditetapkan oleh
                                mediator. Silakan klik tombol <span class="font-semibold">Konfirmasi Kehadiran</span>
                                untuk melanjutkan.
                            @else
                                Halaman ini digunakan oleh <span class="font-semibold">pelapor</span> dan <span
                                    class="font-semibold">terlapor</span> untuk <span
                                    class="font-semibold">mengonfirmasi jadwal panggilan klarifikasi atau mediasi</span>
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
                            <p class="text-white">Konfirmasi kehadiran Anda untuk panggilan klarifikasi/mediasi yang
                                telah
                                dijadwalkan</p>
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
                                <p class="text-gray-600 text-sm">Menunggu Konfirmasi</p>
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
                                <p class="text-gray-600 text-sm">Akan Hadir</p>
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

                {{-- Jadwal List --}}
                @if ($jadwal->count() > 0)
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Daftar Jadwal</h3>
                            <p class="text-sm text-gray-600">Klik "Konfirmasi" untuk memberikan konfirmasi kehadiran
                                Anda</p>
                        </div>

                        <div class="divide-y divide-gray-200">
                            @foreach ($jadwal as $item)
                                @php
                                    $userKonfirmasi =
                                        $user->active_role === 'pelapor'
                                            ? $item->konfirmasi_pelapor
                                            : $item->konfirmasi_terlapor;
                                    $userTanggalKonfirmasi =
                                        $user->active_role === 'pelapor'
                                            ? $item->tanggal_konfirmasi_pelapor
                                            : $item->tanggal_konfirmasi_terlapor;
                                    $userCatatanKonfirmasi =
                                        $user->active_role === 'pelapor'
                                            ? $item->catatan_konfirmasi_pelapor
                                            : $item->catatan_konfirmasi_terlapor;

                                    $otherRole = $user->active_role === 'pelapor' ? 'terlapor' : 'pelapor';
                                    $otherKonfirmasi =
                                        $user->active_role === 'pelapor'
                                            ? $item->konfirmasi_terlapor
                                            : $item->konfirmasi_pelapor;
                                @endphp

                                <div class="p-6 {{ $userKonfirmasi === 'pending' ? 'bg-yellow-50' : 'bg-white' }}">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex-1">
                                            <h4 class="text-lg font-semibold text-gray-900 mb-2">
                                                {{ $item->pengaduan->perihal }}
                                            </h4>
                                            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                                                <div>
                                                    <p class="text-gray-500">Tanggal & Waktu</p>
                                                    <p class="font-medium">
                                                        {{ $item->tanggal->format('d F Y') }}<br>
                                                        {{ $item->waktu->format('H:i') }} WIB
                                                    </p>
                                                </div>
                                                <div>
                                                    <p class="text-gray-500">Tempat</p>
                                                    <p class="font-medium">{{ $item->tempat }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-gray-500">Mediator</p>
                                                    <p class="font-medium">
                                                        {{ $item->mediator->nama_mediator ?? '-' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-gray-500">Status Jadwal</p>
                                                    <span
                                                        class="inline-block px-2 py-1 text-xs rounded-full {{ $item->getStatusBadgeClass() }}">
                                                        {{ ucfirst($item->status_jadwal) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="ml-6 text-right">
                                            <span
                                                class="inline-block px-3 py-1 text-sm rounded-full {{ $item->getKonfirmasiBadgeClass($user->active_role) }}">
                                                {{ ucfirst(str_replace('_', ' ', $userKonfirmasi)) }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Konfirmasi Status --}}
                                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <h5 class="font-semibold text-gray-800 mb-2">Status Konfirmasi</h5>
                                            <div class="space-y-2 text-sm">
                                                <div class="flex justify-between items-center">
                                                    <span>{{ $user->active_role === 'pelapor' ? 'Pelapor (Anda)' : 'Pelapor' }}:
                                                        {{ $item->pengaduan->pelapor->nama_pelapor ?? '-' }}</span>
                                                    <span
                                                        class="px-2 py-1 text-xs rounded-full {{ $item->getKonfirmasiBadgeClass('pelapor') }}">
                                                        {{ ucfirst(str_replace('_', ' ', $item->konfirmasi_pelapor)) }}
                                                    </span>
                                                </div>
                                                <div class="flex justify-between items-center">
                                                    <span>{{ $user->active_role === 'terlapor' ? 'Terlapor (Anda)' : 'Terlapor' }}:
                                                        {{ $item->pengaduan->nama_terlapor ?? '-' }}</span>
                                                    <span
                                                        class="px-2 py-1 text-xs rounded-full {{ $item->getKonfirmasiBadgeClass('terlapor') }}">
                                                        {{ ucfirst(str_replace('_', ' ', $item->konfirmasi_terlapor)) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($userKonfirmasi !== 'pending')
                                            <div class="bg-gray-50 p-4 rounded-lg">
                                                <h5 class="font-semibold text-gray-800 mb-2">Konfirmasi Anda</h5>
                                                <div class="text-sm">
                                                    <p class="text-gray-600">Dikonfirmasi pada:</p>
                                                    <p class="font-medium">
                                                        {{ $userTanggalKonfirmasi ? $userTanggalKonfirmasi->format('d F Y, H:i') : '-' }}
                                                    </p>
                                                    @if ($userCatatanKonfirmasi)
                                                        <p class="text-gray-600 mt-2">Catatan:</p>
                                                        <p class="font-medium">{{ $userCatatanKonfirmasi }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Action Buttons --}}
                                    <div class="flex flex-wrap gap-3">
                                        @if ($userKonfirmasi === 'pending')
                                            <a href="{{ route('konfirmasi.show', $item->jadwal_id) }}"
                                                class="inline-flex items-center gap-2 bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary-dark transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>Konfirmasi Kehadiran</span>
                                            </a>
                                        @else
                                            <a href="{{ route('konfirmasi.show', $item->jadwal_id) }}"
                                                class="inline-flex items-center gap-2 bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                                <span>Lihat Detail</span>
                                            </a>

                                            <form action="{{ route('konfirmasi.cancel', $item->jadwal_id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    onclick="return confirm('Apakah Anda yakin ingin membatalkan konfirmasi ini?')"
                                                    class="inline-flex items-center gap-2 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                    <span>Batal Konfirmasi</span>
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('pengaduan.show', $item->pengaduan_id) }}"
                                            class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            <span>Lihat Pengaduan</span>
                                        </a>
                                    </div>

                                    {{-- Warning jika ada yang tidak hadir --}}
                                    @if ($item->adaYangTidakHadir())
                                        <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                                            <div class="flex items-center gap-2 text-red-800">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="font-semibold">Perhatian!</span>
                                            </div>
                                            <p class="text-red-700 text-sm mt-1">
                                                Ada pihak yang tidak dapat hadir. Status jadwal telah diubah menjadi
                                                "Ditunda".
                                                Mediator akan menghubungi untuk penjadwalan ulang.
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                        <div class="text-8xl mb-6 opacity-50">📅</div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Belum Ada jadwal</h3>
                        <p class="text-gray-600 mb-6 max-w-md mx-auto">
                            Saat ini Anda belum memiliki jadwal yang perlu dikonfirmasi.
                            Jadwal akan muncul setelah mediator menetapkan waktu klarifikasi atau mediasi.
                        </p>
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-dark transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                    </span>
                            </svg>
                            <span>Kembali ke Dashboard</span>
                        </a>
                    </div>
                @endif

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
                            <p>Hubungi: (0746) 21234 atau email: mediasi@disnaker.bungo.go.id</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
</body>

</html>
