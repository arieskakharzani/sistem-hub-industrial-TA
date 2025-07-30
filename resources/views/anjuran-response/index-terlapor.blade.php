<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Anjuran untuk Respon
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($anjurans->count() > 0)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                {{ $anjurans->count() }} Anjuran Menunggu Respon Anda
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
                                            Terbit</th>
                                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Batas Waktu
                                        </th>
                                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Status
                                            Respon</th>
                                        <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($anjurans as $anjuran)
                                        <tr class="border-t hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-2 text-sm text-gray-700">{{ $loop->iteration }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-900 font-semibold">
                                                {{ $anjuran->nomor_anjuran ?? 'A-' . $anjuran->anjuran_id }}
                                            </td>
                                            <td class="px-4 py-2 text-sm text-gray-700">
                                                {{ $anjuran->dokumenHI->pengaduan->mediator->nama_mediator ?? '-' }}
                                            </td>
                                            <td class="px-4 py-2 text-sm text-gray-700">
                                                {{ $anjuran->published_at ? $anjuran->published_at->format('d/m/Y H:i') : '-' }}
                                            </td>
                                            <td class="px-4 py-2 text-sm text-gray-700">
                                                @if ($anjuran->deadline_response_at)
                                                    <span
                                                        class="{{ $anjuran->isResponseDeadlinePassed() ? 'text-red-600 font-semibold' : 'text-gray-700' }}">
                                                        {{ $anjuran->deadline_response_at->format('d/m/Y H:i') }}
                                                    </span>
                                                    @if ($anjuran->isResponseDeadlinePassed())
                                                        <br><span class="text-xs text-red-500">Batas waktu
                                                            terlampaui</span>
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 text-sm">
                                                @if ($anjuran->response_terlapor === 'pending')
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Menunggu Respon
                                                    </span>
                                                @elseif ($anjuran->response_terlapor === 'setuju')
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Setuju
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Tidak Setuju
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 text-center">
                                                <a href="{{ route('anjuran-response.show', $anjuran->anjuran_id) }}"
                                                    class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-md transition">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                    Lihat & Respon
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $anjurans->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div
                                class="mx-auto w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak Ada Anjuran Menunggu Respon</h3>
                            <p class="text-gray-600">Belum ada anjuran yang diterbitkan untuk Anda atau semua anjuran
                                telah direspon.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
