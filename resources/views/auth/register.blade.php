<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi SIPPPHI</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            /* background-color: #f8f9fa; */
            background-image: url('/img/background_disnakertrans.png');
            background-position: center;
        }

        .form-input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.25rem;
        }

        .register-container {
            background-color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
</head>

<body>
    <div class="min-h-screen flex justify-center items-center">
        <div class="register-container w-full max-w-4xl p-8">
            <img src="/img/logo_bungo.png" alt="Logo Kabupaten Bungo" class="w-20 h-20 mx-auto mb-4">
            <h1 class="text-2xl font-bold mb-6">Registrasi akun SIPPPHI</h1>
            <h6 class="text-sm text-gray-500 mb-6">Registrasi hanya dapat dilakukan oleh pihak berselisih yang
                melaporkan perselisihan yang terjadi antara
                pekerja dan perusahaan.</h6>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="flex flex-wrap -mx-4">
                    <!-- Kolom Kiri -->
                    <div class="w-full md:w-1/2 px-4">
                        <div class="mb-4">
                            <label for="nama_pelapor" class="block text-sm mb-1">Nama*</label>
                            <input id="nama_pelapor" type="text" name="name_pelapor"
                                value="{{ old('nama_pelapor') }}" required class="form-input" />
                            @error('nama_pelapor')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="tempat_lahir" class="block text-sm mb-1">Tempat Lahir*</label>
                            <input id="tempat_lahir" type="text" name="tempat_lahir"
                                value="{{ old('tempat_lahir') }}" required class="form-input" />
                            @error('tempat_lahir')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="tanggal_lahir" class="block text-sm mb-1">Tanggal Lahir*</label>
                            <input id="tanggal_lahir" type="date" name="tanggal_lahir"
                                value="{{ old('tanggal_lahir') }}" required class="form-input" />
                            @error('tanggal_lahir')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="jenis_kelamin" class="block text-sm mb-1">Jenis Kelamin*</label>
                            <select id ="jenis_kelamin" name="jenis_kelamin" required
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300 bg-white">
                                <option value="" disabled selected>-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>
                                    Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                                    Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm mb-1">Email*</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                class="form-input" />
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="no_hp" class="block text-sm mb-1">No. HP*</label>
                            <input id="no_hp" type="text" name="no_hp" value="{{ old('no_hp') }}" required
                                class="form-input" />
                            @error('no_hp')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="w-full md:w-1/2 px-4">
                        <div class="mb-4">
                            <label for="alamat" class="block text-sm mb-1">Alamat*</label>
                            <textarea id="alamat" name="alamat" required class="form-input" rows="3">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="perusahaan" class="block text-sm mb-1">Perusahaan*</label>
                            <input id="perusahaan" type="text" name="perusahaan" value="{{ old('perusahaan') }}"
                                required class="form-input" />
                            @error('perusahaan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="npk" class="block text-sm mb-1">NPK*</label>
                            <input id="npk" type="text" name="npk" value="{{ old('npk') }}" required
                                class="form-input" />
                            @error('npk')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-sm mb-1">Password*</label>
                            <input id="password" type="password" name="password" required class="form-input" />
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-sm mb-1">Confirm Password*</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                class="form-input" />
                            @error('password_confirmation')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end items-center mt-6">
                            <div class="mr-4">
                                <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:underline">Sudah
                                    memiliki akun?</a>
                            </div>
                            <button type="submit"
                                class="px-5 py-2 bg-gray-800 text-white font-medium uppercase rounded hover:bg-gray-700">
                                Daftar
                            </button>
                        </div>
                    </div>
                </div>

                @if (session('success'))
                    <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- ✅ FIXED: Add general error message display -->
                @if ($errors->any() && !$errors->has('nama_pelapor') && !$errors->has('email') && !$errors->has('password'))
                    <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </form>
        </div>
    </div>
</body>

</html>
