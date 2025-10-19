<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Aplikasi Laravel dengan Tailwind CSS">

    <title>Selamat Datang di SIPPPHI</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#3B82F6',
                        'primary-light': '#60A5FA',
                        'primary-lighter': '#93C5FD',
                        'primary-dark': '#2563EB',
                        'accent': '#10B981',
                        'accent-light': '#34D399'
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'slide-up': 'slideUp 0.8s ease-out'
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0px)'
                            },
                            '50%': {
                                transform: 'translateY(-10px)'
                            }
                        },
                        slideUp: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(30px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0px)'
                            }
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 text-gray-800 min-h-screen">

    <!-- Main Container -->
    <div class="min-h-screen flex flex-col">

        <!-- Hero Section -->
        <section class="flex-1 flex items-center justify-center px-4 py-8">
            <div class="w-full max-w-7xl mx-auto">

                <!-- Main Card -->
                <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100">

                    <!-- Header Section -->
                    <div class="relative bg-gradient-to-r from-primary via-primary-light to-accent px-8 py-16 lg:py-20">
                        <!-- Background Decorations -->
                        <div class="absolute inset-0 bg-white/10 backdrop-blur-[2px]"></div>
                        <div
                            class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -translate-y-48 translate-x-48">
                        </div>
                        <div
                            class="absolute bottom-0 left-0 w-64 h-64 bg-white/5 rounded-full translate-y-32 -translate-x-32">
                        </div>

                        <div class="relative z-10 grid lg:grid-cols-2 gap-12 items-center">

                            <!-- Text Content -->
                            <div class="text-center lg:text-left space-y-8">
                                <div class="space-y-6">
                                    <h1
                                        class="text-xl lg:text-2xl xl:text-4xl font-bold text-white leading-tight animate-slide-up">
                                        Sistem Informasi Pengaduan dan Penyelesaian Perselisihan Hubungan Industrial
                                    </h1>
                                    <div class="w-24 h-1 bg-accent mx-auto lg:mx-0 rounded-full"></div>
                                    <p class="text-xl lg:text-2xl text-white/90 font-medium">
                                        Kabupaten Bungo
                                    </p>
                                </div>

                                <p class="text-lg text-white/80 leading-relaxed max-w-xl mx-auto lg:mx-0">
                                    Platform digital terpercaya untuk melayani pengaduan dan penyelesaian perselisihan
                                    hubungan industrial secara transparan, efektif, dan profesional.
                                </p>

                                <!-- Action Buttons -->
                                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-4">
                                    @if (Route::has('login'))
                                        <a href="{{ route('login') }}"
                                            class="group bg-white text-primary px-8 py-4 rounded-2xl font-semibold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3">
                                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                            </svg>
                                            Masuk Sistem
                                        </a>
                                    @endif

                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}"
                                            class="group bg-transparent text-white border-2 border-white px-8 py-4 rounded-2xl font-semibold text-lg hover:bg-white hover:text-primary transition-all duration-300 flex items-center justify-center gap-3">
                                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                            </svg>
                                            Daftar Pelapor
                                        </a>
                                    @endif
                                </div>

                                <!-- Additional Links -->
                                <div class="mt-4 text-left">
                                    <a href="{{ route('mediator.register') }}"
                                        class="inline-flex items-center text-white/80 hover:text-white transition-colors duration-200 text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5.121 17.804A3 3 0 017 17h10a3 3 0 012.879 2.804M15 11a3 3 0 10-6 0 3 3 0 006 0z" />
                                        </svg>
                                        Daftar sebagai Mediator
                                    </a>
                                </div>
                            </div>

                            <!-- Logo Section -->
                            <div class="flex justify-center">
                                <div class="relative">
                                    <div class="absolute inset-0 bg-white/20 rounded-3xl blur-xl transform rotate-6">
                                    </div>
                                    <div
                                        class="relative bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/30 animate-float">
                                        <img class="w-64 h-auto object-contain drop-shadow-2xl"
                                            src="/img/logo_bungo.png" alt="Logo Kabupaten Bungo">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Section -->
                    <div class="px-8 py-16 lg:px-16 lg:py-20">

                        <!-- About Section -->
                        <div class="text-center mb-20">
                            <div
                                class="inline-flex items-center gap-3 bg-primary/10 text-primary px-6 py-3 rounded-full font-semibold mb-8">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5v3a.75.75 0 001.5 0v-3A.75.75 0 009 9z"
                                        clip-rule="evenodd" />
                                </svg>
                                Tentang Sistem SIPPPHI
                            </div>
                            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-8 leading-tight">
                                Platform Digital untuk Penyelesaian Hubungan Industrial
                            </h2>
                            <div class="max-w-4xl mx-auto">
                                <p class="text-xl text-gray-600 leading-relaxed">
                                    Sistem ini adalah platform digital yang dikembangkan oleh Dinas Tenaga Kerja dan
                                    Transmigrasi Kabupaten Bungo untuk memfasilitasi proses pengaduan dan penyelesaian
                                    perselisihan hubungan industrial. Melalui sistem ini, pekerja/buruh dan pengusaha
                                    dapat mengajukan pengaduan terkait perselisihan hubungan kerja dengan mudah, cepat,
                                    dan transparan.
                                </p>
                            </div>
                        </div>

                        <!-- User Guides -->
                        <div class="grid lg:grid-cols-2 gap-12">

                            <!-- Complainant Guide -->
                            <div class="group">
                                <div
                                    class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-3xl p-8 lg:p-10 border-2 border-blue-200/50 hover:border-blue-300 transition-all duration-300 hover:shadow-xl transform hover:-translate-y-2">

                                    <!-- Header -->
                                    <div class="flex items-center gap-4 mb-8">
                                        <div
                                            class="bg-gradient-to-r from-primary to-primary-dark text-white p-4 rounded-2xl shadow-lg">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-2xl font-bold text-gray-800">Panduan Pelapor</h3>
                                            <p class="text-gray-600">Langkah mudah mengajukan pengaduan</p>
                                        </div>
                                    </div>

                                    <!-- Steps -->
                                    <div class="space-y-6">
                                        <div class="flex items-start gap-4 group/item">
                                            <div
                                                class="bg-gradient-to-r from-primary to-primary-dark text-white text-sm font-bold w-10 h-10 rounded-full flex items-center justify-center shadow-lg group-hover/item:scale-110 transition-transform flex-shrink-0">
                                                1</div>
                                            <div class="pt-2">
                                                <p class="text-gray-700 font-medium">Klik tombol <span
                                                        class="text-primary font-semibold">"Daftar Sekarang"</span>
                                                    untuk registrasi</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start gap-4 group/item">
                                            <div
                                                class="bg-gradient-to-r from-primary to-primary-dark text-white text-sm font-bold w-10 h-10 rounded-full flex items-center justify-center shadow-lg group-hover/item:scale-110 transition-transform flex-shrink-0">
                                                2</div>
                                            <div class="pt-2">
                                                <p class="text-gray-700 font-medium">Isi formulir data diri dengan
                                                    lengkap dan benar</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start gap-4 group/item">
                                            <div
                                                class="bg-gradient-to-r from-primary to-primary-dark text-white text-sm font-bold w-10 h-10 rounded-full flex items-center justify-center shadow-lg group-hover/item:scale-110 transition-transform flex-shrink-0">
                                                3</div>
                                            <div class="pt-2">
                                                <p class="text-gray-700 font-medium">Klik <span
                                                        class="text-primary font-semibold">"Sign Up"</span> dan masuk
                                                    ke
                                                    sistem</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start gap-4 group/item">
                                            <div
                                                class="bg-gradient-to-r from-primary to-primary-dark text-white text-sm font-bold w-10 h-10 rounded-full flex items-center justify-center shadow-lg group-hover/item:scale-110 transition-transform flex-shrink-0">
                                                4</div>
                                            <div class="pt-2">
                                                <p class="text-gray-700 font-medium">Pilih <span
                                                        class="text-primary font-semibold">"Buat Pengaduan Baru"</span>
                                                    di dashboard</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start gap-4 group/item">
                                            <div
                                                class="bg-gradient-to-r from-primary to-primary-dark text-white text-sm font-bold w-10 h-10 rounded-full flex items-center justify-center shadow-lg group-hover/item:scale-110 transition-transform flex-shrink-0">
                                                5</div>
                                            <div class="pt-2">
                                                <p class="text-gray-700 font-medium">Isi formulir pengaduan dengan
                                                    detail dan bukti yang jelas</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start gap-4 group/item">
                                            <div
                                                class="bg-gradient-to-r from-primary to-primary-dark text-white text-sm font-bold w-10 h-10 rounded-full flex items-center justify-center shadow-lg group-hover/item:scale-110 transition-transform flex-shrink-0">
                                                6</div>
                                            <div class="pt-2">
                                                <p class="text-gray-700 font-medium">Klik <span
                                                        class="text-primary font-semibold">"Kirim Pengaduan"</span>
                                                    untuk submit</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start gap-4 group/item">
                                            <div
                                                class="bg-gradient-to-r from-primary to-primary-dark text-white text-sm font-bold w-10 h-10 rounded-full flex items-center justify-center shadow-lg group-hover/item:scale-110 transition-transform flex-shrink-0">
                                                7</div>
                                            <div class="pt-2">
                                                <p class="text-gray-700 font-medium">Pantau progress melalui email dan
                                                    dashboard sistem</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Reported Party Guide -->
                            <div class="group">
                                <div
                                    class="bg-gradient-to-br from-emerald-50 to-teal-100 rounded-3xl p-8 lg:p-10 border-2 border-emerald-200/50 hover:border-emerald-300 transition-all duration-300 hover:shadow-xl transform hover:-translate-y-2">

                                    <!-- Header -->
                                    <div class="flex items-center gap-4 mb-8">
                                        <div
                                            class="bg-gradient-to-r from-accent to-emerald-600 text-white p-4 rounded-2xl shadow-lg">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m0 0v-5a2 2 0 012-2h10a2 2 0 012 2v5" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-2xl font-bold text-gray-800">Pihak yang Dilaporkan</h3>
                                            <p class="text-gray-600">Informasi akses sistem</p>
                                        </div>
                                    </div>

                                    <!-- Information Card -->
                                    <div class="bg-white rounded-2xl p-6 mb-8 border border-emerald-200 shadow-md">
                                        <div class="flex items-center gap-3 mb-4">
                                            <div class="bg-amber-100 text-amber-600 p-2 rounded-lg">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5v3a.75.75 0 001.5 0v-3A.75.75 0 009 9z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <h4 class="font-bold text-gray-800 text-lg">Informasi Penting</h4>
                                        </div>
                                        <p class="text-gray-700 leading-relaxed mb-4">
                                            Jika Anda adalah pihak yang dilaporkan, Anda akan menerima notifikasi email
                                            berisi:
                                        </p>
                                        <div class="space-y-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-2 h-2 bg-accent rounded-full"></div>
                                                <span class="text-gray-700">Email untuk login ke sistem</span>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <div class="w-2 h-2 bg-accent rounded-full"></div>
                                                <span class="text-gray-700">Password sementara yang aman</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Steps -->
                                    <div class="bg-white rounded-2xl p-6 border border-emerald-200 shadow-md">
                                        <h4 class="font-bold text-gray-800 text-lg mb-6 flex items-center gap-2">
                                            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                            Langkah Selanjutnya
                                        </h4>
                                        <div class="space-y-4">
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="bg-gradient-to-r from-accent to-emerald-600 text-white text-sm font-bold w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0">
                                                    1</div>
                                                <p class="text-gray-700">Klik tombol <span
                                                        class="text-accent font-semibold">"Masuk Sistem"</span></p>
                                            </div>
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="bg-gradient-to-r from-accent to-emerald-600 text-white text-sm font-bold w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0">
                                                    2</div>
                                                <p class="text-gray-700">Login dengan kredensial yang dikirimkan</p>
                                            </div>
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="bg-gradient-to-r from-accent to-emerald-600 text-white text-sm font-bold w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0">
                                                    3</div>
                                                <p class="text-gray-700">Baca dan berikan tanggapan terhadap pengaduan
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Section -->
                        <div
                            class="mt-20 bg-gradient-to-r from-gray-50 to-blue-50 rounded-3xl p-8 lg:p-12 border border-gray-200">
                            <div class="text-center">
                                <div
                                    class="inline-flex items-center gap-3 bg-primary/10 text-primary px-6 py-3 rounded-full font-semibold mb-6">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Bantuan & Dukungan
                                </div>
                                <h3 class="text-2xl lg:text-3xl font-bold text-gray-800 mb-4">Butuh Bantuan?</h3>
                                <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
                                    Tim profesional kami siap membantu Anda dalam menggunakan sistem ini dengan optimal
                                </p>
                                <div class="flex flex-col sm:flex-row justify-center items-center gap-6">
                                    <div
                                        class="flex items-center gap-3 bg-white px-6 py-4 rounded-2xl shadow-md border border-gray-200">
                                        <div class="bg-primary/10 text-primary p-2 rounded-lg">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                            </svg>
                                        </div>
                                        <div class="text-left">
                                            <p class="text-sm text-gray-500 font-medium">Email</p>
                                            <p class="text-gray-800 font-semibold">nakertrans@bungokab.go.id</p>
                                        </div>
                                    </div>
                                    <div
                                        class="flex items-center gap-3 bg-white px-6 py-4 rounded-2xl shadow-md border border-gray-200">
                                        <div class="bg-accent/10 text-accent p-2 rounded-lg">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                            </svg>
                                        </div>
                                        <div class="text-left">
                                            <p class="text-sm text-gray-500 font-medium">Telepon</p>
                                            <p class="text-gray-800 font-semibold">(0747) 21013</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 py-8">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <p class="text-gray-600">
                    &copy; {{ date('Y') }} <span class="font-semibold">Dinas Tenaga Kerja dan Transmigrasi
                        Kabupaten Bungo</span>. All rights reserved.
                </p>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script>
        function toggleDarkMode() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark')
                localStorage.theme = 'light'
            } else {
                document.documentElement.classList.add('dark')
                localStorage.theme = 'dark'
            }
        }

        // Add smooth scrolling for better UX
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>

</html>
