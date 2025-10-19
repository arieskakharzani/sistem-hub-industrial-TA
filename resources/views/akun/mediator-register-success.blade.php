<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registrasi Berhasil - SIPPPHI</title>
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
                    }
                }
            }
        }
    </script>
</head>

<body class="min-h-screen bg-gradient-to-br from-primary via-primary-light to-accent relative overflow-hidden">

    <!-- Background Decorations -->
    <div class="absolute inset-0 bg-white/10 backdrop-blur-[1px]"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full -translate-y-48 translate-x-48"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/10 rounded-full translate-y-32 -translate-x-32"></div>
    <div class="absolute top-1/4 right-1/4 w-32 h-32 bg-white/5 rounded-full"></div>
    <div class="absolute bottom-1/4 left-1/4 w-24 h-24 bg-white/5 rounded-full"></div>

    <!-- Main Content Container -->
    <div class="relative z-10 min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md">

            <!-- Success Card -->
            <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/50 p-8">

                <!-- Success Icon -->
                <div class="text-center mb-8">
                    <div
                        class="mx-auto h-16 w-16 bg-gradient-to-r from-accent to-accent-light rounded-full flex items-center justify-center mb-4">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">
                        Registrasi Berhasil!
                    </h2>
                    <p class="text-gray-600">
                        Pendaftaran mediator Anda telah berhasil dikirim
                    </p>
                </div>

                <!-- Success Message -->
                <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">
                                Status Pendaftaran
                            </h3>
                            <div class="mt-2 text-sm text-green-700">
                                <p>Pendaftaran Anda sedang dalam proses review oleh Kepala Dinas. Anda akan menerima
                                    notifikasi melalui email setelah:</p>
                                <ul class="mt-2 list-disc list-inside space-y-1">
                                    <li>Dokumen SK Anda telah diverifikasi</li>
                                    <li>Akun Anda telah disetujui</li>
                                    <li>Kredensial login akan dikirim ke email Anda</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Next Steps -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">
                                Langkah Selanjutnya
                            </h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Periksa email Anda secara berkala untuk notifikasi</li>
                                    <li>Pastikan email yang Anda gunakan aktif</li>
                                    <li>Hubungi mediator aktif jika tidak ada kabar dalam 3 hari kerja</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <a href="{{ route('login') }}"
                        class="w-full bg-gradient-to-r from-primary to-primary-light text-white py-3 px-4 rounded-xl font-semibold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Kembali ke Login
                    </a>

                    <a href="{{ route('mediator.register') }}"
                        class="w-full bg-transparent text-primary border-2 border-primary py-3 px-4 rounded-xl font-semibold text-lg hover:bg-primary hover:text-white transition-all duration-200 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A3 3 0 017 17h10a3 3 0 012.879 2.804M15 11a3 3 0 10-6 0 3 3 0 006 0z" />
                        </svg>
                        Daftar Mediator Lain
                    </a>
                </div>

                <!-- Contact Info -->
                <div class="mt-8 text-center">
                    <p class="text-xs text-gray-500">
                        Butuh bantuan? Hubungi admin di
                        <a href="mailto:admin@sippphi-bungo.go.id"
                            class="text-primary hover:text-primary-dark font-medium">
                            admin@sippphi-bungo.go.id
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
