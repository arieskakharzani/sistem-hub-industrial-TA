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

                    <!-- Response Status Section (jika published) -->
                    @if ($anjuran->status_approval === 'published')
                        <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-3">Status Respon Para Pihak</h4>

                            <div class="grid md:grid-cols-2 gap-4">
                                <!-- Pelapor Response -->
                                <div class="p-3 bg-white border rounded-lg">
                                    <h5 class="font-medium text-gray-800 mb-2">Pelapor</h5>
                                    @if ($anjuran->hasPelaporResponded())
                                        <div class="flex items-center">
                                            @if ($anjuran->response_pelapor === 'setuju')
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    Setuju
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    Tidak Setuju
                                                </span>
                                            @endif
                                            <span class="text-xs text-gray-500 ml-2">
                                                {{ $anjuran->response_at_pelapor ? $anjuran->response_at_pelapor->format('d/m/Y H:i') : '' }}
                                            </span>
                                        </div>
                                        @if ($anjuran->response_note_pelapor)
                                            <p class="text-sm text-gray-600 mt-1">{{ $anjuran->response_note_pelapor }}
                                            </p>
                                        @endif
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Menunggu Respon
                                        </span>
                                    @endif
                                </div>

                                <!-- Terlapor Response -->
                                <div class="p-3 bg-white border rounded-lg">
                                    <h5 class="font-medium text-gray-800 mb-2">Terlapor</h5>
                                    @if ($anjuran->hasTerlaporResponded())
                                        <div class="flex items-center">
                                            @if ($anjuran->response_terlapor === 'setuju')
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    Setuju
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    Tidak Setuju
                                                </span>
                                            @endif
                                            <span class="text-xs text-gray-500 ml-2">
                                                {{ $anjuran->response_at_terlapor ? $anjuran->response_at_terlapor->format('d/m/Y H:i') : '' }}
                                            </span>
                                        </div>
                                        @if ($anjuran->response_note_terlapor)
                                            <p class="text-sm text-gray-600 mt-1">
                                                {{ $anjuran->response_note_terlapor }}</p>
                                        @endif
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Menunggu Respon
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Overall Status -->
                            @if ($anjuran->isResponseComplete())
                                <div class="mt-4 p-3 border rounded-lg">
                                    <h5 class="font-medium text-gray-800 mb-2">Status Keseluruhan</h5>
                                    @if ($anjuran->isBothPartiesAgree())
                                        <div class="flex items-center">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Kedua Pihak Setuju
                                            </span>
                                        </div>
                                    @elseif ($anjuran->isBothPartiesDisagree())
                                        <div class="flex items-center">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Kedua Pihak Tidak Setuju
                                            </span>
                                        </div>
                                    @else
                                        <div class="flex items-center">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Respon Berbeda
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        @if (auth()->user()->active_role === 'mediator' && $anjuran->status_approval === 'draft')
                            <form action="{{ route('dokumen.anjuran.submit', $anjuran->anjuran_id) }}" method="POST"
                                class="inline">
                                @csrf
                                <x-primary-button type="submit" class="bg-blue-500 hover:bg-blue-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                    Submit untuk Approval
                                </x-primary-button>
                            </form>
                        @endif

                        @if (auth()->user()->active_role === 'mediator' && $anjuran->status_approval === 'approved')
                            <form action="{{ route('dokumen.anjuran.publish', $anjuran->anjuran_id) }}"
                                method="POST" class="inline">
                                @csrf
                                <x-primary-button type="submit" class="bg-green-500 hover:bg-green-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
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

                        <!-- Action Buttons untuk Mediator berdasarkan Response Status -->
                        @if (auth()->user()->active_role === 'mediator' &&
                                $anjuran->status_approval === 'published' &&
                                $anjuran->isResponseComplete())
                            <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <h4 class="font-semibold text-blue-900 mb-3">Aksi Berdasarkan Respon Para Pihak</h4>

                                @if ($anjuran->isBothPartiesAgree())
                                    <!-- Jika kedua pihak setuju -->
                                    <div class="space-y-3">
                                        <p class="text-blue-800">Kedua pihak telah menyetujui anjuran. Anda dapat:</p>
                                        @if ($anjuran->hasTtdPerjanjianBersamaSchedule())
                                            <!-- Jika jadwal sudah ada, tampilkan link lihat jadwal -->
                                            @php
                                                $jadwalTtd = $anjuran->getTtdPerjanjianBersamaSchedule();
                                            @endphp
                                            <a href="{{ route('jadwal.show', $jadwalTtd->jadwal_id) }}"
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded shadow font-semibold transition">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                                Lihat Jadwal TTD Perjanjian Bersama
                                            </a>
                                        @else
                                            <!-- Jika jadwal belum ada, tampilkan button buat jadwal -->
                                            <a href="{{ route('jadwal.create', ['pengaduan_id' => $anjuran->dokumenHI->pengaduan->pengaduan_id, 'jenis_jadwal' => 'ttd_perjanjian_bersama']) }}"
                                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded shadow font-semibold transition">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                Buat Jadwal TTD Perjanjian Bersama
                                            </a>
                                        @endif
                                    </div>
                                @elseif ($anjuran->isBothPartiesDisagree())
                                    <!-- Jika kedua pihak tidak setuju -->
                                    <div class="space-y-3">
                                        <p class="text-red-800">Kedua pihak telah menolak anjuran. Anda dapat:</p>
                                        <form
                                            action="{{ route('dokumen.anjuran.finalize-case', $anjuran->anjuran_id) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded shadow font-semibold transition">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Selesaikan Kasus
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <!-- Jika respon berbeda -->
                                    <div class="space-y-3">
                                        <p class="text-yellow-800">Para pihak memberikan respon yang berbeda. Anda
                                            dapat:</p>
                                        <div class="flex space-x-3">
                                            @if ($anjuran->hasTtdPerjanjianBersamaSchedule())
                                                <!-- Jika jadwal sudah ada, tampilkan link lihat jadwal -->
                                                @php
                                                    $jadwalTtd = $anjuran->getTtdPerjanjianBersamaSchedule();
                                                @endphp
                                                <a href="{{ route('jadwal.show', $jadwalTtd->jadwal_id) }}"
                                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded shadow font-semibold transition">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                    Lihat Jadwal TTD Perjanjian Bersama
                                                </a>
                                            @else
                                                <!-- Jika jadwal belum ada, tampilkan button buat jadwal -->
                                                <a href="{{ route('jadwal.create', ['pengaduan_id' => $anjuran->dokumenHI->pengaduan->pengaduan_id, 'jenis_jadwal' => 'ttd_perjanjian_bersama']) }}"
                                                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded shadow font-semibold transition">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                    Buat Jadwal TTD Perjanjian Bersama
                                                </a>
                                            @endif
                                            <form
                                                action="{{ route('dokumen.anjuran.finalize-case', $anjuran->anjuran_id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded shadow font-semibold transition">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Selesaikan Kasus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
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
                            <p class="text-sm text-gray-600">Nomor:
                                {{ $anjuran->nomor_anjuran ?? 'A-' . $anjuran->anjuran_id }}</p>
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
                            <div class="text-blue-700 text-justify">
                                <p class="mb-4">
                                    Dokumen anjuran ini dikeluarkan oleh Mediator Hubungan Industrial dan disetujui
                                    oleh Kepala Dinas Tenaga Kerja dan Transmigrasi Kabupaten Bungo.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
