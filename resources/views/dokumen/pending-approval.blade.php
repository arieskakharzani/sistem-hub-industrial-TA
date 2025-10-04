<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Anjuran Menunggu Approval</title>
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
                Anjuran Menunggu Approval
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        @if ($pendingAnjurans->count() > 0)
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                    {{ $pendingAnjurans->count() }} Anjuran Menunggu Approval
                                </h3>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">#</th>
                                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Nomor
                                                Anjuran</th>
                                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Mediator
                                            </th>
                                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Tanggal
                                            </th>
                                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">
                                                Pengusaha
                                            </th>
                                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Pekerja
                                            </th>
                                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Isi
                                                Anjuran
                                            </th>
                                            <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Aksi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pendingAnjurans as $anjuran)
                                            <tr class="border-t hover:bg-gray-50 transition-colors">
                                                <td class="px-4 py-2 text-sm text-gray-700">{{ $loop->iteration }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-900 font-semibold">
                                                    {{ $anjuran->nomor_anjuran ?? 'A-' . $anjuran->anjuran_id }}
                                                </td>
                                                <td class="px-4 py-2 text-sm text-gray-700">
                                                    {{ $anjuran->dokumenHI->pengaduan->mediator->nama_mediator ?? '-' }}
                                                </td>
                                                <td class="px-4 py-2 text-sm text-gray-700">
                                                    {{ $anjuran->created_at->format('d/m/Y H:i') }}
                                                </td>
                                                <td class="px-4 py-2 text-sm text-gray-700">
                                                    {{ $anjuran->nama_pengusaha }}<br>
                                                    <span
                                                        class="text-xs text-gray-500">{{ $anjuran->perusahaan_pengusaha }}</span>
                                                </td>
                                                <td class="px-4 py-2 text-sm text-gray-700">
                                                    {{ $anjuran->nama_pekerja }}<br>
                                                    <span
                                                        class="text-xs text-gray-500">{{ $anjuran->perusahaan_pekerja }}</span>
                                                </td>
                                                <td class="px-4 py-2 text-sm text-gray-700">
                                                    {{ Str::limit($anjuran->isi_anjuran, 60) }}
                                                </td>
                                                <td class="px-4 py-2 text-center">
                                                    <a href="{{ route('dokumen.anjuran.show', $anjuran) }}"
                                                        class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs rounded-md transition">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        Review & Approve
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-6">
                                {{ $pendingAnjurans->links() }}
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div
                                    class="mx-auto w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak Ada Anjuran Menunggu Approval
                                </h3>
                                <p class="text-gray-600">Semua anjuran telah diproses atau belum ada anjuran yang
                                    disubmit
                                    untuk approval.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
</body>

</html>
