<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registrasi Mediator - SIPPPHI</title>
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

<body class="min-h-screen bg-gradient-to-br from-primary via-primary-light to-accent relative">

    <!-- Background Decorations -->
    <div class="absolute inset-0 bg-white/10 backdrop-blur-[1px]"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full -translate-y-48 translate-x-48"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/10 rounded-full translate-y-32 -translate-x-32"></div>
    <div class="absolute top-1/4 right-1/4 w-32 h-32 bg-white/5 rounded-full"></div>
    <div class="absolute bottom-1/4 left-1/4 w-24 h-24 bg-white/5 rounded-full"></div>
    <div class="absolute top-3/4 right-1/2 w-16 h-16 bg-white/5 rounded-full"></div>

    <!-- Main Content Container -->
    <div class="relative z-10 min-h-screen py-8 px-4 flex justify-center items-start">
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/50 w-full max-w-5xl p-8">

            <!-- Header -->
            <div class="text-left mb-8">
                <div class="flex justify-center mb-4">
                    <div
                        class="w-20 h-20 bg-gradient-to-r from-primary to-accent rounded-2xl flex items-center justify-center p-3 shadow-lg">
                        <img src="/img/logo_bungo.png" alt="Logo Kabupaten Bungo" class="w-full h-full object-contain">
                    </div>
                </div>
                <h1 class="text-2xl font-bold mb-2 text-gray-800">Registrasi Mediator SIPPPHI</h1>
                <h3 class="text-lg font-semibold mb-3 text-gray-600">
                    Sistem Informasi Pengaduan dan Penyelesaian Hubungan Industrial Kab. Bungo
                </h3>
                <div class="text-sm text-gray-600 mb-4">
                    <p class="text-gray-600 leading-relaxed">
                        Registrasi mediator dengan mengupload Surat Keterangan (SK) pengangkatan yang ditandatangani
                        oleh Menteri.
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('mediator.register') }}" enctype="multipart/form-data">
                @csrf
                <div class="flex flex-wrap -mx-4">
                    <!-- Kolom Kiri -->
                    <div class="w-full md:w-1/2 px-4">
                        <div class="mb-6">
                            <label for="nama_mediator" class="block text-sm font-semibold text-gray-700 mb-2">Nama
                                Lengkap*</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <input id="nama_mediator" type="text" name="nama_mediator"
                                    value="{{ old('nama_mediator') }}" required
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-300 bg-white/90 @error('nama_mediator') border-red-500 @enderror"
                                    placeholder="Masukkan nama lengkap" />
                            </div>
                            @error('nama_mediator')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="nip" class="block text-sm font-semibold text-gray-700 mb-2">NIP*</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                    </svg>
                                </div>
                                <input id="nip" type="text" name="nip" value="{{ old('nip') }}" required
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-300 bg-white/90 @error('nip') border-red-500 @enderror"
                                    placeholder="Masukkan NIP" />
                            </div>
                            @error('nip')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email*</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </div>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-300 bg-white/90 @error('email') border-red-500 @enderror"
                                    placeholder="Masukkan email" />
                            </div>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="w-full md:w-1/2 px-4">
                        <div class="mb-6">
                            <label for="sk_file" class="block text-sm font-semibold text-gray-700 mb-2">File SK
                                Pengangkatan Mediator*</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <input id="sk_file" type="file" name="sk_file" accept=".pdf" required
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-300 bg-white/90 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark @error('sk_file') border-red-500 @enderror" />
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Format: PDF, Maksimal: 5MB</p>
                            @error('sk_file')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Info Box -->
                        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
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
                                        Informasi Penting
                                    </h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>File SK harus berformat PDF dan maksimal 5MB</li>
                                            <li>Akun akan aktif setelah disetujui oleh Kepala Dinas</li>
                                            <li>Kredensial login akan dikirim ke email setelah disetujui</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-between items-center mt-8 gap-4">
                            <div>
                                <a href="{{ route('login') }}"
                                    class="text-primary hover:text-primary-dark font-medium transition-colors underline">
                                    Sudah memiliki akun?
                                </a>
                            </div>
                            <button type="submit"
                                class="bg-gradient-to-r from-primary to-primary-dark text-white py-3 px-8 rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5.121 17.804A3 3 0 017 17h10a3 3 0 012.879 2.804M15 11a3 3 0 10-6 0 3 3 0 006 0z" />
                                </svg>
                                Daftar Mediator
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Success Message -->
                @if (session('success'))
                    <div class="mt-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl shadow-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                <!-- Error Messages -->
                @if (
                    $errors->any() &&
                        !$errors->has('nama_mediator') &&
                        !$errors->has('nip') &&
                        !$errors->has('email') &&
                        !$errors->has('sk_file'))
                    <div class="mt-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl shadow-sm">
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div>
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>
</body>

</html>
