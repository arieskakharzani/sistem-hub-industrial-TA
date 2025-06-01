<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Aplikasi Laravel dengan Tailwind CSS">

    <title>Selamat Datang di SIPPPHI</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Tailwind CSS -->
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
    <!-- Tambahan untuk aksesibilitas dan mode gelap -->
    {{-- <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script> --}}
</head>

<body
    class="bg-gray-50 dark:bg-gray-300 text-gray-800 dark:text-gray-200 flex p-4 md:p-6 lg:p-8 items-center justify-center min-h-screen flex-col">

    <main class="w-full max-w-6xl">
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-3xl overflow-hidden">
            <div
                class="relative isolate overflow-hidden bg-gray-200 px-6 py-16 sm:rounded-3xl sm:px-16 md:py-24 lg:flex lg:gap-x-20 lg:px-24 lg:py-0">
                <svg viewBox="0 0 1024 1024"
                    class="absolute top-1/2 left-1/2 -z-10 h-[64rem] w-[64rem] -translate-y-1/2 [mask-image:radial-gradient(closest-side,white,transparent)] sm:left-full sm:-ml-80 lg:left-1/2 lg:ml-0 lg:-translate-x-1/2 lg:translate-y-0"
                    aria-hidden="true">
                    <circle cx="512" cy="512" r="512" fill="url(#759c1415-0410-454c-8f7c-9a820de03641)"
                        fill-opacity="0.7" />
                    <defs>
                        <radialGradient id="759c1415-0410-454c-8f7c-9a820de03641">
                            <stop stop-color="#D1D5DB" />
                            <stop offset="1" stop-color="#9CA3AF" />
                        </radialGradient>
                    </defs>
                </svg>
                <div class="mx-auto max-w-md text-center lg:mx-0 lg:flex-auto lg:py-32 lg:text-left">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-800 sm:text-4xl" id="main-heading">
                        Selamat datang di Sistem Informasi Pengaduan dan Penyelesaian Perselisihan Hubungan Industrial
                        Kab. Bungo.
                    </h1>
                    <p class="mt-6 text-lg leading-8 text-gray-600">
                        Ac euismod vel sit maecenas id pellentesque eu sed consectetur. Malesuada adipiscing sagittis
                        vel nulla.
                    </p>
                    <br>
                    <nav class="flex items-center justify-end gap-4 mb-8">
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}"
                                class="inline-block bg-primary px-5 py-1.5 border border-gray-300 hover:border-gray-300 dark:hover:border-gray-700 rounded-md text-sm text-white"
                                aria-label="Log in to your account">
                                Masuk
                            </a>
                        @endif


                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="inline-block px-5 py-1.5 border-primary hover:border-primary dark:hover:border-gray-300 rounded-md text-sm text-primary border-2 hover:bg-primary hover:text-white"
                                aria-label="Register a new account">
                                Daftar
                            </a>
                        @endif
                        {{-- <a href="{{ route('login') }}"
                            class="inline-block bg-black px-5 py-1.5 border border-gray-300 hover:border-gray-300 dark:hover:border-gray-700 rounded-md text-sm text-white"
                            aria-label="Log in to your account">
                            Masuk
                        </a> --}}
                    </nav>
                </div>
                {{-- gambar sisi kanan --}}
                <div class="relative mt-16 h-80 lg:mt-8 flex justify-center items-center">
                    <img class="rounded-md bg-white/5 ring-1 ring-white/10 max-w-full object-contain"
                        src="/img/logo_bungo.png" alt="Logo Kabupaten Bungo"
                        style="width: 300px; height: auto; object-position: center;">
                </div>
            </div>
        </div>
    </main>

    <footer class="mt-12 text-center text-gray-500 dark:text-gray-400 text-sm">
        <p>&copy; {{ date('Y') }} Dinas Tenaga Kerja dan Transmigrasi Kabupaten Bungo. All rights reserved.</p>
    </footer>

    <!-- Script untuk toggle mode gelap -->
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
    </script>
</body>

</html>
