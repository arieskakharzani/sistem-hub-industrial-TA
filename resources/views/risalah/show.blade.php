<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detail Risalah {{ ucfirst($risalah->jenis_risalah) }}</title>
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

</body>

</html>
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Risalah ' . ucfirst($risalah->jenis_risalah)) }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('risalah.edit', $risalah) }}"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300">
                    Edit Risalah
                </a>
                <a href="{{ route('risalah.pdf', $risalah) }}" target="_blank"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300">
                    Cetak PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Status Pengaduan dan Button untuk Risalah Penyelesaian -->
            @if ($risalah->jenis_risalah === 'penyelesaian' && $detail)
                <div class="mb-6 bg-white p-4 rounded-lg shadow-md">
                    <div class="flex justify-between items-center">
                        <div class="text-sm">
                            <span class="text-gray-600">Status Pengaduan: </span>
                            <span
                                class="font-semibold {{ $risalah->jadwal->pengaduan->status === 'selesai' ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ ucfirst($risalah->jadwal->pengaduan->status) }}
                            </span>
                        </div>

                        {{-- Debug info --}}
                        {{-- @if (config('app.debug'))
                            <div class="mb-4 p-2 bg-gray-100 text-xs">
                                <strong>Debug Info:</strong><br>
                                Dokumen HI ID: {{ $dokumen_hi_id ?? 'null' }}<br>
                                Perjanjian Bersama: {{ $perjanjianBersama ? 'ADA' : 'TIDAK ADA' }}<br>
                                Anjuran: {{ $anjuran ? 'ADA' : 'TIDAK ADA' }}<br>
                                @if ($perjanjianBersama)
                                    PB ID: {{ $perjanjianBersama->perjanjian_bersama_id }}<br>
                                @endif
                                @if ($anjuran)
                                    Anjuran ID: {{ $anjuran->anjuran_id }}<br>
                                @endif
                            </div>
                        @endif --}}

                        <div class="flex gap-4">
                            @if ($perjanjianBersama)
                                {{-- Jika sudah ada Perjanjian Bersama, tampilkan button Lihat saja --}}
                                <a href="{{ route('dokumen.perjanjian-bersama.show', $perjanjianBersama->perjanjian_bersama_id) }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded shadow font-semibold transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    Lihat Perjanjian Bersama
                                </a>
                            @elseif ($anjuran)
                                {{-- Jika sudah ada Anjuran, tampilkan button Lihat saja --}}
                                <a href="{{ route('dokumen.anjuran.show', $anjuran->anjuran_id) }}"
                                    class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded shadow font-semibold transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    Lihat Anjuran
                                </a>
                            @else
                                {{-- Jika belum ada dokumen sama sekali, tampilkan pilihan untuk membuat salah satu --}}
                                <div class="flex gap-4">
                                    <a href="{{ route('dokumen.perjanjian-bersama.create', $dokumen_hi_id) }}"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded shadow font-semibold transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        Buat Perjanjian Bersama
                                    </a>

                                    <span class="inline-flex items-center px-4 py-2 text-gray-500">
                                        <span class="text-sm">atau</span>
                                    </span>

                                    <a href="{{ route('dokumen.anjuran.create', $dokumen_hi_id) }}"
                                        class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded shadow font-semibold transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                            </path>
                                        </svg>
                                        Buat Anjuran
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="relative">
                    <div class="pointer-events-none select-none absolute inset-0 flex items-center justify-center z-50"
                        style="opacity:0.12; font-size:5rem; font-weight:bold; color:#1e293b; transform:rotate(-20deg);">
                        DRAFT
                    </div>
                    <div class="p-6 text-gray-900">
                        <!-- Header -->
                        <div class="text-center mb-8">
                            <h2 class="text-2xl font-bold mb-2">RISALAH {{ strtoupper($risalah->jenis_risalah) }}</h2>
                        </div>

                        <!-- Pembuka -->
                        <div class="mb-6 text-gray-700">
                            <p>Pada hari ini tanggal
                                {{ $risalah->tanggal_perundingan ? $risalah->tanggal_perundingan->translatedFormat('d F Y') : '-' }}
                                telah dilaksanakan
                                {{ $risalah->jenis_risalah === 'klarifikasi' ? 'klarifikasi' : ($risalah->jenis_risalah === 'mediasi' ? 'mediasi' : 'penyelesaian') }}
                                antara:</p>
                        </div>

                        <!-- Data Para Pihak -->
                        <div class="grid md:grid-cols-2 gap-8 mb-8">
                            <!-- Pihak Pengusaha -->
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h3 class="font-semibold text-lg mb-4 text-gray-800">Pihak Pengusaha:</h3>
                                <div class="space-y-2">
                                    <p><span class="font-medium">Perusahaan:</span> {{ $risalah->nama_perusahaan }}</p>
                                    <p><span class="font-medium">Jenis Usaha:</span> {{ $risalah->jenis_usaha }}</p>
                                    <p><span class="font-medium">Alamat:</span> {{ $risalah->alamat_perusahaan }}</p>
                                </div>
                            </div>

                            <!-- Pihak Pekerja -->
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h3 class="font-semibold text-lg mb-4 text-gray-800">Pihak Pekerja/Buruh/SP/SB:</h3>
                                <div class="space-y-2">
                                    <p><span class="font-medium">Nama:</span> {{ $risalah->nama_pekerja }}</p>
                                    <p><span class="font-medium">Alamat:</span> {{ $risalah->alamat_pekerja }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Perundingan -->
                        <div class="mb-8">
                            <h3 class="font-semibold text-lg mb-4 text-gray-800">Informasi Perundingan:</h3>
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <p><span class="font-medium">Tanggal:</span>
                                            {{ $risalah->tanggal_perundingan ? $risalah->tanggal_perundingan->translatedFormat('d F Y') : '-' }}
                                        </p>
                                    </div>
                                    <div>
                                        <p><span class="font-medium">Tempat:</span> {{ $risalah->tempat_perundingan }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pokok Masalah -->
                        <div class="mb-8">
                            <h3 class="font-semibold text-lg mb-4 text-gray-800">Pokok Masalah:</h3>
                            <div class="bg-gray-50 p-6 rounded-lg whitespace-pre-line text-gray-700">
                                {!! nl2br(e($risalah->pokok_masalah ?? '-')) !!}
                            </div>
                        </div>

                        <!-- Pendapat Para Pihak -->
                        <div class="grid md:grid-cols-2 gap-8 mb-8">
                            <div>
                                <h3 class="font-semibold text-lg mb-4 text-gray-800">Pendapat Pekerja:</h3>
                                <div class="bg-gray-50 p-6 rounded-lg whitespace-pre-line text-gray-700">
                                    {!! nl2br(e($risalah->pendapat_pekerja)) !!}
                                </div>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg mb-4 text-gray-800">Pendapat Pengusaha:</h3>
                                <div class="bg-gray-50 p-6 rounded-lg whitespace-pre-line text-gray-700">
                                    {!! nl2br(e($risalah->pendapat_pengusaha)) !!}
                                </div>
                            </div>
                        </div>

                        @if ($risalah->jenis_risalah === 'klarifikasi' && $detail)
                            <!-- Arahan Mediator -->
                            <div class="mb-8">
                                <h3 class="font-semibold text-lg mb-4 text-gray-800">Arahan Mediator:</h3>
                                <div class="bg-gray-50 p-6 rounded-lg whitespace-pre-line text-gray-700">
                                    {!! nl2br(e($detail->arahan_mediator ?? '-')) !!}
                                </div>
                            </div>

                            <!-- Kesimpulan Klarifikasi -->
                            <div class="mb-8">
                                <h3 class="font-semibold text-lg mb-4 text-gray-800">Kesimpulan Klarifikasi:</h3>
                                <div class="bg-gray-50 p-6 rounded-lg">
                                    <p class="text-gray-700">
                                        @if ($detail->kesimpulan_klarifikasi === 'bipartit_lagi')
                                            Perundingan Bipartit
                                        @elseif($detail->kesimpulan_klarifikasi === 'lanjut_ke_tahap_mediasi')
                                            Lanjut ke Tahap Mediasi
                                        @else
                                            {{ $detail->kesimpulan_klarifikasi ?? '-' }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endif

                        @if ($risalah->jenis_risalah === 'mediasi' && $detail)
                            <!-- Informasi Sidang -->
                            <div class="mb-8">
                                <h3 class="font-semibold text-lg mb-4 text-gray-800">Informasi Sidang:</h3>
                                <div class="bg-gray-50 p-6 rounded-lg">
                                    <div class="grid md:grid-cols-2 gap-4">
                                        <div>
                                            <p><span class="font-medium">Sidang Ke:</span>
                                                {{ $detail->sidang_ke ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p><span class="font-medium">Status:</span>
                                                @if ($detail->status_sidang === 'selesai')
                                                    Selesai
                                                @elseif($detail->status_sidang === 'lanjut_sidang_berikutnya')
                                                    Lanjut Sidang Berikutnya
                                                @else
                                                    {{ $detail->status_sidang ?? '-' }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Ringkasan Pembahasan -->
                            <div class="mb-8">
                                <h3 class="font-semibold text-lg mb-4 text-gray-800">Ringkasan Pembahasan:</h3>
                                <div class="bg-gray-50 p-6 rounded-lg whitespace-pre-line text-gray-700">
                                    {!! nl2br(e($detail->ringkasan_pembahasan ?? '-')) !!}
                                </div>
                            </div>

                            <!-- Kesepakatan Sementara -->
                            <div class="mb-8">
                                <h3 class="font-semibold text-lg mb-4 text-gray-800">Kesepakatan Sementara:</h3>
                                <div class="bg-gray-50 p-6 rounded-lg whitespace-pre-line text-gray-700">
                                    {!! nl2br(e($detail->kesepakatan_sementara ?? '-')) !!}
                                </div>
                            </div>

                            <!-- Ketidaksepakatan Sementara -->
                            <div class="mb-8">
                                <h3 class="font-semibold text-lg mb-4 text-gray-800">Ketidaksepakatan Sementara:</h3>
                                <div class="bg-gray-50 p-6 rounded-lg whitespace-pre-line text-gray-700">
                                    {!! nl2br(e($detail->ketidaksepakatan_sementara ?? '-')) !!}
                                </div>
                            </div>

                            <!-- Catatan Khusus -->
                            <div class="mb-8">
                                <h3 class="font-semibold text-lg mb-4 text-gray-800">Catatan Khusus:</h3>
                                <div class="bg-gray-50 p-6 rounded-lg whitespace-pre-line text-gray-700">
                                    {!! nl2br(e($detail->catatan_khusus ?? '-')) !!}
                                </div>
                            </div>

                            <!-- Rekomendasi Mediator -->
                            <div class="mb-8">
                                <h3 class="font-semibold text-lg mb-4 text-gray-800">Rekomendasi Mediator:</h3>
                                <div class="bg-gray-50 p-6 rounded-lg whitespace-pre-line text-gray-700">
                                    {!! nl2br(e($detail->rekomendasi_mediator ?? '-')) !!}
                                </div>
                            </div>
                        @endif

                        @if ($risalah->jenis_risalah === 'penyelesaian' && $detail)
                            <!-- Kesimpulan Penyelesaian -->
                            <div class="mb-8">
                                <h3 class="font-semibold text-lg mb-4 text-gray-800">Kesimpulan Penyelesaian:</h3>
                                <div class="bg-gray-50 p-6 rounded-lg whitespace-pre-line text-gray-700">
                                    {!! nl2br(e($detail->kesimpulan_penyelesaian ?? '-')) !!}
                                </div>
                            </div>
                        @endif

                        <!-- Tanda Tangan -->
                        <div class="text-center">
                            <p class="font-medium mb-2">Mediator Hubungan Industrial,</p>
                            <p class="font-medium mb-20">&nbsp;</p>
                            @php
                                $mediator = null;
                                if ($risalah->jadwal && $risalah->jadwal->mediator) {
                                    $mediator = $risalah->jadwal->mediator;
                                }
                            @endphp
                            <p class="font-medium">{{ $mediator ? $mediator->nama_mediator : '-' }}</p>
                            <p class="text-gray-600">NIP. {{ $mediator ? $mediator->nip : '-' }}</p>
                        </div>

                        {{-- TOMBOL AKSI --}}
                        <div class="mt-8 flex gap-4">
                            @if ($risalah->jenis_risalah === 'mediasi' && $detail)
                                @php
                                    $sidangKe = $detail->sidang_ke ?? 1;
                                    $statusSidang = $detail->status_sidang ?? 'lanjut_sidang_berikutnya';
                                    $jadwal = optional($risalah->jadwal);
                                    $pengaduanId = optional($jadwal)->pengaduan_id;
                                    $jadwalId = optional($jadwal)->jadwal_id;

                                    $hasNextSchedule = false;
                                    $nextSchedule = null;
                                    if ($pengaduanId && $sidangKe < 3) {
                                        $nextSchedule = optional($jadwal->pengaduan)
                                            ->jadwal()
                                            ->where('jenis_jadwal', 'mediasi')
                                            ->where('sidang_ke', $sidangKe + 1)
                                            ->first();
                                        $hasNextSchedule = $nextSchedule !== null;
                                    }
                                @endphp
                                @if ($statusSidang === 'lanjut_sidang_berikutnya' && $sidangKe < 3)
                                    @if (!$hasNextSchedule)
                                        <a href="{{ route('jadwal.create', ['pengaduan_id' => $pengaduanId, 'sidang_ke' => $sidangKe + 1]) }}"
                                            class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded shadow font-semibold transition">
                                            <span>➕</span> Lanjut ke Mediasi Berikutnya (Sidang ke-{{ $sidangKe + 1 }})
                                        </a>
                                        <a href="{{ route('risalah.create', [$jadwalId, 'penyelesaian']) }}"
                                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded shadow font-semibold transition">
                                            <span>📄</span> Selesai Mediasi
                                        </a>
                                    @else
                                        <a href="{{ route('jadwal.show', $nextSchedule->jadwal_id) }}"
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded shadow font-semibold transition">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg> Lihat Jadwal Sidang ke-{{ $sidangKe + 1 }}
                                        </a>
                                    @endif
                                @elseif ($statusSidang === 'selesai' || ($sidangKe == 3 && $statusSidang === 'lanjut_sidang_berikutnya'))
                                    <a href="{{ route('risalah.create', [$jadwalId, 'penyelesaian']) }}"
                                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded shadow font-semibold transition">
                                        <span>📄</span> Buat Risalah Penyelesaian
                                    </a>
                                @endif
                            @endif


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
