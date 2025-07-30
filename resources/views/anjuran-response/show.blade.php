<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Anjuran & Respon
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Status dan Countdown -->
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                Anjuran #{{ $anjuran->nomor_anjuran ?? 'A-' . $anjuran->anjuran_id }}
                            </h3>
                            <p class="text-sm text-gray-600">
                                Diterbitkan pada:
                                {{ $anjuran->published_at ? $anjuran->published_at->format('d F Y H:i') : '-' }}
                            </p>
                        </div>

                        @if ($anjuran->deadline_response_at)
                            <div class="text-right">
                                <div class="text-sm text-gray-600">Batas Waktu Respon:</div>
                                <div
                                    class="text-lg font-semibold {{ $anjuran->isResponseDeadlinePassed() ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $anjuran->deadline_response_at->format('d F Y H:i') }}
                                </div>
                                @if (!$anjuran->isResponseDeadlinePassed())
                                    <div class="text-sm text-gray-500">
                                        {{ $anjuran->getDaysUntilDeadline() }} hari tersisa
                                    </div>
                                @else
                                    <div class="text-sm text-red-500">Batas waktu terlampaui</div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Detail Anjuran -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Anjuran</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Informasi Pengusaha</h4>
                            <div class="space-y-2 text-sm">
                                <div><span class="font-medium">Nama:</span> {{ $anjuran->nama_pengusaha }}</div>
                                <div><span class="font-medium">Jabatan:</span> {{ $anjuran->jabatan_pengusaha }}</div>
                                <div><span class="font-medium">Perusahaan:</span> {{ $anjuran->perusahaan_pengusaha }}
                                </div>
                                <div><span class="font-medium">Alamat:</span> {{ $anjuran->alamat_pengusaha }}</div>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Informasi Pekerja</h4>
                            <div class="space-y-2 text-sm">
                                <div><span class="font-medium">Nama:</span> {{ $anjuran->nama_pekerja }}</div>
                                <div><span class="font-medium">Jabatan:</span> {{ $anjuran->jabatan_pekerja }}</div>
                                <div><span class="font-medium">Perusahaan:</span> {{ $anjuran->perusahaan_pekerja }}
                                </div>
                                <div><span class="font-medium">Alamat:</span> {{ $anjuran->alamat_pekerja }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h4 class="font-medium text-gray-900 mb-2">Isi Anjuran</h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="text-sm text-gray-700 whitespace-pre-wrap">{{ $anjuran->isi_anjuran }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Respon -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Respon</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Respon Pelapor</h4>
                            @if ($anjuran->response_pelapor === 'pending')
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    Menunggu Respon
                                </span>
                            @elseif ($anjuran->response_pelapor === 'setuju')
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    Setuju
                                </span>
                                @if ($anjuran->response_at_pelapor)
                                    <div class="text-xs text-gray-500 mt-1">
                                        Direspon pada: {{ $anjuran->response_at_pelapor->format('d/m/Y H:i') }}
                                    </div>
                                @endif
                            @else
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    Tidak Setuju
                                </span>
                                @if ($anjuran->response_at_pelapor)
                                    <div class="text-xs text-gray-500 mt-1">
                                        Direspon pada: {{ $anjuran->response_at_pelapor->format('d/m/Y H:i') }}
                                    </div>
                                @endif
                            @endif

                            @if ($anjuran->response_note_pelapor)
                                <div class="mt-2 text-sm">
                                    <span class="font-medium">Catatan:</span>
                                    <div class="bg-gray-50 p-2 rounded mt-1">{{ $anjuran->response_note_pelapor }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Respon Terlapor</h4>
                            @if ($anjuran->response_terlapor === 'pending')
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    Menunggu Respon
                                </span>
                            @elseif ($anjuran->response_terlapor === 'setuju')
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    Setuju
                                </span>
                                @if ($anjuran->response_at_terlapor)
                                    <div class="text-xs text-gray-500 mt-1">
                                        Direspon pada: {{ $anjuran->response_at_terlapor->format('d/m/Y H:i') }}
                                    </div>
                                @endif
                            @else
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    Tidak Setuju
                                </span>
                                @if ($anjuran->response_at_terlapor)
                                    <div class="text-xs text-gray-500 mt-1">
                                        Direspon pada: {{ $anjuran->response_at_terlapor->format('d/m/Y H:i') }}
                                    </div>
                                @endif
                            @endif

                            @if ($anjuran->response_note_terlapor)
                                <div class="mt-2 text-sm">
                                    <span class="font-medium">Catatan:</span>
                                    <div class="bg-gray-50 p-2 rounded mt-1">{{ $anjuran->response_note_terlapor }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Respon -->
            @php
                $responseField = $userRole === 'pelapor' ? 'response_pelapor' : 'response_terlapor';
                $hasResponded = $anjuran->$responseField !== 'pending';
            @endphp

            @if (!$hasResponded && $anjuran->canStillRespond())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Berikan Respon Anda</h3>

                        <form action="{{ route('anjuran-response.submit', $anjuran->anjuran_id) }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Respon Anda *
                                </label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="radio" name="response" value="setuju" class="mr-2" required>
                                        <span class="text-sm">Setuju dengan anjuran ini</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="response" value="tidak_setuju" class="mr-2"
                                            required>
                                        <span class="text-sm">Tidak setuju dengan anjuran ini</span>
                                    </label>
                                </div>
                            </div>

                            <div class="mb-6">
                                <label for="note" class="block text-sm font-medium text-gray-700 mb-2">
                                    Catatan (Opsional)
                                </label>
                                <textarea id="note" name="note" rows="4"
                                    class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    placeholder="Berikan alasan atau catatan tambahan untuk respon Anda..."></textarea>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-md transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Kirim Respon
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @elseif ($hasResponded)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-center">
                            <div
                                class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Anda Sudah Memberikan Respon</h3>
                            <p class="text-gray-600">Respon Anda telah disimpan dan tidak dapat diubah.</p>
                        </div>
                    </div>
                </div>
            @elseif (!$anjuran->canStillRespond())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-center">
                            <div
                                class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Batas Waktu Telah Berakhir</h3>
                            <p class="text-gray-600">Batas waktu untuk memberikan respon telah terlampaui.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
