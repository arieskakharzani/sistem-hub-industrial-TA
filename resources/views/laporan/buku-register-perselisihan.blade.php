<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Buku Register Perselisihan</title>
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
        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
        }

        .table-hover tbody tr:hover {
            background-color: #f9fafb;
        }

        .badge-perselisihan {
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
        }
    </style>
</head>

<body>
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Buku Register Perselisihan') }}
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
                            <h3 class="text-lg font-semibold text-gray-900">Buku Register Perselisihan</h3>
                            <p class="text-sm text-gray-600">
                                @if ($user->active_role === 'mediator')
                                    Buku register perselisihan untuk kasus perselisihan hubungan industrial
                                @else
                                    Buku register perselisihan internal dinas
                                @endif
                            </p>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    🔵 Hak
                                </span>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    🟢 Kepentingan
                                </span>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    🔴 PHK
                                </span>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    🟣 Serikat Pekerja
                                </span>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    ⚪ Lainnya
                                </span>
                            </div>
                            {{-- <div class="mt-2 text-xs text-gray-600">
                                <strong>Keterangan Tindak Lanjut PHI:</strong> "Ya" jika anjuran mediator ditolak oleh
                                salah satu atau kedua pihak
                            </div> --}}
                        </div>
                        <a href="{{ route('laporan.index') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            Kembali
                        </a>
                    </div>
                </div>

                <!-- Register Table -->
                <div class="bg-white rounded-lg shadow-sm">
                    @if ($bukuRegister->count() > 0)
                        <div class="overflow-x-auto table-responsive">
                            <table class="min-w-full divide-y divide-gray-200 table-hover">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal Pencatatan
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Pihak Pencatat
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Pihak Pekerja
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Pihak Pengusaha
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Jenis Perselisihan
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Proses Penyelesaian
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tindak Lanjut PHI
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($bukuRegister as $index => $register)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                                {{ $index + 1 + ($bukuRegister->currentPage() - 1) * $bukuRegister->perPage() }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ \Carbon\Carbon::parse($register->tanggal_pencatatan)->format('d/m/Y') }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $register->pihak_mencatat }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $register->pihak_pekerja }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $register->pihak_pengusaha }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                @php
                                                    $jenisPerselisihan =
                                                        $register->dokumenHI->pengaduan->perihal ?? 'Tidak Diketahui';
                                                    $jenisClass = '';
                                                    $jenisColor = '';

                                                    if (str_contains(strtolower($jenisPerselisihan), 'serikat')) {
                                                        $jenisClass = 'bg-purple-100 text-purple-800';
                                                        $jenisColor = 'Serikat Pekerja';
                                                    } elseif (str_contains(strtolower($jenisPerselisihan), 'hak')) {
                                                        $jenisClass = 'bg-blue-100 text-blue-800';
                                                        $jenisColor = 'Hak';
                                                    } elseif (
                                                        str_contains(strtolower($jenisPerselisihan), 'kepentingan')
                                                    ) {
                                                        $jenisClass = 'bg-green-100 text-green-800';
                                                        $jenisColor = 'Kepentingan';
                                                    } elseif (str_contains(strtolower($jenisPerselisihan), 'phk')) {
                                                        $jenisClass = 'bg-red-100 text-red-800';
                                                        $jenisColor = 'PHK';
                                                    } else {
                                                        $jenisClass = 'bg-gray-100 text-gray-800';
                                                        $jenisColor = 'Lainnya';
                                                    }
                                                @endphp
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $jenisClass }}">
                                                    {{ $jenisColor }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="flex flex-col space-y-1">
                                                    @if ($register->penyelesaian_klarifikasi === 'ya')
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                            Klarifikasi
                                                        </span>
                                                    @endif
                                                    @if ($register->penyelesaian_mediasi === 'ya')
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            Mediasi
                                                        </span>
                                                    @endif
                                                    @if ($register->penyelesaian_anjuran === 'ya')
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            Anjuran
                                                        </span>
                                                    @endif
                                                    @if ($register->penyelesaian_pb === 'ya')
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            Perjanjian Bersama
                                                        </span>
                                                    @endif
                                                    @if ($register->penyelesaian_risalah === 'ya')
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                            Risalah
                                                        </span>
                                                    @endif
                                                    @if (
                                                        $register->penyelesaian_klarifikasi !== 'ya' &&
                                                            $register->penyelesaian_mediasi !== 'ya' &&
                                                            $register->penyelesaian_anjuran !== 'ya' &&
                                                            $register->penyelesaian_pb !== 'ya' &&
                                                            $register->penyelesaian_risalah !== 'ya')
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                            Belum Selesai
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                @php
                                                    // Tindak Lanjut PHI = "Ya" jika anjuran ditolak oleh salah satu atau kedua pihak
                                                    // Logika: Jika ada anjuran tapi tidak ada perjanjian bersama, berarti anjuran ditolak
                                                    $tindakLanjut = 'Tidak';
                                                    $tindakLanjutClass = 'bg-gray-100 text-gray-800';

                                                    if (
                                                        $register->penyelesaian_anjuran === 'ya' &&
                                                        $register->penyelesaian_pb !== 'ya'
                                                    ) {
                                                        $tindakLanjut = 'Ya';
                                                        $tindakLanjutClass = 'bg-red-100 text-red-800';
                                                    }
                                                @endphp
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tindakLanjutClass }}">
                                                    {{ $tindakLanjut }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('laporan.buku-register.show', $register->buku_register_perselisihan_id) }}"
                                                    class="text-primary hover:text-primary-dark font-medium">
                                                    Lihat Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="px-6 py-3 border-t border-gray-200">
                            {{ $bukuRegister->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 5.477 5.754 5 7.5 5s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 5.477 18.246 5 16.5 5c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada buku register perselisihan</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Buku register perselisihan akan muncul di sini setelah kasus selesai.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </x-app-layout>
</body>

</html>
