<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detail Anjuran</title>
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

                            <!-- Tanda Tangan -->
                            <div class="grid md:grid-cols-2 gap-8">
                                <div class="text-center">
                                    <p class="font-medium mb-2">Mengetahui</p>
                                    <p class="font-medium mb-20">Kepala Dinas,</p>
                                    <p class="font-medium">
                                        ({{ $anjuran->kepalaDinas->nama_kepala_dinas ?? '-' }})
                                    </p>
                                    <p class="text-gray-600">NIP. {{ $anjuran->kepalaDinas->nip ?? '-' }}
                                    </p>
                                </div>
                                <div class="text-center">
                                    <p class="font-medium mb-2">Mediator Hubungan Industrial,</p>
                                    <p class="font-medium mb-20">&nbsp;</p>
                                    <p class="font-medium">
                                        ({{ $anjuran->dokumenHI->pengaduan->mediator->nama_mediator ?? '-' }})
                                    </p>
                                    <p class="text-gray-600">NIP.
                                        {{ $anjuran->dokumenHI->pengaduan->mediator->nip ?? '-' }}
                                    </p>
                                </div>
                            </div>
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
</body>

</html>
