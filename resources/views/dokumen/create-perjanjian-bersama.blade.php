<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Perjanjian Bersama') }}
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto px-5 py-8">
        <!-- Form Container -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">Form Perjanjian Bersama</h2>
                <p class="text-sm text-gray-600">Silakan lengkapi formulir di bawah ini untuk membuat perjanjian bersama
                    antara pihak pekerja dan pengusaha</p>
            </div>

            <!-- Form Body -->
            <form method="POST" action="{{ route('dokumen.perjanjian-bersama.store') }}" class="p-8 space-y-8">
                @csrf
                <input type="hidden" name="dokumen_hi_id" value="{{ $dokumen_hi_id }}">

                <!-- Data Pengusaha -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-800 pb-3 border-b-2 border-gray-200">Data Pengusaha</h3>
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
                                placeholder="Masukkan jabatan pengusaha" required
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300">
                        </div>

                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-medium text-gray-700">
                                Perusahaan
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="text" name="perusahaan_pengusaha"
                                value="{{ old('perusahaan_pengusaha', $risalah->nama_perusahaan) }}"
                                placeholder="Masukkan nama perusahaan" readonly
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300 bg-gray-50">
                        </div>

                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-medium text-gray-700">
                                Alamat
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="text" name="alamat_pengusaha"
                                value="{{ old('alamat_pengusaha', $risalah->alamat_perusahaan) }}"
                                placeholder="Masukkan alamat lengkap" readonly
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300 bg-gray-50">
                        </div>
                    </div>
                </div>

                <!-- Data Pekerja -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-800 pb-3 border-b-2 border-gray-200">Data Pekerja</h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-medium text-gray-700">
                                Nama Pekerja
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="text" name="nama_pekerja"
                                value="{{ old('nama_pekerja', $risalah->nama_pekerja) }}"
                                placeholder="Masukkan nama pekerja" readonly
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300 bg-gray-50">
                        </div>

                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-medium text-gray-700">
                                Jabatan Pekerja
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="text" name="jabatan_pekerja" value="{{ old('jabatan_pekerja') }}"
                                placeholder="Masukkan jabatan pekerja" required
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300">
                        </div>

                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-medium text-gray-700">
                                Perusahaan
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="text" name="perusahaan_pekerja" value="{{ old('perusahaan_pekerja') }}"
                                placeholder="Masukkan nama perusahaan" required
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300">
                        </div>

                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-medium text-gray-700">
                                Alamat
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="text" name="alamat_pekerja"
                                value="{{ old('alamat_pekerja', $risalah->alamat_pekerja) }}"
                                placeholder="Masukkan alamat lengkap" readonly
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300 bg-gray-50">
                        </div>
                    </div>
                </div>

                <!-- Isi Kesepakatan -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-800 pb-3 border-b-2 border-gray-200">Isi Kesepakatan</h3>
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-medium text-gray-700">
                            Detail Kesepakatan
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <textarea name="isi_kesepakatan" rows="6" required
                            placeholder="Tuliskan detail kesepakatan yang telah dicapai antara kedua belah pihak"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300 resize-vertical">{{ old('isi_kesepakatan') }}</textarea>
                        <p class="text-xs text-gray-500 italic">Mohon jelaskan dengan detail semua poin kesepakatan yang
                            telah disetujui</p>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4 pt-4">
                    <a href="{{ url()->previous() }}"
                        class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-all duration-300">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-lg font-medium transition-all duration-300">
                        Simpan Perjanjian Bersama
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
