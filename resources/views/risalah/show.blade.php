<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Risalah') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Alert untuk status kasus berdasarkan hasil klarifikasi --}}
            @if ($risalah->jenis_risalah === 'klarifikasi' && isset($detail->kesimpulan_klarifikasi))
                @if ($detail->kesimpulan_klarifikasi === 'bipartit_lagi')
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">
                                    Kasus akan diselesaikan melalui perundingan bipartit. Status kasus akan diubah
                                    menjadi selesai.
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif ($detail->kesimpulan_klarifikasi === 'lanjut_ke_tahap_mediasi')
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    Kasus akan dilanjutkan ke tahap mediasi. Silakan buat jadwal mediasi untuk
                                    melanjutkan proses.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            <div class="bg-white rounded-lg shadow-sm p-8">
                <div class="text-center mb-8">
                    <h3 class="text-lg font-bold uppercase tracking-wide">RISALAH {{ ucfirst($risalah->jenis_risalah) }}
                        PERSELISIHAN<br>HUBUNGAN INDUSTRIAL</h3>
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
                    @if ($risalah->jenis_risalah === 'klarifikasi')
                        <div class="flex">
                            <div class="w-8">10.</div>
                            <div class="w-64">Arahan Mediator</div>
                            <div class="mx-2">:</div>
                            <div class="flex-1">{{ $detail->arahan_mediator ?? '-' }}</div>
                        </div>
                        <div class="flex">
                            <div class="w-8">11.</div>
                            <div class="w-64">Kesimpulan atau Hasil Klarifikasi</div>
                            <div class="mx-2">:</div>
                            <div class="flex-1">
                                @if (($detail->kesimpulan_klarifikasi ?? null) === 'bipartit_lagi')
                                    Perundingan Bipartit
                                @elseif(($detail->kesimpulan_klarifikasi ?? null) === 'lanjut_ke_tahap_mediasi')
                                    Lanjut ke Tahap Mediasi
                                @else
                                    {{ $detail->kesimpulan_klarifikasi ?? '-' }}
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
                    @endif
                    @if ($risalah->jenis_risalah === 'penyelesaian')
                        <div class="flex">
                            <div class="w-8">10.</div>
                            <div class="w-64">Kesimpulan atau Hasil Perundingan</div>
                            <div class="mx-2">:</div>
                            <div class="flex-1">{{ $detail->kesimpulan_penyelesaian ?? '-' }}</div>
                        </div>
                    @endif
                </div>
                <div class="mt-12 flex justify-end">
                    <div class="text-left">
                        <div class="mb-2">Muara Bungo,
                            {{ \Carbon\Carbon::parse($risalah->tanggal_perundingan)->translatedFormat('d F Y') }}</div>
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

                    @if ($risalah->jenis_risalah === 'klarifikasi' && isset($detail->kesimpulan_klarifikasi))
                        @if ($detail->kesimpulan_klarifikasi === 'lanjut_ke_tahap_mediasi')
                            {{-- Cek apakah sudah ada jadwal mediasi untuk kasus ini --}}
                            @if (!$risalah->jadwal->pengaduan->hasActiveMediasiSchedule())
                                <a href="{{ route('jadwal.create', ['pengaduan_id' => $risalah->jadwal->pengaduan_id, 'jenis' => 'mediasi', 'sidang_ke' => '1']) }}"
                                    class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded shadow font-semibold transition">
                                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Buat Jadwal Mediasi
                                </a>
                            @else
                                <a href="{{ route('jadwal.show', $risalah->jadwal->pengaduan->getLatestMediasiSchedule()->jadwal_id) }}"
                                    class="inline-block bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded shadow font-semibold transition">
                                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    Lihat Jadwal Mediasi
                                </a>
                            @endif
                        @endif
                    @endif

                    @if ($risalah->jenis_risalah === 'penyelesaian' && $dokumen_hi_id)
                        @if ($perjanjianBersama)
                            <a href="{{ route('dokumen.perjanjian-bersama.show', $perjanjianBersama->perjanjian_bersama_id) }}"
                                class="inline-block bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded shadow font-semibold transition">Lihat
                                Perjanjian Bersama</a>
                        @else
                            <a href="{{ route('dokumen.perjanjian-bersama.create', ['dokumen_hi_id' => $dokumen_hi_id]) }}"
                                class="inline-block bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded shadow font-semibold transition">Buat
                                Perjanjian Bersama</a>
                        @endif

                        @if ($anjuran)
                            <a href="{{ route('dokumen.anjuran.show', $anjuran->anjuran_id) }}"
                                class="inline-block bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded shadow font-semibold transition">Lihat
                                Anjuran</a>
                        @else
                            <a href="{{ route('dokumen.anjuran.create', ['dokumen_hi_id' => $dokumen_hi_id]) }}"
                                class="inline-block bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded shadow font-semibold transition">Buat
                                Anjuran</a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
