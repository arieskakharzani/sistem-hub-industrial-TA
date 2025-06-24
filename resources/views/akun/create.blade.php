<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tambah Akun Terlapor</title>
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
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Tambah Akun Terlapor') }}
                        @if (isset($pengaduan))
                            <span class="text-sm text-gray-500 font-normal">- Pengaduan
                                #{{ $pengaduan->pengaduan_id }}</span>
                        @endif
                    </h2>
                    <p class="text-gray-600 text-sm mt-1">
                        @if (isset($pengaduan))
                            Buat akun untuk pihak yang dilaporkan dalam pengaduan "{{ $pengaduan->perihal }}"
                        @else
                            Buat akun baru untuk pihak berselisih yang dilaporkan
                        @endif
                    </p>
                </div>

                @if (isset($pengaduan))
                    <div class="flex gap-3">
                        <x-secondary-button
                            onclick="window.location.href='{{ route('pengaduan.show', $pengaduan->pengaduan_id) }}'">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali ke Pengaduan
                        </x-secondary-button>
                    </div>
                @endif

            </div>
        </x-slot>

        <div class="py-6">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

                <!-- Alert Messages -->
                @if (session('error'))
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg" role="alert">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                <!-- Auto-fill Notification -->
                @if (isset($pengaduan))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"
                        role="alert">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <span class="font-medium">Data telah diisi otomatis</span>
                                <p class="text-sm mt-1">Informasi terlapor telah diambil dari pengaduan. Silakan periksa
                                    dan edit jika diperlukan.</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Form Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Informasi Pihak Berselisih yang Dilaporkan</h3>
                        <p class="text-sm text-gray-600 mt-1">Isi semua informasi dengan lengkap dan benar</p>
                    </div>

                    <form action="{{ route('mediator.akun.store') }}" method="POST" id="createTerlaporForm"
                        class="p-6">
                        @csrf

                        <!-- Hidden field untuk pengaduan_id jika ada -->
                        @if (isset($pengaduan))
                            <input type="hidden" name="pengaduan_id" value="{{ $pengaduan->pengaduan_id }}">
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Pihak yang Dilaporkan -->
                            <div class="md:col-span-1">
                                <x-input-label for="nama_terlapor" value="Nama Perusahaan/Terlapor" />
                                <x-text-input id="nama_terlapor" name="nama_terlapor" type="text"
                                    class="mt-1 block w-full @error('nama_terlapor') @enderror @if (isset($pengaduan)) bg-green-50 border-green-300 @endif"
                                    value="{{ old('nama_terlapor', $pengaduan->nama_terlapor ?? '') }}"
                                    placeholder="Masukkan nama perusahaan/terlapor" required />
                                @if (isset($pengaduan))
                                    <p class="mt-1 text-xs text-green-600">✓ Data diambil dari pengaduan</p>
                                @endif
                                @error('nama_terlapor')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email Pihak ynag Dilaporkan -->
                            <div class="md:col-span-1">
                                <x-input-label for="email_terlapor" value="Email Terlapor" />
                                <x-text-input id="email_terlapor" name="email_terlapor" type="email"
                                    class="mt-1 block w-full @error('email_terlapor') @enderror @if (isset($pengaduan)) bg-green-50 border-green-300 @endif"
                                    value="{{ old('email_terlapor', $pengaduan->email_terlapor ?? '') }}"
                                    placeholder="contoh@gmail.com" required />
                                <p class="mt-1 text-xs text-gray-500">Email ini akan digunakan untuk login ke sistem</p>
                                @if (isset($pengaduan))
                                    <p class="mt-1 text-xs text-green-600">✓ Data diambil dari pengaduan</p>
                                @endif
                                @error('email_terlapor')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- No HP -->
                            <div class="md:col-span-1">
                                <x-input-label for="no_hp_terlapor" value="Nomor HP/Telepon" />
                                <x-text-input id="no_hp_terlapor" name="no_hp_terlapor" type="text"
                                    class="mt-1 block w-full @error('no_hp_terlapor') @enderror @if (isset($pengaduan) && $pengaduan->no_hp_terlapor) bg-green-50 border-green-300 @endif"
                                    value="{{ old('no_hp_terlapor', $pengaduan->no_hp_terlapor ?? '') }}"
                                    placeholder="08xxxxxxxxxx" />
                                @if (isset($pengaduan) && $pengaduan->no_hp_terlapor)
                                    <p class="mt-1 text-xs text-green-600">✓ Data diambil dari pengaduan</p>
                                @endif
                                @error('no_hp_terlapor')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status Field (optional info) -->
                            @if (isset($pengaduan))
                                <div class="md:col-span-1">
                                    <x-input-label value="Status Pengaduan" />
                                    <div class="mt-1 flex items-center">
                                        @php
                                            $statusClass = match ($pengaduan->status) {
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'proses' => 'bg-blue-100 text-blue-800',
                                                'selesai' => 'bg-green-100 text-green-800',
                                                default => 'bg-gray-100 text-gray-800',
                                            };
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                                            {{ ucfirst($pengaduan->status) }}
                                        </span>
                                        <span class="ml-2 text-xs text-gray-500">
                                            Dibuat: {{ $pengaduan->tanggal_laporan->format('d M Y') }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Alamat -->
                        <div class="mt-6">
                            <x-input-label for="alamat_kantor_cabang" value="Alamat Kantor/Cabang" />
                            <textarea id="alamat_kantor_cabang" name="alamat_kantor_cabang" rows="3"
                                class="mt-1 block w-full focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm @error('alamat_kantor_cabang')  @enderror @if (isset($pengaduan)) bg-green-50 border-green-300 @endif"
                                placeholder="Masukkan alamat lengkap kantor/cabang" required>{{ old('alamat_kantor_cabang', $pengaduan->alamat_kantor_cabang ?? '') }}</textarea>
                            @if (isset($pengaduan))
                                <p class="mt-1 text-xs text-green-600">✓ Data diambil dari pengaduan</p>
                            @endif
                            @error('alamat_kantor_cabang')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                        </div>

                        <!-- Pengaduan Info (jika ada) -->
                        @if (isset($pengaduan))
                            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-blue-800">Informasi Pengaduan Terkait</h4>
                                        <div class="mt-2 text-sm text-blue-700">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <p><span class="font-medium">Perihal:</span>
                                                        {{ $pengaduan->perihal }}</p>
                                                    <p><span class="font-medium">Pelapor:</span>
                                                        {{ $pengaduan->pelapor->nama_pelapor ?? 'N/A' }}</p>
                                                </div>
                                                <div>
                                                    <p><span class="font-medium">Tanggal:</span>
                                                        {{ $pengaduan->tanggal_laporan->format('d F Y') }}</p>
                                                    @if ($pengaduan->mediator)
                                                        <p><span class="font-medium">Mediator:</span>
                                                            {{ $pengaduan->mediator->nama_mediator }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            @if ($pengaduan->narasi_kasus)
                                                <div class="mt-3 p-3 bg-white rounded border">
                                                    <p class="font-medium text-gray-700">Narasi Kasus:</p>
                                                    <p class="text-gray-600 text-sm mt-1">
                                                        {{ Str::limit($pengaduan->narasi_kasus, 200) }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Info Box -->
                        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-blue-800">Informasi Penting</h4>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Password sementara akan digenerate otomatis</li>
                                            <li>Kredensial login akan dikirim ke email yang didaftarkan</li>
                                            <li>Terlapor disarankan untuk mengganti password setelah login pertama</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                            @if (isset($pengaduan))
                                <x-secondary-button type="button"
                                    onclick="window.location.href='{{ route('pengaduan.show', $pengaduan->pengaduan_id) }}'">
                                    Batal
                                </x-secondary-button>
                            @else
                                <x-secondary-button type="button"
                                    onclick="window.location.href='{{ route('mediator.akun.index') }}'">
                                    Batal
                                </x-secondary-button>
                            @endif

                            <x-primary-button type="submit" id="submitBtn">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                @if (isset($pengaduan))
                                    Buat Akun
                                @else
                                    Simpan Akun
                                @endif
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('createTerlaporForm');
                const submitBtn = document.getElementById('submitBtn');

                form.addEventListener('submit', function(e) {
                    // Disable submit button untuk mencegah double submit
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `
                        <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Menyimpan...
                    `;

                    // Re-enable button setelah 5 detik (jika ada error)
                    setTimeout(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = `
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Akun
                        `;
                    }, 5000);
                });

                // Validasi email format
                const emailInput = document.getElementById('email_terlapor');
                emailInput.addEventListener('blur', function() {
                    const email = this.value;
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                    if (email && !emailRegex.test(email)) {
                        this.classList.add('border-red-500');
                        this.classList.remove('border-gray-300');

                        // Show error message
                        let errorMsg = this.parentNode.querySelector('.email-error');
                        if (!errorMsg) {
                            errorMsg = document.createElement('p');
                            errorMsg.className = 'mt-1 text-sm text-red-600 email-error';
                            this.parentNode.appendChild(errorMsg);
                        }
                        errorMsg.textContent = 'Format email tidak valid';
                    } else {
                        this.classList.remove('border-red-500');
                        this.classList.add('border-gray-300');

                        // Remove error message
                        const errorMsg = this.parentNode.querySelector('.email-error');
                        if (errorMsg) {
                            errorMsg.remove();
                        }
                    }
                });
            });
        </script>
    </x-app-layout>

</body>

</html>
