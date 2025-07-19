{{-- Tambahkan defensive check di awal view --}}
@php
    // Pastikan variabel ada dengan default values
    $terlapors = $terlapors ?? collect([]);
    $pelapors = $pelapors ?? collect([]);
    $pelaporStats = $pelaporStats ?? ['total' => 0, 'active' => 0, 'inactive' => 0, 'this_month' => 0];
    $terlaporStats = $terlaporStats ?? ['total' => 0, 'active' => 0, 'inactive' => 0, 'this_month' => 0];

    // Helper functions untuk menghitung data dengan aman
    function safeCount($data)
    {
        try {
            if (is_object($data) && method_exists($data, 'count')) {
                return $data->count();
            }
            return is_countable($data) ? count($data) : 0;
        } catch (Exception $e) {
            return 0;
        }
    }
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Akun</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

        // Fungsi untuk menangani aktivasi/deaktivasi akun
        function handleAccountStatus(type, action, id) {
            if (!confirm('Yakin ingin ' + (action === 'activate' ? 'mengaktifkan' : 'menonaktifkan') + ' akun ini?')) {
                return;
            }

            const url = type === 'pelapor' ?
                `/mediator/akun/pelapor/${id}/${action}` :
                `/mediator/akun/${id}/${action}`;

            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                data: {
                    _method: 'PATCH'
                },
                success: function(response) {
                    alert(response.message);
                    location.reload();
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseJSON);
                    alert('Terjadi kesalahan: ' + (xhr.responseJSON?.error || xhr.responseJSON?.message ||
                        'Unknown error'));
                }
            });
        }

        // Fungsi untuk menangani tab switching
        $(document).ready(function() {
            // Set tab pertama sebagai active
            $('.tab-button:first').addClass('border-blue-500 text-blue-600');
            $('#pelapor-tab').show();
            $('#terlapor-tab').hide();

            // Handle tab clicks
            $('.tab-button').click(function() {
                $('.tab-button').removeClass('border-blue-500 text-blue-600').addClass(
                    'border-transparent text-gray-500');
                $(this).removeClass('border-transparent text-gray-500').addClass(
                    'border-blue-500 text-blue-600');

                const tab = $(this).data('tab');
                $('.tab-content').hide();
                $(`#${tab}-tab`).show();
            });
        });
    </script>
</head>

