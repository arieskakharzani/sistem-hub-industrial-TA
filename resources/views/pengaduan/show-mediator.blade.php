<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detail Pengaduan</title>
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
    {{-- resources/views/pengaduan/show-mediator.blade.php --}}
    <x-app-layout>
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Detail Pengaduan #' . str_pad($pengaduan->id, 6, '0', STR_PAD_LEFT)) }}
                </h2>
                <div class="flex items-center gap-3">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        Mediator Panel
                    </span>
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    @if ($pengaduan->status == 'pending') bg-yellow-100 text-yellow-800
                    @elseif($pengaduan->status == 'proses') bg-blue-100 text-blue-800
                    @else bg-green-100 text-green-800 @endif">
                        {{ $pengaduan->status_text }}
                    </span>
                </div>
            </div>
        </x-slot>

        <div class="max-w-7xl mx-auto px-5 py-8">
            <!-- Success Message -->
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <span class="text-green-500 text-lg">✅</span>
                        </div>
                        <div>
                            <p class="text-sm text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Pengaduan Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Pengaduan Info Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-blue-200">
                            <h3 class="text-lg font-semibold text-gray-800">Informasi Pengaduan</h3>
                            <p class="text-sm text-gray-600">{{ $pengaduan->perihal }}</p>
                        </div>

                        <div class="p-6 space-y-6">
                            <!-- Informasi Dasar -->
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Tanggal Laporan</label>
                                    <p class="text-gray-800">{{ $pengaduan->tanggal_laporan->format('d F Y') }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Dibuat</label>
                                    <p class="text-gray-800">{{ $pengaduan->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>

                            <!-- Data Pekerja -->
                            <div class="border-t pt-6">
                                <h4 class="text-md font-semibold text-gray-800 mb-4">Data Pekerja</h4>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Nama Pekerja</label>
                                        <p class="text-gray-800">{{ $pengaduan->user->name }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">NPK</label>
                                        <p class="text-gray-800">{{ $pengaduan->user->npk ?: 'Tidak tersedia' }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Masa Kerja</label>
                                        <p class="text-gray-800">{{ $pengaduan->masa_kerja }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Kontak Pekerja</label>
                                        <p class="text-gray-800">{{ $pengaduan->kontak_pekerja }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Perusahaan -->
                            <div class="border-t pt-6">
                                <h4 class="text-md font-semibold text-gray-800 mb-4">Data Perusahaan</h4>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Nama Perusahaan</label>
                                        <p class="text-gray-800">{{ $pengaduan->nama_perusahaan }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Kontak Perusahaan</label>
                                        <p class="text-gray-800">{{ $pengaduan->kontak_perusahaan }}</p>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="text-sm font-medium text-gray-600">Alamat Kantor Cabang</label>
                                        <p class="text-gray-800">{{ $pengaduan->alamat_kantor_cabang }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Detail Kasus -->
                            <div class="border-t pt-6">
                                <h4 class="text-md font-semibold text-gray-800 mb-4">Detail Kasus</h4>
                                <div class="space-y-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Narasi Kasus</label>
                                        <div class="mt-2 p-4 bg-gray-50 rounded-lg">
                                            <p class="text-gray-800 whitespace-pre-line">{{ $pengaduan->narasi_kasus }}
                                            </p>
                                        </div>
                                    </div>

                                    @if ($pengaduan->catatan_tambahan)
                                        <div>
                                            <label class="text-sm font-medium text-gray-600">Catatan Tambahan</label>
                                            <div class="mt-2 p-4 bg-gray-50 rounded-lg">
                                                <p class="text-gray-800 whitespace-pre-line">
                                                    {{ $pengaduan->catatan_tambahan }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Dokumen Pendukung -->
                            @if ($pengaduan->hasLampiran())
                                <div class="border-t pt-6">
                                    <h4 class="text-md font-semibold text-gray-800 mb-4">Dokumen Pendukung</h4>
                                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @foreach ($pengaduan->lampiran as $index => $file)
                                            @php
                                                $fileName = basename($file);
                                                $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
                                                $fileUrl = Storage::url($file);
                                            @endphp
                                            <div
                                                class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                        @if (in_array($fileExtension, ['jpg', 'jpeg', 'png']))
                                                            🖼️
                                                        @elseif($fileExtension == 'pdf')
                                                            📄
                                                        @elseif(in_array($fileExtension, ['doc', 'docx']))
                                                            📝
                                                        @else
                                                            📎
                                                        @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-medium text-gray-800 truncate">
                                                            Dokumen {{ $index + 1 }}
                                                        </p>
                                                        <p class="text-xs text-gray-500 uppercase">{{ $fileExtension }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <a href="{{ $fileUrl }}" target="_blank"
                                                        class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                                        <span>Lihat File</span>
                                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column - Actions & Status -->
                <div class="space-y-6">
                    <!-- Status Management Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-green-200">
                            <h3 class="text-lg font-semibold text-gray-800">Kelola Status</h3>
                        </div>

                        <div class="p-6">
                            <form action="{{ route('pengaduan.update-status', $pengaduan) }}" method="POST"
                                class="space-y-4">
                                @csrf
                                @method('PATCH')

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Pengaduan</label>
                                    <select name="status"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                        <option value="pending"
                                            {{ $pengaduan->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                        <option value="proses" {{ $pengaduan->status == 'proses' ? 'selected' : '' }}>
                                            Sedang Diproses</option>
                                        <option value="selesai"
                                            {{ $pengaduan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Mediator</label>
                                    <textarea name="catatan_mediator" rows="4"
                                        placeholder="Tambahkan catatan atau update terkait penanganan pengaduan..."
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">{{ $pengaduan->catatan_mediator }}</textarea>
                                    <p class="text-xs text-gray-500 mt-1">Catatan ini akan terlihat oleh pelapor</p>
                                </div>

                                <button type="submit"
                                    class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                    Update Status
                                </button>
                            </form>

                            @if ($pengaduan->status == 'pending' && !$pengaduan->mediator_id)
                                <div class="mt-4 pt-4 border-t">
                                    <form action="{{ route('pengaduan.assign', $pengaduan) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors"
                                            onclick="return confirm('Ambil pengaduan ini untuk ditangani?')">
                                            Ambil Pengaduan
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Assignment Info -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800">Info Penanganan</h3>
                        </div>

                        <div class="p-6 space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-600">Mediator</label>
                                <p class="text-gray-800">
                                    @if ($pengaduan->mediator_id)
                                        {{ $pengaduan->mediator->name ?? 'N/A' }}
                                        @if ($pengaduan->mediator_id == Auth::id())
                                            <span class="text-blue-600">(Anda)</span>
                                        @endif
                                    @else
                                        <span class="text-gray-500">Belum ditangani</span>
                                    @endif
                                </p>
                            </div>

                            @if ($pengaduan->assigned_at)
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Waktu Pengambilan</label>
                                    <p class="text-gray-800">{{ $pengaduan->assigned_at->format('d M Y, H:i') }}</p>
                                </div>
                            @endif

                            @if ($pengaduan->catatan_mediator)
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Catatan Terakhir</label>
                                    <div class="mt-2 p-3 bg-gray-50 rounded-lg">
                                        <p class="text-sm text-gray-800">{{ $pengaduan->catatan_mediator }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div
                            class="bg-gradient-to-r from-purple-50 to-purple-100 px-6 py-4 border-b border-purple-200">
                            <h3 class="text-lg font-semibold text-gray-800">Kontak</h3>
                        </div>

                        <div class="p-6 space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-600">Pelapor</label>
                                <p class="text-gray-800">{{ $pengaduan->user->name }}</p>
                                <p class="text-sm text-gray-600">{{ $pengaduan->kontak_pekerja }}</p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-600">Perusahaan</label>
                                <p class="text-gray-800">{{ $pengaduan->nama_perusahaan }}</p>
                                <p class="text-sm text-gray-600">{{ $pengaduan->kontak_perusahaan }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Back Button -->
                    <div class="flex gap-4">
                        <a href="{{ route('pengaduan.index') }}"
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 text-center transition-all duration-300">
                            ← Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
</body>

</html>
