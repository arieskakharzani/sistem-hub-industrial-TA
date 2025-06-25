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

            <!-- Main Content Grid -->
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Main Content Section - Takes 2 columns -->
                <div class="lg:col-span-2">

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
                                    Pengaduan Anda sudah berhasil dikirim dan sedang dalam proses peninjauan oleh
                                    mediator.
                                </p>

                                <!-- Status Badge -->
                                @if ($latestPengaduan->status == 'pending')
                                    <div
                                        class="inline-flex items-center gap-2 bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full font-medium mb-6">
                                        <div class="w-2 h-2 bg-yellow-500 rounded-full animate-pulse"></div>
                                        <span>Menunggu Review</span>
                                    </div>
                                @elseif($latestPengaduan->status == 'proses')
                                    <div
                                        class="inline-flex items-center gap-2 bg-blue-100 text-blue-800 px-4 py-2 rounded-full font-medium mb-6">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                                        <span>Sedang Diproses</span>
                                    </div>
                                @elseif($latestPengaduan->status == 'selesai')
                                    <div
                                        class="inline-flex items-center gap-2 bg-green-100 text-green-800 px-4 py-2 rounded-full font-medium mb-6">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <span>Selesai</span>
                                    </div>
                                @endif

                                <!-- Action Button -->
                                <a href="{{ route('pengaduan.show', $latestPengaduan->pengaduan_id) }}"
                                    class="inline-flex items-center gap-3 bg-primary text-white px-8 py-3 rounded-xl font-medium hover:bg-primary-dark transform hover:-translate-y-1 transition-all duration-300">
                                    <span>📄</span>
                                    <span>Lihat Detail Pengaduan</span>
                                </a>
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

                                <!-- Contact Info untuk Follow Up -->
                                <div class="mt-8 p-6 bg-blue-50 border border-blue-200 rounded-xl">
                                    <h6 class="font-semibold text-blue-800 mb-3 flex items-center gap-2">
                                        <span>📞</span>
                                        <span>Butuh Bantuan?</span>
                                    </h6>
                                    <p class="text-blue-700 text-sm mb-3">
                                        Jika Anda memiliki pertanyaan atau memerlukan informasi lebih lanjut tentang
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
                            <h3 class="text-lg font-semibold text-gray-800">Statistik Sistem</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-2 gap-4 mb-5">
                                <div
                                    class="text-center p-5 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                                    <div class="text-3xl font-bold text-primary mb-1">1,247</div>
                                    <div class="text-xs text-gray-600 font-medium">Total Kasus</div>
                                </div>
                                <div
                                    class="text-center p-5 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                                    <div class="text-3xl font-bold text-primary mb-1">892</div>
                                    <div class="text-xs text-gray-600 font-medium">Berhasil Diselesaikan</div>
                                </div>
                                <div
                                    class="text-center p-5 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                                    <div class="text-3xl font-bold text-primary mb-1">85%</div>
                                    <div class="text-xs text-gray-600 font-medium">Tingkat Keberhasilan</div>
                                </div>
                                <div
                                    class="text-center p-5 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                                    <div class="text-3xl font-bold text-primary mb-1">12</div>
                                    <div class="text-xs text-gray-600 font-medium">Hari Rata-rata</div>
                                </div>
                            </div>
                            <div class="text-center text-xs text-gray-500">
                                Data per Mei 2025
                            </div>
                        </div>
                    </div>

                    <!-- Help & Support -->
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
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
                    </div>

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