<body>
    <x-app-layout>
        <x-slot name="header">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Kelola Akun') }}
                    </h2>
                    <p class="text-gray-600 text-sm mt-1">Kelola akun pelapor dan terlapor untuk Sistem Informasi
                        Pengaduan dan Penyelesaian Hubungan Industrial Kab. Bungo
                    </p>
                </div>

                <div class="flex gap-3">
                    <x-secondary-button onclick="location.reload()">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                        Refresh
                    </x-secondary-button>

                    <x-primary-button onclick="window.location.href='{{ route('pengaduan.kelola') }}'">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Buat Akun Baru
                    </x-primary-button>
                </div>
            </div>
        </x-slot>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <!-- Alert Banner/Flash Messages -->
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"
                        role="alert">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg" role="alert">
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

                <!-- Overall Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Akun</p>
                                <p class="text-2xl font-bold text-blue-600">
                                    {{ $pelaporStats['total'] + $terlaporStats['total'] }}
                                </p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Pelapor</p>
                                <p class="text-2xl font-bold text-green-600">
                                    {{ $pelaporStats['total'] }}
                                </p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-full">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Terlapor</p>
                                <p class="text-2xl font-bold text-orange-600">
                                    {{ $terlaporStats['total'] }}
                                </p>
                            </div>
                            <div class="p-3 bg-orange-100 rounded-full">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Akun Aktif</p>
                                <p class="text-2xl font-bold text-purple-600">
                                    {{ $pelaporStats['active'] + $terlaporStats['active'] }}
                                </p>
                            </div>
                            <div class="p-3 bg-purple-100 rounded-full">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Navigation -->
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8">
                            <button
                                class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm"
                                data-tab="pelapor">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Akun Pelapor ({{ $pelaporStats['total'] }})
                            </button>
                            <button
                                class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm"
                                data-tab="terlapor">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                                Akun Terlapor ({{ $terlaporStats['total'] }})
                            </button>
                        </nav>
                    </div>
                </div>

                <!-- Pelapor Tab Content -->
                <div id="pelapor-tab" class="tab-content">
                    <!-- Pelapor Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Total Pelapor</p>
                                    <p class="text-2xl font-bold text-blue-600">
                                        {{ $pelaporStats['total'] }}
                                    </p>
                                </div>
                                <div class="p-3 bg-blue-100 rounded-full">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Aktif</p>
                                    <p class="text-2xl font-bold text-green-600">
                                        {{ $pelaporStats['active'] }}
                                    </p>
                                </div>
                                <div class="p-3 bg-green-100 rounded-full">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Nonaktif</p>
                                    <p class="text-2xl font-bold text-red-600">
                                        {{ $pelaporStats['inactive'] }}
                                    </p>
                                </div>
                                <div class="p-3 bg-red-100 rounded-full">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Bulan Ini</p>
                                    <p class="text-2xl font-bold text-purple-600">
                                        {{ $pelaporStats['this_month'] }}
                                    </p>
                                </div>
                                <div class="p-3 bg-purple-100 rounded-full">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pelapor Filter & Search -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-1">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <input id="search-pelapor"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full pl-10"
                                        type="text"
                                        placeholder="Cari berdasarkan nama, email, atau perusahaan..." />
                                </div>
                            </div>

                            <div class="sm:w-48">
                                <select id="status-filter-pelapor"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                                    <option value="">Semua Status</option>
                                    <option value="active">Aktif</option>
                                    <option value="inactive">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Pelapor Table -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Daftar Pelapor</h3>
                        </div>

                        @if (safeCount($pelapors) > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Nama & Info</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Email & HP</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Perusahaan & NPK</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Dibuat</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200" id="pelapor-table">
                                        @foreach ($pelapors as $pelapor)
                                            <tr class="hover:bg-gray-50"
                                                data-status="{{ isset($pelapor->user) && $pelapor->user->is_active ? 'active' : 'inactive' }}"
                                                data-search="{{ strtolower(($pelapor->nama_pelapor ?? '') . ' ' . ($pelapor->email ?? '') . ' ' . ($pelapor->perusahaan ?? '') . ' ' . ($pelapor->npk ?? '')) }}">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $pelapor->nama_pelapor ?? 'N/A' }}</div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $pelapor->jenis_kelamin ?? '' }} |
                                                            {{ $pelapor->tempat_lahir ?? '' }},
                                                            {{ isset($pelapor->tanggal_lahir) ? $pelapor->tanggal_lahir->format('d M Y') : '' }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $pelapor->email ?? 'N/A' }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">{{ $pelapor->no_hp ?? 'N/A' }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">
                                                        {{ $pelapor->perusahaan ?? 'N/A' }}</div>
                                                    <div class="text-sm text-gray-500">NPK:
                                                        {{ $pelapor->npk ?? 'N/A' }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if (isset($pelapor->user) && $pelapor->user->is_active)
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            <svg class="w-2 h-2 mr-1" fill="currentColor"
                                                                viewBox="0 0 8 8">
                                                                <circle cx="4" cy="4" r="3"></circle>
                                                            </svg>
                                                            Aktif
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            <svg class="w-2 h-2 mr-1" fill="currentColor"
                                                                viewBox="0 0 8 8">
                                                                <circle cx="4" cy="4" r="3"></circle>
                                                            </svg>
                                                            Nonaktif
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ isset($pelapor->created_at) ? $pelapor->created_at->format('d M Y') : 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                    <a href="{{ route('mediator.akun.pelapor.show', $pelapor->pelapor_id) }}"
                                                        class="text-indigo-600 hover:text-indigo-900">Detail</a>

                                                    @if (isset($pelapor->user) && $pelapor->user->is_active)
                                                        <button
                                                            onclick="handleAccountStatus('pelapor', 'deactivate', '{{ $pelapor->pelapor_id }}')"
                                                            class="text-red-600 hover:text-red-900">Nonaktifkan</button>
                                                    @else
                                                        <button
                                                            onclick="handleAccountStatus('pelapor', 'activate', '{{ $pelapor->pelapor_id }}')"
                                                            class="text-green-600 hover:text-green-900">Aktifkan</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination for Pelapor -->
                            @if (method_exists($pelapors, 'links'))
                                <div class="px-6 py-4 border-t border-gray-200">
                                    {{ $pelapors->appends(['pelapor_page' => request('pelapor_page')])->links() }}
                                </div>
                            @endif
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                    </path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada pelapor</h3>
                                <p class="mt-1 text-sm text-gray-500">Akun pelapor akan muncul di sini setelah
                                    melakukan registrasi.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Terlapor Tab Content -->
                <div id="terlapor-tab" class="tab-content hidden">
                    <!-- Terlapor Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Total Terlapor</p>
                                    <p class="text-2xl font-bold text-blue-600">
                                        {{ $terlaporStats['total'] }}
                                    </p>
                                </div>
                                <div class="p-3 bg-blue-100 rounded-full">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Aktif</p>
                                    <p class="text-2xl font-bold text-green-600">
                                        {{ $terlaporStats['active'] }}
                                    </p>
                                </div>
                                <div class="p-3 bg-green-100 rounded-full">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Nonaktif</p>
                                    <p class="text-2xl font-bold text-red-600">
                                        {{ $terlaporStats['inactive'] }}
                                    </p>
                                </div>
                                <div class="p-3 bg-red-100 rounded-full">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Bulan Ini</p>
                                    <p class="text-2xl font-bold text-purple-600">
                                        {{ $terlaporStats['this_month'] }}
                                    </p>
                                </div>
                                <div class="p-3 bg-purple-100 rounded-full">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Terlapor Filter & Search -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-1">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <input id="search-terlapor"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full pl-10"
                                        type="text" placeholder="Cari berdasarkan nama perusahaan atau email..." />
                                </div>
                            </div>

                            <div class="sm:w-48">
                                <select id="status-filter-terlapor"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                                    <option value="">Semua Status</option>
                                    <option value="active">Aktif</option>
                                    <option value="inactive">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Terlapor Table -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Daftar Terlapor</h3>
                            <p class="text-sm text-gray-500 mt-1">Menampilkan semua akun terlapor di sistem</p>
                        </div>

                        @if (safeCount($terlapors) > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Perusahaan</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Email</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                No. HP</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Dibuat oleh</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Dibuat</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200" id="terlapor-table">
                                        @foreach ($terlapors as $terlapor)
                                            <tr class="hover:bg-gray-50"
                                                data-status="{{ $terlapor->status ?? 'inactive' }}"
                                                data-search="{{ strtolower(($terlapor->nama_terlapor ?? '') . ' ' . ($terlapor->email_terlapor ?? '')) }}">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $terlapor->nama_terlapor ?? 'N/A' }}</div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ Str::limit($terlapor->alamat_kantor_cabang ?? '', 50) }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $terlapor->email_terlapor ?? 'N/A' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $terlapor->no_hp_terlapor ?: '-' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">
                                                        @if (isset($terlapor->mediator))
                                                            {{ $terlapor->mediator->nama_mediator }}
                                                            @if ($terlapor->created_by_mediator_id === Auth::user()->mediator->mediator_id)
                                                                <span
                                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 ml-1">
                                                                    Anda
                                                                </span>
                                                            @endif
                                                        @else
                                                            <span class="text-gray-400">Unknown</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if (isset($terlapor->user) && ($terlapor->status ?? '') === 'active' && $terlapor->user->is_active)
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            <svg class="w-2 h-2 mr-1" fill="currentColor"
                                                                viewBox="0 0 8 8">
                                                                <circle cx="4" cy="4" r="3"></circle>
                                                            </svg>
                                                            Aktif
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            <svg class="w-2 h-2 mr-1" fill="currentColor"
                                                                viewBox="0 0 8 8">
                                                                <circle cx="4" cy="4" r="3"></circle>
                                                            </svg>
                                                            Nonaktif
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ isset($terlapor->created_at) ? $terlapor->created_at->format('d M Y') : 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                    <a href="{{ route('mediator.akun.show', $terlapor->terlapor_id) }}"
                                                        class="text-indigo-600 hover:text-indigo-900">Detail</a>

                                                    @if (isset($terlapor->user) && ($terlapor->status ?? '') === 'active' && $terlapor->user->is_active)
                                                        <button
                                                            onclick="handleAccountStatus('terlapor', 'deactivate', '{{ $terlapor->terlapor_id }}')"
                                                            class="text-red-600 hover:text-red-900">Nonaktifkan</button>
                                                    @else
                                                        <button
                                                            onclick="handleAccountStatus('terlapor', 'activate', '{{ $terlapor->terlapor_id }}')"
                                                            class="text-green-600 hover:text-green-900">Aktifkan</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination for Terlapor -->
                            @if (method_exists($terlapors, 'links'))
                                <div class="px-6 py-4 border-t border-gray-200">
                                    {{ $terlapors->appends(['terlapor_page' => request('terlapor_page')])->links() }}
                                </div>
                            @endif
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada terlapor</h3>
                                <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat akun terlapor pertama Anda.
                                </p>
                                <div class="mt-6">
                                    <x-primary-button
                                        onclick="window.location.href='{{ route('pengaduan.kelola') }}'">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Buat Akun Terlapor
                                    </x-primary-button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Tab functionality
            document.addEventListener('DOMContentLoaded', function() {
                const tabButtons = document.querySelectorAll('.tab-button');
                const tabContents = document.querySelectorAll('.tab-content');

                // Set initial active tab
                if (tabButtons.length > 0) {
                    tabButtons[0].classList.remove('border-transparent', 'text-gray-500');
                    tabButtons[0].classList.add('border-indigo-500', 'text-indigo-600');
                }

                tabButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const targetTab = this.getAttribute('data-tab');

                        // Remove active classes from all tabs
                        tabButtons.forEach(btn => {
                            btn.classList.remove('border-indigo-500', 'text-indigo-600');
                            btn.classList.add('border-transparent', 'text-gray-500');
                        });

                        // Add active classes to clicked tab
                        this.classList.remove('border-transparent', 'text-gray-500');
                        this.classList.add('border-indigo-500', 'text-indigo-600');

                        // Hide all tab contents
                        tabContents.forEach(content => {
                            content.classList.add('hidden');
                        });

                        // Show target tab content
                        const targetElement = document.getElementById(targetTab + '-tab');
                        if (targetElement) {
                            targetElement.classList.remove('hidden');
                        }
                    });
                });
            });

            // Search & Filter functionality for Pelapor
            const searchPelapor = document.getElementById('search-pelapor');
            const statusFilterPelapor = document.getElementById('status-filter-pelapor');

            if (searchPelapor) {
                searchPelapor.addEventListener('input', filterPelaporTable);
            }
            if (statusFilterPelapor) {
                statusFilterPelapor.addEventListener('change', filterPelaporTable);
            }

            function filterPelaporTable() {
                const searchTerm = searchPelapor ? searchPelapor.value.toLowerCase() : '';
                const statusFilter = statusFilterPelapor ? statusFilterPelapor.value : '';
                const rows = document.querySelectorAll('#pelapor-table tr');

                rows.forEach(row => {
                    const searchData = row.getAttribute('data-search') || '';
                    const statusData = row.getAttribute('data-status') || '';

                    const matchesSearch = searchData.includes(searchTerm);
                    const matchesStatus = !statusFilter || statusData === statusFilter;

                    row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
                });
            }

            // Search & Filter functionality for Terlapor
            const searchTerlapor = document.getElementById('search-terlapor');
            const statusFilterTerlapor = document.getElementById('status-filter-terlapor');

            if (searchTerlapor) {
                searchTerlapor.addEventListener('input', filterTerlaporTable);
            }
            if (statusFilterTerlapor) {
                statusFilterTerlapor.addEventListener('change', filterTerlaporTable);
            }

            function filterTerlaporTable() {
                const searchTerm = searchTerlapor ? searchTerlapor.value.toLowerCase() : '';
                const statusFilter = statusFilterTerlapor ? statusFilterTerlapor.value : '';
                const rows = document.querySelectorAll('#terlapor-table tr');

                rows.forEach(row => {
                    const searchData = row.getAttribute('data-search') || '';
                    const statusData = row.getAttribute('data-status') || '';

                    const matchesSearch = searchData.includes(searchTerm);
                    const matchesStatus = !statusFilter || statusData === statusFilter;

                    row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
                });
            }

            // Toggle Status for Terlapor
            async function toggleStatus(terlaporId, action) {
                if (!confirm(
                        `Apakah Anda yakin ingin ${action === 'activate' ? 'mengaktifkan' : 'menonaktifkan'} akun terlapor ini?`
                    )) {
                    return;
                }

                try {
                    const url = `{{ url('mediator/akun') }}/${terlaporId}/${action}`;
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-HTTP-Method-Override': 'PATCH'
                        }
                    });

                    if (response.ok) {
                        alert(`Akun terlapor berhasil ${action === 'activate' ? 'diaktifkan' : 'dinonaktifkan'}.`);
                        location.reload();
                    } else {
                        const result = await response.json();
                        alert('Error: ' + (result.message || 'Terjadi kesalahan'));
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengubah status');
                }
            }

            // Toggle Status for Pelapor
            async function togglePelaporStatus(pelaporId, action) {
                if (!confirm(
                        `Apakah Anda yakin ingin ${action === 'activate' ? 'mengaktifkan' : 'menonaktifkan'} akun pelapor ini?`
                    )) {
                    return;
                }

                try {
                    const url = `{{ url('mediator/akun') }}/pelapor/${pelaporId}/${action}`;
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-HTTP-Method-Override': 'PATCH'
                        }
                    });

                    if (response.ok) {
                        alert(`Akun pelapor berhasil ${action === 'activate' ? 'diaktifkan' : 'dinonaktifkan'}.`);
                        location.reload();
                    } else {
                        const result = await response.json();
                        alert('Error: ' + (result.message || 'Terjadi kesalahan'));
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengubah status');
                }
            }
        </script>
    </x-app-layout>

</body>

</html>
