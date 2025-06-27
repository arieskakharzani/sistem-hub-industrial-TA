<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pengaduan Saya</title>
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
                {{ __('Pengaduan Saya') }}
            </h2>
        </x-slot>

        <div class="max-w-6xl mx-auto px-5 py-8">
            <!-- Main Content Grid -->
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Main Content Section - Takes 2 columns -->
                <div class="lg:col-span-3">

                    @if ($pengaduans->count() == 0)
                        <!-- Empty State - Belum Ada Pengaduan -->
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                            <!-- Empty State Illustration -->
                            <div class="text-center py-16 px-8 bg-gradient-to-br from-blue-50 to-indigo-50">
                                <div class="text-8xl mb-6 opacity-70">📋</div>
                                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Belum Ada Pengaduan</h3>
                                <p class="text-gray-600 mb-8 max-w-md mx-auto leading-relaxed">
                                    Anda belum memiliki pengaduan yang aktif. Mulai dengan membuat pengaduan pertama
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
                                <h4 class="text-xl font-semibold text-gray-800 mb-6">Proses Mediasi - 3 Langkah Mudah
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
                                                Lengkapi formulir dengan data diri, informasi perusahaan, dan detail
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
                                            <h5 class="text-lg font-semibold text-gray-800 mb-2">Proses Mediasi</h5>
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
                                            <h5 class="text-lg font-semibold text-gray-800 mb-2">Penyelesaian</h5>
                                            <p class="text-gray-600 text-sm">
                                                Hasil berupa Perjanjian Bersama atau Anjuran Tertulis sesuai kesepakatan
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Status Pengaduan Aktif -->
                        @php
                            $latestPengaduan = $pengaduans->first();
                        @endphp

                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                            <!-- Header Status -->
                            <div class="text-center py-12 px-8 bg-gradient-to-br from-green-50 to-emerald-50">
                                <div class="text-8xl mb-6 opacity-70">✅</div>
                                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Pengaduan Sudah Terkirim</h3>
                                <p class="text-gray-600 mb-6 max-w-md mx-auto leading-relaxed">
                                    Pengaduan Anda sudah berhasil dikirim dan sedang dalam proses peninjauan oleh tim
                                    mediator.
                                </p>

                                <!-- Status Badge -->
                                @if ($latestPengaduan->status == 'pending')
                                    <div
                                        class="inline-flex items-center gap-2 bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full font-medium mb-6">
                                        <div class="w-2 h-2 bg-yellow-500 rounded-full animate-pulse"></div>
                                        <span>Menunggu Review</span>
                                    </div>
                                    <!-- Action Button -->
                                    <a href="{{ route('pengaduan.show', $latestPengaduan->pengaduan_id) }}"
                                        class="inline-flex items-center gap-3 bg-primary text-white px-8 py-3 rounded-xl font-medium hover:bg-primary-dark transform hover:-translate-y-1 transition-all duration-300">
                                        <span>📄</span>
                                        <span>Lihat Detail Pengaduan</span>
                                    </a>
                                @elseif($latestPengaduan->status == 'proses')
                                    <div
                                        class="inline-flex items-center gap-2 bg-blue-100 text-blue-800 px-4 py-2 rounded-full font-medium mb-6">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                                        <span>Sedang Diproses</span>
                                    </div>
                                    <!-- Action Button -->
                                    <a href="{{ route('pengaduan.show', $latestPengaduan->pengaduan_id) }}"
                                        class="inline-flex items-center gap-3 bg-primary text-white px-8 py-3 rounded-xl font-medium hover:bg-primary-dark transform hover:-translate-y-1 transition-all duration-300">
                                        <span>📄</span>
                                        <span>Lihat Detail Pengaduan</span>
                                    </a>
                                @elseif($latestPengaduan->status == 'selesai')
                                    <div
                                        class="inline-flex items-center gap-2 bg-green-100 text-green-800 px-4 py-2 rounded-full font-medium mb-6">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <span>Selesai</span>
                                    </div>
                                    <!-- Action Button -->
                                    <a href="{{ route('penyelesaian.index') }}"
                                        class="inline-flex items-center gap-3 bg-primary text-white px-8 py-3 rounded-xl font-medium hover:bg-primary-dark transform hover:-translate-y-1 transition-all duration-300">
                                        <span>📄</span>
                                        <span>Lihat Hasil Kesepakatan</span>
                                    </a>
                                @endif
                            </div>

                            <!-- Pengaduan Details -->
                            <div class="p-8">
                                <h4 class="text-xl font-semibold text-gray-800 mb-6">Detail Pengaduan Terakhir</h4>

                                <div class="bg-gray-50 rounded-xl p-6 mb-6">
                                    <div class="grid md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-600 mb-1">Tanggal Laporan</p>
                                            <p class="font-semibold text-gray-800">
                                                {{ $latestPengaduan->tanggal_laporan->format('d F Y') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600 mb-1">Perihal</p>
                                            <p class="font-semibold text-gray-800">{{ $latestPengaduan->perihal }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600 mb-1">Perusahaan</p>
                                            <p class="font-semibold text-gray-800">
                                                {{ $latestPengaduan->nama_terlapor }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600 mb-1">Status</p>
                                            <span
                                                class="inline-flex items-center gap-1 {{ $latestPengaduan->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($latestPengaduan->status == 'proses' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }} px-3 py-1 rounded-full text-sm font-medium">
                                                {{ ucfirst($latestPengaduan->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

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
                                                <h6 class="font-semibold text-yellow-800 mb-1">Menunggu Review Mediator
                                                </h6>
                                                <p class="text-yellow-700 text-sm">Tim mediator sedang meninjau
                                                    pengaduan Anda. Proses ini biasanya memakan waktu 1-3 hari kerja.
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex items-start gap-4 p-4 border border-gray-200 rounded-xl">
                                            <div
                                                class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 font-bold text-sm flex-shrink-0">
                                                2</div>
                                            <div>
                                                <h6 class="font-semibold text-gray-600 mb-1">Penjadwalan Mediasi</h6>
                                                <p class="text-gray-600 text-sm">Setelah review, mediator akan
                                                    menghubungi Anda untuk penjadwalan sesi mediasi.</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start gap-4 p-4 border border-gray-200 rounded-xl">
                                            <div
                                                class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 font-bold text-sm flex-shrink-0">
                                                3</div>
                                            <div>
                                                <h6 class="font-semibold text-gray-600 mb-1">Pelaksanaan Mediasi</h6>
                                                <p class="text-gray-600 text-sm">Mediasi akan dilaksanakan sesuai jadwal
                                                    yang telah disepakati.</p>
                                            </div>
                                        </div>
                                    @elseif($latestPengaduan->status == 'proses')
                                        <div
                                            class="flex items-start gap-4 p-4 bg-green-50 border border-green-200 rounded-xl">
                                            <div
                                                class="w-8 h-8 bg-green-200 rounded-full flex items-center justify-center text-green-700 font-bold text-sm flex-shrink-0">
                                                ✓</div>
                                            <div>
                                                <h6 class="font-semibold text-green-800 mb-1">Review Selesai</h6>
                                                <p class="text-green-700 text-sm">Pengaduan Anda sudah direview dan
                                                    disetujui untuk proses mediasi.</p>
                                            </div>
                                        </div>

                                        <div
                                            class="flex items-start gap-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                                            <div
                                                class="w-8 h-8 bg-blue-200 rounded-full flex items-center justify-center text-blue-700 font-bold text-sm flex-shrink-0">
                                                📅</div>
                                            <div>
                                                <h6 class="font-semibold text-blue-800 mb-1">Proses Mediasi Berlangsung
                                                </h6>
                                                <p class="text-blue-700 text-sm">Mediasi sedang berlangsung. Harap
                                                    menunggu hasil dari sesi mediasi.</p>
                                            </div>
                                        </div>
                                    @elseif($latestPengaduan->status == 'selesai')
                                        <div
                                            class="flex items-start gap-4 p-4 bg-green-50 border border-green-200 rounded-xl">
                                            <div
                                                class="w-8 h-8 bg-green-200 rounded-full flex items-center justify-center text-green-700 font-bold text-sm flex-shrink-0">
                                                🎉</div>
                                            <div>
                                                <h6 class="font-semibold text-green-800 mb-1">Mediasi Selesai</h6>
                                                <p class="text-green-700 text-sm">Proses mediasi telah selesai. Lihat
                                                    detail pengaduan untuk hasil mediasi.</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Information Cards -->
            <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Process Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div
                                class="w-8 h-8 bg-primary bg-opacity-10 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Proses Pengaduan</h3>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-start space-x-3">
                                <div
                                    class="w-6 h-6 bg-primary text-white rounded-full flex items-center justify-center text-xs font-bold">
                                    1</div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Isi Form Pengaduan</p>
                                    <p class="text-xs text-gray-600">Lengkapi data dan detail perselisihan</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div
                                    class="w-6 h-6 bg-gray-300 text-white rounded-full flex items-center justify-center text-xs font-bold">
                                    2</div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Proses Mediasi</p>
                                    <p class="text-xs text-gray-500">Tim mediator akan meninjau dan menjadwalkan
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div
                                    class="w-6 h-6 bg-gray-300 text-white rounded-full flex items-center justify-center text-xs font-bold">
                                    3</div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Penyelesaian</p>
                                    <p class="text-xs text-gray-500">Hasil mediasi dalam bentuk PB/AT</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div
                                class="w-8 h-8 bg-primary bg-opacity-10 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Kontak Bantuan</h3>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Call Center</p>
                                    <p class="text-xs text-gray-600">(021) 1500-123</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-3 h-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z">
                                        </path>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Email Support</p>
                                    <p class="text-xs text-gray-600">mediasi@disnaker.go.id</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-orange-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-3 h-3 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Jam Layanan</p>
                                    <p class="text-xs text-gray-600">Senin-Jumat 08:00-16:00</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>

    {{-- <script>
        // Add click handlers for buttons
        document.querySelectorAll('a[href*="pengaduan.create"]').forEach(button => {
            button.addEventListener('click', function(e) {
                // You can add any additional logic here before navigation
                console.log('Navigating to create pengaduan form...');
            });
        });

        // Add hover effects for help items
        document.querySelectorAll('.flex.items-start.space-x-3').forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.classList.add('bg-gray-50', 'rounded-lg', 'p-2', '-m-2');
            });

            item.addEventListener('mouseleave', function() {
                this.classList.remove('bg-gray-50', 'rounded-lg', 'p-2', '-m-2');
            });
        });
    </script> --}}


    {{-- </x-app-layout> --}}
</body>

</html>
