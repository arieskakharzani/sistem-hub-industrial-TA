<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Pengaduan</title>
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
                    {{ __('Edit Pengaduan #' . $pengaduan->pengaduan_id) }}
                </h2>
                <div class="flex space-x-3">
                    <a href="{{ route('pengaduan.show', $pengaduan->pengaduan_id) }}"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Batal
                    </a>
                </div>
            </div>
        </x-slot>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

                <!-- Alert Warning -->
                <div class="mb-6 bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded relative">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.232 13.5c-.77.833.192 2.5 1.732 2.5z">
                            </path>
                        </svg>
                        <div>
                            <strong class="font-bold">Perhatian!</strong>
                            <span class="block sm:inline">Anda dapat mengedit pengaduan ini karena statusnya masih
                                pending. Setelah direview oleh mediator, pengaduan tidak dapat diubah lagi.</span>
                        </div>
                    </div>
                </div>

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-red-500 mr-2 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <strong class="font-bold">Terdapat kesalahan pada form:</strong>
                                <ul class="mt-2 list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Edit Form -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form method="POST" action="{{ route('pengaduan.update', $pengaduan->pengaduan_id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Informasi Dasar -->
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    Informasi Dasar Pengaduan
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Tanggal Laporan -->
                                    <div>
                                        <label for="tanggal_laporan"
                                            class="block text-sm font-medium text-gray-700 mb-2">
                                            Tanggal Laporan <span class="text-red-500">*</span>
                                        </label>
                                        <input type="date" name="tanggal_laporan" id="tanggal_laporan" required
                                            value="{{ old('tanggal_laporan', $pengaduan->tanggal_laporan->format('Y-m-d')) }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                        @error('tanggal_laporan')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Perihal -->
                                    <div>
                                        <label for="perihal" class="block text-sm font-medium text-gray-700 mb-2">
                                            Perihal <span class="text-red-500">*</span>
                                        </label>
                                        <select name="perihal" id="perihal" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                            <option value="">Pilih Perihal</option>
                                            @foreach ($perihalOptions as $option)
                                                <option value="{{ $option }}"
                                                    {{ old('perihal', $pengaduan->perihal) == $option ? 'selected' : '' }}>
                                                    {{ $option }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('perihal')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Masa Kerja -->
                                    <div class="md:col-span-2">
                                        <label for="masa_kerja" class="block text-sm font-medium text-gray-700 mb-2">
                                            Masa Kerja <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="masa_kerja" id="masa_kerja" required
                                            value="{{ old('masa_kerja', $pengaduan->masa_kerja) }}"
                                            placeholder="Contoh: 2 tahun 3 bulan"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                        @error('masa_kerja')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Pihak yang Dilaporkan -->
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                    Informasi Pihak yang Dilaporkan
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Nama Pihak yang Dilaporkan -->
                                    <div>
                                        <label for="nama_terlapor" class="block text-sm font-medium text-gray-700 mb-2">
                                            Nama Pihak yang Dilaporkan<span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="nama_terlapor" id="nama_terlapor" required
                                            value="{{ old('nama_terlapor', $pengaduan->nama_terlapor) }}"
                                            placeholder="Masukkan nama pihak yang dilaporkan"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                        @error('nama_terlapor')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Email Terlapor -->
                                    <div>
                                        <label for="email_terlapor"
                                            class="block text-sm font-medium text-gray-700 mb-2">
                                            Email Perusahaan <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" name="email_terlapor" id="email_terlapor" required
                                            value="{{ old('email_terlapor', $pengaduan->email_terlapor) }}"
                                            placeholder="contoh@perusahaan.com"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                        @error('email_terlapor')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Nomor Telepon -->
                                    <div>
                                        <label for="no_hp_terlapor"
                                            class="block text-sm font-medium text-gray-700 mb-2">
                                            Nomor Telepon <span class="text-red-500">*</span>
                                        </label>
                                        <input type="tel" name="no_hp_terlapor" id="no_hp_terlapor" required
                                            value="{{ old('no_hp_terlapor', $pengaduan->no_hp_terlapor) }}"
                                            placeholder="0812-3456-7890"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                        @error('no_hp_terlapor')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Alamat Kantor/Cabang -->
                                    <div class="md:col-span-2">
                                        <label for="alamat_kantor_cabang"
                                            class="block text-sm font-medium text-gray-700 mb-2">
                                            Alamat Kantor/Cabang
                                        </label>
                                        <textarea name="alamat_kantor_cabang" id="alamat_kantor_cabang" rows="3"
                                            placeholder="Masukkan alamat lengkap kantor/cabang"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('alamat_kantor_cabang', $pengaduan->alamat_kantor_cabang) }}</textarea>
                                        @error('alamat_kantor_cabang')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Detail Kasus -->
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    Detail Kasus
                                </h3>

                                <!-- Narasi Kasus -->
                                <div class="mb-6">
                                    <label for="narasi_kasus" class="block text-sm font-medium text-gray-700 mb-2">
                                        Narasi Kasus <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="narasi_kasus" id="narasi_kasus" rows="6" required
                                        placeholder="Jelaskan secara detail kronologi kejadian, permasalahan yang dialami, dan dampaknya terhadap Anda..."
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('narasi_kasus', $pengaduan->narasi_kasus) }}</textarea>
                                    <p class="mt-1 text-xs text-gray-500">Minimum 50 karakter. Jelaskan secara detail
                                        dan kronologis.</p>
                                    @error('narasi_kasus')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Catatan Tambahan -->
                                <div>
                                    <label for="catatan_tambahan"
                                        class="block text-sm font-medium text-gray-700 mb-2">
                                        Catatan Tambahan
                                    </label>
                                    <textarea name="catatan_tambahan" id="catatan_tambahan" rows="4"
                                        placeholder="Informasi tambahan yang perlu diketahui mediator (opsional)"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('catatan_tambahan', $pengaduan->catatan_tambahan) }}</textarea>
                                    @error('catatan_tambahan')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Lampiran yang Ada -->
                            @if ($pengaduan->lampiran && count($pengaduan->lampiran) > 0)
                                <div class="mb-8">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.586-6.586a4 4 0 00-5.656-5.656l-6.586 6.586a6 6 0 108.486 8.486L20.5 13">
                                            </path>
                                        </svg>
                                        Lampiran Saat Ini
                                    </h3>

                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            @foreach ($pengaduan->lampiran as $index => $lampiran)
                                                <div
                                                    class="flex items-center p-3 bg-white border border-gray-200 rounded-lg">
                                                    <svg class="w-6 h-6 text-gray-400 mr-3" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                        </path>
                                                    </svg>
                                                    <div class="flex-1">
                                                        <p class="text-sm font-medium text-gray-900">
                                                            {{ basename($lampiran) }}</p>
                                                        <p class="text-xs text-gray-500">Lampiran {{ $index + 1 }}
                                                        </p>
                                                    </div>
                                                    <div class="flex space-x-2">
                                                        <a href="{{ asset('storage/' . $lampiran) }}" target="_blank"
                                                            class="text-primary hover:text-primary-dark">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                                </path>
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                                </path>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <p class="mt-3 text-xs text-gray-600">
                                            <strong>Catatan:</strong> Lampiran yang sudah ada akan tetap tersimpan. Jika
                                            Anda menambah lampiran baru di bawah, file akan ditambahkan ke lampiran yang
                                            sudah ada.
                                        </p>
                                    </div>
                                </div>
                            @endif

                            <!-- Upload Lampiran Baru -->
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                        </path>
                                    </svg>
                                    {{ $pengaduan->lampiran && count($pengaduan->lampiran) > 0 ? 'Tambah Lampiran Baru' : 'Upload Lampiran' }}
                                </h3>

                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6">
                                    <div class="text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"
                                            fill="none" viewBox="0 0 48 48">
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="mt-4">
                                            <label for="lampiran" class="cursor-pointer">
                                                <span class="mt-2 block text-sm font-medium text-gray-900">
                                                    Upload file pendukung
                                                </span>
                                                <input id="lampiran" name="lampiran[]" type="file" multiple
                                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="sr-only">
                                            </label>
                                            <p class="mt-2 text-xs text-gray-500">
                                                PDF, DOC, DOCX, JPG, JPEG, PNG hingga 5MB per file
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- File preview area -->
                                <div id="file-preview" class="mt-4 hidden">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">File yang akan diupload:</h4>
                                    <div id="file-list" class="space-y-2"></div>
                                </div>

                                @error('lampiran.*')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                                <a href="{{ route('pengaduan.show', $pengaduan->pengaduan_id) }}"
                                    class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                                    Batal
                                </a>
                                <button type="submit"
                                    class="px-8 py-3 bg-black marker: text-white rounded-lg font-medium hover:bg-primary-dark transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- JavaScript for file preview -->
        <script>
            document.getElementById('lampiran').addEventListener('change', function(e) {
                const files = e.target.files;
                const preview = document.getElementById('file-preview');
                const fileList = document.getElementById('file-list');

                if (files.length > 0) {
                    preview.classList.remove('hidden');
                    fileList.innerHTML = '';

                    Array.from(files).forEach((file, index) => {
                        const fileItem = document.createElement('div');
                        fileItem.className = 'flex items-center p-2 bg-blue-50 border border-blue-200 rounded';

                        const fileSize = (file.size / 1024 / 1024).toFixed(2);

                        fileItem.innerHTML = `
                            <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            <span class="text-sm text-blue-900 flex-1">${file.name}</span>
                            <span class="text-xs text-blue-600">${fileSize} MB</span>
                        `;

                        fileList.appendChild(fileItem);
                    });
                } else {
                    preview.classList.add('hidden');
                }
            });

            // Form submission loading state
            document.querySelector('form').addEventListener('submit', function(e) {
                const submitBtn = document.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Menyimpan...
                `;
            });

            // Auto-resize textareas
            function autoResize(textarea) {
                textarea.style.height = 'auto';
                textarea.style.height = textarea.scrollHeight + 'px';
            }

            document.querySelectorAll('textarea').forEach(textarea => {
                textarea.addEventListener('input', function() {
                    autoResize(this);
                });
                autoResize(textarea); // Initial resize
            });
        </script>
    </x-app-layout>
</body>

</html>
