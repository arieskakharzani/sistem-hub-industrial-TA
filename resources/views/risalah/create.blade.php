<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Buat Risalah {{ ucfirst($jenis_risalah) }}</title>
</head>

<body>
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Buat Risalah ' . ucfirst($jenis_risalah)) }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <!-- Form Container -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <!-- Form Header -->
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">Form Risalah {{ ucfirst($jenis_risalah) }}
                        </h2>
                        <p class="text-sm text-gray-600">Silakan lengkapi formulir di bawah ini untuk membuat risalah
                            {{ $jenis_risalah }}</p>
                    </div>

                    <!-- Form Body -->
                    <form method="POST" action="{{ route('risalah.store', [$jadwal->jadwal_id, $jenis_risalah]) }}"
                        class="p-8 space-y-8">
                        @csrf
                        <input type="hidden" name="jenis_risalah" value="{{ $jenis_risalah }}">

                        <!-- Data Perusahaan -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-800 pb-3 border-b-2 border-gray-200">Data
                                Perusahaan</h3>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="flex items-center text-sm font-medium text-gray-700">
                                        Nama Perusahaan
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan') }}"
                                        placeholder="Masukkan nama perusahaan" required
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300">
                                </div>

                                <div class="space-y-2">
                                    <label class="flex items-center text-sm font-medium text-gray-700">
                                        Jenis Usaha
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <input type="text" name="jenis_usaha" value="{{ old('jenis_usaha') }}"
                                        placeholder="Masukkan jenis usaha" required
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300">
                                </div>

                                <div class="space-y-2 md:col-span-2">
                                    <label class="flex items-center text-sm font-medium text-gray-700">
                                        Alamat Perusahaan
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <input type="text" name="alamat_perusahaan"
                                        value="{{ old('alamat_perusahaan') }}"
                                        placeholder="Masukkan alamat lengkap perusahaan" required
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300">
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
                                        Nama Pekerja/Buruh/SP/SB
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <input type="text" name="nama_pekerja" value="{{ old('nama_pekerja') }}"
                                        placeholder="Masukkan nama pekerja" required
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300">
                                </div>

                                <div class="space-y-2">
                                    <label class="flex items-center text-sm font-medium text-gray-700">
                                        Alamat Pekerja/Buruh/SP/SB
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <input type="text" name="alamat_pekerja" value="{{ old('alamat_pekerja') }}"
                                        placeholder="Masukkan alamat lengkap pekerja" required
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300">
                                </div>
                            </div>
                        </div>

                        <!-- Detail Perundingan -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-800 pb-3 border-b-2 border-gray-200">Detail
                                Perundingan</h3>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="flex items-center text-sm font-medium text-gray-700">
                                        Tanggal Perundingan
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" name="tanggal_perundingan"
                                            value="{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d/m/Y') }}"
                                            readonly
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-50 cursor-not-allowed">
                                        <input type="hidden" name="tanggal_perundingan"
                                            value="{{ $jadwal->tanggal }}">
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="flex items-center text-sm font-medium text-gray-700">
                                        Tempat Perundingan
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <input type="text" name="tempat_perundingan" value="{{ $jadwal->tempat }}"
                                        readonly
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-50 cursor-not-allowed">
                                </div>

                                <div class="space-y-2 md:col-span-2">
                                    <label class="flex items-center text-sm font-medium text-gray-700">
                                        Pokok Masalah/Alasan Perselisihan
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <textarea name="pokok_masalah" rows="4" required
                                        placeholder="Jelaskan pokok permasalahan atau alasan perselisihan"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300 resize-vertical">{{ old('pokok_masalah') }}</textarea>
                                </div>

                                <div class="space-y-2 md:col-span-2">
                                    <label class="flex items-center text-sm font-medium text-gray-700">
                                        Pendapat Pekerja/Buruh/SP/SB
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <textarea name="pendapat_pekerja" rows="4" required placeholder="Tuliskan pendapat dari pihak pekerja"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300 resize-vertical">{{ old('pendapat_pekerja') }}</textarea>
                                </div>

                                <div class="space-y-2 md:col-span-2">
                                    <label class="flex items-center text-sm font-medium text-gray-700">
                                        Pendapat Pengusaha
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <textarea name="pendapat_pengusaha" rows="4" required placeholder="Tuliskan pendapat dari pihak pengusaha"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300 resize-vertical">{{ old('pendapat_pengusaha') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Kesimpulan -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-800 pb-3 border-b-2 border-gray-200">Kesimpulan
                            </h3>
                            <div class="space-y-6">
                                @if ($jenis_risalah === 'klarifikasi')
                                    <div class="space-y-2">
                                        <label class="flex items-center text-sm font-medium text-gray-700">
                                            Arahan Mediator
                                            <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <textarea name="arahan_mediator" rows="4" required placeholder="Tuliskan arahan dari mediator"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300 resize-vertical">{{ old('arahan_mediator') }}</textarea>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="flex items-center text-sm font-medium text-gray-700">
                                            Kesimpulan Klarifikasi
                                            <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <select name="kesimpulan_klarifikasi" required
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300">
                                            <option value="">-- Pilih Kesimpulan --</option>
                                            <option value="bipartit_lagi"
                                                {{ old('kesimpulan_klarifikasi') == 'bipartit_lagi' ? 'selected' : '' }}>
                                                Perundingan Bipartit
                                            </option>
                                            <option value="lanjut_ke_tahap_mediasi"
                                                {{ old('kesimpulan_klarifikasi') == 'lanjut_ke_tahap_mediasi' ? 'selected' : '' }}>
                                                Lanjut ke Tahap Mediasi
                                            </option>
                                        </select>
                                        <p class="text-xs text-gray-500 italic">Pilih kesimpulan yang sesuai dengan
                                            hasil
                                            klarifikasi</p>
                                    </div>
                                @endif

                                @if ($jenis_risalah === 'penyelesaian')
                                    <div class="space-y-2">
                                        <label class="flex items-center text-sm font-medium text-gray-700">
                                            Kesimpulan atau Hasil Perundingan
                                            <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <textarea name="kesimpulan_penyelesaian" rows="6" required
                                            placeholder="Tuliskan kesimpulan atau hasil dari perundingan"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300 resize-vertical">{{ old('kesimpulan_penyelesaian') }}</textarea>
                                        <p class="text-xs text-gray-500 italic">Jelaskan dengan detail hasil
                                            perundingan yang telah
                                            dicapai</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-4 pt-4">
                            <a href="{{ url()->previous() }}"
                                class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-all duration-300">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-3 bg-blue-700 hover:bg-blue-800 text-white rounded-lg font-medium transition-all duration-300">
                                Simpan Risalah
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-app-layout>
</body>

</html>
