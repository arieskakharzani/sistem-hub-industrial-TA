<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Anjuran') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('dokumen.anjuran.edit', ['id' => $anjuran->anjuran_id]) }}"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300">
                    Edit Anjuran
                </a>
                <a href="{{ route('dokumen.anjuran.pdf', ['id' => $anjuran->anjuran_id]) }}" target="_blank"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300">
                    Cetak PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Status Approval Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Status Approval</h3>

                        <!-- Status Badge -->
                        @php
                            $statusClasses = [
                                'published' => 'bg-green-100 text-green-800',
                                'approved' => 'bg-blue-100 text-blue-800',
                                'pending_kepala_dinas' => 'bg-yellow-100 text-yellow-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                'draft' => 'bg-gray-100 text-gray-800',
                            ];
                            $currentStatus = $anjuran->status_approval;
                            $statusClass = $statusClasses[$currentStatus] ?? $statusClasses['draft'];
                        @endphp
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                            Status: {{ ucfirst(str_replace('_', ' ', $anjuran->status_approval)) }}
                        </span>
                    </div>

                    <!-- Countdown Timer (jika published) -->
                    @if ($anjuran->status_approval === 'published' && $anjuran->deadline_response_at)
                        <div class="mb-4 p-4 bg-orange-50 border border-orange-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-orange-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-orange-800">Deadline Response</h4>
                                    <p class="text-orange-700">
                                        Para pihak memiliki waktu <strong>{{ $anjuran->getDaysUntilDeadline() }}
                                            hari</strong> lagi
                                        (hingga {{ $anjuran->deadline_response_at->format('d/m/Y H:i') }})
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        @if (auth()->user()->active_role === 'mediator' && $anjuran->status_approval === 'draft')
                            <form action="{{ route('dokumen.anjuran.submit', $anjuran->anjuran_id) }}" method="POST"
                                class="inline">
                                @csrf
                                <x-primary-button type="submit" class="bg-blue-500 hover:bg-blue-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                    Submit untuk Approval
                                </x-primary-button>
                            </form>
                        @endif

                        @if (auth()->user()->active_role === 'mediator' && $anjuran->status_approval === 'approved')
                            <form action="{{ route('dokumen.anjuran.publish', $anjuran->anjuran_id) }}" method="POST"
                                class="inline">
                                @csrf
                                <x-primary-button type="submit" class="bg-green-500 hover:bg-green-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                    Publish ke Para Pihak
                                </x-primary-button>
                            </form>
                        @endif

                        @if (auth()->user()->active_role === 'kepala_dinas' && $anjuran->status_approval === 'pending_kepala_dinas')
                            <div class="space-y-3">
                                <form action="{{ route('dokumen.anjuran.approve', $anjuran->anjuran_id) }}"
                                    method="POST" class="inline">
                                    @csrf
                                    <div class="mb-3">
                                        <x-input-label for="notes" value="Catatan (Opsional)" />
                                        <x-textarea-input id="notes" name="notes" rows="3"
                                            class="mt-1 block w-full" />
                                    </div>
                                    <x-primary-button type="submit" class="bg-green-500 hover:bg-green-600">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Setujui Anjuran
                                    </x-primary-button>
                                </form>

                                <form action="{{ route('dokumen.anjuran.reject', $anjuran->anjuran_id) }}"
                                    method="POST" class="inline">
                                    @csrf
                                    <div class="mb-3">
                                        <x-input-label for="reason" value="Alasan Penolakan *" />
                                        <x-textarea-input id="reason" name="reason" rows="3" required
                                            class="mt-1 block w-full" />
                                    </div>
                                    <x-danger-button type="submit">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Tolak Anjuran
                                    </x-danger-button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <!-- Notes dari Kepala Dinas -->
                    @if ($anjuran->notes_kepala_dinas)
                        <div class="mt-4 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-2">Catatan Kepala Dinas:</h4>
                            <p class="text-gray-700">{{ $anjuran->notes_kepala_dinas }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Anjuran Content -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="relative">
                    <div class="pointer-events-none select-none absolute inset-0 flex items-center justify-center z-50"
                        style="opacity:0.12; font-size:5rem; font-weight:bold; color:#1e293b; transform:rotate(-20deg);">
                        DRAFT
                    </div>
                    <div class="p-6 text-gray-900">
                        <!-- Header -->
                        <div class="text-center mb-8">
                            <h2 class="text-xl font-bold mb-2">ANJURAN</h2>
                        </div>

                        <!-- Nomor Surat -->
                        <div class="space-y-2 mb-6 text-right">
                            <p class="text-gray-700">Muara Bungo,
                                {{ \Carbon\Carbon::parse($anjuran->created_at)->translatedFormat('d F Y') }}</p>
                        </div>

                        <!-- Tujuan Surat -->
                        <div class="mb-6 text-gray-700">
                            <p>Yth.</p>
                            <p>1. Sdr. {{ $anjuran->nama_pengusaha }} (Pengusaha)</p>
                            <p>2. Sdr. {{ $anjuran->nama_pekerja }} (Pekerja/Buruh/SP/SB)</p>
                            <p>di tempat</p>
                        </div>

                        <!-- Pembuka -->
                        <div class="mb-8 text-gray-700">
                            <p>
                                Sehubungan dengan penyelesaian perselisihan hubungan industrial antara
                                {{ $anjuran->perusahaan_pengusaha }} dengan
                                Sdr. {{ $anjuran->nama_pekerja }} yang telah dilaksanakan
                                melalui mediasi tidak tercapai kesepakatan dan sesuai ketentuan Pasal 13 ayat (2)
                                Undang-Undang Nomor 2 Tahun 2004 tentang Penyelesaian Perselisihan Hubungan
                                Industrial,
                                maka Mediator Hubungan Industrial mengeluarkan anjuran.
                            </p>
                        </div>

                        <!-- Keterangan Pekerja -->
                        <div class="mb-8">
                            <h3 class="font-semibold text-lg mb-4 text-gray-800">A. Keterangan pihak
                                Pekerja/Buruh/Serikat
                                Pekerja/Serikat Buruh:</h3>
                            <div class="bg-gray-50 p-6 rounded-lg whitespace-pre-line text-gray-700">
                                {!! nl2br(e($anjuran->keterangan_pekerja)) !!}
                            </div>
                        </div>

                        <!-- Keterangan Pengusaha -->
                        <div class="mb-8">
                            <h3 class="font-semibold text-lg mb-4 text-gray-800">B. Keterangan pihak Pengusaha:</h3>
                            <div class="bg-gray-50 p-6 rounded-lg whitespace-pre-line text-gray-700">
                                {!! nl2br(e($anjuran->keterangan_pengusaha)) !!}
                            </div>
                        </div>

                        <!-- Pertimbangan Hukum -->
                        <div class="mb-8">
                            <h3 class="font-semibold text-lg mb-4 text-gray-800">C. Pertimbangan Hukum dan
                                Kesimpulan
                                Mediator:</h3>
                            <div class="bg-gray-50 p-6 rounded-lg whitespace-pre-line text-gray-700">
                                {!! nl2br(e($anjuran->pertimbangan_hukum)) !!}
                            </div>
                        </div>

                        <!-- Isi Anjuran -->
                        <div class="mb-8">
                            <h3 class="font-semibold text-lg mb-4 text-gray-800">MENGANJURKAN:</h3>
                            <div class="bg-gray-50 p-6 rounded-lg whitespace-pre-line text-gray-700">
                                {!! nl2br(e($anjuran->isi_anjuran)) !!}
                            </div>
                        </div>

                        <!-- Penutup -->
                        <div class="mb-8 text-gray-700">
                            <p class="mb-4">
                                Dan agar kedua belah pihak memberikan jawaban atas anjuran tersebut
                                selambat-lambatnya
                                dalam
                                jangka waktu 10 (sepuluh) hari kerja setelah menerima surat anjuran ini.
                            </p>
                            <p>Demikian untuk diketahui dan menjadi perhatian.</p>
                        </div>

                        <!-- Informasi Approval -->
                        @if ($anjuran->status_approval === 'approved' || $anjuran->status_approval === 'published')
                            <div class="mb-8 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                                <h3 class="font-semibold text-lg mb-4 text-yellow-800">INFORMASI APPROVAL</h3>
                                <div class="text-yellow-700">
                                    <p class="mb-2">
                                        <strong>Status:</strong> Anjuran ini telah disetujui oleh Kepala Dinas Tenaga
                                        Kerja dan Transmigrasi
                                    </p>
                                    <p class="mb-2">
                                        <strong>Tanggal Approval:</strong>
                                        {{ $anjuran->approved_by_kepala_dinas_at ? \Carbon\Carbon::parse($anjuran->approved_by_kepala_dinas_at)->translatedFormat('d F Y') : '-' }}
                                    </p>
                                    @if ($anjuran->notes_kepala_dinas)
                                        <p class="mb-2">
                                            <strong>Catatan Kepala Dinas:</strong> {{ $anjuran->notes_kepala_dinas }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Pernyataan Resmi -->
                        <div class="mb-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
                            <h3 class="font-semibold text-lg mb-4 text-blue-800 text-center">PERNYATAAN RESMI</h3>
                            <div class="text-blue-700 text-justify">
                                <p class="mb-4">
                                    Dokumen anjuran ini dikeluarkan secara resmi oleh Mediator Hubungan Industrial yang
                                    ditunjuk
                                    oleh Dinas Tenaga Kerja dan Transmigrasi Kabupaten Bungo. Anjuran ini telah melalui
                                    proses
                                    approval dan disetujui oleh Kepala Dinas Tenaga Kerja dan Transmigrasi, sehingga
                                    memiliki
                                    kekuatan hukum sesuai dengan ketentuan Undang-Undang Nomor 2 Tahun 2004 tentang
                                    Penyelesaian
                                    Perselisihan Hubungan Industrial.
                                </p>
                                <p class="text-sm italic">
                                    <strong>Catatan:</strong> Dokumen ini memiliki kekuatan hukum resmi melalui sistem
                                    approval digital.
                                </p>
                            </div>
                        </div>

                        <!-- Legalisasi Digital -->
                        @if ($anjuran->status_approval === 'approved' || $anjuran->status_approval === 'published')
                            <div class="mb-8 bg-green-50 border-2 border-green-200 rounded-lg p-6 text-center">
                                <div class="text-green-800">
                                    <div class="text-2xl font-bold mb-4">✓ DOKUMEN RESMI & TERAPPROVE</div>
                                    <div class="text-sm space-y-1">
                                        <p><strong>Disetujui oleh:</strong>
                                            {{ $anjuran->kepalaDinas->nama_kepala_dinas ?? 'Kepala Dinas' }}</p>
                                        <p><strong>Tanggal:</strong>
                                            {{ $anjuran->approved_by_kepala_dinas_at ? \Carbon\Carbon::parse($anjuran->approved_by_kepala_dinas_at)->translatedFormat('d F Y') : '-' }}
                                        </p>
                                        <p><strong>Dikeluarkan oleh:</strong>
                                            {{ $anjuran->dokumenHI->pengaduan->mediator->nama_mediator ?? 'Mediator' }}
                                        </p>
                                        <p class="font-bold text-base">Dinas Tenaga Kerja dan Transmigrasi Kabupaten
                                            Bungo</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        {{-- @if ($anjuran->isFullySigned())
                                <form method="POST" action="{{ route('penyelesaian.finalize') }}"
                                    class="mt-8 text-center">
                                    @csrf
                                    <input type="hidden" name="document_type" value="anjuran">
                                    <input type="hidden" name="document_id" value="{{ $anjuran->anjuran_id }}">
                                    <button type="submit"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold transition-all duration-200">
                                        Kirim Final ke Para Pihak & Selesaikan Kasus
                                    </button>
                                </form>
                            @endif --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
