@props(['risalah'])

<div class="bg-white p-6 rounded-lg border">
    <h3 class="text-lg font-semibold mb-4">Preview Risalah {{ ucfirst($risalah->jenis_risalah) }}</h3>

    <div class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nomor Risalah</label>
                <p class="mt-1 text-sm text-gray-900">{{ $risalah->risalah_id }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Jenis Risalah</label>
                <p class="mt-1 text-sm text-gray-900">{{ ucfirst($risalah->jenis_risalah) }}</p>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Nama Perusahaan</label>
            <p class="mt-1 text-sm text-gray-900">{{ $risalah->nama_perusahaan }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Jenis Usaha</label>
            <p class="mt-1 text-sm text-gray-900">{{ $risalah->jenis_usaha }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Alamat Perusahaan</label>
            <p class="mt-1 text-sm text-gray-900">{{ $risalah->alamat_perusahaan }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Nama Pekerja</label>
            <p class="mt-1 text-sm text-gray-900">{{ $risalah->nama_pekerja }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Alamat Pekerja</label>
            <p class="mt-1 text-sm text-gray-900">{{ $risalah->alamat_pekerja }}</p>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Perundingan</label>
                <p class="mt-1 text-sm text-gray-900">
                    {{ $risalah->tanggal_perundingan ? $risalah->tanggal_perundingan->format('d/m/Y') : '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tempat Perundingan</label>
                <p class="mt-1 text-sm text-gray-900">{{ $risalah->tempat_perundingan }}</p>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Pokok Masalah</label>
            <p class="mt-1 text-sm text-gray-900">{{ $risalah->pokok_masalah ?? '-' }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Keterangan/Pendapat Pekerja</label>
            <p class="mt-1 text-sm text-gray-900">{{ $risalah->pendapat_pekerja }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Keterangan/Pendapat Pengusaha</label>
            <p class="mt-1 text-sm text-gray-900">{{ $risalah->pendapat_pengusaha }}</p>
        </div>

        @if ($risalah->jenis_risalah === 'klarifikasi' && $detail)
            <div>
                <label class="block text-sm font-medium text-gray-700">Arahan Mediator</label>
                <p class="mt-1 text-sm text-gray-900">{{ $detail->arahan_mediator ?? '-' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Kesimpulan Klarifikasi</label>
                <p class="mt-1 text-sm text-gray-900">
                    @if ($detail->kesimpulan_klarifikasi === 'bipartit_lagi')
                        Perundingan Bipartit
                    @elseif($detail->kesimpulan_klarifikasi === 'lanjut_ke_tahap_mediasi')
                        Lanjut ke Tahap Mediasi
                    @else
                        {{ $detail->kesimpulan_klarifikasi ?? '-' }}
                    @endif
                </p>
            </div>
        @endif

        @if ($risalah->jenis_risalah === 'mediasi' && $detail)
            <div>
                <label class="block text-sm font-medium text-gray-700">Sidang Ke</label>
                <p class="mt-1 text-sm text-gray-900">{{ $detail->sidang_ke ?? '-' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Ringkasan Pembahasan</label>
                <p class="mt-1 text-sm text-gray-900">{{ $detail->ringkasan_pembahasan ?? '-' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Kesepakatan Sementara</label>
                <p class="mt-1 text-sm text-gray-900">{{ $detail->kesepakatan_sementara ?? '-' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Ketidaksepakatan Sementara</label>
                <p class="mt-1 text-sm text-gray-900">{{ $detail->ketidaksepakatan_sementara ?? '-' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Catatan Khusus</label>
                <p class="mt-1 text-sm text-gray-900">{{ $detail->catatan_khusus ?? '-' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Rekomendasi Mediator</label>
                <p class="mt-1 text-sm text-gray-900">{{ $detail->rekomendasi_mediator ?? '-' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Status Sidang</label>
                <p class="mt-1 text-sm text-gray-900">
                    @if ($detail->status_sidang === 'selesai')
                        Selesai
                    @elseif($detail->status_sidang === 'lanjut_sidang_berikutnya')
                        Lanjut Sidang Berikutnya
                    @else
                        {{ $detail->status_sidang ?? '-' }}
                    @endif
                </p>
            </div>
        @endif

        @if ($risalah->jenis_risalah === 'penyelesaian' && $detail)
            <div>
                <label class="block text-sm font-medium text-gray-700">Kesimpulan Penyelesaian</label>
                <p class="mt-1 text-sm text-gray-900">{{ $detail->kesimpulan_penyelesaian ?? '-' }}</p>
            </div>
        @endif
    </div>
</div>
