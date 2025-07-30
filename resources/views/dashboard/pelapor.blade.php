<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
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
                {{ __('Dashboard') }}
            </h2>
        </x-slot>

        <!-- Main Content -->
        <div class="max-w-6xl mx-auto px-5 py-8">
            <!-- Welcome Hero -->
            <div
                class="bg-gradient-to-br from-primary to-primary-light rounded-3xl p-12 text-white mb-10 relative overflow-hidden">
                <!-- Decorative elements -->
                <div
                    class="absolute top-0 right-0 w-72 h-72 bg-white bg-opacity-10 rounded-full transform translate-x-24 -translate-y-24">
                </div>
                <div
                    class="absolute bottom-0 left-0 w-48 h-48 bg-white bg-opacity-5 rounded-full transform -translate-x-12 translate-y-12">
                </div>

                <div class="relative z-10 text-center">
                    <h1 class="text-4xl font-bold mb-4">Selamat Datang di Sistem Informasi Pengaduan dan Penyelesaian
                        Hubungan Industrial Kab. Bungo</h1>
                    <p class="text-lg opacity-90 mb-8 max-w-2xl mx-auto">
                        Platform digital untuk penyelesaian perselisihan hubungan industrial secara efektif dan
                        transparan
                    </p>
                </div>
            </div>

            <!-- Jadwal Alert (jika ada) -->
            @if ($jadwal->where('konfirmasi_pelapor', 'pending')->count() > 0)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 mb-8 rounded-r-xl">
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
                                Anda memiliki {{ $jadwal->where('konfirmasi_pelapor', 'pending')->count() }}
                                jadwal yang menunggu konfirmasi
                                kehadiran.
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

            @php
                $activePengaduan = $pengaduans->where('status', '!=', 'selesai')->first();
                $completedPengaduan = $pengaduans->where('status', 'selesai')->first();
                $latestPengaduan = $pengaduans->first(); // Pengaduan terbaru untuk next steps
            @endphp

            <!-- Pengaduan Selesai Alert (hanya jika TIDAK ada pengaduan aktif) -->
            @if ($completedPengaduan && !$activePengaduan)
                <div class="bg-green-50 border-l-4 border-green-400 p-6 mb-8 rounded-r-xl">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                <span class="font-medium">Selamat!</span>
                                Pengaduan Anda dengan nomor
                                <strong>{{ $completedPengaduan->nomor_pengaduan ?? $completedPengaduan->pengaduan_id }}</strong>
                                telah selesai diproses.
                                Anda dapat melihat hasil akhir di halaman riwayat pengaduan.
                            </p>
                        </div>
                        <div class="ml-auto">
                            <a href="{{ route('pengaduan.index') }}"
                                class="bg-green-400 hover:bg-green-500 text-green-900 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                Lihat Riwayat
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Main Content Grid -->
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Main Content Section - Takes 2 columns -->
                <div class="lg:col-span-2">

                    @if ($activePengaduan)
                        <!-- Status Pengaduan Aktif -->
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                            <!-- Header Status -->
                            <div class="text-center py-12 px-8 bg-gradient-to-br from-green-50 to-emerald-50">
                                <div class="text-8xl mb-6 opacity-70">✅</div>
                                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Pengaduan Sudah Terkirim</h3>
                                <p class="text-gray-600 mb-6 max-w-md mx-auto leading-relaxed">
                                    Pengaduan Anda sudah berhasil dikirim dan sedang dalam proses peninjauan oleh
                                    mediator.
                                </p>

                                <!-- Status Badge -->
                                @if ($activePengaduan->status == 'pending')
                                    <div
                                        class="inline-flex items-center gap-2 bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full font-medium mb-6">
                                        <div class="w-2 h-2 bg-yellow-500 rounded-full animate-pulse"></div>
                                        <span>Menunggu Review</span>
                                    </div>
                                    <!-- Action Button -->
                                    <a href="{{ route('pengaduan.show', $activePengaduan->pengaduan_id) }}"
                                        class="inline-flex items-center gap-3 bg-primary text-white px-8 py-3 rounded-xl font-medium hover:bg-primary-dark transform hover:-translate-y-1 transition-all duration-300">
                                        <span>📄</span>
                                        <span>Lihat Detail Pengaduan</span>
                                    </a>
                                @elseif($activePengaduan->status == 'proses')
                                    <div
                                        class="inline-flex items-center gap-2 bg-blue-100 text-blue-800 px-4 py-2 rounded-full font-medium mb-6">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                                        <span>Sedang Diproses</span>
                                    </div>
                                    <!-- Action Buttons -->
                                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                        <a href="{{ route('pengaduan.show', $activePengaduan->pengaduan_id) }}"
                                            class="inline-flex items-center gap-3 bg-primary text-white px-8 py-3 rounded-xl font-medium hover:bg-primary-dark transform hover:-translate-y-1 transition-all duration-300">
                                            <span>📄</span>
                                            <span>Lihat Detail Pengaduan</span>
                                        </a>
                                        @if ($jadwal->count() > 0)
                                            <a href="{{ route('konfirmasi.index') }}"
                                                class="inline-flex items-center gap-3 bg-orange-500 text-white px-8 py-3 rounded-xl font-medium hover:bg-orange-600 transform hover:-translate-y-1 transition-all duration-300">
                                                <span>🗓️</span>
                                                <span>Jadwal</span>
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Pengaduan Details -->
                            <div class="p-8">
                                <h4 class="text-xl font-semibold text-gray-800 mb-6">Detail Pengaduan Terakhir</h4>

                                <div class="grid md:grid-cols-3 gap-6">
                                    <!-- Detail Pengaduan -->
                                    <div class="md:col-span-3">
                                        <div class="bg-gray-50 rounded-xl p-6">
                                            <div class="grid md:grid-cols-2 gap-4">
                                                <div>
                                                    <p class="text-sm text-gray-600 mb-1">Tanggal Laporan</p>
                                                    <p class="font-semibold text-gray-800">
                                                        {{ $activePengaduan->tanggal_laporan->format('d F Y') }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-600 mb-1">Perihal</p>
                                                    <p class="font-semibold text-gray-800">
                                                        {{ $activePengaduan->perihal }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-600 mb-1">Perusahaan</p>
                                                    <p class="font-semibold text-gray-800">
                                                        {{ $activePengaduan->nama_terlapor }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-600 mb-1">Status</p>
                                                    <span
                                                        class="inline-flex items-center gap-1 {{ $activePengaduan->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($activePengaduan->status == 'proses' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }} px-3 py-1 rounded-full text-sm font-medium">
                                                        {{ ucfirst($activePengaduan->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Statistik Saya -->
                                    {{-- <div class="md:col-span-1">
                                        <div class="bg-white rounded-xl border border-gray-200 p-4">
                                            <h5 class="text-sm font-semibold text-gray-800 mb-3">Statistik Saya</h5>
                                            <div class="space-y-3">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs text-gray-600">Total Pengaduan</span>
                                                    <span
                                                        class="text-lg font-bold text-primary">{{ $stats['total_pengaduan'] }}</span>
                                                </div>
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs text-gray-600">Dalam Proses</span>
                                                    <span
                                                        class="text-lg font-bold text-orange-600">{{ $stats['pengaduan_proses'] }}</span>
                                                </div>
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs text-gray-600">Selesai</span>
                                                    <span
                                                        class="text-lg font-bold text-green-600">{{ $stats['pengaduan_selesai'] }}</span>
                                                </div>
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs text-gray-600">Menunggu Konfirmasi</span>
                                                    <span
                                                        class="text-lg font-bold text-purple-600">{{ $stats['jadwal_menunggu_konfirmasi'] }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Empty State - Belum Ada Pengaduan Aktif -->
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                            <!-- Empty State Illustration -->
                            <div class="text-center py-16 px-8 bg-gradient-to-br from-blue-50 to-indigo-50">
                                <div class="text-8xl mb-6 opacity-70">📋</div>
                                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Belum Ada Pengaduan Aktif
                                </h3>
                                <p class="text-gray-600 mb-8 max-w-md mx-auto leading-relaxed">
                                    Anda belum memiliki pengaduan yang aktif. Mulai dengan membuat pengaduan
                                    pertama
                                    Anda untuk menyelesaikan perselisihan hubungan industrial.
                                </p>
                                <a href="{{ route('pengaduan.create') }}"
                                    class="inline-flex items-center gap-3 bg-primary text-white px-8 py-3 rounded-xl font-medium hover:bg-primary-dark transform hover:-translate-y-1 transition-all duration-300">
                                    <span>➕</span>
                                    <span>Buat Pengaduan</span>
                                </a>
                            </div>

                            <!-- Process Steps Preview -->
                            <div class="p-8">
                                <h4 class="text-xl font-semibold text-gray-800 mb-6">Proses Mediasi - 3 Langkah
                                    Mudah
                                </h4>

                                <div class="space-y-6">
                                    <!-- Step 1 -->
                                    <div
                                        class="flex items-start gap-5 p-6 bg-gradient-to-r from-primary to-primary-light rounded-xl text-white">
                                        <div
                                            class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-lg font-bold flex-shrink-0">
                                            1</div>
                                        <div>
                                            <h5 class="text-lg font-semibold mb-2">Isi Form Pengaduan</h5>
                                            <p class="text-white text-opacity-90 text-sm">
                                                Lengkapi formulir dengan data diri, informasi perusahaan, dan
                                                detail
                                                perselisihan
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Step 2 -->
                                    <div class="flex items-start gap-5 p-6 border-2 border-gray-200 rounded-xl">
                                        <div
                                            class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-lg font-bold text-gray-500 flex-shrink-0">
                                            2</div>
                                        <div>
                                            <h5 class="text-lg font-semibold text-gray-800 mb-2">Proses Mediasi
                                            </h5>
                                            <p class="text-gray-600 text-sm">
                                                Tim mediator akan meninjau dan menjadwalkan sesi mediasi secara
                                                profesional
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Step 3 -->
                                    <div class="flex items-start gap-5 p-6 border-2 border-gray-200 rounded-xl">
                                        <div
                                            class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-lg font-bold text-gray-500 flex-shrink-0">
                                            3</div>
                                        <div>
                                            <h5 class="text-lg font-semibold text-gray-800 mb-2">Penyelesaian
                                            </h5>
                                            <p class="text-gray-600 text-sm">
                                                Hasil berupa Perjanjian Bersama atau Anjuran Tertulis sesuai
                                                kesepakatan
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Jadwal Section (jika ada) -->
                        @if ($jadwal->count() > 0)
                            <div class="mb-6">
                                <h5 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                    <span>🗓️</span>
                                    <span>Jadwal</span>
                                </h5>
                                @foreach ($jadwal->take(3) as $item)
                                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-3">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <p class="font-semibold text-blue-800">
                                                    {{ $item->tanggal->format('d F Y') }} -
                                                    {{ $item->waktu->format('H:i') }} WIB
                                                </p>
                                                <p class="text-sm text-blue-600">{{ $item->tempat }}
                                                </p>
                                                <p class="text-xs text-blue-700 mt-1">Jenis Jadwal:
                                                    <span class="font-bold">{{ ucfirst($item->jenis_jadwal) }}</span>
                                                </p>
                                            </div>
                                            <span
                                                class="text-xs px-2 py-1 rounded-full {{ $item->getKonfirmasiBadgeClass('pelapor') }}">
                                                {{ ucfirst(str_replace('_', ' ', $item->konfirmasi_pelapor)) }}
                                            </span>
                                        </div>
                                        @if ($item->konfirmasi_pelapor === 'pending')
                                            <a href="{{ route('konfirmasi.show', $item->jadwal_id) }}"
                                                class="inline-flex items-center gap-2 bg-blue-600 text-white px-3 py-1 rounded-lg text-sm hover:bg-blue-700 transition-colors">
                                                <span>✓</span>
                                                <span>Konfirmasi Kehadiran
                                                    {{ ucfirst($item->jenis_jadwal) }}</span>
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                                @if ($jadwal->count() > 3)
                                    <a href="{{ route('konfirmasi.index') }}"
                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Lihat semua jadwal ({{ $jadwal->count() }} total)
                                    </a>
                                @endif
                            </div>
                        @endif

                        <!-- Next Steps -->
                        <div class="space-y-4">
                            <h5 class="text-lg font-semibold text-gray-800">Langkah Selanjutnya</h5>

                            @if ($latestPengaduan->status == 'pending')
                                <div
                                    class="flex items-start gap-4 p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                                    <div
                                        class="w-8 h-8 bg-yellow-200 rounded-full flex items-center justify-center text-yellow-700 font-bold text-sm flex-shrink-0">
                                        1</div>
                                    <div>
                                        <h6 class="font-semibold text-yellow-800 mb-1">Menunggu Review
                                            Mediator
                                        </h6>
                                        <p class="text-yellow-700 text-sm">Tim mediator sedang meninjau
                                            pengaduan Anda. Proses ini biasanya memakan waktu 1-3 hari
                                            kerja.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4 p-4 border border-gray-200 rounded-xl">
                                    <div
                                        class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 font-bold text-sm flex-shrink-0">
                                        2</div>
                                    <div>
                                        <h6 class="font-semibold text-gray-600 mb-1">Penjadwalan
                                            Mediasi</h6>
                                        <p class="text-gray-600 text-sm">Setelah review, mediator akan
                                            menghubungi Anda untuk penjadwalan sesi mediasi.</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4 p-4 border border-gray-200 rounded-xl">
                                    <div
                                        class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 font-bold text-sm flex-shrink-0">
                                        3</div>
                                    <div>
                                        <h6 class="font-semibold text-gray-600 mb-1">Pelaksanaan
                                            Mediasi</h6>
                                        <p class="text-gray-600 text-sm">Mediasi akan dilaksanakan
                                            sesuai
                                            jadwal
                                            yang telah disepakati.</p>
                                    </div>
                                </div>
                            @elseif($latestPengaduan->status == 'proses')
                                <div class="flex items-start gap-4 p-4 bg-green-50 border border-green-200 rounded-xl">
                                    <div
                                        class="w-8 h-8 bg-green-200 rounded-full flex items-center justify-center text-green-700 font-bold text-sm flex-shrink-0">
                                        ✓</div>
                                    <div>
                                        <h6 class="font-semibold text-green-800 mb-1">Review Selesai
                                        </h6>
                                        <p class="text-green-700 text-sm">Pengaduan Anda sudah direview
                                            dan
                                            disetujui untuk proses mediasi.</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                                    <div
                                        class="w-8 h-8 bg-blue-200 rounded-full flex items-center justify-center text-blue-700 font-bold text-sm flex-shrink-0">
                                        📅</div>
                                    <div>
                                        <h6 class="font-semibold text-blue-800 mb-1">Proses Mediasi
                                            Berlangsung
                                        </h6>
                                        <p class="text-blue-700 text-sm">Mediasi sedang berlangsung.
                                            @if ($jadwal->where('konfirmasi_pelapor', 'pending')->count() > 0)
                                                Pastikan Anda telah mengkonfirmasi kehadiran untuk
                                                jadwal
                                                mediasi.
                                            @else
                                                Harap menunggu hasil dari sesi mediasi.
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @elseif($latestPengaduan->status == 'selesai')
                                <div class="flex items-start gap-4 p-4 bg-green-50 border border-green-200 rounded-xl">
                                    <div
                                        class="w-8 h-8 bg-green-200 rounded-full flex items-center justify-center text-green-700 font-bold text-sm flex-shrink-0">
                                        🎉</div>
                                    <div>
                                        <h6 class="font-semibold text-green-800 mb-1">Mediasi Selesai
                                        </h6>
                                        <p class="text-green-700 text-sm">Proses mediasi telah selesai.
                                            Lihat
                                            detail pengaduan untuk hasil mediasi.</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Contact Info untuk Follow Up -->
                        <div class="mt-8 p-6 bg-blue-50 border border-blue-200 rounded-xl">
                            <h6 class="font-semibold text-blue-800 mb-3 flex items-center gap-2">
                                <span>📞</span>
                                <span>Butuh Bantuan?</span>
                            </h6>
                            <p class="text-blue-700 text-sm mb-3">
                                Jika Anda memiliki pertanyaan atau memerlukan informasi lebih lanjut
                                tentang
                                status pengaduan, silakan hubungi:
                            </p>
                            <div class="space-y-2 text-sm">
                                <div class="flex items-center gap-2 text-blue-700">
                                    <span>📧</span>
                                    <span>Email: mediasi@disnaker.bungo.go.id</span>
                                </div>
                                <div class="flex items-center gap-2 text-blue-700">
                                    <span>📱</span>
                                    <span>Telepon: (0746) 21234</span>
                                </div>
                                <div class="flex items-center gap-2 text-blue-700">
                                    <span>🕒</span>
                                    <span>Jam Layanan: Senin - Jumat, 08:00 - 16:00 WIB</span>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Statistics Widget -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Statistik Saya</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4 mb-5">
                        <div
                            class="text-center p-5 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                            <div class="text-3xl font-bold text-primary mb-1">{{ $stats['total_pengaduan'] }}
                            </div>
                            <div class="text-xs text-gray-600 font-medium">Total Pengaduan</div>
                        </div>
                        <div
                            class="text-center p-5 bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl border border-yellow-100">
                            <div class="text-3xl font-bold text-orange-600 mb-1">
                                {{ $stats['pengaduan_proses'] }}</div>
                            <div class="text-xs text-gray-600 font-medium">Dalam Proses</div>
                        </div>
                        <div
                            class="text-center p-5 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border border-green-100">
                            <div class="text-3xl font-bold text-green-600 mb-1">
                                {{ $stats['pengaduan_selesai'] }}</div>
                            <div class="text-xs text-gray-600 font-medium">Selesai</div>
                        </div>
                        <div
                            class="text-center p-5 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                            <div class="text-3xl font-bold text-purple-600 mb-1">
                                {{ $stats['jadwal_menunggu_konfirmasi'] }}</div>
                            <div class="text-xs text-gray-600 font-medium">Menunggu Konfirmasi</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            @if ($pengaduans->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-5 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Aksi Cepat</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('pengaduan.index') }}"
                            class="flex items-center gap-4 p-4 border border-gray-200 rounded-xl hover:bg-blue-50 hover:border-primary transform hover:translate-x-1 transition-all duration-300">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-primary to-primary-light rounded-lg flex items-center justify-center text-white">
                                📋
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-medium text-gray-800">Lihat Semua Pengaduan</div>
                                <div class="text-xs text-gray-600">Riwayat pengaduan saya</div>
                            </div>
                        </a>

                        @if ($jadwal->count() > 0)
                            <a href="{{ route('konfirmasi.index') }}"
                                class="flex items-center gap-4 p-4 border border-gray-200 rounded-xl hover:bg-orange-50 hover:border-orange-300 transform hover:translate-x-1 transition-all duration-300">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-500 rounded-lg flex items-center justify-center text-white">
                                    🗓️
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-gray-800">Jadwal</div>
                                    <div class="text-xs text-gray-600">Konfirmasi kehadiran</div>
                                </div>
                                @if ($stats['jadwal_menunggu_konfirmasi'] > 0)
                                    <div
                                        class="w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                                        {{ $stats['jadwal_menunggu_konfirmasi'] }}
                                    </div>
                                @endif
                            </a>
                        @endif

                        <a href="{{ route('konfirmasi.index') }}"
                            class="flex items-center gap-4 p-4 border border-gray-200 rounded-xl hover:bg-purple-50 hover:border-purple-300 transform hover:translate-x-1 transition-all duration-300">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-lg flex items-center justify-center text-white">
                                📅
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-medium text-gray-800">Jadwal Saya</div>
                                <div class="text-xs text-gray-600">Konfirmasi kehadiran & riwayat jadwal</div>
                            </div>
                        </a>
                    </div>
                </div>
            @endif

            <!-- Help & Support -->
            {{-- <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Bantuan & Dukungan</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="tel:074621234"
                        class="flex items-center gap-4 p-4 border border-gray-200 rounded-xl hover:bg-blue-50 hover:border-primary transform hover:translate-x-1 transition-all duration-300">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-primary to-primary-light rounded-lg flex items-center justify-center text-white">
                            📞
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-800">Hubungi Call Center</div>
                            <div class="text-xs text-gray-600">(0746) 21234</div>
                        </div>
                    </a>

                    <a href="mailto:mediasi@disnaker.bungo.go.id"
                        class="flex items-center gap-4 p-4 border border-gray-200 rounded-xl hover:bg-blue-50 hover:border-primary transform hover:translate-x-1 transition-all duration-300">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-primary to-primary-light rounded-lg flex items-center justify-center text-white">
                            📧
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-800">Email Support</div>
                            <div class="text-xs text-gray-600">mediasi@disnaker.bungo.go.id</div>
                        </div>
                    </a>

                    <a href="#"
                        class="flex items-center gap-4 p-4 border border-gray-200 rounded-xl hover:bg-blue-50 hover:border-primary transform hover:translate-x-1 transition-all duration-300">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-primary to-primary-light rounded-lg flex items-center justify-center text-white">
                            ❓
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-800">FAQ & Panduan</div>
                            <div class="text-xs text-gray-600">Pertanyaan umum</div>
                        </div>
                    </a>
                </div>
            </div> --}}

            <!-- Important Information -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Informasi Penting</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="bg-blue-50 border-l-4 border-primary p-4 rounded-r-lg">
                        <div class="flex items-center gap-2 text-primary font-semibold text-sm mb-2">
                            <span>⏰</span>
                            <span>Jam Layanan</span>
                        </div>
                        <div class="text-xs text-gray-600 leading-relaxed">
                            Senin - Jumat: 08:00 - 16:00 WIB<br>
                            Sabtu - Minggu: Tutup
                        </div>
                    </div>

                    <div class="bg-blue-50 border-l-4 border-primary p-4 rounded-r-lg">
                        <div class="flex items-center gap-2 text-primary font-semibold text-sm mb-2">
                            <span>📋</span>
                            <span>Dokumen Diperlukan</span>
                        </div>
                        <div class="text-xs text-gray-600 leading-relaxed">
                            Kontrak kerja, slip gaji, surat peringatan, risalah bipartit, bukti komunikasi
                            dengan perusahaan, dan dokumen lain yang diperlukan.
                        </div>
                    </div>

                    <div class="bg-blue-50 border-l-4 border-primary p-4 rounded-r-lg">
                        <div class="flex items-center gap-2 text-primary font-semibold text-sm mb-2">
                            <span>🔒</span>
                            <span>Kerahasiaan Data</span>
                        </div>
                        <div class="text-xs text-gray-600 leading-relaxed">
                            Semua data dan informasi Anda dijamin kerahasiaannya sesuai dengan ketentuan yang
                            berlaku.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>

        <script>
            // Add interactivity to buttons
            document.querySelectorAll('a[href="#"]').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const text = this.textContent.trim();

                    if (text.includes('FAQ')) {
                        alert('Halaman FAQ akan segera tersedia.');
                    }
                });
            });

            // Welcome animation
            window.addEventListener('load', function() {
                const hero = document.querySelector('.bg-gradient-to-br');
                if (hero) {
                    hero.style.opacity = '0';
                    hero.style.transform = 'translateY(20px)';

                    setTimeout(() => {
                        hero.style.transition = 'all 0.6s ease';
                        hero.style.opacity = '1';
                        hero.style.transform = 'translateY(0)';
                    }, 100);
                }
            });

            // Status animation
            const statusBadges = document.querySelectorAll('.animate-pulse');
            statusBadges.forEach(badge => {
                badge.addEventListener('mouseenter', function() {
                    this.classList.remove('animate-pulse');
                });
                badge.addEventListener('mouseleave', function() {
                    this.classList.add('animate-pulse');
                });
            });
        </script>

    </x-app-layout>

</body>

</html>
