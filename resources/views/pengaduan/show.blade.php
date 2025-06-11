<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detail Pengaduan - Mediator</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                    {{ __('Detail Pengaduan') }}
                </h2>
                <a href="{{ route('pengaduan.kelola') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Kembali ke Kelola
                </a>
            </div>
        </x-slot>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

                <!-- Success Alert -->
                @if (session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        <strong class="font-bold">Berhasil!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Header Info -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">
                                    Pengaduan #{{ $pengaduan->pengaduan_id }}
                                </h3>
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span>📅 {{ $pengaduan->tanggal_laporan->format('d F Y') }}</span>
                                    <span>•</span>
                                    <span>📂 {{ $pengaduan->perihal }}</span>
                                </div>
                            </div>
                            <div class="text-right">
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
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <!-- Left Column - Detail Pengaduan -->
                    <div class="lg:col-span-2 space-y-6">

                        <!-- Informasi Pelapor -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pelapor</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nama Pelapor</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ $pengaduan->pelapor->nama_pelapor ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Kontak Pekerja</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $pengaduan->kontak_pekerja }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Email</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $pengaduan->pelapor->email ?? 'N/A' }}
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Masa Kerja</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $pengaduan->masa_kerja }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Perusahaan -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Informasi Perusahaan</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nama Perusahaan</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $pengaduan->nama_perusahaan }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Kontak Perusahaan</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $pengaduan->kontak_perusahaan }}</p>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Alamat Kantor
                                            Cabang</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $pengaduan->alamat_kantor_cabang }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Kasus -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Detail Kasus</h4>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Perihal</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $pengaduan->perihal }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Narasi Kasus</label>
                                        <div class="mt-1 text-sm text-gray-900 bg-gray-50 p-4 rounded-lg">
                                            {{ $pengaduan->narasi_kasus }}
                                        </div>
                                    </div>
                                    @if ($pengaduan->catatan_tambahan)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Catatan
                                                Tambahan</label>
                                            <div class="mt-1 text-sm text-gray-900 bg-gray-50 p-4 rounded-lg">
                                                {{ $pengaduan->catatan_tambahan }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Lampiran -->
                        @if ($pengaduan->lampiran && count($pengaduan->lampiran) > 0)
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Lampiran</h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        @foreach ($pengaduan->lampiran as $lampiran)
                                            <div class="flex items-center p-3 border border-gray-200 rounded-lg">
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
                                                    <p class="text-xs text-gray-500">Lampiran</p>
                                                </div>
                                                <a href="{{ asset('storage/' . $lampiran) }}" target="_blank"
                                                    class="text-primary hover:text-primary-dark">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>

                    <!-- Right Column - Actions & Info -->
                    <div class="space-y-6">

                        <!-- Status Management -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Kelola Status</h4>

                                <!-- Assign to Self (if not assigned) -->
                                @if (!$pengaduan->mediator_id)
                                    <form method="POST"
                                        action="{{ route('pengaduan.assign', $pengaduan->pengaduan_id) }}"
                                        class="mb-4">
                                        @csrf
                                        <button type="submit"
                                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                                            onclick="return confirm('Ambil pengaduan ini untuk ditangani?')">
                                            Ambil Pengaduan
                                        </button>
                                    </form>
                                @endif

                                <!-- Update Status Form -->
                                <form method="POST"
                                    action="{{ route('pengaduan.updateStatus', $pengaduan->pengaduan_id) }}">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                        <select name="status" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                                            <option value="pending"
                                                {{ $pengaduan->status == 'pending' ? 'selected' : '' }}>Pending
                                            </option>
                                            <option value="proses"
                                                {{ $pengaduan->status == 'proses' ? 'selected' : '' }}>Dalam Proses
                                            </option>
                                            <option value="selesai"
                                                {{ $pengaduan->status == 'selesai' ? 'selected' : '' }}>Selesai
                                            </option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan
                                            Mediator</label>
                                        <textarea name="catatan_mediator" rows="4"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                                            placeholder="Tambahkan catatan untuk pengaduan ini...">{{ $pengaduan->catatan_mediator }}</textarea>
                                    </div>
                                    <button type="submit"
                                        class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded">
                                        Update Status
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Mediator Info -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Informasi Mediator</h4>
                                @if ($pengaduan->mediator)
                                    <div class="text-center">
                                        <div
                                            class="w-16 h-16 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <span class="text-lg font-medium text-primary">
                                                {{ substr($pengaduan->mediator->name, 0, 2) }}
                                            </span>
                                        </div>
                                        <p class="font-medium text-gray-900">{{ $pengaduan->mediator->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $pengaduan->mediator->email }}</p>
                                        @if ($pengaduan->assigned_at)
                                            <p class="text-xs text-gray-400 mt-2">
                                                Ditugaskan: {{ $pengaduan->assigned_at->format('d M Y H:i') }}
                                            </p>
                                        @endif
                                    </div>
                                @else
                                    <p class="text-center text-gray-500 italic">Belum ditugaskan ke mediator</p>
                                @endif
                            </div>
                        </div>

                        <!-- Timeline -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Timeline</h4>
                                <div class="space-y-3">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-green-400 rounded-full mr-3"></div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Pengaduan Dibuat</p>
                                            <p class="text-xs text-gray-500">
                                                {{ $pengaduan->created_at->format('d M Y H:i') }}</p>
                                        </div>
                                    </div>
                                    @if ($pengaduan->assigned_at)
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-blue-400 rounded-full mr-3"></div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Ditugaskan ke Mediator</p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $pengaduan->assigned_at->format('d M Y H:i') }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-gray-300 rounded-full mr-3"></div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Terakhir Diupdate</p>
                                            <p class="text-xs text-gray-500">
                                                {{ $pengaduan->updated_at->format('d M Y H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
</body>

</html>
