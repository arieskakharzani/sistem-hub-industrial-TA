<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Buat Jadwal Mediasi Baru
            </h2>
            <a href="{{ route('jadwal.index') }}"
                class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    {{-- Tampilkan error jika ada --}}
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('jadwal.store') }}">
                        @csrf

                        {{-- Pilih Pengaduan --}}
                        <div class="mb-6">
                            <label for="pengaduan_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Pilih Pengaduan <span class="text-red-500">*</span>
                            </label>
                            <select name="pengaduan_id" id="pengaduan_id" class="w-full rounded-md border-gray-300"
                                required>
                                <option value="">-- Pilih Pengaduan --</option>
                                @foreach ($pengaduanList as $pengaduan)
                                    <option value="{{ $pengaduan->pengaduan_id }}"
                                        {{ old('pengaduan_id') == $pengaduan->pengaduan_id ? 'selected' : '' }}>
                                        #{{ $pengaduan->pengaduan_id }} - {{ $pengaduan->perihal }}
                                        ({{ $pengaduan->pelapor->nama_pelapor }})
                                    </option>
                                @endforeach
                            </select>
                            @if ($pengaduanList->isEmpty())
                                <p class="text-sm text-gray-500 mt-1">
                                    Tidak ada pengaduan yang tersedia untuk dijadwalkan.
                                    Pastikan pengaduan sudah diassign kepada Anda dan statusnya 'proses'.
                                </p>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            {{-- Tanggal Mediasi --}}
                            <div>
                                <label for="tanggal_mediasi" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Mediasi <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal_mediasi" id="tanggal_mediasi"
                                    value="{{ old('tanggal_mediasi') }}" min="{{ date('Y-m-d') }}"
                                    class="w-full rounded-md border-gray-300" required>
                            </div>

                            {{-- Waktu Mediasi --}}
                            <div>
                                <label for="waktu_mediasi" class="block text-sm font-medium text-gray-700 mb-2">
                                    Waktu Mediasi <span class="text-red-500">*</span>
                                </label>
                                <input type="time" name="waktu_mediasi" id="waktu_mediasi"
                                    value="{{ old('waktu_mediasi') }}" class="w-full rounded-md border-gray-300"
                                    required>
                                <p class="text-xs text-gray-500 mt-1">Jam kerja: 08:00 - 16:00</p>
                            </div>
                        </div>

                        {{-- Tempat Mediasi --}}
                        <div class="mb-6">
                            <label for="tempat_mediasi" class="block text-sm font-medium text-gray-700 mb-2">
                                Tempat Mediasi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="tempat_mediasi" id="tempat_mediasi"
                                value="{{ old('tempat_mediasi') }}"
                                placeholder="Contoh: Ruang Mediasi A, Kantor Disnakertrans"
                                class="w-full rounded-md border-gray-300" required>
                        </div>

                        {{-- Catatan Jadwal --}}
                        <div class="mb-6">
                            <label for="catatan_jadwal" class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan Jadwal
                            </label>
                            <textarea name="catatan_jadwal" id="catatan_jadwal" rows="4"
                                placeholder="Tambahkan catatan khusus untuk jadwal mediasi ini..." class="w-full rounded-md border-gray-300">{{ old('catatan_jadwal') }}</textarea>
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('jadwal.index') }}"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">
                                Batal
                            </a>
                            <button type="submit" style="background-color: #1D4ED8; color: white;"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                                Buat Jadwal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
