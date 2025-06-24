<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Jadwal Mediasi
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Pengaduan #{{ $jadwal->pengaduan->pengaduan_id }} - {{ $jadwal->pengaduan->perihal }}
                </p>
            </div>
            <a href="{{ route('jadwal.show', $jadwal) }}"
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

                    {{-- Info Pengaduan --}}
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Informasi Pengaduan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">ID Pengaduan:</span>
                                <span class="ml-2 text-gray-900">#{{ $jadwal->pengaduan->pengaduan_id }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Perihal:</span>
                                <span class="ml-2 text-gray-900">{{ $jadwal->pengaduan->perihal }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Pelapor:</span>
                                <span class="ml-2 text-gray-900">{{ $jadwal->pengaduan->pelapor->nama_pelapor }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Perusahaan:</span>
                                <span class="ml-2 text-gray-900">{{ $jadwal->pengaduan->nama_terlapor }}</span>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('jadwal.update', $jadwal) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            {{-- Tanggal Mediasi --}}
                            <div>
                                <label for="tanggal_mediasi" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Mediasi <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal_mediasi" id="tanggal_mediasi"
                                    value="{{ old('tanggal_mediasi', $jadwal->tanggal_mediasi->format('Y-m-d')) }}"
                                    min="{{ date('Y-m-d') }}" class="w-full rounded-md border-gray-300" required>
                            </div>

                            {{-- Waktu Mediasi --}}
                            <div>
                                <label for="waktu_mediasi" class="block text-sm font-medium text-gray-700 mb-2">
                                    Waktu Mediasi <span class="text-red-500">*</span>
                                </label>
                                <input type="time" name="waktu_mediasi" id="waktu_mediasi"
                                    value="{{ old('waktu_mediasi', $jadwal->waktu_mediasi->format('H:i')) }}"
                                    class="w-full rounded-md border-gray-300" required>
                                <p class="text-xs text-gray-500 mt-1">Jam kerja: 08:00 - 17:00</p>
                            </div>
                        </div>

                        {{-- Tempat Mediasi --}}
                        <div class="mb-6">
                            <label for="tempat_mediasi" class="block text-sm font-medium text-gray-700 mb-2">
                                Tempat Mediasi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="tempat_mediasi" id="tempat_mediasi"
                                value="{{ old('tempat_mediasi', $jadwal->tempat_mediasi) }}"
                                placeholder="Contoh: Ruang Mediasi A, Kantor Disnakertrans"
                                class="w-full rounded-md border-gray-300" required>
                        </div>

                        {{-- Status Jadwal --}}
                        <div class="mb-6">
                            <label for="status_jadwal" class="block text-sm font-medium text-gray-700 mb-2">
                                Status Jadwal <span class="text-red-500">*</span>
                            </label>
                            <select name="status_jadwal" id="status_jadwal" class="w-full rounded-md border-gray-300"
                                required>
                                @foreach (\App\Models\JadwalMediasi::getStatusOptions() as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('status_jadwal', $jadwal->status_jadwal) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Catatan Jadwal --}}
                        <div class="mb-6">
                            <label for="catatan_jadwal" class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan Jadwal
                            </label>
                            <textarea name="catatan_jadwal" id="catatan_jadwal" rows="4"
                                placeholder="Tambahkan catatan khusus untuk jadwal mediasi ini..." class="w-full rounded-md border-gray-300">{{ old('catatan_jadwal', $jadwal->catatan_jadwal) }}</textarea>
                        </div>

                        {{-- Hasil Mediasi (hanya muncul jika status selesai) --}}
                        <div class="mb-6" id="hasil_mediasi_section"
                            style="{{ old('status_jadwal', $jadwal->status_jadwal) == 'selesai' ? '' : 'display: none;' }}">
                            <label for="hasil_mediasi" class="block text-sm font-medium text-gray-700 mb-2">
                                Hasil Mediasi
                            </label>
                            <textarea name="hasil_mediasi" id="hasil_mediasi" rows="4"
                                placeholder="Deskripsikan hasil dari mediasi yang telah dilakukan..." class="w-full rounded-md border-gray-300">{{ old('hasil_mediasi', $jadwal->hasil_mediasi) }}</textarea>
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('jadwal.show', $jadwal) }}"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded-lg">
                                Batal
                            </a>
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Show/hide hasil mediasi section based on status
        document.getElementById('status_jadwal').addEventListener('change', function() {
            const hasilMediasiSection = document.getElementById('hasil_mediasi_section');
            if (this.value === 'selesai') {
                hasilMediasiSection.style.display = 'block';
            } else {
                hasilMediasiSection.style.display = 'none';
            }
        });
    </script>
</x-app-layout>
