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
    {{-- <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard Pelapor
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-8 text-white mb-6">
                    <h3 class="text-2xl font-bold mb-2">Selamat Datang, {{ $user->name }}</h3>
                    <p class="text-blue-100">Dashboard Pelapor - Sistem Mediasi Hubungan Industrial</p>
                    <div class="mt-4">
                        <span class="bg-blue-400 px-3 py-1 rounded-full text-sm font-medium">
                            Role: {{ ucfirst($user->role) }}
                        </span>
                    </div>
                </div>

            
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white p-6 rounded-lg shadow-sm">
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
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_pengaduan'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="p-3 bg-yellow-100 rounded-lg">
                                <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-600 text-sm">Dalam Proses</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['pengaduan_proses'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-600 text-sm">Selesai</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['pengaduan_selesai'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

       
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h4 class="text-lg font-semibold mb-4">Aksi Cepat</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <a href="{{ route('pengaduan.create') }}"
                            class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-colors">
                            <div class="p-2 bg-blue-100 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Buat Pengaduan</p>
                                <p class="text-sm text-gray-600">Ajukan pengaduan baru</p>
                            </div>
                        </a>

                        <a href="#"
                            class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-colors">
                            <div class="p-2 bg-green-100 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Pengaduan Saya</p>
                                <p class="text-sm text-gray-600">Lihat status pengaduan</p>
                            </div>
                        </a>

                        <a href="#"
                            class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-colors">
                            <div class="p-2 bg-purple-100 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Jadwal Mediasi</p>
                                <p class="text-sm text-gray-600">Lihat jadwal saya</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout> --}}

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
                <!-- Empty State Section - Takes 2 columns -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <!-- Empty State Illustration -->
                        <div class="text-center py-16 px-8 bg-gradient-to-br from-blue-50 to-indigo-50">
                            <div class="text-8xl mb-6 opacity-70">📋</div>
                            <h3 class="text-2xl font-semibold text-gray-800 mb-4">Belum Ada Pengaduan</h3>
                            <p class="text-gray-600 mb-8 max-w-md mx-auto leading-relaxed">
                                Anda belum memiliki pengaduan yang aktif. Mulai dengan membuat pengaduan pertama Anda
                                untuk menyelesaikan perselisihan hubungan industrial.
                            </p>
                            <a href="{{ route('pengaduan.index') }}"
                                class="inline-flex items-center gap-3 bg-primary text-white px-8 py-3 rounded-xl font-medium hover:bg-primary-dark transform hover:-translate-y-1 transition-all duration-300">
                                <span>➕</span>
                                <span>Buat Pengaduan</span>
                            </a>
                        </div>

                        <!-- Process Steps Preview -->
                        <div class="p-8">
                            <h4 class="text-xl font-semibold text-gray-800 mb-6">Proses Mediasi - 3 Langkah Mudah</h4>

                            <div class="space-y-6">
                                <!-- Step 1 -->
                                <div
                                    class="flex items-start gap-5 p-6 bg-gradient-to-r from-primary to-primary-light rounded-xl text-white">
                                    <div
                                        class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-lg font-bold flex-shrink-0">
                                        1
                                    </div>
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
                                        2
                                    </div>
                                    <div>
                                        <h5 class="text-lg font-semibold text-gray-800 mb-2">Proses Mediasi</h5>
                                        <p class="text-gray-600 text-sm">
                                            Tim mediator akan meninjau dan menjadwalkan sesi mediasi secara profesional
                                        </p>
                                    </div>
                                </div>

                                <!-- Step 3 -->
                                <div class="flex items-start gap-5 p-6 border-2 border-gray-200 rounded-xl">
                                    <div
                                        class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-lg font-bold text-gray-500 flex-shrink-0">
                                        3
                                    </div>
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
                            <a href="#"
                                class="flex items-center gap-4 p-4 border border-gray-200 rounded-xl hover:bg-blue-50 hover:border-primary transform hover:translate-x-1 transition-all duration-300">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-primary to-primary-light rounded-lg flex items-center justify-center text-white">
                                    📞
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-gray-800">Hubungi Call Center</div>
                                    <div class="text-xs text-gray-600">(021) 1500-123</div>
                                </div>
                            </a>

                            <a href="#"
                                class="flex items-center gap-4 p-4 border border-gray-200 rounded-xl hover:bg-blue-50 hover:border-primary transform hover:translate-x-1 transition-all duration-300">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-primary to-primary-light rounded-lg flex items-center justify-center text-white">
                                    📧
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-gray-800">Email Support</div>
                                    <div class="text-xs text-gray-600">mediasi@disnaker.go.id</div>
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

                    if (text.includes('Buat Pengaduan')) {
                        alert('Mengalihkan ke halaman form pengaduan...');
                    } else if (text.includes('Call Center')) {
                        window.open('tel:02115000123');
                    } else if (text.includes('Email')) {
                        window.open('mailto:mediasi@disnaker.go.id');
                    } else if (text.includes('FAQ')) {
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
        </script>


    </x-app-layout>

</body>

</html>
