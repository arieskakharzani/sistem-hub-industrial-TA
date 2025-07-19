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
                <p class="text-sm text-gray-600">Silakan lengkapi formulir di bawah ini untuk membuat anjuran tertulis
                    terkait penyelesaian perselisihan</p>
            </div>

            <!-- Form Body -->
            <form method="POST" action="{{ route('dokumen.anjuran.store') }}" class="p-8 space-y-8">
                @csrf
                <input type="hidden" name="dokumen_hi_id" value="{{ $dokumen_hi_id }}">

                <!-- Keterangan Para Pihak -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-800 pb-3 border-b-2 border-gray-200">Keterangan Para
                        Pihak
                    </h3>
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-medium text-gray-700">
                                Keterangan Pihak Pekerja
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <textarea name="keterangan_pekerja" rows="4" required
                                placeholder="Tuliskan keterangan dari pihak pekerja terkait perselisihan"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300 resize-vertical">{{ old('keterangan_pekerja') }}</textarea>
                            <p class="text-xs text-gray-500 italic">Jelaskan posisi dan argumen dari pihak pekerja</p>
                        </div>

                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-medium text-gray-700">
                                Keterangan Pihak Pengusaha
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <textarea name="keterangan_pengusaha" rows="4" required
                                placeholder="Tuliskan keterangan dari pihak pengusaha terkait perselisihan"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300 resize-vertical">{{ old('keterangan_pengusaha') }}</textarea>
                            <p class="text-xs text-gray-500 italic">Jelaskan posisi dan argumen dari pihak pengusaha</p>
                        </div>
                    </div>
                </div>

                <!-- Pertimbangan dan Anjuran -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-800 pb-3 border-b-2 border-gray-200">Pertimbangan dan
                        Anjuran
                    </h3>
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-medium text-gray-700">
                                Pertimbangan Hukum
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <textarea name="pertimbangan_hukum" rows="4" required
                                placeholder="Tuliskan pertimbangan hukum yang menjadi dasar anjuran"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300 resize-vertical">{{ old('pertimbangan_hukum') }}</textarea>
                            <p class="text-xs text-gray-500 italic">Cantumkan dasar hukum dan pertimbangan yang relevan
                            </p>
                        </div>

                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-medium text-gray-700">
                                Isi Anjuran
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <textarea name="isi_anjuran" rows="6" required
                                placeholder="Tuliskan anjuran tertulis untuk penyelesaian perselisihan"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300 resize-vertical">{{ old('isi_anjuran') }}</textarea>
                            <p class="text-xs text-gray-500 italic">Jelaskan dengan detail anjuran yang diberikan untuk
                                menyelesaikan perselisihan</p>
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
