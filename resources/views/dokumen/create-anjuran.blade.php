<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Buat Anjuran Tertulis</title>
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
                {{ __('Buat Anjuran Tertulis') }}
            </h2>
        </x-slot>

        <div class="max-w-6xl mx-auto px-5 py-8">
            <!-- Form Container -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <!-- Form Header -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">Form Anjuran Tertulis</h2>
                    <p class="text-sm text-gray-600">Silakan lengkapi formulir di bawah ini untuk membuat anjuran
                        tertulis
                        terkait penyelesaian perselisihan</p>
                </div>

                <!-- Form Body -->
                <form method="POST" action="{{ route('dokumen.anjuran.store') }}" class="p-8 space-y-8">
                    @csrf
                    <input type="hidden" name="dokumen_hi_id" value="{{ $dokumen_hi_id }}">

                    <!-- Data Pengusaha -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-800 pb-3 border-b-2 border-gray-200">Data Pengusaha
                        </h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-medium text-gray-700">
                                    Nama Pengusaha
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <input type="text" name="nama_pengusaha" value="{{ old('nama_pengusaha') }}"
                                    placeholder="Masukkan nama pengusaha" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300">
                            </div>

                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-medium text-gray-700">
                                    Jabatan Pengusaha
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <input type="text" name="jabatan_pengusaha" value="{{ old('jabatan_pengusaha') }}"
                                    required placeholder="Masukkan jabatan pengusaha"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300">
                                @error('jabatan_pengusaha')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-medium text-gray-700">
                                    Perusahaan
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <input type="text" name="perusahaan_pengusaha"
                                    value="{{ $risalah->nama_perusahaan }}" readonly
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-50">
                            </div>

                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-medium text-gray-700">
                                    Alamat
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <input type="text" name="alamat_pengusaha" value="{{ $risalah->alamat_perusahaan }}"
                                    readonly class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-50">
                            </div>
                        </div>
                    </div>

                    <!-- Data Pekerja -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-800 pb-3 border-b-2 border-gray-200">Data Pekerja
                        </h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-medium text-gray-700">
                                    Nama Pekerja
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <input type="text" name="nama_pekerja" value="{{ $risalah->nama_pekerja }}" readonly
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-50">
                            </div>

                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-medium text-gray-700">
                                    Jabatan Pekerja
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <input type="text" name="jabatan_pekerja" value="{{ old('jabatan_pekerja') }}"
                                    required placeholder="Masukkan jabatan pekerja"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300">
                                @error('jabatan_pekerja')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-medium text-gray-700">
                                    Perusahaan Pekerja
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <input type="text" name="perusahaan_pekerja" value="{{ old('perusahaan_pekerja') }}"
                                    required placeholder="Masukkan perusahaan pekerja"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300">
                                @error('perusahaan_pekerja')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-medium text-gray-700">
                                    Alamat
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <input type="text" name="alamat_pekerja"
                                    value="{{ old('alamat_pekerja', $risalah->alamat_pekerja) }}" required
                                    placeholder="Masukkan alamat lengkap pekerja"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300">
                                @error('alamat_pekerja')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Keterangan Para Pihak -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-800 pb-3 border-b-2 border-gray-200">Keterangan Para
                            Pihak</h3>
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-medium text-gray-700">
                                    Keterangan Pihak Pekerja
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <textarea name="keterangan_pekerja" rows="4" required
                                    placeholder="Tuliskan keterangan dari pihak pekerja terkait perselisihan"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300 resize-vertical">{{ old('keterangan_pekerja') }}</textarea>
                                <p class="text-xs text-gray-500 italic">Jelaskan posisi dan argumen dari pihak pekerja
                                </p>
                                @error('keterangan_pekerja')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-medium text-gray-700">
                                    Keterangan Pihak Pengusaha
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <textarea name="keterangan_pengusaha" rows="4" required
                                    placeholder="Tuliskan keterangan dari pihak pengusaha terkait perselisihan"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300 resize-vertical">{{ old('keterangan_pengusaha') }}</textarea>
                                <p class="text-xs text-gray-500 italic">Jelaskan posisi dan argumen dari pihak
                                    pengusaha
                                </p>
                                @error('keterangan_pengusaha')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Pertimbangan dan Anjuran -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-800 pb-3 border-b-2 border-gray-200">Pertimbangan
                            dan
                            Anjuran</h3>
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-medium text-gray-700">
                                    Pertimbangan Hukum
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <textarea name="pertimbangan_hukum" rows="4" required
                                    placeholder="Tuliskan pertimbangan hukum yang menjadi dasar anjuran"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300 resize-vertical">{{ old('pertimbangan_hukum') }}</textarea>
                                <p class="text-xs text-gray-500 italic">Cantumkan dasar hukum dan pertimbangan yang
                                    relevan
                                </p>
                                @error('pertimbangan_hukum')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-medium text-gray-700">
                                    Isi Anjuran
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <textarea name="isi_anjuran" rows="6" required
                                    placeholder="Tuliskan anjuran tertulis untuk penyelesaian perselisihan"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300 resize-vertical">{{ old('isi_anjuran') }}</textarea>
                                <p class="text-xs text-gray-500 italic">Jelaskan dengan detail anjuran yang diberikan
                                    untuk
                                    menyelesaikan perselisihan</p>
                                @error('isi_anjuran')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4 pt-4">
                        <a href="{{ url()->previous() }}"
                            class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-all duration-300">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg font-medium transition-all duration-300">
                            Simpan Anjuran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </x-app-layout>
</body>

</html>
