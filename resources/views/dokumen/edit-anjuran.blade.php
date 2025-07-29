<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Anjuran</title>
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
                {{ __('Edit Anjuran') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <form method="POST" action="{{ route('dokumen.anjuran.update', ['id' => $anjuran->anjuran_id]) }}"
                        class="p-6">
                        @csrf
                        @method('PUT')

                        <!-- Data Pengusaha -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-800 pb-3 border-b-2 border-gray-200">Data
                                Pengusaha
                            </h3>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="flex items-center text-sm font-medium text-gray-700">
                                        Nama Pengusaha
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <input type="text" name="nama_pengusaha"
                                        value="{{ old('nama_pengusaha', $anjuran->nama_pengusaha) }}" required
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300">
                                    @error('nama_pengusaha')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label class="flex items-center text-sm font-medium text-gray-700">
                                        Jabatan Pengusaha
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <input type="text" name="jabatan_pengusaha"
                                        value="{{ old('jabatan_pengusaha', $anjuran->jabatan_pengusaha) }}" required
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
                                        value="{{ old('perusahaan_pengusaha', $anjuran->perusahaan_pengusaha) }}"
                                        required
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300">
                                    @error('perusahaan_pengusaha')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label class="flex items-center text-sm font-medium text-gray-700">
                                        Alamat
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <input type="text" name="alamat_pengusaha"
                                        value="{{ old('alamat_pengusaha', $anjuran->alamat_pengusaha) }}" required
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300">
                                    @error('alamat_pengusaha')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Data Pekerja -->
                        <div class="space-y-6 mt-8">
                            <h3 class="text-lg font-semibold text-gray-800 pb-3 border-b-2 border-gray-200">Data Pekerja
                            </h3>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="flex items-center text-sm font-medium text-gray-700">
                                        Nama Pekerja
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <input type="text" name="nama_pekerja"
                                        value="{{ old('nama_pekerja', $anjuran->nama_pekerja) }}" required
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300">
                                    @error('nama_pekerja')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label class="flex items-center text-sm font-medium text-gray-700">
                                        Jabatan Pekerja
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <input type="text" name="jabatan_pekerja"
                                        value="{{ old('jabatan_pekerja', $anjuran->jabatan_pekerja) }}" required
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
                                    <input type="text" name="perusahaan_pekerja"
                                        value="{{ old('perusahaan_pekerja', $anjuran->perusahaan_pekerja) }}" required
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
                                        value="{{ old('alamat_pekerja', $anjuran->alamat_pekerja) }}" required
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300">
                                    @error('alamat_pekerja')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Keterangan Pekerja -->
                        <div class="space-y-6 mt-8">
                            <h3 class="text-lg font-semibold text-gray-800 pb-3 border-b-2 border-gray-200">Keterangan
                                Pekerja</h3>
                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-medium text-gray-700">
                                    Keterangan pihak Pekerja/Buruh/Serikat Pekerja/Serikat Buruh
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <textarea name="keterangan_pekerja" rows="4" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300 resize-vertical">{{ old('keterangan_pekerja', $anjuran->keterangan_pekerja) }}</textarea>
                                @error('keterangan_pekerja')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Keterangan Pengusaha -->
                        <div class="space-y-6 mt-8">
                            <h3 class="text-lg font-semibold text-gray-800 pb-3 border-b-2 border-gray-200">Keterangan
                                Pengusaha</h3>
                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-medium text-gray-700">
                                    Keterangan pihak Pengusaha
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <textarea name="keterangan_pengusaha" rows="4" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300 resize-vertical">{{ old('keterangan_pengusaha', $anjuran->keterangan_pengusaha) }}</textarea>
                                @error('keterangan_pengusaha')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Pertimbangan Hukum -->
                        <div class="space-y-6 mt-8">
                            <h3 class="text-lg font-semibold text-gray-800 pb-3 border-b-2 border-gray-200">
                                Pertimbangan
                                Hukum</h3>
                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-medium text-gray-700">
                                    Pertimbangan Hukum dan Kesimpulan Mediator
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <textarea name="pertimbangan_hukum" rows="4" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300 resize-vertical">{{ old('pertimbangan_hukum', $anjuran->pertimbangan_hukum) }}</textarea>
                                @error('pertimbangan_hukum')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Isi Anjuran -->
                        <div class="space-y-6 mt-8">
                            <h3 class="text-lg font-semibold text-gray-800 pb-3 border-b-2 border-gray-200">Isi Anjuran
                            </h3>
                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-medium text-gray-700">
                                    Detail Anjuran
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <textarea name="isi_anjuran" rows="6" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300 resize-vertical">{{ old('isi_anjuran', $anjuran->isi_anjuran) }}</textarea>
                                @error('isi_anjuran')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 italic">Mohon jelaskan dengan detail semua poin anjuran
                                    yang diberikan</p>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-4 mt-8">
                            <a href="{{ route('dokumen.anjuran.show', ['id' => $anjuran->anjuran_id]) }}"
                                class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-all duration-300">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-3 bg-blue-700 hover:bg-blue-800 text-white rounded-lg font-medium transition-all duration-300">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-app-layout>
</body>

</html>
