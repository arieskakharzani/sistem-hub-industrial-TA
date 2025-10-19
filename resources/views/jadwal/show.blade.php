<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Jadwal</title>
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
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Detail jadwal
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        {{ $jadwal->pengaduan->perihal }}
                    </p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('jadwal.index') }}"
                        class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg">
                        Kembali
                    </a>
                    @if (!in_array($jadwal->status_jadwal, ['selesai', 'dibatalkan']))
                        <a href="{{ route('jadwal.edit', $jadwal) }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                            Edit Jadwal
                        </a>
                    @endif
                </div>
            </div>
        </x-slot>

        <div class="py-12">
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

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

                {{-- Alert Status Konfirmasi Kehadiran --}}
                @if ($jadwal->status_jadwal === 'dijadwalkan')
                    @if ($jadwal->jenis_jadwal === 'klarifikasi')
                        {{-- Logika untuk klarifikasi --}}
                        @if ($jadwal->konfirmasi_pelapor === 'pending' && $jadwal->konfirmasi_terlapor === 'pending')
                            {{-- Kedua pihak masih pending - tidak tampilkan button --}}
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 mb-6 rounded-r-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-lg font-medium text-yellow-800">⏳ Menunggu Konfirmasi Kehadiran
                                        </h3>
                                        <p class="text-yellow-700 mt-1">
                                            Menunggu konfirmasi kehadiran dari kedua belah pihak untuk klarifikasi.
                                            <br><br>
                                            <strong>Jadwal:</strong> {{ $jadwal->tanggal->format('d F Y') }} pukul
                                            {{ $jadwal->waktu->format('H:i') }} WIB
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @elseif (
                            $jadwal->tanggal < now()->toDateString() &&
                                ($jadwal->konfirmasi_pelapor === 'pending' || $jadwal->konfirmasi_terlapor === 'pending'))
                            {{-- Hari H sudah lewat dan masih ada yang pending - tidak tampilkan button --}}
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 mb-6 rounded-r-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-lg font-medium text-yellow-800">⏰ Menunggu Update Status</h3>
                                        <p class="text-yellow-700 mt-1">
                                            Hari H klarifikasi telah lewat dan masih ada pihak yang belum konfirmasi.
                                            Status akan otomatis berubah menjadi selesai dan Anda dapat membuat jadwal
                                            mediasi.
                                            <br><br>
                                            <strong>Jadwal:</strong> {{ $jadwal->tanggal->format('d F Y') }} pukul
                                            {{ $jadwal->waktu->format('H:i') }} WIB
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @elseif ($jadwal->adaYangTidakHadir())
                            @if ($jadwal->konfirmasi_pelapor === 'tidak_hadir' && $jadwal->konfirmasi_terlapor === 'tidak_hadir')
                                {{-- Kedua pihak tidak hadir - tidak bisa buat risalah klarifikasi --}}
                                <div class="bg-red-50 border-l-4 border-red-400 p-6 mb-6 rounded-r-lg">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-lg font-medium text-red-800">❌ Kedua Pihak Tidak Hadir</h3>
                                            <p class="text-red-700 mt-1">
                                                Kedua belah pihak tidak dapat hadir pada klarifikasi.
                                                Mediator dapat langsung membuat jadwal mediasi tanpa risalah
                                                klarifikasi.
                                                <br><br>
                                                <strong>Jadwal:</strong> {{ $jadwal->tanggal->format('d F Y') }} pukul
                                                {{ $jadwal->waktu->format('H:i') }} WIB
                                                <br>
                                                <strong>Status Konfirmasi:</strong>
                                                <br>• Pelapor: Tidak Hadir
                                                <br>• Terlapor: Tidak Hadir
                                            </p>
                                            <div class="mt-3">
                                                <a href="{{ route('jadwal.create', ['pengaduan_id' => $jadwal->pengaduan_id, 'jenis_jadwal' => 'mediasi', 'sidang_ke' => 1]) }}"
                                                    class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                    Buat Jadwal Mediasi ke-1
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                {{-- Ada yang tidak hadir tapi ada yang hadir - tetap bisa buat risalah klarifikasi --}}
                                <div class="bg-orange-50 border-l-4 border-orange-400 p-6 mb-6 rounded-r-lg">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <svg class="h-6 w-6 text-orange-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-lg font-medium text-orange-800">📋 Klarifikasi Dapat
                                                Dilaksanakan</h3>
                                            <p class="text-orange-700 mt-1">
                                                Ada pihak yang tidak dapat hadir, namun klarifikasi tetap dapat
                                                dilaksanakan dengan pihak yang hadir.
                                                <br><br>
                                                <strong>Jadwal:</strong> {{ $jadwal->tanggal->format('d F Y') }} pukul
                                                {{ $jadwal->waktu->format('H:i') }} WIB
                                                <br>
                                                <strong>Status Konfirmasi:</strong>
                                                <br>• Pelapor:
                                                {{ ucfirst(str_replace('_', ' ', $jadwal->konfirmasi_pelapor)) }}
                                                <br>• Terlapor:
                                                {{ ucfirst(str_replace('_', ' ', $jadwal->konfirmasi_terlapor)) }}
                                            </p>
                                            <div class="mt-3">
                                                @if ($jadwal->risalahKlarifikasi && $jadwal->risalahKlarifikasi->risalah_id)
                                                    @php
                                                        try {
                                                            $risalahUrl = route(
                                                                'risalah.show',
                                                                $jadwal->risalahKlarifikasi->risalah_id,
                                                            );
                                                        } catch (\Exception $e) {
                                                            $risalahUrl = '#';
                                                        }
                                                    @endphp
                                                    <a href="{{ $risalahUrl }}"
                                                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                            </path>
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                            </path>
                                                        </svg>
                                                        Lihat Risalah Klarifikasi
                                                    </a>
                                                @else
                                                    <a href="{{ route('risalah.create', [$jadwal->jadwal_id, 'klarifikasi']) }}"
                                                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                            </path>
                                                        </svg>
                                                        Buat Risalah Klarifikasi
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                            {{-- Kedua pihak hadir - tampilkan button buat risalah klarifikasi --}}
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-6 mb-6 rounded-r-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-lg font-medium text-blue-800">📋 Klarifikasi Siap Dilaksanakan
                                        </h3>
                                        <p class="text-blue-700 mt-1">
                                            Kedua belah pihak telah mengkonfirmasi kehadiran. Klarifikasi dapat
                                            dilaksanakan sesuai jadwal.
                                            <br><br>
                                            <strong>Jadwal:</strong> {{ $jadwal->tanggal->format('d F Y') }} pukul
                                            {{ $jadwal->waktu->format('H:i') }} WIB
                                        </p>
                                        <div class="mt-3">
                                            @if ($jadwal->risalahKlarifikasi && $jadwal->risalahKlarifikasi->risalah_id)
                                                @php
                                                    try {
                                                        $risalahUrl = route(
                                                            'risalah.show',
                                                            $jadwal->risalahKlarifikasi->risalah_id,
                                                        );
                                                    } catch (\Exception $e) {
                                                        $risalahUrl = '#';
                                                    }
                                                @endphp
                                                <a href="{{ $risalahUrl }}"
                                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                    Lihat Risalah Klarifikasi
                                                </a>
                                            @else
                                                <a href="{{ route('risalah.create', [$jadwal->jadwal_id, 'klarifikasi']) }}"
                                                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                        </path>
                                                    </svg>
                                                    Buat Risalah Klarifikasi
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @elseif ($jadwal->jenis_jadwal === 'mediasi')
                        {{-- Logika untuk semua mediasi (sidang ke 1-3) --}}
                        @if ($jadwal->konfirmasi_pelapor === 'pending' && $jadwal->konfirmasi_terlapor === 'pending')
                            {{-- Kedua pihak masih pending - tidak tampilkan button --}}
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 mb-6 rounded-r-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-lg font-medium text-yellow-800">⏳ Menunggu Konfirmasi Kehadiran
                                        </h3>
                                        <p class="text-yellow-700 mt-1">
                                            Menunggu konfirmasi kehadiran dari kedua belah pihak untuk mediasi sidang
                                            ke-{{ $jadwal->sidang_ke }}.
                                            <br><br>
                                            <strong>Jadwal:</strong> {{ $jadwal->tanggal->format('d F Y') }} pukul
                                            {{ $jadwal->waktu->format('H:i') }} WIB
                                            <br>
                                            <strong>Sidang Ke:</strong> {{ $jadwal->sidang_ke }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @elseif (
                            $jadwal->tanggal < now()->toDateString() &&
                                ($jadwal->konfirmasi_pelapor === 'pending' || $jadwal->konfirmasi_terlapor === 'pending'))
                            {{-- Hari H sudah lewat dan masih ada yang pending - tampilkan button buat jadwal berikutnya --}}
                            <div class="bg-orange-50 border-l-4 border-orange-400 p-6 mb-6 rounded-r-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-orange-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-lg font-medium text-orange-800">⏰ Mediasi Sidang
                                            Ke-{{ $jadwal->sidang_ke }} Selesai - Buat Jadwal Berikutnya</h3>
                                        <p class="text-orange-700 mt-1">
                                            Hari H mediasi sidang ke-{{ $jadwal->sidang_ke }} telah lewat dan masih ada
                                            pihak yang belum konfirmasi.
                                            Status mediasi sidang ke-{{ $jadwal->sidang_ke }} otomatis berubah menjadi
                                            selesai.
                                            <br><br>
                                            <strong>Jadwal:</strong> {{ $jadwal->tanggal->format('d F Y') }} pukul
                                            {{ $jadwal->waktu->format('H:i') }} WIB
                                            <br>
                                            <strong>Sidang Ke:</strong> {{ $jadwal->sidang_ke }}
                                        </p>
                                        <div class="mt-3">
                                            @if ($jadwal->sidang_ke < 3)
                                                @if (!$jadwal->pengaduan->hasNextMediasiSchedule($jadwal->sidang_ke))
                                                    <a href="{{ route('jadwal.create', ['pengaduan_id' => $jadwal->pengaduan_id, 'jenis_jadwal' => 'mediasi', 'sidang_ke' => $jadwal->sidang_ke + 1]) }}"
                                                        class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                        <svg class="w-4 h-4 mr-2" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                        Buat Jadwal Mediasi ke-{{ $jadwal->sidang_ke + 1 }}
                                                    </a>
                                                @else
                                                    @php
                                                        $nextSchedule = $jadwal->pengaduan->getNextMediasiSchedule(
                                                            $jadwal->sidang_ke,
                                                        );
                                                    @endphp
                                                    <div class="text-sm text-green-600">
                                                        <strong>✓ Jadwal Mediasi ke-{{ $jadwal->sidang_ke + 1 }} sudah
                                                            dibuat</strong>
                                                        <br>
                                                        <span class="text-gray-600">
                                                            Tanggal: {{ $nextSchedule->tanggal->format('d F Y') }}
                                                            pukul {{ $nextSchedule->waktu->format('H:i') }} WIB
                                                        </span>
                                                        <br>
                                                        <a href="{{ route('jadwal.show', $nextSchedule->jadwal_id) }}"
                                                            class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 mt-2">
                                                            <svg class="w-4 h-4 mr-2" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                                </path>
                                                            </svg>
                                                            Lihat Detail Jadwal
                                                        </a>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="text-sm text-orange-600">
                                                    <strong>Catatan:</strong> Mediasi sudah mencapai maksimal 3 sesi.
                                                    Silakan buat risalah penyelesaian atau anjuran.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- Ada yang konfirmasi hadir - tampilkan tombol sesuai kondisi. Jika ada yang tidak hadir, jangan tampilkan tombol jadwal berikutnya sampai mediator menandai selesai. --}}
                            <div class="bg-purple-50 border-l-4 border-purple-400 p-6 mb-6 rounded-r-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-purple-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-lg font-medium text-purple-800">📋 Silahkan Update Status
                                            Jadwal menjadi "Selesai"
                                        </h3>
                                        <p class="text-purple-700 mt-1">
                                            @if ($jadwal->sudahDikonfirmasiSemua() && !$jadwal->adaYangTidakHadir())
                                                Kedua belah pihak telah mengkonfirmasi kehadiran. Mediasi tidak dapat
                                                dilanjutkan karena ada pihak yang tidak hadir.
                                            @elseif ($jadwal->adaYangTidakHadir())
                                                Ada pihak yang tidak dapat hadir, sehingga proses mediasi tidak dapat
                                                dilanjutkan. Silahkan membuat jadwal mediasi selanjutnya.
                                            @else
                                                Ada pihak yang telah mengkonfirmasi kehadiran. Mediasi tidak dapat
                                                dilaksanakan.
                                            @endif
                                            <br><br>
                                            <strong>Jadwal:</strong> {{ $jadwal->tanggal->format('d F Y') }} pukul
                                            {{ $jadwal->waktu->format('H:i') }} WIB
                                            <br>
                                            <strong>Sidang Ke:</strong> {{ $jadwal->sidang_ke }}
                                        </p>
                                        <div class="mt-3">
                                            @php
                                                $keduaHadir =
                                                    $jadwal->sudahDikonfirmasiSemua() && !$jadwal->adaYangTidakHadir();
                                            @endphp
                                            @if ($jadwal->risalahPenyelesaian && $jadwal->risalahPenyelesaian->risalah_id)
                                                @php
                                                    try {
                                                        $risalahUrl = route(
                                                            'risalah.show',
                                                            $jadwal->risalahPenyelesaian->risalah_id,
                                                        );
                                                    } catch (\Exception $e) {
                                                        $risalahUrl = '#';
                                                    }
                                                @endphp
                                                <a href="{{ $risalahUrl }}"
                                                    class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                    Lihat Risalah Mediasi ke-{{ $jadwal->sidang_ke }}
                                                </a>
                                            @else
                                                @if ($keduaHadir)
                                                    <a href="{{ route('risalah.create', [$jadwal->jadwal_id, 'mediasi']) }}"
                                                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                        <svg class="w-4 h-4 mr-2" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                            </path>
                                                        </svg>
                                                        Buat Risalah Mediasi ke-{{ $jadwal->sidang_ke }}
                                                    </a>
                                                @endif

                                                @if ($keduaHadir && $jadwal->sidang_ke < 3)
                                                    @if (!$jadwal->pengaduan->hasNextMediasiSchedule($jadwal->sidang_ke))
                                                        <a href="{{ route('jadwal.create', ['pengaduan_id' => $jadwal->pengaduan_id, 'jenis_jadwal' => 'mediasi', 'sidang_ke' => $jadwal->sidang_ke + 1]) }}"
                                                            class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150 ml-2">
                                                            <svg class="w-4 h-4 mr-2" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                </path>
                                                            </svg>
                                                            Buat Jadwal Mediasi ke-{{ $jadwal->sidang_ke + 1 }}
                                                        </a>
                                                    @else
                                                        @php
                                                            $nextSchedule = $jadwal->pengaduan->getNextMediasiSchedule(
                                                                $jadwal->sidang_ke,
                                                            );
                                                        @endphp
                                                        <div class="text-sm text-green-600 ml-2">
                                                            <strong>✓ Jadwal Mediasi ke-{{ $jadwal->sidang_ke + 1 }}
                                                                sudah dibuat</strong>
                                                            <br>
                                                            <a href="{{ route('jadwal.show', $nextSchedule->jadwal_id) }}"
                                                                class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 mt-2">
                                                                <svg class="w-4 h-4 mr-2" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                                    </path>
                                                                </svg>
                                                                Lihat Detail Jadwal
                                                            </a>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @elseif (
                        $jadwal->jenis_jadwal === 'ttd_perjanjian_bersama' &&
                            $jadwal->sudahDikonfirmasiSemua() &&
                            !$jadwal->adaYangTidakHadir())
                        {{-- Untuk TTD: tetap kondisi lama --}}
                        <div class="bg-green-50 border-l-4 border-green-400 p-6 mb-6 rounded-r-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-lg font-medium text-green-800">🎉
                                        {{ $jadwal->jenis_jadwal === 'ttd_perjanjian_bersama' ? 'Pertemuan Penandatanganan Perjanjian Bersama' : ucfirst($jadwal->jenis_jadwal) }}
                                        Siap Dilaksanakan!</h3>
                                    <p class="text-green-700 mt-1">
                                        Kedua belah pihak telah mengkonfirmasi kehadiran.
                                        {{ $jadwal->jenis_jadwal === 'ttd_perjanjian_bersama' ? 'Pertemuan Penandatanganan Perjanjian Bersama' : ucfirst($jadwal->jenis_jadwal) }}
                                        dapat dilaksanakan sesuai jadwal
                                        pada <strong>{{ $jadwal->tanggal->format('d F Y') }}</strong>
                                        pukul <strong>{{ $jadwal->waktu->format('H:i') }} WIB</strong>.
                                    </p>
                                    <div class="mt-3">
                                        @if ($jadwal->jenis_jadwal === 'ttd_perjanjian_bersama')
                                            @php
                                                $dokumenHI = $jadwal->pengaduan->dokumenHI->first();
                                                $perjanjianBersama = $dokumenHI
                                                    ? $dokumenHI->perjanjianBersama->first()
                                                    : null;
                                            @endphp
                                            @if ($perjanjianBersama)
                                                <a href="{{ route('dokumen.perjanjian-bersama.show', $perjanjianBersama->perjanjian_bersama_id) }}"
                                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                    Lihat Perjanjian Bersama
                                                </a>
                                            @else
                                                <a href="{{ route('dokumen.perjanjian-bersama.create', $dokumenHI ? $dokumenHI->dokumen_hi_id : null) }}"
                                                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                        </path>
                                                    </svg>
                                                    Buat Perjanjian Bersama
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($jadwal->adaYangTidakHadir())
                        {{-- Ada yang tidak hadir - perlu reschedule --}}
                        <div class="bg-red-50 border-l-4 border-red-400 p-6 mb-6 rounded-r-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-lg font-medium text-red-800">⚠️ Penjadwalan Ulang Diperlukan</h3>
                                    <p class="text-red-700 mt-1">
                                        Ada pihak yang tidak dapat hadir pada jadwal.
                                        Status jadwal telah diubah menjadi "Ditunda". Silakan koordinasikan jadwal baru
                                        dengan semua pihak.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Masih menunggu konfirmasi --}}
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 mb-6 rounded-r-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-lg font-medium text-yellow-800">⏳ Menunggu Konfirmasi Kehadiran
                                        Panggilan
                                        {{ $jadwal->jenis_jadwal === 'ttd_perjanjian_bersama' ? 'Pertemuan Penandatanganan Perjanjian Bersama' : ucfirst($jadwal->jenis_jadwal) }}
                                    </h3>
                                    <p class="text-yellow-700 mt-1">
                                        Masih menunggu konfirmasi kehadiran dari
                                        @if ($jadwal->konfirmasi_pelapor === 'pending' && $jadwal->konfirmasi_terlapor === 'pending')
                                            pelapor dan terlapor
                                        @elseif ($jadwal->konfirmasi_pelapor === 'pending')
                                            pelapor
                                        @else
                                            terlapor
                                        @endif
                                        untuk jadwal ini.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                @elseif ($jadwal->status_jadwal === 'selesai')
                    {{-- Status selesai - cek apakah sudah ada risalah --}}
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-6 mb-6 rounded-r-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-lg font-medium text-blue-800">
                                        @if ($jadwal->jenis_jadwal === 'klarifikasi')
                                            ✅ Klarifikasi Telah Selesai
                                        @elseif ($jadwal->jenis_jadwal === 'ttd_perjanjian_bersama')
                                            ✅ Pertemuan Penandatanganan Perjanjian Bersama Telah Selesai
                                        @else
                                            ✅ Mediasi Sidang Ke-{{ $jadwal->sidang_ke }} Telah Selesai
                                        @endif
                                    </h3>
                                    <p class="text-blue-700 mt-1">
                                        @if ($jadwal->jenis_jadwal === 'klarifikasi')
                                            Klarifikasi telah dilaksanakan dan selesai.
                                            @if ($jadwal->risalahKlarifikasi)
                                                Risalah klarifikasi sudah dibuat.
                                            @else
                                                Belum ada risalah klarifikasi yang dibuat.
                                            @endif
                                        @elseif ($jadwal->jenis_jadwal === 'ttd_perjanjian_bersama')
                                            Pertemuan penandatanganan perjanjian bersama telah dilaksanakan dan selesai.
                                            Perjanjian bersama telah ditandatangani oleh kedua belah pihak.
                                        @else
                                            @php
                                                $labelPelapor = match ($jadwal->konfirmasi_pelapor) {
                                                    'hadir' => 'Hadir',
                                                    'tidak_hadir' => 'Tidak Hadir',
                                                    default => 'Pending',
                                                };
                                                $labelTerlapor = match ($jadwal->konfirmasi_terlapor) {
                                                    'hadir' => 'Hadir',
                                                    'tidak_hadir' => 'Tidak Hadir',
                                                    default => 'Pending',
                                                };
                                            @endphp
                                            Mediasi sidang ke-{{ $jadwal->sidang_ke }} telah selesai.
                                            <br>
                                            <span class="text-sm">Kondisi selesai: Pelapor
                                                <strong>{{ $labelPelapor }}</strong>, Terlapor
                                                <strong>{{ $labelTerlapor }}</strong>.</span>
                                            @if ($jadwal->risalahPenyelesaian)
                                                Risalah mediasi sudah dibuat.
                                            @else
                                                Belum ada risalah mediasi yang dibuat.
                                            @endif

                                            @if (!$jadwal->pengaduan->hasReachedMaxMediasiSessions() && !$jadwal->risalahPenyelesaian)
                                                <div class="mt-2 text-sm">
                                                    <span class="font-medium">Catatan:</span> Anda dapat membuat jadwal
                                                    sidang mediasi berikutnya jika belum mencapai kesepakatan.
                                                </div>
                                            @endif
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                @if ($jadwal->jenis_jadwal === 'klarifikasi')
                                    {{-- Untuk klarifikasi selesai, tampilkan button buat jadwal mediasi ke-1 --}}
                                    <a href="{{ route('jadwal.create', ['pengaduan_id' => $jadwal->pengaduan_id, 'jenis_jadwal' => 'mediasi', 'sidang_ke' => 1]) }}"
                                        class="inline-flex items-center px-3 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        Buat Jadwal Mediasi ke-1
                                    </a>
                                @elseif ($jadwal->jenis_jadwal === 'ttd_perjanjian_bersama')
                                    @php
                                        $dokumenHI = $jadwal->pengaduan->dokumenHI->first();
                                        $perjanjianBersama = $dokumenHI ? $dokumenHI->perjanjianBersama->first() : null;
                                    @endphp
                                    @if ($perjanjianBersama)
                                        <a href="{{ route('dokumen.perjanjian-bersama.show', $perjanjianBersama->perjanjian_bersama_id) }}"
                                            class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                            Lihat Perjanjian Bersama
                                        </a>
                                    @else
                                        <a href="{{ route('dokumen.perjanjian-bersama.create', $dokumenHI ? $dokumenHI->dokumen_hi_id : null) }}"
                                            class="inline-flex items-center px-3 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            Buat Perjanjian Bersama
                                        </a>
                                    @endif
                                @elseif ($jadwal->jenis_jadwal === 'mediasi')
                                    <div class="space-y-2">
                                        @if (
                                            $jadwal->risalahPenyelesaian &&
                                                $jadwal->risalahPenyelesaian->risalah_id &&
                                                $jadwal->risalahPenyelesaian->risalah_id !== null)
                                            @php
                                                try {
                                                    $risalahUrl = route(
                                                        'risalah.show',
                                                        $jadwal->risalahPenyelesaian->risalah_id,
                                                    );
                                                } catch (\Exception $e) {
                                                    $risalahUrl = '#';
                                                }
                                            @endphp
                                            <a href="{{ $risalahUrl }}"
                                                class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                                Lihat Risalah Penyelesaian
                                            </a>
                                        @else
                                            @if ($detailMediasiTerakhir)
                                                {{-- Jika sudah ada risalah mediasi, tampilkan button lihat risalah mediasi --}}
                                                @php
                                                    try {
                                                        $risalahMediasiUrl = route(
                                                            'risalah.show',
                                                            $detailMediasiTerakhir->risalah->risalah_id,
                                                        );
                                                    } catch (\Exception $e) {
                                                        $risalahMediasiUrl = '#';
                                                    }
                                                @endphp
                                                <a href="{{ $risalahMediasiUrl }}"
                                                    class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                    Lihat Risalah Mediasi
                                                </a>
                                            @else
                                                @php
                                                    $keduaHadir =
                                                        $jadwal->konfirmasi_pelapor === 'hadir' &&
                                                        $jadwal->konfirmasi_terlapor === 'hadir';
                                                @endphp
                                                @if ($keduaHadir)
                                                    <a href="{{ route('risalah.create', [$jadwal->jadwal_id, 'mediasi']) }}"
                                                        class="inline-flex items-center px-3 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                                        <svg class="w-4 h-4 mr-2" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                            </path>
                                                        </svg>
                                                        Buat Risalah Mediasi
                                                    </a>
                                                @endif

                                                @if ($jadwal->sidang_ke < 3)
                                                    @if (!$jadwal->pengaduan->hasNextMediasiSchedule($jadwal->sidang_ke))
                                                        <a href="{{ route('jadwal.create', ['pengaduan_id' => $jadwal->pengaduan_id, 'jenis_jadwal' => 'mediasi', 'sidang_ke' => $jadwal->sidang_ke + 1]) }}"
                                                            class="inline-flex items-center px-3 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 ml-2">
                                                            <svg class="w-4 h-4 mr-2" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                </path>
                                                            </svg>
                                                            Buat Jadwal Mediasi ke-{{ $jadwal->sidang_ke + 1 }}
                                                        </a>
                                                    @else
                                                        @php
                                                            $nextSchedule = $jadwal->pengaduan->getNextMediasiSchedule(
                                                                $jadwal->sidang_ke,
                                                            );
                                                        @endphp
                                                        <div class="text-sm text-green-600 ml-2">
                                                            <strong>✓ Jadwal Mediasi ke-{{ $jadwal->sidang_ke + 1 }}
                                                                sudah dibuat</strong>
                                                            <br>
                                                            <a href="{{ route('jadwal.show', $nextSchedule->jadwal_id) }}"
                                                                class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 mt-2">
                                                                <svg class="w-4 h-4 mr-2" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                                    </path>
                                                                </svg>
                                                                Lihat Detail Jadwal
                                                            </a>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Tombol Buat Anjuran untuk Mixed Attendance Failure --}}
                @if (
                    $jadwal->jenis_jadwal === 'mediasi' &&
                        $jadwal->sidang_ke == 3 &&
                        $jadwal->pengaduan->canCreateAnjuran() &&
                        !$jadwal->pengaduan->hasAnjuran())
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 mb-6 rounded-r-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-medium text-yellow-800">⚠️ Mediasi Gagal - Buat Anjuran</h3>
                                <p class="text-yellow-700 mt-1">
                                    @if ($jadwal->pengaduan->canCreateAnjuranDueToMixedAttendanceFailure())
                                        Mediasi telah mencapai maksimal 3 sesi dengan kondisi kehadiran yang bercampur.
                                        Tidak ada risalah mediasi yang dibuat karena tidak ada kesepakatan yang dicapai.
                                        <br><br>
                                        <strong>Kondisi:</strong> Kedua belah pihak tidak konsisten dalam kehadiran,
                                        sehingga mediasi tidak dapat menghasilkan kesepakatan.
                                    @elseif ($jadwal->pengaduan->canCreateAnjuranDueToTerlaporUnresponsiveness())
                                        Mediasi telah mencapai maksimal 3 sesi dengan terlapor yang tidak responsif
                                        (tidak pernah hadir di semua sesi mediasi).
                                        <br><br>
                                        <strong>Kondisi:</strong> Terlapor tidak pernah hadir di semua sesi mediasi.
                                    @endif
                                </p>
                                <div class="mt-4">
                                    @php
                                        $dokumenHI = $jadwal->pengaduan->dokumenHI->first();
                                    @endphp
                                    @if ($dokumenHI)
                                        <a href="{{ route('dokumen.anjuran.create', $dokumenHI->dokumen_hi_id) }}"
                                            class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded shadow font-semibold transition">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                                </path>
                                            </svg>
                                            Buat Anjuran
                                        </a>
                                    @else
                                        <div class="text-sm text-red-600">
                                            <strong>Error:</strong> Dokumen Hubungan Industrial tidak ditemukan.
                                            Silakan hubungi administrator.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif (
                    $jadwal->jenis_jadwal === 'mediasi' &&
                        $jadwal->sidang_ke == 3 &&
                        $jadwal->pengaduan->canCreateAnjuran() &&
                        $jadwal->pengaduan->hasAnjuran())
                    {{-- Anjuran sudah dibuat, tampilkan tombol Lihat --}}
                    @php
                        $anjuran = $jadwal->pengaduan->getAnjuran();
                    @endphp
                    @if ($anjuran)
                        <div class="bg-green-50 border-l-4 border-green-400 p-6 mb-6 rounded-r-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-lg font-medium text-green-800">✅ Anjuran Sudah Dibuat</h3>
                                    <p class="text-green-700 mt-1">
                                        Anjuran untuk pengaduan ini sudah dibuat dan dapat dilihat.
                                        <br><br>
                                        <strong>Nomor Anjuran:</strong>
                                        {{ $anjuran->nomor_anjuran ?? 'Belum ada nomor' }}
                                        <br>
                                        <strong>Status:</strong>
                                        {{ ucfirst(str_replace('_', ' ', $anjuran->status_approval ?? 'draft')) }}
                                    </p>
                                    <div class="mt-4">
                                        <a href="{{ route('dokumen.anjuran.show', $anjuran->anjuran_id) }}"
                                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded shadow font-semibold transition">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                            Lihat Anjuran
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-red-50 border-l-4 border-red-400 p-6 mb-6 rounded-r-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-lg font-medium text-red-800">❌ Error</h3>
                                    <p class="text-red-700 mt-1">
                                        Terjadi kesalahan saat mengambil data anjuran. Silakan hubungi administrator.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Konten Utama --}}
                    <div class="lg:col-span-2 space-y-6">
                        {{-- Informasi Jadwal --}}
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Jadwal</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                                        <p class="text-sm text-gray-900">{{ $jadwal->tanggal->format('d F Y') }}
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Waktu</label>
                                        <p class="text-sm text-gray-900">{{ $jadwal->waktu->format('H:i') }} WIB
                                        </p>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tempat</label>
                                        <p class="text-sm text-gray-900">{{ $jadwal->tempat }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $jadwal->getStatusBadgeClass() }}">
                                            {!! $jadwal->getStatusIcon() !!}
                                            <span class="ml-1">{{ ucfirst($jadwal->status_jadwal) }}</span>
                                        </span>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Mediator</label>
                                        <p class="text-sm text-gray-900">{{ $jadwal->mediator->nama_mediator }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis
                                            Jadwal</label>
                                        <p class="text-sm text-gray-900">
                                            {{ $jadwal->jenis_jadwal === 'ttd_perjanjian_bersama' ? 'Pertemuan Penandatanganan Perjanjian Bersama' : ucfirst($jadwal->jenis_jadwal) }}
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Sidang Ke-</label>
                                        <p class="text-sm text-gray-900">{{ $jadwal->sidang_ke ?? '-' }}</p>
                                    </div>
                                    </tr>
                                </div>

                                @if ($jadwal->catatan_jadwal)
                                    <div class="mt-6">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan
                                            Jadwal</label>
                                        <div class="bg-blue-50 p-4 rounded-md">
                                            <p class="text-sm text-blue-800">{{ $jadwal->catatan_jadwal }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if ($jadwal->hasil)
                                    <div class="mt-6">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Hasil
                                            Mediasi</label>
                                        <div class="bg-green-50 p-4 rounded-md">
                                            <p class="text-sm text-green-800">{{ $jadwal->hasil }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Informasi Pengaduan --}}
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Pengaduan</h3>

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor
                                            Jadwal</label>
                                        <p class="text-sm text-gray-900">
                                            #{{ $jadwal->nomor_jadwal ?? $jadwal->jadwal_id }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor
                                            Pengaduan</label>
                                        <p class="text-sm text-gray-900">
                                            #{{ $jadwal->pengaduan->nomor_pengaduan ?? $jadwal->pengaduan->pengaduan_id }}
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Perihal</label>
                                        <p class="text-sm text-gray-900">{{ $jadwal->pengaduan->perihal }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                            Laporan</label>
                                        <p class="text-sm text-gray-900">
                                            {{ $jadwal->pengaduan->tanggal_laporan->format('d F Y') }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Pihak yang
                                            Dilaporkan</label>
                                        <p class="text-sm text-gray-900">{{ $jadwal->pengaduan->nama_terlapor }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Narasi
                                            Kasus</label>
                                        <div class="bg-gray-50 p-4 rounded-md">
                                            <p class="text-sm text-gray-700">{{ $jadwal->pengaduan->narasi_kasus }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Sidebar --}}
                    <div class="space-y-6">
                        {{-- Status Konfirmasi Kehadiran --}}
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Konfirmasi Kehadiran</h3>

                                <div class="space-y-4">
                                    {{-- Konfirmasi Pelapor --}}
                                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Pelapor</p>
                                            <p class="text-xs text-gray-600">
                                                {{ $jadwal->pengaduan->pelapor->nama_pelapor }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $jadwal->getKonfirmasiBadgeClass('pelapor') }}">
                                                {{ ucfirst(str_replace('_', ' ', $jadwal->konfirmasi_pelapor)) }}
                                            </span>
                                            @if ($jadwal->tanggal_konfirmasi_pelapor)
                                                <p class="text-xs text-gray-500 mt-1">
                                                    {{ $jadwal->tanggal_konfirmasi_pelapor->format('d/m/Y H:i') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Konfirmasi Terlapor --}}
                                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Terlapor</p>
                                            <p class="text-xs text-gray-600">{{ $jadwal->pengaduan->nama_terlapor }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $jadwal->getKonfirmasiBadgeClass('terlapor') }}">
                                                {{ ucfirst(str_replace('_', ' ', $jadwal->konfirmasi_terlapor)) }}
                                            </span>
                                            @if ($jadwal->tanggal_konfirmasi_terlapor)
                                                <p class="text-xs text-gray-500 mt-1">
                                                    {{ $jadwal->tanggal_konfirmasi_terlapor->format('d/m/Y H:i') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Catatan Konfirmasi --}}
                                    @if ($jadwal->catatan_konfirmasi_pelapor)
                                        <div class="bg-blue-50 p-3 rounded-lg">
                                            <p class="text-xs font-medium text-blue-800 mb-1">Catatan Pelapor:</p>
                                            <p class="text-xs text-blue-700">{{ $jadwal->catatan_konfirmasi_pelapor }}
                                            </p>
                                        </div>
                                    @endif

                                    @if ($jadwal->catatan_konfirmasi_terlapor)
                                        <div class="bg-blue-50 p-3 rounded-lg">
                                            <p class="text-xs font-medium text-blue-800 mb-1">Catatan Terlapor:</p>
                                            <p class="text-xs text-blue-700">
                                                {{ $jadwal->catatan_konfirmasi_terlapor }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Informasi Pelapor --}}
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pelapor</h3>

                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                                        <p class="text-sm text-gray-900">
                                            {{ $jadwal->pengaduan->pelapor->nama_pelapor }}
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                        <p class="text-sm text-gray-900">{{ $jadwal->pengaduan->pelapor->email }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                                        <p class="text-sm text-gray-900">{{ $jadwal->pengaduan->pelapor->no_hp }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Perusahaan</label>
                                        <p class="text-sm text-gray-900">
                                            {{ $jadwal->pengaduan->pelapor->perusahaan }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Aksi Cepat --}}
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>

                                @if ($jadwal->status_jadwal === 'selesai')
                                    {{-- Tampilan ketika status sudah selesai --}}
                                    <div class="bg-green-50 border border-green-200 rounded-md p-4">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-green-800">
                                                jadwal Selesai
                                            </span>
                                        </div>
                                        <p class="text-xs text-green-700 mt-2">
                                            Status tidak dapat diubah lagi karena mediasi telah selesai dilaksanakan.
                                        </p>
                                    </div>

                                    {{-- Form disabled untuk display saja --}}
                                    <div class="mt-4 opacity-50">
                                        <div class="mb-3">
                                            <label for="status_jadwal_disabled"
                                                class="block text-sm font-medium text-gray-700 mb-1">
                                                Status Saat Ini
                                            </label>
                                            <select id="status_jadwal_disabled"
                                                class="w-full rounded-md border-gray-300 bg-gray-100" disabled>
                                                <option value="selesai" selected>Selesai</option>
                                            </select>
                                        </div>

                                        @if ($jadwal->catatan_jadwal)
                                            <div class="mb-3">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    Catatan Terakhir
                                                </label>
                                                <textarea rows="3" class="w-full rounded-md border-gray-300 bg-gray-100" disabled readonly>{{ $jadwal->catatan_jadwal }}</textarea>
                                            </div>
                                        @endif

                                        <button type="button"
                                            class="w-full bg-gray-300 text-gray-500 font-bold py-2 px-4 rounded-lg cursor-not-allowed"
                                            disabled>
                                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                                </path>
                                            </svg>
                                            Status Terkunci
                                        </button>
                                    </div>
                                @else
                                    {{-- Form normal untuk status selain 'selesai' --}}
                                    <div class="space-y-3">
                                        <form id="statusForm">
                                            @csrf

                                            <div class="mb-3">
                                                <label for="status_jadwal"
                                                    class="block text-sm font-medium text-gray-700 mb-1">
                                                    Ubah Status
                                                </label>
                                                <select name="status_jadwal" id="status_jadwal"
                                                    class="w-full rounded-md border-gray-300">
                                                    @foreach (\App\Models\Jadwal::getStatusOptions() as $key => $label)
                                                        <option value="{{ $key }}"
                                                            {{ $jadwal->status_jadwal == $key ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="catatan_jadwal"
                                                    class="block text-sm font-medium text-gray-700 mb-1">
                                                    Catatan
                                                </label>
                                                <textarea name="catatan_jadwal" id="catatan_jadwal" rows="3" class="w-full rounded-md border-gray-300"
                                                    placeholder="Tambahkan catatan...">{{ $jadwal->catatan_jadwal }}</textarea>
                                            </div>

                                            <button type="submit"
                                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                                                Update Status
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Script hanya dijalankan jika status bukan 'selesai'
            @if ($jadwal->status_jadwal !== 'selesai')
                document.getElementById('statusForm').addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('_method', 'PATCH');

                    // Disable button sementara
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML =
                        '<svg class="w-4 h-4 inline mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Memproses...';

                    fetch('{{ route('jadwal.updateStatus', $jadwal) }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Status berhasil diperbarui!');
                                location.reload();
                            } else {
                                alert('Terjadi kesalahan: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat memperbarui status');
                        })
                        .finally(() => {
                            // Restore button
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        });
                });

                // Warning saat akan mengubah status ke 'selesai'
                document.getElementById('status_jadwal').addEventListener('change', function() {
                    if (this.value === 'selesai') {
                        const confirmed = confirm(
                            'Apakah Anda yakin ingin mengubah status ke "Selesai"?\n\nSetelah diubah ke status selesai, Anda tidak akan dapat mengubah status lagi.'
                        );
                        if (!confirmed) {
                            // Reset ke status sebelumnya
                            this.value = '{{ $jadwal->status_jadwal }}';
                        }
                    }
                });
            @endif
        </script>
    </x-app-layout>
</body>

</html>
