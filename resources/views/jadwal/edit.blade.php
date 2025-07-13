<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit jadwal
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    {{ $jadwal->pengaduan->perihal }}
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
                            {{-- <div>
                                <span class="text-gray-500">ID Pengaduan:</span>
                                <span class="ml-2 text-gray-900">#{{ $jadwal->pengaduan->pengaduan_id }}</span>
                            </div> --}}
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

                    <form method="POST" action="{{ route('jadwal.update', $jadwal) }}" id="editJadwalForm">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            {{-- Tanggal --}}
                            <div>
                                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal" id="tanggal"
                                    value="{{ old('tanggal', $jadwal->tanggal->format('Y-m-d')) }}"
                                    min="{{ date('Y-m-d') }}" class="w-full rounded-md border-gray-300" required>
                                <p class="text-xs text-gray-500 mt-1">Minimal tanggal hari ini ({{ date('d/m/Y') }})
                                </p>
                            </div>

                            {{-- Waktu --}}
                            <div>
                                <label for="waktu" class="block text-sm font-medium text-gray-700 mb-2">
                                    Waktu <span class="text-red-500">*</span>
                                </label>
                                <input type="time" name="waktu" id="waktu"
                                    value="{{ old('waktu', $jadwal->waktu->format('H:i')) }}" min="08:00"
                                    max="16:00" class="w-full rounded-md border-gray-300" required>
                                <p class="text-xs text-gray-500 mt-1" id="waktu-info">
                                    Jam kerja: 08:00 - 16:00
                                </p>
                                <p class="text-xs text-red-500 mt-1 hidden" id="waktu-error">
                                    Waktu harus lebih dari waktu saat ini untuk hari ini
                                </p>
                            </div>
                        </div>

                        {{-- Tempat --}}
                        <div class="mb-6">
                            <label for="tempat" class="block text-sm font-medium text-gray-700 mb-2">
                                Tempat <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="tempat" id="tempat"
                                value="{{ old('tempat', $jadwal->tempat) }}"
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
                                @foreach (\App\Models\Jadwal::getStatusOptions() as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('status_jadwal', $jadwal->status_jadwal) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Jenis Jadwal --}}
                        <div class="mb-4">
                            <label for="jenis_jadwal" class="block text-gray-700">Jenis Jadwal</label>
                            <select name="jenis_jadwal" id="jenis_jadwal" class="form-select mt-1 block w-full">
                                <option value="mediasi" {{ $jadwal->jenis_jadwal == 'mediasi' ? 'selected' : '' }}>
                                    Mediasi</option>
                                <option value="klarifikasi"
                                    {{ $jadwal->jenis_jadwal == 'klarifikasi' ? 'selected' : '' }}>Klarifikasi</option>
                            </select>
                        </div>
                        <div class="mb-4" id="sidang_ke_field" style="display: none;">
                            <label for="sidang_ke" class="block text-gray-700">Sidang Ke-</label>
                            <input type="text" name="sidang_ke" id="sidang_ke" class="form-input mt-1 block w-full"
                                value="{{ $jadwal->sidang_ke }}" placeholder="I/II/III">
                        </div>
                        <script>
                            const jenisJadwal = document.getElementById('jenis_jadwal');
                            const sidangKeField = document.getElementById('sidang_ke_field');
                            jenisJadwal.addEventListener('change', function() {
                                if (this.value === 'mediasi') {
                                    sidangKeField.style.display = '';
                                } else {
                                    sidangKeField.style.display = 'none';
                                }
                            });
                            // Trigger on page load
                            if (jenisJadwal.value === 'mediasi') sidangKeField.style.display = '';
                        </script>

                        {{-- Catatan Jadwal --}}
                        <div class="mb-6">
                            <label for="catatan_jadwal" class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan Jadwal
                            </label>
                            <textarea name="catatan_jadwal" id="catatan_jadwal" rows="4"
                                placeholder="Tambahkan catatan khusus untuk jadwal ini..." class="w-full rounded-md border-gray-300">{{ old('catatan_jadwal', $jadwal->catatan_jadwal) }}</textarea>
                        </div>

                        {{-- Hasil Mediasi (hanya muncul jika status selesai) --}}
                        <div class="mb-6" id="hasil_section"
                            style="{{ old('status_jadwal', $jadwal->status_jadwal) == 'selesai' ? '' : 'display: none;' }}">
                            <label for="hasil" class="block text-sm font-medium text-gray-700 mb-2">
                                Hasil Mediasi
                            </label>
                            <textarea name="hasil" id="hasil" rows="4"
                                placeholder="Deskripsikan hasil dari mediasi yang telah dilakukan..." class="w-full rounded-md border-gray-300">{{ old('hasil', $jadwal->hasil) }}</textarea>
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('jadwal.show', $jadwal) }}"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded-lg">
                                Batal
                            </a>
                            <button type="submit" id="submitBtn"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript untuk validasi tanggal, waktu, dan status --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tanggalInput = document.getElementById('tanggal');
            const waktuInput = document.getElementById('waktu');
            const waktuInfo = document.getElementById('waktu-info');
            const waktuError = document.getElementById('waktu-error');
            const statusSelect = document.getElementById('status_jadwal');
            const hasilMediasiSection = document.getElementById('hasil_section');
            const submitBtn = document.getElementById('submitBtn');
            const form = document.getElementById('editJadwalForm');

            // Fungsi untuk mendapatkan waktu saat ini dalam format HH:MM
            function getCurrentTime() {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                return `${hours}:${minutes}`;
            }

            // Fungsi untuk mendapatkan tanggal hari ini dalam format YYYY-MM-DD
            function getTodayDate() {
                const today = new Date();
                return today.toISOString().split('T')[0];
            }

            // Fungsi untuk memvalidasi waktu
            function validateTime() {
                const selectedDate = tanggalInput.value;
                const selectedTime = waktuInput.value;
                const today = getTodayDate();
                const currentTime = getCurrentTime();

                // Reset error state
                waktuError.classList.add('hidden');
                waktuInfo.classList.remove('hidden');
                waktuInput.classList.remove('border-red-500');

                if (selectedDate && selectedTime) {
                    // Jika tanggal yang dipilih adalah hari ini
                    if (selectedDate === today) {
                        // Periksa apakah waktu yang dipilih sudah lewat
                        if (selectedTime <= currentTime) {
                            waktuError.classList.remove('hidden');
                            waktuInfo.classList.add('hidden');
                            waktuInput.classList.add('border-red-500');
                            return false;
                        }
                    }
                }
                return true;
            }

            // Fungsi untuk mengatur waktu minimal berdasarkan tanggal
            function updateTimeConstraints() {
                const selectedDate = tanggalInput.value;
                const today = getTodayDate();

                if (selectedDate === today) {
                    // Jika hari ini, set waktu minimal 1 jam dari sekarang
                    const now = new Date();
                    now.setHours(now.getHours() + 1);
                    const minTime = now.toTimeString().slice(0, 5);

                    // Pastikan tidak melebihi jam kerja
                    if (minTime > '16:00') {
                        waktuInput.min = '16:00';
                        waktuInput.max = '16:00';
                        waktuInfo.textContent =
                            'Hari ini sudah terlalu sore untuk mengubah jadwal. Pilih tanggal besok atau setelahnya.';
                        waktuInfo.classList.add('text-red-500');
                    } else {
                        waktuInput.min = minTime > '08:00' ? minTime : '08:00';
                        waktuInput.max = '16:00';
                        waktuInfo.textContent = `Jam kerja: ${waktuInput.min} - 16:00 (untuk hari ini)`;
                        waktuInfo.classList.remove('text-red-500');
                    }
                } else {
                    // Jika tanggal lain, gunakan jam kerja normal
                    waktuInput.min = '08:00';
                    waktuInput.max = '16:00';
                    waktuInfo.textContent = 'Jam kerja: 08:00 - 16:00';
                    waktuInfo.classList.remove('text-red-500');
                }

                // Jangan reset value yang sudah ada saat edit, kecuali tidak valid
                const currentValue = waktuInput.value;
                if (currentValue && (currentValue < waktuInput.min || currentValue > waktuInput.max)) {
                    // Hanya beri warning, jangan reset value
                    waktuInfo.textContent += ' (Waktu saat ini tidak valid)';
                    waktuInfo.classList.add('text-orange-500');
                }
            }

            // Event listener untuk perubahan tanggal
            tanggalInput.addEventListener('change', function() {
                updateTimeConstraints();
                validateTime();
            });

            // Event listener untuk perubahan waktu
            waktuInput.addEventListener('change', validateTime);
            waktuInput.addEventListener('input', validateTime);

            // Show/hide hasil mediasi section based on status
            statusSelect.addEventListener('change', function() {
                if (this.value === 'selesai') {
                    hasilMediasiSection.style.display = 'block';
                } else {
                    hasilMediasiSection.style.display = 'none';
                }
            });

            // Validasi form sebelum submit
            form.addEventListener('submit', function(e) {
                const selectedDate = tanggalInput.value;
                const selectedTime = waktuInput.value;
                const today = getTodayDate();

                // Validasi tanggal tidak boleh kemarin
                if (selectedDate < today) {
                    e.preventDefault();
                    alert('Tanggal tidak boleh di masa lalu.');
                    return false;
                }

                // Validasi waktu untuk hari ini
                if (selectedDate === today && !validateTime()) {
                    e.preventDefault();
                    alert(
                        'Mohon pilih waktu yang valid. Waktu harus setelah waktu saat ini jika memilih hari ini.'
                    );
                    return false;
                }

                // Validasi jam kerja
                if (selectedTime < '08:00' || selectedTime > '16:00') {
                    e.preventDefault();
                    alert('Waktu harus dalam jam kerja (08:00 - 16:00).');
                    return false;
                }

                // Validasi hasil mediasi jika status selesai
                if (statusSelect.value === 'selesai') {
                    const hasilMediasi = document.getElementById('hasil').value.trim();
                    if (!hasilMediasi) {
                        e.preventDefault();
                        alert('Hasil mediasi harus diisi jika status jadwal adalah "Selesai".');
                        return false;
                    }
                }
            });

            // Inisialisasi saat halaman dimuat
            updateTimeConstraints();

            // Update setiap menit untuk memastikan validasi waktu tetap akurat
            setInterval(function() {
                if (tanggalInput.value === getTodayDate()) {
                    updateTimeConstraints();
                    validateTime();
                }
            }, 60000); // Update setiap 1 menit
        });
    </script>
</x-app-layout>
