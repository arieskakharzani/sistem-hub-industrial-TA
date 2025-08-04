<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detail Laporan Hasil Mediasi</title>
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
                {{ __('Detail Laporan Hasil Mediasi') }}
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
                            <h3 class="text-lg font-semibold text-gray-900">Detail Laporan Hasil Mediasi</h3>
                            <p class="text-sm text-gray-600">
                                Nomor Pengaduan: {{ $pengaduan->nomor_pengaduan }}
                            </p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('laporan.hasil-mediasi.cetak-pdf', $laporanHasilMediasi->laporan_id) }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                Cetak PDF
                            </a>
                            <a href="{{ route('laporan.hasil-mediasi') }}"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                Kembali
                            </a>
                        </div>
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
                                    <p class="mt-1 text-sm text-gray-900">{{ $pengaduan->nomor_pengaduan }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Perihal</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $pengaduan->perihal }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Pelapor</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $pengaduan->pelapor->nama_pelapor }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Terlapor</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $pengaduan->terlapor->nama_terlapor }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Mediator</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $pengaduan->mediator->nama_mediator }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Laporan -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Informasi Laporan</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tanggal Penerimaan
                                        Pengaduan</label>
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($laporanHasilMediasi->tanggal_penerimaan_pengaduan)->format('d/m/Y') }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Waktu Penyelesaian
                                        Mediasi</label>
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ $laporanHasilMediasi->waktu_penyelesaian_mediasi }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Masa Kerja</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $laporanHasilMediasi->masa_kerja }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Pekerja -->
                    <div class="mt-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pekerja</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Pekerja</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $laporanHasilMediasi->nama_pekerja }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Alamat Pekerja</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $laporanHasilMediasi->alamat_pekerja }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Perusahaan -->
                    <div class="mt-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Informasi Perusahaan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Perusahaan</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $laporanHasilMediasi->nama_perusahaan }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Alamat Perusahaan</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $laporanHasilMediasi->alamat_perusahaan }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis Usaha</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $laporanHasilMediasi->jenis_usaha }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Permasalahan dan Pendapat -->
                    <div class="mt-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Permasalahan dan Pendapat</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Permasalahan</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $laporanHasilMediasi->permasalahan }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pendapat Pekerja</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $laporanHasilMediasi->pendapat_pekerja }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pendapat Pengusaha</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $laporanHasilMediasi->pendapat_pengusaha }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Upaya Penyelesaian</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $laporanHasilMediasi->upaya_penyelesaian }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
</body>

</html>
