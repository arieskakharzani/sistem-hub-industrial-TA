<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Buat Jadwal Baru</title>
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
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Buat jadwal Baru
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

                        <form method="POST" action="{{ route('jadwal.store') }}" id="jadwalForm">
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
                                            {{ $pengaduan->perihal }}
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
                                {{-- Tanggal --}}
                                <div>
                                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanggal <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal') }}"
                                        min="{{ date('Y-m-d') }}" class="w-full rounded-md border-gray-300" required>
                                    <p class="text-xs text-gray-500 mt-1">Minimal tanggal hari ini ({{ date('d/m/Y') }})
                                    </p>
                                </div>

                                {{-- Waktu --}}
                                <div>
                                    <label for="waktu" class="block text-sm font-medium text-gray-700 mb-2">
                                        Waktu <span class="text-red-500">*</span>
                                    </label>
                                    <input type="time" name="waktu" id="waktu" value="{{ old('waktu') }}"
                                        min="08:00" max="16:00" class="w-full rounded-md border-gray-300"
                                        required>
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
                                <input type="text" name="tempat" id="tempat" value="{{ old('tempat') }}"
                                    placeholder="Contoh: Ruang Rapat A, Kantor Disnakertrans"
                                    class="w-full rounded-md border-gray-300" required>
                            </div>

                            {{-- Jenis Jadwal --}}
                            <div class="mb-6">
                                <label for="jenis_jadwal" class="block text-sm font-medium text-gray-700 mb-2">Jenis
                                    Jadwal</label>
                                <select name="jenis_jadwal" id="jenis_jadwal" class="form-select mt-1 block w-full">
                                    <option value="mediasi">Mediasi</option>
                                    <option value="klarifikasi">Klarifikasi</option>
                                </select>
                            </div>
                            {{-- Sidang Ke (jika jenis_jadwal=mediasi) --}}
                            <div class="mb-6" id="sidang_ke_field" style="display: none;">
                                <label for="sidang_ke" class="block text-gray-700">Sidang Ke-</label>
                                <input type="text" name="sidang_ke" id="sidang_ke"
                                    class="form-input mt-1 block w-full" placeholder="I/II/III">
                            </div>

                            {{-- Catatan Jadwal --}}
                            <div class="mb-6">
                                <label for="catatan_jadwal" class="block text-sm font-medium text-gray-700 mb-2">
                                    Catatan Jadwal
                                </label>
                                <textarea name="catatan_jadwal" id="catatan_jadwal" rows="4"
                                    placeholder="Tambahkan catatan khusus untuk jadwal ini..." class="w-full rounded-md border-gray-300">{{ old('catatan_jadwal') }}</textarea>
                            </div>

                            {{-- Tombol Submit --}}
                            <div class="flex justify-end space-x-4">
                                <a href="{{ route('jadwal.index') }}"
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">
                                    Batal
                                </a>
                                <button type="submit" id="submitBtn" style="background-color: #1D4ED8; color: white;"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                                    Buat Jadwal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- JavaScript untuk validasi tanggal dan waktu --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const tanggalInput = document.getElementById('tanggal');
                const waktuInput = document.getElementById('waktu');
                const waktuInfo = document.getElementById('waktu-info');
                const waktuError = document.getElementById('waktu-error');
                const submitBtn = document.getElementById('submitBtn');
                const form = document.getElementById('jadwalForm');
                const jenisJadwal = document.getElementById('jenis_jadwal');
                const sidangKeField = document.getElementById('sidang_ke_field');

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
                                'Hari ini sudah terlalu sore untuk membuat jadwal. Pilih tanggal besok atau setelahnya.';
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

                    // Reset value jika tidak valid
                    if (waktuInput.value && (waktuInput.value < waktuInput.min || waktuInput.value > waktuInput.max)) {
                        waktuInput.value = '';
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

                // Validasi form sebelum submit
                form.addEventListener('submit', function(e) {
                    if (!validateTime()) {
                        e.preventDefault();
                        alert(
                            'Mohon pilih waktu yang valid. Waktu harus setelah waktu saat ini jika memilih hari ini.'
                        );
                        return false;
                    }

                    // Validasi tambahan untuk jam kerja
                    const selectedTime = waktuInput.value;
                    if (selectedTime < '08:00' || selectedTime > '16:00') {
                        e.preventDefault();
                        alert('Waktu harus dalam jam kerja (08:00 - 16:00).');
                        return false;
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

                // Event listener untuk perubahan jenis jadwal
                jenisJadwal.addEventListener('change', function() {
                    if (this.value === 'mediasi') {
                        sidangKeField.style.display = '';
                    } else {
                        sidangKeField.style.display = 'none';
                    }
                });
                // Trigger on page load
                if (jenisJadwal.value === 'mediasi') sidangKeField.style.display = '';
            });
        </script>
    </x-app-layout>
</body>

</html>
