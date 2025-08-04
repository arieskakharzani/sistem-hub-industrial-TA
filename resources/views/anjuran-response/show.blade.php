<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Respon Anjuran</title>
</head>

<body>
    <x-app-layout>
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detail Anjuran & Respon
                </h2>
                <!-- Cetak PDF Button -->
                <a href="{{ route('dokumen.anjuran.pdf', $anjuran->anjuran_id) }}" target="_blank"
                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-md transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Cetak PDF
                </a>
            </div>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header dengan Action Buttons -->
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

                            @php
                                $bothPartiesAgreed =
                                    $anjuran->response_pelapor === 'setuju' && $anjuran->response_terlapor === 'setuju';
                            @endphp

                            @if ($anjuran->deadline_response_at && !$bothPartiesAgreed && $anjuran->dokumenHI->pengaduan->status !== 'selesai')
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
                            @elseif ($anjuran->deadline_response_at && $bothPartiesAgreed && $anjuran->dokumenHI->pengaduan->status !== 'selesai')
                                <div class="text-right">
                                    <div class="text-sm text-gray-600">Batas Waktu Respon:</div>
                                    <div class="text-lg font-semibold text-green-600">
                                        {{ $anjuran->deadline_response_at->format('d F Y H:i') }}
                                    </div>
                                    <div class="text-sm text-green-500 font-medium">
                                        ✅ Kedua pihak telah setuju
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Alert ketika kedua pihak setuju -->
                @if ($bothPartiesAgreed)
                    <div class="bg-green-50 border-l-4 border-green-400 p-6 mb-6 rounded-r-xl">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">
                                    <span class="font-medium">Selamat!</span>
                                    Kedua pihak telah menyetujui anjuran ini. Mediator akan segera menghubungi Anda
                                    untuk langkah selanjutnya.
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif (
                    $anjuran->response_pelapor !== 'pending' &&
                        $anjuran->response_terlapor !== 'pending' &&
                        $anjuran->dokumenHI->pengaduan->status !== 'selesai')
                    <!-- Alert ketika kedua pihak sudah merespon tapi tidak setuju -->
                    <div class="bg-red-50 border-l-4 border-red-400 p-6 mb-6 rounded-r-xl">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    <span class="font-medium">Perhatian!</span>
                                    Kedua pihak telah memberikan respon namun tidak mencapai kesepakatan. Mediator akan
                                    menghubungi Anda untuk langkah selanjutnya.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Status Respon (jika kasus belum selesai) -->
                @if ($anjuran->dokumenHI->pengaduan->status !== 'selesai')
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
                                            <div class="bg-gray-50 p-2 rounded mt-1">
                                                {{ $anjuran->response_note_pelapor }}
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
                                                Direspon pada:
                                                {{ $anjuran->response_at_terlapor->format('d/m/Y H:i') }}
                                            </div>
                                        @endif
                                    @else
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            Tidak Setuju
                                        </span>
                                        @if ($anjuran->response_at_terlapor)
                                            <div class="text-xs text-gray-500 mt-1">
                                                Direspon pada:
                                                {{ $anjuran->response_at_terlapor->format('d/m/Y H:i') }}
                                            </div>
                                        @endif
                                    @endif

                                    @if ($anjuran->response_note_terlapor)
                                        <div class="mt-2 text-sm">
                                            <span class="font-medium">Catatan:</span>
                                            <div class="bg-gray-50 p-2 rounded mt-1">
                                                {{ $anjuran->response_note_terlapor }}
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
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Berikan Respon Anda</h3>

                                <form action="{{ route('anjuran-response.submit', $anjuran->anjuran_id) }}"
                                    method="POST">
                                    @csrf

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Respon Anda *
                                        </label>
                                        <div class="space-y-2">
                                            <label class="flex items-center">
                                                <input type="radio" name="response" value="setuju" class="mr-2"
                                                    required>
                                                <span class="text-sm">Setuju dengan anjuran ini</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="response" value="tidak_setuju"
                                                    class="mr-2" required>
                                                <span class="text-sm">Tidak setuju dengan anjuran ini</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mb-6">
                                        <label for="note" class="block text-sm font-medium text-gray-700 mb-2">
                                            Catatan (Opsional)
                                        </label>
                                        <textarea id="note" name="note" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Berikan catatan respon tambahan jika diperlukan..."></textarea>
                                    </div>

                                    <div class="flex justify-end">
                                        <x-primary-button type="submit">
                                            Kirim Respon
                                        </x-primary-button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                @endif

                <!-- Detail Anjuran Lengkap -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Anjuran</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Informasi Pengusaha</h4>
                                <div class="space-y-2 text-sm">
                                    <div><span class="font-medium">Nama:</span> {{ $anjuran->nama_pengusaha }}
                                    </div>
                                    <div><span class="font-medium">Jabatan:</span>
                                        {{ $anjuran->jabatan_pengusaha }}
                                    </div>
                                    <div><span class="font-medium">Perusahaan:</span>
                                        {{ $anjuran->perusahaan_pengusaha }}</div>
                                    <div><span class="font-medium">Alamat:</span> {{ $anjuran->alamat_pengusaha }}
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Informasi Pekerja</h4>
                                <div class="space-y-2 text-sm">
                                    <div><span class="font-medium">Nama:</span> {{ $anjuran->nama_pekerja }}</div>
                                    <div><span class="font-medium">Jabatan:</span> {{ $anjuran->jabatan_pekerja }}
                                    </div>
                                    <div><span class="font-medium">Perusahaan:</span>
                                        {{ $anjuran->perusahaan_pekerja }}</div>
                                    <div><span class="font-medium">Alamat:</span> {{ $anjuran->alamat_pekerja }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Grid untuk poin A, B, C, dan MENGANJURKAN -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                            <!-- Keterangan Pekerja -->
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">A. Keterangan pihak
                                    Pekerja/Buruh/Serikat
                                    Pekerja/Serikat Buruh:</h4>
                                <div class="bg-gray-50 p-4 rounded-lg h-48 overflow-y-auto">
                                    <div class="text-sm text-gray-700">
                                        {{ $anjuran->keterangan_pekerja }}</div>
                                </div>
                            </div>

                            <!-- Keterangan Pengusaha -->
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">B. Keterangan pihak Pengusaha:</h4>
                                <div class="bg-gray-50 p-4 rounded-lg h-48 overflow-y-auto">
                                    <div class="text-sm text-gray-700">
                                        {{ $anjuran->keterangan_pengusaha }}</div>
                                </div>
                            </div>

                            <!-- Pertimbangan Hukum -->
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">C. Pertimbangan Hukum dan Kesimpulan
                                    Mediator:</h4>
                                <div class="bg-gray-50 p-4 rounded-lg h-48 overflow-y-auto">
                                    <div class="text-sm text-gray-700">
                                        {{ $anjuran->pertimbangan_hukum }}</div>
                                </div>
                            </div>

                            <!-- Isi Anjuran -->
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">MENGANJURKAN:</h4>
                                <div class="bg-gray-50 p-4 rounded-lg h-48 overflow-y-auto">
                                    <div class="text-sm text-gray-700">{{ $anjuran->isi_anjuran }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Approval -->
                        @if ($anjuran->status_approval === 'approved' || $anjuran->status_approval === 'published')
                            <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <h4 class="font-medium text-yellow-800 mb-2">INFORMASI APPROVAL</h4>
                                <div class="text-yellow-700 text-sm">
                                    <p class="mb-2">
                                        <strong>Status:</strong> Anjuran ini telah disetujui oleh Kepala Dinas
                                        Tenaga
                                        Kerja dan Transmigrasi
                                    </p>
                                    <p class="mb-2">
                                        <strong>Tanggal Approval:</strong>
                                        {{ $anjuran->approved_by_kepala_dinas_at ? \Carbon\Carbon::parse($anjuran->approved_by_kepala_dinas_at)->translatedFormat('d F Y') : '-' }}
                                    </p>
                                    @if ($anjuran->notes_kepala_dinas)
                                        <p class="mb-2">
                                            <strong>Catatan:</strong> {{ $anjuran->notes_kepala_dinas }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Pernyataan Resmi -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">

                            <div class="text-gray-600 text-sm italic">
                                <p>Dokumen ini dikeluarkan oleh Mediator Hubungan Industrial dan disetujui oleh
                                    Kepala Dinas Tenaga Kerja dan Transmigrasi Kabupaten Bungo.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
</body>

</html>
