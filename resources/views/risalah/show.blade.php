<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detail Risalah {{ ucfirst($risalah->jenis_risalah) }}</title>

</head>

<body>
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Risalah Klarifikasi Perselisihan Hubungan Industrial
            </h2>
        </x-slot>
        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-sm p-8">
                    <div class="text-center mb-8">
                        <h3 class="text-lg font-bold uppercase tracking-wide">RISALAH KLARIFIKASI
                            PERSELISIHAN<br>HUBUNGAN
                            INDUSTRIAL</h3>
                    </div>
                    <div class="space-y-2 text-base">
                        <div class="flex">
                            <div class="w-8">1.</div>
                            <div class="w-64">Nama Perusahaan</div>
                            <div class="mx-2">:</div>
                            <div class="flex-1">{{ $risalah->nama_perusahaan }}</div>
                        </div>
                        <div class="flex">
                            <div class="w-8">2.</div>
                            <div class="w-64">Jenis Usaha</div>
                            <div class="mx-2">:</div>
                            <div class="flex-1">{{ $risalah->jenis_usaha }}</div>
                        </div>
                        <div class="flex">
                            <div class="w-8">3.</div>
                            <div class="w-64">Alamat Perusahaan</div>
                            <div class="mx-2">:</div>
                            <div class="flex-1">{{ $risalah->alamat_perusahaan }}</div>
                        </div>
                        <div class="flex">
                            <div class="w-8">4.</div>
                            <div class="w-64">Nama Pekerja/Buruh/SP/SB</div>
                            <div class="mx-2">:</div>
                            <div class="flex-1">{{ $risalah->nama_pekerja }}</div>
                        </div>
                        <div class="flex">
                            <div class="w-8">5.</div>
                            <div class="w-64">Alamat Pekerja/Buruh/SP/SB</div>
                            <div class="mx-2">:</div>
                            <div class="flex-1">{{ $risalah->alamat_pekerja }}</div>
                        </div>
                        <div class="flex">
                            <div class="w-8">6.</div>
                            <div class="w-64">Tanggal dan Tempat Perundingan</div>
                            <div class="mx-2">:</div>
                            <div class="flex-1">{{ $risalah->tanggal_perundingan }}, {{ $risalah->tempat_perundingan }}
                            </div>
                        </div>
                        <div class="flex mt-4">
                            <div class="w-8">7.</div>
                            <div class="w-64">Pokok Masalah/Alasan Perselisihan</div>
                            <div class="mx-2">:</div>
                            <div class="flex-1">{{ $risalah->pokok_masalah }}</div>
                        </div>
                        <div class="flex">
                            <div class="w-8">8.</div>
                            <div class="w-64">Keterangan/Pendapat Pekerja/Buruh/SP/SB</div>
                            <div class="mx-2">:</div>
                            <div class="flex-1">{{ $risalah->pendapat_pekerja }}</div>
                        </div>
                        <div class="flex">
                            <div class="w-8">9.</div>
                            <div class="w-64">Keterangan/Pendapat Pengusaha</div>
                            <div class="mx-2">:</div>
                            <div class="flex-1">{{ $risalah->pendapat_pengusaha }}</div>
                        </div>
                        <div class="flex">
                            <div class="w-8">10.</div>
                            <div class="w-64">Arahan Mediator</div>
                            <div class="mx-2">:</div>
                            <div class="flex-1">{{ $risalah->arahan_mediator }}</div>
                        </div>
                        <div class="flex">
                            <div class="w-8">11.</div>
                            <div class="w-64">Kesimpulan atau Hasil Klarifikasi</div>
                            <div class="mx-2">:</div>
                            <div class="flex-1">
                                @if ($risalah->kesimpulan_klarifikasi === 'bipartit_lagi')
                                    Perundingan Bipartit
                                @elseif($risalah->kesimpulan_klarifikasi === 'lanjut_ke_tahap_mediasi')
                                    Lanjut ke Tahap Mediasi
                                @else
                                    {{ $risalah->kesimpulan_klarifikasi }}
                                @endif
                            </div>
                        </div>
                        <div class="flex">
                            <div class="w-8"></div>
                            <div class="w-64"></div>
                            <div class="mx-2"></div>
                            <div class="flex-1 text-xs text-gray-600 mt-2">Keterangan: dalam membuat Kesimpulan atau
                                hasil
                                klarifikasi agar ditegaskan penyelesaian perselisihannya. Ada 3 alternatif, yaitu a)
                                sepakat
                                untuk melakukan perundingan bipartit; atau b) sepakat akan melanjutkan penyelesaian
                                melalui
                                mediasi dengan hasil perjanjian bersama; atau c) sepakat akan melanjutkan penyelesaian
                                melalui mediasi dengan hasil anjuran.</div>
                        </div>
                    </div>
                    <div class="mt-12 flex justify-end">
                        <div class="text-left">
                            <div class="mb-2">Muara Bungo,
                                {{ \Carbon\Carbon::parse($risalah->tanggal_perundingan)->translatedFormat('d F Y') }}
                            </div>
                            <div class="font-semibold">Mediator Hubungan Industrial,</div>
                            <div class="mb-14"></div>
                            <div class="mt-2">{{ $risalah->jadwal->mediator->nama_mediator ?? '-' }}</div>
                            <div class="text-sm text-gray-600">NIP: {{ $risalah->jadwal->mediator->nip ?? '-' }}</div>
                            <div class="mt-12">&nbsp;</div>
                        </div>
                    </div>
                    <div class="mt-8 flex gap-4">
                        <a href="{{ route('risalah.edit', $risalah) }}"
                            class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow font-semibold transition">Edit</a>
                        <a href="{{ route('risalah.pdf', $risalah) }}"
                            class="inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow font-semibold transition"
                            target="_blank">Cetak PDF</a>
                        <a href="{{ url()->previous() }}"
                            class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded shadow font-semibold transition">&larr;
                            Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>

</body>

</html>
<
