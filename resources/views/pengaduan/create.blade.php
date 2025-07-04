<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Isi Form Pengaduan</title>
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
    <style>
        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        .animate-shake {
            animation: shake 0.5s ease-in-out 3;
        }
    </style>
</head>

<body>
    <x-app-layout>

        <!-- Main Content -->
        <div class="max-w-6xl mx-auto px-5 py-8">
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Isi Form Pengaduan') }}
                </h2>
            </x-slot>

            <!-- Success Alert -->
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                    role="alert">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Error Alert -->
            @if ($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                    role="alert">
                    <strong class="font-bold">Terjadi kesalahan!</strong>
                    <ul class="mt-2 ml-4 list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Container -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <!-- Form Header -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">Form Pengaduan Perselisihan Hubungan Industrial
                    </h2>
                    <p class="text-sm text-gray-600">Silakan lengkapi formulir di bawah ini untuk mengajukan pengaduan
                        terkait perselisihan hubungan industrial</p>
                </div>

                <!-- Form Body -->
                <form method="POST" action="{{ route('pengaduan.store') }}" enctype="multipart/form-data"
                    class="p-8 space-y-8">
                    @csrf

                    <!-- Informasi Dasar -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-800 pb-3 border-b-2 border-gray-200">Informasi Dasar
                        </h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-medium text-gray-700">
                                    Tanggal Membuat Laporan
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <input type="date" name="tanggal_laporan" readonly
                                    value="{{ old('tanggal_laporan', date('Y-m-d')) }}" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300">
                            </div>

                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-medium text-gray-700">
                                    Perihal
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <select name="perihal" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300 bg-white">
                                    <option value="" disabled {{ old('perihal') ? '' : 'selected' }}>-- Pilih
                                        Jenis Perselisihan --</option>
                                    <option value="Perselisihan Hak"
                                        {{ old('perihal') == 'Perselisihan Hak' ? 'selected' : '' }}>Perselisihan Hak
                                    </option>
                                    <option value="Perselisihan Kepentingan"
                                        {{ old('perihal') == 'Perselisihan Kepentingan' ? 'selected' : '' }}>
                                        Perselisihan Kepentingan</option>
                                    <option value="Perselisihan PHK"
                                        {{ old('perihal') == 'Perselisihan PHK' ? 'selected' : '' }}>Perselisihan PHK
                                    </option>
                                    <option value="Perselisihan antar SP/SB"
                                        {{ old('perihal') == 'Perselisihan antar SP/SB' ? 'selected' : '' }}>
                                        Perselisihan antar SP/SB</option>
                                </select>
                                <p class="text-xs text-gray-500 italic">Pilih jenis perselisihan yang sesuai dengan
                                    kasus Anda</p>
                            </div>
                        </div>
                    </div>

                    <!-- Data Pelapor -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-800 pb-3 border-b-2 border-gray-200">Data Pelapor
                        </h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-medium text-gray-700">
                                    Nama Pelapor
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <input type="text" name="nama_pelapor"
                                    value="{{ old('nama_pelapor', $pelapor->nama_pelapor) }}" readonly
                                    {{-- placeholder="Masukkan nama pelapor" required --}}
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-100 text-gray-600">
                                {{-- <p class="text-xs text-gray-500 italic">Nama diambil dari data akun Anda</p> --}}
                            </div>

                            <div class="space-y-2 md:col-span-1">
                                <label class="flex items-center text-sm font-medium text-gray-700">
                                    Masa Kerja
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <input type="text" name="masa_kerja" value="{{ old('masa_kerja') }}"
                                    placeholder="Contoh: 5 tahun 3 bulan" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300">
                                <p class="text-xs text-gray-500 italic">Format: tahun dan bulan</p>
                            </div>
                        </div>
                    </div>

                    <!-- Data Terlapor -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-800 pb-3 border-b-2 border-gray-200">Data Terlapor
                        </h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-medium text-gray-700">
                                    Nama Terlapor
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <input type="text" name="nama_terlapor" value="{{ old('nama_terlapor') }}"
                                    placeholder="Masukkan nama terlapor" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300">
                                <p class="text-xs text-gray-500 italic">Nama perusahaan atau pihak yang dilaporkan</p>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700">
                                    Email Terlapor
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <input type="text" name="email_terlapor" value="{{ old('email_terlapor') }}"
                                    placeholder="Email aktif resmi pihak yang dilaporkan" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300">
                                <p class="text-xs text-gray-500 italic"> Email yang diinputkan harus email aktif pihak
                                    terlapor</p>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700">
                                    Nomor Telepon Terlapor
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <input type="text" name="no_hp_terlapor" value="{{ old('no_hp_terlapor') }}"
                                    placeholder="Nomor telepon aktif pihak yang dilaporkan" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300">
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <label class="flex items-center text-sm font-medium text-gray-700">
                                    Alamat Kantor Cabang
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <textarea name="alamat_kantor_cabang" rows="3" placeholder="Masukkan alamat lengkap kantor cabang" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300 resize-vertical">{{ old('alamat_kantor_cabang') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Kasus -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-800 pb-3 border-b-2 border-gray-200">Detail Kasus
                        </h3>
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-medium text-gray-700">
                                    Narasi Kasus / Detail Kasus
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <textarea name="narasi_kasus" rows="6"
                                    placeholder="Jelaskan secara detail kronologi kejadian, masalah yang dihadapi, dan dampaknya terhadap pekerja. Sertakan tanggal kejadian jika memungkinkan."
                                    required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300 resize-vertical">{{ old('narasi_kasus') }}</textarea>
                                <p class="text-xs text-gray-500 italic">Mohon jelaskan dengan detail agar dapat
                                    diproses dengan baik</p>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700">
                                    Catatan Tambahan
                                </label>
                                <textarea name="catatan_tambahan" rows="4" placeholder="Informasi tambahan yang perlu disampaikan (opsional)"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-10 transition-all duration-300 resize-vertical">{{ old('catatan_tambahan') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Risalah Bipartit (WAJIB) -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-800 pb-3 border-b-2 border-red-200">
                            <span class="flex items-center">
                                Risalah Bipartit
                                {{-- <span class="text-red-500 ml-2 text-sm">(WAJIB)</span> --}}
                            </span>
                        </h3>

                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                        <span class="text-red-600 text-sm">⚠️</span>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-red-800 mb-2">Dokumen Wajib: Risalah Bipartit
                                    </h4>
                                    <p class="text-xs text-red-700">
                                        Sesuai UU No. 2 Tahun 2004, wajib melampirkan bukti upaya penyelesaian melalui
                                        perundingan bipartit.
                                        Upload file PDF risalah/notulen perundingan antara pekerja dengan perusahaan.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-medium text-gray-700">
                                    Upload Risalah Bipartit
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <div
                                    class="border-2 border-dashed border-red-300 rounded-lg p-6 text-center hover:border-red-400 transition-colors duration-300">
                                    <div class="space-y-4">
                                        <div
                                            class="mx-auto w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <label for="risalah-bipartit-upload" class="cursor-pointer">
                                                <span class="text-sm font-medium text-red-600 hover:text-red-700">Klik
                                                    untuk upload</span>
                                                <span class="text-sm text-gray-500"> file PDF risalah bipartit</span>
                                            </label>
                                            <input id="risalah-bipartit-upload" name="risalah_bipartit"
                                                type="file" accept=".pdf" required class="hidden">
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            <strong>Format:</strong> PDF saja | <strong>Ukuran maksimal:</strong> 10MB
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview Risalah Bipartit -->
                            <div id="risalah-preview" class="hidden">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">File Risalah Bipartit:</h4>
                                <div id="risalah-container" class="p-3 bg-red-50 border border-red-200 rounded-lg">
                                    <!-- File info will be added here -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Dokumen -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-800 pb-3 border-b-2 border-gray-200">Upload Dokumen
                            Pendukung</h3>
                        <div class="space-y-6">
                            <!-- File Upload Area -->
                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700">
                                        Dokumen Pendukung
                                    </label>
                                    <div
                                        class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary transition-colors duration-300">
                                        <div class="space-y-4">
                                            <div
                                                class="mx-auto w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-6 h-6 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                </svg>
                                            </div>
                                            <div>
                                                <label for="file-upload" class="cursor-pointer">
                                                    <span
                                                        class="text-sm font-medium text-primary hover:text-primary-dark">Klik
                                                        untuk upload</span>
                                                    <span class="text-sm text-gray-500"> atau drag & drop file di
                                                        sini</span>
                                                </label>
                                                <input id="file-upload" name="lampiran[]" type="file" multiple
                                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="hidden">
                                            </div>
                                            <p class="text-xs text-gray-500">
                                                Format yang didukung: PDF, DOC, DOCX, JPG, PNG (Maksimal 5MB per file)
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- File List -->
                                <div id="file-list" class="hidden space-y-2">
                                    <h4 class="text-sm font-medium text-gray-700">File yang dipilih:</h4>
                                    <div id="files-container" class="space-y-2">
                                        <!-- File items will be added here -->
                                    </div>
                                </div>

                                <!-- Document Types Help -->
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-8 h-8 bg-primary bg-opacity-10 rounded-lg flex items-center justify-center">
                                                <span class="text-primary text-sm">💡</span>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-primary mb-2">Dokumen yang Disarankan:
                                            </h4>
                                            <ul class="text-xs text-gray-600 space-y-1">
                                                {{-- <li>• Risalah Bipartit</li> --}}
                                                <li>• Kontrak kerja atau surat pengangkatan</li>
                                                <li>• Slip gaji atau bukti pembayaran upah</li>
                                                <li>• Surat peringatan atau teguran (jika ada)</li>
                                                <li>• Email atau surat komunikasi dengan perusahaan</li>
                                                <li>• Foto atau screenshot bukti pendukung</li>
                                                <li>• Dokumen lain yang relevan dengan kasus</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Button Actions -->
                    <div class="flex flex-col sm:flex-row justify-end gap-4 pt-6 border-t border-gray-200">
                        <button type="submit"
                            class="px-8 py-3 bg-black marker: text-white rounded-lg font-medium hover:bg-primary-dark transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2">
                            <span>Kirim Pengaduan</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </x-app-layout>

    <script>
        // File upload functionality (existing)
        const fileUpload = document.getElementById('file-upload');
        const fileList = document.getElementById('file-list');
        const filesContainer = document.getElementById('files-container');
        let selectedFiles = [];

        // ✅ NEW: Risalah Bipartit upload functionality dengan validasi
        const risalahUpload = document.getElementById('risalah-bipartit-upload');
        const risalahPreview = document.getElementById('risalah-preview');
        const risalahContainer = document.getElementById('risalah-container');
        let risalahFile = null; // Track risalah file

        if (risalahUpload) {
            risalahUpload.addEventListener('change', function(e) {
                const file = e.target.files[0];

                if (file) {
                    if (file.type !== 'application/pdf') {
                        alert('❌ File risalah bipartit harus berformat PDF!');
                        e.target.value = '';
                        risalahPreview.classList.add('hidden');
                        risalahFile = null;
                        updateUploadAreaStyle();
                        return;
                    }

                    if (file.size > 10 * 1024 * 1024) { // 10MB limit
                        alert('❌ Ukuran file risalah bipartit tidak boleh lebih dari 10MB!');
                        e.target.value = '';
                        risalahPreview.classList.add('hidden');
                        risalahFile = null;
                        updateUploadAreaStyle();
                        return;
                    }

                    // File valid, simpan reference
                    risalahFile = file;

                    // Show preview
                    risalahContainer.innerHTML = `
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-200 rounded flex items-center justify-center">
                            <span class="text-green-700 text-sm">✅</span>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-medium text-green-900">${file.name}</div>
                            <div class="text-xs text-green-600">${formatFileSize(file.size)} - PDF</div>
                        </div>
                        <button type="button" class="text-red-500 hover:text-red-700 p-1" onclick="removeRisalah()">
                            🗑️
                        </button>
                    </div>
                `;

                    risalahPreview.classList.remove('hidden');
                    updateUploadAreaStyle();

                    // ✅ NEW: Hapus peringatan jika ada
                    removeWarningMessage();

                } else {
                    risalahFile = null;
                    risalahPreview.classList.add('hidden');
                    updateUploadAreaStyle();
                }
            });
        }

        function removeRisalah() {
            if (risalahUpload) {
                risalahUpload.value = '';
                risalahPreview.classList.add('hidden');
                risalahFile = null;
                updateUploadAreaStyle();
            }
        }

        // ✅ NEW: Update upload area style berdasarkan risalah file (tanpa disable button)
        function updateUploadAreaStyle() {
            const uploadArea = document.querySelector('.border-dashed.border-red-300');

            if (risalahFile) {
                // File sudah ada - update upload area style
                uploadArea.classList.remove('border-red-300');
                uploadArea.classList.add('border-green-300', 'bg-green-50');
            } else {
                // File belum ada - kembalikan ke style warning
                uploadArea.classList.remove('border-green-300', 'bg-green-50');
                uploadArea.classList.add('border-red-300');
            }
        }

        // ✅ NEW: Form submission validation dengan peringatan (button tetap enabled)
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!risalahFile) {
                e.preventDefault(); // Stop form submission

                // Scroll ke bagian risalah bipartit
                document.querySelector('.border-red-300').scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });

                // Tampilkan peringatan
                showWarningMessage();

                // Shake effect pada upload area
                const uploadArea = document.querySelector('.border-dashed.border-red-300');
                uploadArea.classList.add('animate-shake');
                setTimeout(() => {
                    uploadArea.classList.remove('animate-shake');
                }, 1500);

                // Flash effect pada submit button untuk memberikan feedback
                const submitBtn = document.querySelector('button[type="submit"]');
                submitBtn.classList.add('animate-pulse');
                setTimeout(() => {
                    submitBtn.classList.remove('animate-pulse');
                }, 2000);

                return false;
            }

            // File ada, lanjutkan submission dengan loading state
            const submitBtn = document.querySelector('button[type="submit"]');
            submitBtn.disabled = true; // Disable only during submission
            submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Mengirim Pengaduan...
        `;
        });

        // ✅ NEW: Show warning message dengan styling yang lebih prominent
        function showWarningMessage() {
            // Remove existing warning first
            removeWarningMessage();

            const warningHtml = `
            <div id="risalah-warning" class="mt-4 bg-red-100 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-r relative animate-pulse">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-red-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            <h4 class="font-bold text-red-800">⚠️ Risalah Bipartit Belum Diupload!</h4>
                        </div>
                        <p class="text-sm">
                            Pengaduan tidak dapat dikirim karena <strong>risalah bipartit wajib dilampirkan</strong>. 
                            Ini merupakan syarat wajib sesuai <strong>UU No. 2 Tahun 2004 Pasal 3</strong>.
                        </p>
                        <div class="mt-3 text-xs bg-red-50 border border-red-200 rounded p-2">
                            <p class="font-medium">Yang perlu dilakukan:</p>
                            <ol class="list-decimal list-inside mt-1 space-y-1">
                                <li>Upload file PDF risalah/notulen perundingan bipartit</li>
                                <li>Pastikan file berukuran maksimal 10MB</li>
                                <li>Klik "Kirim Pengaduan" setelah file terupload</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        `;

            // Insert after risalah upload area
            const uploadArea = document.querySelector('.border-dashed.border-red-300').parentElement;
            uploadArea.insertAdjacentHTML('afterend', warningHtml);

            // Auto remove warning after 10 seconds
            setTimeout(() => {
                removeWarningMessage();
            }, 10000);
        }

        // ✅ NEW: Remove warning message
        function removeWarningMessage() {
            const existingWarning = document.getElementById('risalah-warning');
            if (existingWarning) {
                existingWarning.remove();
            }
        }

        // ✅ NEW: Initialize upload area style
        updateUploadAreaStyle();

        // Existing lampiran functionality
        fileUpload.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);

            files.forEach(file => {
                if (file.size <= 5 * 1024 * 1024) { // 5MB limit
                    selectedFiles.push(file);
                    addFileToList(file);
                } else {
                    alert(`File ${file.name} terlalu besar. Maksimal 5MB per file.`);
                }
            });

            if (selectedFiles.length > 0) {
                fileList.classList.remove('hidden');
            }
        });

        function addFileToList(file) {
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg border';

            const fileInfo = document.createElement('div');
            fileInfo.className = 'flex items-center gap-3';

            const fileIcon = document.createElement('div');
            fileIcon.className = 'w-8 h-8 bg-primary bg-opacity-10 rounded flex items-center justify-center';
            fileIcon.innerHTML = getFileIcon(file.type);

            const fileDetails = document.createElement('div');
            fileDetails.innerHTML = `
            <div class="text-sm font-medium text-gray-800">${file.name}</div>
            <div class="text-xs text-gray-500">${formatFileSize(file.size)}</div>
        `;

            fileInfo.appendChild(fileIcon);
            fileInfo.appendChild(fileDetails);

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'text-red-500 hover:text-red-700 p-1';
            removeBtn.innerHTML = '🗑️';
            removeBtn.onclick = () => {
                const index = selectedFiles.indexOf(file);
                if (index > -1) {
                    selectedFiles.splice(index, 1);
                    fileItem.remove();

                    if (selectedFiles.length === 0) {
                        fileList.classList.add('hidden');
                    }
                }
            };

            fileItem.appendChild(fileInfo);
            fileItem.appendChild(removeBtn);
            filesContainer.appendChild(fileItem);
        }

        function getFileIcon(fileType) {
            if (fileType.includes('pdf')) return '📄';
            if (fileType.includes('doc')) return '📝';
            if (fileType.includes('image')) return '🖼️';
            return '📎';
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Existing drag and drop functionality
        const uploadArea = document.querySelector('.border-dashed');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            uploadArea.classList.add('border-primary', 'bg-blue-50');
        }

        function unhighlight() {
            uploadArea.classList.remove('border-primary', 'bg-blue-50');
        }

        uploadArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;

            Array.from(files).forEach(file => {
                if (file.size <= 5 * 1024 * 1024) {
                    selectedFiles.push(file);
                    addFileToList(file);
                } else {
                    alert(`File ${file.name} terlalu besar. Maksimal 5MB per file.`);
                }
            });

            if (selectedFiles.length > 0) {
                fileList.classList.remove('hidden');
            }
        }
    </script>

</body>

</html>
