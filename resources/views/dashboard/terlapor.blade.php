<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard Terlapor</title>
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
                Dashboard Terlapor
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                {{-- Welcome Section --}}
                <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl p-8 text-white mb-6">
                    <h3 class="text-2xl font-bold mb-2">Selamat Datang, {{ $user->terlapor->nama_terlapor }}</h3>
                    <p class="text-yellow-100">Dashboard Pihak Berselisih yang Dilaporkan - Sistem Informasi Pengaduan
                        dan Penyelesaian Perselisihan Hubungan Industrial Kab. Bungo</p>
                </div>

                {{-- Jadwal Mediasi Alert (jika ada) --}}
                @if ($jadwalMediasi->where('konfirmasi_terlapor', 'pending')->count() > 0)
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 mb-6 rounded-r-xl">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    <span class="font-medium">Perhatian!</span>
                                    Anda memiliki {{ $jadwalMediasi->where('konfirmasi_terlapor', 'pending')->count() }}
                                    jadwal mediasi yang menunggu konfirmasi kehadiran.
                                </p>
                            </div>
                            <div class="ml-auto">
                                <a href="{{ route('konfirmasi.index') }}"
                                    class="bg-yellow-400 hover:bg-yellow-500 text-yellow-900 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    Lihat Jadwal
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-3 lg:grid-cols-3 gap-6">
                    {{-- Statistics --}}
                    <div class="lg:col-span-2">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm">
                                <div class="flex items-center">
                                    <div class="p-3 bg-red-100 rounded-lg">
                                        <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-gray-600 text-sm">Total Aduan</p>
                                        <p class="text-2xl font-bold text-gray-900">
                                            {{ $stats['total_aduan_terhadap_saya'] }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white p-6 rounded-lg shadow-sm">
                                <div class="flex items-center">
                                    <div class="p-3 bg-blue-100 rounded-lg">
                                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-gray-600 text-sm">Dijadwalkan Mediasi</p>
                                        {{-- <p class="text-2xl font-bold text-gray-900">{{ $stats['dijadwalkan_mediasi'] }} --}}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white p-6 rounded-lg shadow-sm">
                                <div class="flex items-center">
                                    <div class="p-3 bg-purple-100 rounded-lg">
                                        <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-gray-600 text-sm">Menunggu Konfirmasi</p>
                                        <p class="text-2xl font-bold text-gray-900">
                                            {{ $stats['jadwal_menunggu_konfirmasi'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Jadwal Mediasi Section --}}
                        @if ($jadwalMediasi->count() > 0)
                            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                                <h4 class="text-lg font-semibold mb-4 flex items-center gap-2">
                                    <span>🗓️</span>
                                    <span>Jadwal Mediasi</span>
                                    @if ($jadwalMediasi->where('konfirmasi_terlapor', 'pending')->count() > 0)
                                        <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                            {{ $jadwalMediasi->where('konfirmasi_terlapor', 'pending')->count() }}
                                            menunggu
                                        </span>
                                    @endif
                                </h4>

                                <div class="space-y-4">
                                    @foreach ($jadwalMediasi->take(5) as $jadwal)
                                        <div
                                            class="border border-gray-200 rounded-lg p-4 {{ $jadwal->konfirmasi_terlapor === 'pending' ? 'bg-yellow-50 border-yellow-300' : 'bg-gray-50' }}">
                                            <div class="flex justify-between items-start mb-3">
                                                <div>
                                                    <h5 class="font-semibold text-gray-800">
                                                        Mediasi - {{ $jadwal->pengaduan->perihal }}
                                                    </h5>
                                                    <p class="text-sm text-gray-600 mb-1">
                                                        Pelapor: {{ $jadwal->pengaduan->pelapor->nama_pelapor ?? '-' }}
                                                    </p>
                                                    <p class="text-sm text-gray-600 mb-2">
                                                        Mediator: {{ $jadwal->mediator->nama_mediator ?? '-' }}
                                                    </p>
                                                </div>
                                                <span
                                                    class="text-xs px-2 py-1 rounded-full {{ $jadwal->getKonfirmasiBadgeClass('terlapor') }}">
                                                    {{ ucfirst(str_replace('_', ' ', $jadwal->konfirmasi_terlapor)) }}
                                                </span>
                                            </div>

                                            <div class="grid md:grid-cols-3 gap-4 mb-3">
                                                <div>
                                                    <p class="text-xs text-gray-500">Tanggal & Waktu</p>
                                                    <p class="text-sm font-medium">
                                                        {{ $jadwal->tanggal_mediasi->format('d M Y') }}<br>
                                                        {{ $jadwal->waktu_mediasi->format('H:i') }} WIB
                                                    </p>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500">Tempat</p>
                                                    <p class="text-sm font-medium">{{ $jadwal->tempat_mediasi }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500">Status Jadwal</p>
                                                    <span
                                                        class="text-xs px-2 py-1 rounded-full {{ $jadwal->getStatusBadgeClass() }}">
                                                        {{ ucfirst($jadwal->status_jadwal) }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center gap-4 text-sm">
                                                    <div>
                                                        <span class="text-gray-500">Pelapor:</span>
                                                        <span
                                                            class="ml-1 px-2 py-1 rounded-full text-xs {{ $jadwal->getKonfirmasiBadgeClass('pelapor') }}">
                                                            {{ ucfirst(str_replace('_', ' ', $jadwal->konfirmasi_pelapor)) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Terlapor:</span>
                                                        <span
                                                            class="ml-1 px-2 py-1 rounded-full text-xs {{ $jadwal->getKonfirmasiBadgeClass('terlapor') }}">
                                                            {{ ucfirst(str_replace('_', ' ', $jadwal->konfirmasi_terlapor)) }}
                                                        </span>
                                                    </div>
                                                </div>

                                                @if ($jadwal->konfirmasi_terlapor === 'pending')
                                                    <a href="{{ route('konfirmasi.show', $jadwal->jadwal_id) }}"
                                                        class="inline-flex items-center gap-2 bg-orange-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-orange-700 transition-colors">
                                                        <span>✓</span>
                                                        <span>Konfirmasi Kehadiran</span>
                                                    </a>
                                                @else
                                                    <a href="{{ route('konfirmasi.show', $jadwal->jadwal_id) }}"
                                                        class="inline-flex items-center gap-2 bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700 transition-colors">
                                                        <span>Lihat Detail</span>
                                                    </a>
                                                @endif
                                            </div>

                                            @if ($jadwal->konfirmasi_terlapor === 'tidak_hadir' && $jadwal->catatan_konfirmasi_terlapor)
                                                <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                                    <p class="text-xs text-red-600 font-medium">Catatan Anda:</p>
                                                    <p class="text-sm text-red-700">
                                                        {{ $jadwal->catatan_konfirmasi_terlapor }}</p>
                                                </div>
                                            @elseif($jadwal->konfirmasi_terlapor === 'hadir' && $jadwal->catatan_konfirmasi_terlapor)
                                                <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                                                    <p class="text-xs text-green-600 font-medium">Catatan Anda:</p>
                                                    <p class="text-sm text-green-700">
                                                        {{ $jadwal->catatan_konfirmasi_terlapor }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach

                                    @if ($jadwalMediasi->count() > 5)
                                        <div class="text-center">
                                            <a href="{{ route('konfirmasi.index') }}"
                                                class="text-primary hover:text-primary-dark font-medium">
                                                Lihat semua jadwal ({{ $jadwalMediasi->count() }} total)
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- Quick Actions --}}
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h4 class="text-lg font-semibold mb-4">Aksi Cepat</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <a href="{{ route('pengaduan.index-terlapor') }}"
                                    class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-yellow-50 hover:border-yellow-300 transition-colors">
                                    <div class="p-2 bg-orange-100 rounded-lg mr-3">
                                        <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                            <path fill-rule="evenodd"
                                                d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">Lihat Pengaduan Terhadap Saya</p>
                                        <p class="text-sm text-gray-600">Pengaduan yang melibatkan perusahaan/instansi
                                            Anda</p>
                                    </div>
                                </a>

                                <a href="{{ route('konfirmasi.index') }}"
                                    class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-yellow-50 hover:border-yellow-300 transition-colors">
                                    <div class="p-2 bg-purple-100 rounded-lg mr-3">
                                        <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">Jadwal Mediasi</p>
                                        <p class="text-sm text-gray-600">Jadwal sesi mediasi saya</p>
                                    </div>
                                    @if ($stats['jadwal_menunggu_konfirmasi'] > 0)
                                        <div
                                            class="w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                                            {{ $stats['jadwal_menunggu_konfirmasi'] }}
                                        </div>
                                    @endif
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Sidebar --}}
                    <div class="space-y-6">
                        {{-- Status Overview --}}
                        {{-- <div class="bg-white rounded-lg shadow-sm p-6">
                            <h4 class="text-lg font-semibold mb-4">Status Overview</h4>
                            <div class="space-y-3">
                                @if ($stats['total_aduan_terhadap_saya'] > 0)
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Total Aduan</span>
                                        <span class="font-semibold">{{ $stats['total_aduan_terhadap_saya'] }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Dalam Mediasi</span>
                                        <span
                                            class="font-semibold text-blue-600">{{ $stats['dijadwalkan_mediasi'] }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Menunggu Konfirmasi</span>
                                        <span
                                            class="font-semibold text-purple-600">{{ $stats['jadwal_menunggu_konfirmasi'] }}</span>
                                    </div>
                                @else
                                    <div class="text-center text-gray-500 py-4">
                                        <div class="text-4xl mb-2">🎉</div>
                                        <p class="text-sm">Tidak ada aduan yang sedang berjalan</p>
                                    </div>
                                @endif
                            </div>
                        </div> --}}

                        {{-- Important Notes --}}
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h4 class="text-lg font-semibold mb-4">Catatan Penting</h4>
                            <div class="space-y-3 text-sm text-gray-600">
                                <div class="flex items-start gap-2">
                                    <span class="text-yellow-500 mt-1">⚠️</span>
                                    <p>Pastikan untuk mengkonfirmasi kehadiran pada jadwal mediasi yang telah
                                        ditetapkan.</p>
                                </div>
                                <div class="flex items-start gap-2">
                                    <span class="text-blue-500 mt-1">💼</span>
                                    <p>Siapkan dokumen-dokumen yang diperlukan untuk sesi mediasi.</p>
                                </div>
                                <div class="flex items-start gap-2">
                                    <span class="text-green-500 mt-1">🤝</span>
                                    <p>Mediasi bertujuan untuk mencari solusi terbaik bagi semua pihak.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Help & Support --}}
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h4 class="text-lg font-semibold mb-4">Bantuan & Dukungan</h4>
                            <div class="space-y-3">
                                <a href="tel:074621234"
                                    class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="p-2 bg-primary rounded-lg mr-3">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Call Center</p>
                                        <p class="text-xs text-gray-600">(0746) 21234</p>
                                    </div>
                                </a>

                                <a href="mailto:mediasi@disnaker.bungo.go.id"
                                    class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="p-2 bg-primary rounded-lg mr-3">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z">
                                            </path>
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Email Support</p>
                                        <p class="text-xs text-gray-600">mediasi@disnaker.bungo.go.id</p>
                                    </div>
                                </a>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
</body>

</html>
