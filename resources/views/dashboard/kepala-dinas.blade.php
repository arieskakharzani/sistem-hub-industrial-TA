<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard Kepala Dinas</title>
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
                Dashboard Kepala Dinas
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                {{-- Welcome Section --}}
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-8 text-white mb-6">
                    <h3 class="text-2xl font-bold mb-2">Selamat Datang, {{ $user->email }}
                    </h3>
                    <p class="text-purple-100">Dashboard Kepala Dinas - Sistem Informasi Pengaduan dan Penyelesaian
                        Perselisihan Hubungan Industrial Kab. Bungo</p>
                    <div class="mt-4">
                        <span class="bg-purple-400 px-3 py-1 rounded-full text-sm font-medium text-purple-900">
                            Role: {{ ucfirst(str_replace('_', ' ', $user->active_role)) }}
                        </span>
                    </div>
                </div>

                {{-- Statistics --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                    <path fill-rule="evenodd"
                                        d="M4 5a2 2 0 012-2v1a1 1 0 001 1h6a1 1 0 001-1V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-600 text-sm">Total Pengaduan</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_pengaduan'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="p-3 bg-yellow-100 rounded-lg">
                                <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-600 text-sm">Menunggu Approval</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['menunggu_approval'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="p-3 bg-orange-100 rounded-lg">
                                <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-600 text-sm">Dalam Proses</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['dalam_proses'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-600 text-sm">Selesai Bulan Ini</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['selesai_bulan_ini'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h4 class="text-lg font-semibold mb-4">Aksi Cepat</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <a href="{{ route('pengaduan.index-kepala-dinas') }}"
                            class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                            <div class="p-2 bg-blue-100 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Semua Pengaduan</p>
                                <p class="text-sm text-gray-600">Monitor semua kasus</p>
                            </div>
                        </a>

                        <a href="{{ route('dokumen.anjuran.pending-approval') }}"
                            class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                            <div class="p-2 bg-yellow-100 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Approval Anjuran</p>
                                <p class="text-sm text-gray-600">{{ $stats['menunggu_approval'] }} anjuran menunggu</p>
                            </div>
                        </a>

                        <a href="{{ route('kepala-dinas.mediator.approval.index') }}"
                            class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors {{ $stats['mediator_pending'] > 0 ? 'ring-2 ring-orange-200 bg-orange-50' : '' }}">
                            <div class="p-2 bg-orange-100 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5.121 17.804A3 3 0 017 17h10a3 3 0 012.879 2.804M15 11a3 3 0 10-6 0 3 3 0 006 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Approval Mediator</p>
                                <p class="text-sm text-gray-600">
                                    @if ($stats['mediator_pending'] > 0)
                                        <span class="text-orange-600 font-semibold">{{ $stats['mediator_pending'] }}
                                            mediator menunggu</span>
                                    @else
                                        Tidak ada pending
                                    @endif
                                </p>
                            </div>
                            @if ($stats['mediator_pending'] > 0)
                                <div class="ml-auto">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        {{ $stats['mediator_pending'] }}
                                    </span>
                                </div>
                            @endif
                        </a>

                        <a href="{{ route('laporan.index') }}"
                            class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                            <div class="p-2 bg-green-100 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Laporan</p>
                                <p class="text-sm text-gray-600">Semua Laporan</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
</body>

</html>
