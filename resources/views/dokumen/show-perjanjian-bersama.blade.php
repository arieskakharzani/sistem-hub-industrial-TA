<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Perjanjian Bersama') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('dokumen.perjanjian-bersama.edit', ['id' => $perjanjian->perjanjian_bersama_id]) }}"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300">
                    Edit Perjanjian
                </a>
                <a href="{{ route('dokumen.perjanjian-bersama.pdf', ['id' => $perjanjian->perjanjian_bersama_id]) }}"
                    target="_blank"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300">
                    Cetak PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold mb-2">PERJANJIAN BERSAMA</h2>
                    </div>

                    <!-- Pembuka -->
                    <div class="mb-6 text-gray-700">
                        <p>Pada hari ini ............tanggal ............ bulan ............ tahun ............</p>
                        <p>kami yang bertanda tangan di bawah ini:</p>
                    </div>

                    <!-- Data Para Pihak -->
                    <div class="grid md:grid-cols-2 gap-8 mb-8">
                        <!-- Pihak Pengusaha -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="font-semibold text-lg mb-4 text-gray-800">Pihak Pengusaha:</h3>
                            <div class="space-y-2">
                                <p><span class="font-medium">Nama:</span> {{ $perjanjian->nama_pengusaha }}</p>
                                <p><span class="font-medium">Jabatan:</span> {{ $perjanjian->jabatan_pengusaha }}</p>
                                <p><span class="font-medium">Perusahaan:</span> {{ $perjanjian->perusahaan_pengusaha }}
                                </p>
                                <p><span class="font-medium">Alamat:</span> {{ $perjanjian->alamat_pengusaha }}</p>
                            </div>
                        </div>

                        <!-- Pihak Pekerja -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="font-semibold text-lg mb-4 text-gray-800">Pihak Pekerja/Buruh/SP/SB:</h3>
                            <div class="space-y-2">
                                <p><span class="font-medium">Nama:</span> {{ $perjanjian->nama_pekerja }}</p>
                                <p><span class="font-medium">Jabatan:</span> {{ $perjanjian->jabatan_pekerja }}</p>
                                <p><span class="font-medium">Perusahaan:</span> {{ $perjanjian->perusahaan_pekerja }}
                                </p>
                                <p><span class="font-medium">Alamat:</span> {{ $perjanjian->alamat_pekerja }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Dasar Hukum -->
                    <div class="mb-6 text-gray-700">
                        <p class="mb-4">
                            Berdasarkan ketentuan Pasal 13 ayat (1) Undang-Undang Nomor 2 Tahun 2004 tentang
                            Penyelesaian
                            Perselisihan Hubungan Industrial, antara Pihak Pengusaha dan Pihak Pekerja/Buruh/SP/SB telah
                            tercapai kesepakatan penyelesaian perselisihan hubungan industrial melalui Mediasi sebagai
                            berikut:
                        </p>
                    </div>

                    <!-- Isi Kesepakatan -->
                    <div class="mb-8">
                        <h3 class="font-semibold text-lg mb-4 text-gray-800">Isi Kesepakatan:</h3>
                        <div class="bg-gray-50 p-6 rounded-lg whitespace-pre-line text-gray-700">
                            {!! nl2br(e($perjanjian->isi_kesepakatan)) !!}
                        </div>
                    </div>

                    <!-- Penutup -->
                    <div class="mb-8 text-gray-700">
                        <p class="mb-4">
                            Kesepakatan ini merupakan perjanjian bersama yang berlaku sejak ditandatangani diatas
                            materai
                            cukup.
                        </p>
                        <p>
                            Demikian Perjanjian Bersama ini dibuat dalam keadaan sadar tanpa paksaan dari pihak manapun,
                            dan dilaksanakan dengan penuh rasa tanggung jawab yang didasari itikad baik.
                        </p>
                    </div>

                    <!-- Tanda Tangan -->
                    <div class="grid md:grid-cols-2 gap-8 mb-8">
                        <div class="text-center">
                            <p class="font-medium mb-20">Pihak Pengusaha,</p>
                            <p class="font-medium">({{ $perjanjian->nama_pengusaha }})</p>
                        </div>
                        <div class="text-center">
                            <p class="font-medium mb-20">Pihak Pekerja/Buruh/SP/SB,</p>
                            <p class="font-medium">({{ $perjanjian->nama_pekerja }})</p>
                        </div>
                    </div>

                    <!-- Mediator -->
                    <div class="text-center">
                        <p class="font-medium mb-2">Menyaksikan</p>
                        <p class="font-medium mb-20">Mediator Hubungan Industrial,</p>
                        <p class="font-medium">........................................</p>
                        <p class="text-gray-600">NIP. ........................................</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
