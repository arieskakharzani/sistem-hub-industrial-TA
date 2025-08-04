<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detail Buku Register Perselisihan</title>
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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Buku Register Perselisihan') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Header Section -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Detail Buku Register Perselisihan</h3>
                            <p class="text-sm text-gray-600">
                                Nomor Pengaduan: {{ $bukuRegister->dokumenHI->pengaduan->nomor_pengaduan }}
                            </p>
                        </div>
                        <a href="{{ route('laporan.buku-register') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            Kembali
                        </a>
                    </div>
                </div>

                <!-- Detail Content -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Informasi Pengaduan -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pengaduan</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nomor Pengaduan</label>
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ $bukuRegister->dokumenHI->pengaduan->nomor_pengaduan }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Perihal</label>
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ $bukuRegister->dokumenHI->pengaduan->perihal }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Pelapor</label>
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ $bukuRegister->dokumenHI->pengaduan->pelapor->nama_pelapor }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Terlapor</label>
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ $bukuRegister->dokumenHI->pengaduan->terlapor->nama_terlapor }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Mediator</label>
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ $bukuRegister->dokumenHI->pengaduan->mediator->nama_mediator }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Register -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Informasi Register</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tanggal Pencatatan</label>
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($bukuRegister->tanggal_pencatatan)->format('d/m/Y') }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Pihak Mencatat</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $bukuRegister->pihak_mencatat }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $bukuRegister->keterangan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Pihak -->
                    <div class="mt-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pihak</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pihak Pekerja</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $bukuRegister->pihak_pekerja }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pihak Pengusaha</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $bukuRegister->pihak_pengusaha }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Status Penyelesaian -->
                    <div class="mt-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Status Penyelesaian</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Perselisihan PHK</label>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bukuRegister->perselisihan_phk === 'ya' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $bukuRegister->perselisihan_phk === 'ya' ? 'Ya' : 'Tidak' }}
                                </span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Perselisihan SP/SB</label>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bukuRegister->perselisihan_sp_sb === 'ya' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $bukuRegister->perselisihan_sp_sb === 'ya' ? 'Ya' : 'Tidak' }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Perselisihan Kepentingan</label>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bukuRegister->perselisihan_kepentingan === 'ya' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $bukuRegister->perselisihan_kepentingan === 'ya' ? 'Ya' : 'Tidak' }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Perselisihan Hak</label>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bukuRegister->perselisihan_hak === 'ya' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $bukuRegister->perselisihan_hak === 'ya' ? 'Ya' : 'Tidak' }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Penyelesaian Bipartit</label>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bukuRegister->penyelesaian_bipartit === 'ya' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $bukuRegister->penyelesaian_bipartit === 'ya' ? 'Ya' : 'Tidak' }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Penyelesaian Klarifikasi</label>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bukuRegister->penyelesaian_klarifikasi === 'ya' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $bukuRegister->penyelesaian_klarifikasi === 'ya' ? 'Ya' : 'Tidak' }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Penyelesaian Mediasi</label>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bukuRegister->penyelesaian_mediasi === 'ya' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $bukuRegister->penyelesaian_mediasi === 'ya' ? 'Ya' : 'Tidak' }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Penyelesaian Perjanjian
                                    Bersama</label>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bukuRegister->penyelesaian_pb === 'ya' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $bukuRegister->penyelesaian_pb === 'ya' ? 'Ya' : 'Tidak' }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Penyelesaian Anjuran</label>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bukuRegister->penyelesaian_anjuran === 'ya' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $bukuRegister->penyelesaian_anjuran === 'ya' ? 'Ya' : 'Tidak' }}
                                </span>
                            </div>
                            @if (isset($bukuRegister->tindak_lanjut_phi))
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tindak Lanjut PHI</label>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bukuRegister->tindak_lanjut_phi === 'ya' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $bukuRegister->tindak_lanjut_phi === 'ya' ? 'Ya' : 'Tidak' }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
</body>

</html>
