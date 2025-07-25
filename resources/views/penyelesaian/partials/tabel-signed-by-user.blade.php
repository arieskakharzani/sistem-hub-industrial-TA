<div class="bg-white rounded-lg shadow-sm p-6 mb-8">
    {{-- <h4 class="font-semibold mb-4">Dokumen yang Sudah Anda Tandatangani (Menunggu Pihak Lain)</h4> --}}
    <table class="min-w-full table-auto mb-6">
        <thead>
            <tr class="bg-gray-50">
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor
                    Pengaduan</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal
                    Dokumen</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Dokumen
                </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                    Perusahaan</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pekerja
                </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @php
                $allSignedByUser = collect([]);
                if (isset($signedByUser['risalah'])) {
                    $allSignedByUser = $allSignedByUser->concat($signedByUser['risalah']);
                }
                if (isset($signedByUser['perjanjian_bersama'])) {
                    $allSignedByUser = $allSignedByUser->concat($signedByUser['perjanjian_bersama']);
                }
                if (isset($signedByUser['anjuran'])) {
                    $allSignedByUser = $allSignedByUser->concat($signedByUser['anjuran']);
                }
            @endphp
            @if ($allSignedByUser->count() > 0)
                @foreach ($allSignedByUser as $index => $doc)
                    <tr>
                        <td class="px-4 py-4">{{ $index + 1 }}</td>
                        <td class="px-4 py-4">
                            @php
                                $pengaduan = null;
                                if ($doc instanceof \App\Models\Risalah) {
                                    $pengaduan = optional($doc->jadwal)->pengaduan;
                                } elseif ($doc instanceof \App\Models\PerjanjianBersama) {
                                    $pengaduan = optional(optional($doc->dokumenHI)->pengaduan);
                                } elseif ($doc instanceof \App\Models\Anjuran) {
                                    $pengaduan = optional(optional($doc->dokumenHI)->pengaduan);
                                }
                            @endphp
                            {{ optional($pengaduan)->nomor_pengaduan ?? (optional($pengaduan)->pengaduan_id ?? '-') }}
                        </td>
                        <td class="px-4 py-4">{{ $doc->created_at ? $doc->created_at->format('d/m/Y') : '-' }}</td>
                        <td class="px-4 py-4">
                            @php
                                $jenis = '';
                                if ($doc instanceof \App\Models\Risalah) {
                                    $jenis =
                                        $doc->jenis_risalah == 'klarifikasi'
                                            ? 'Risalah Klarifikasi'
                                            : 'Risalah Penyelesaian';
                                } elseif ($doc instanceof \App\Models\PerjanjianBersama) {
                                    $jenis = 'Perjanjian Bersama';
                                } elseif ($doc instanceof \App\Models\Anjuran) {
                                    $jenis = 'Anjuran';
                                }
                                $badgeClass =
                                    $jenis === 'Risalah Klarifikasi'
                                        ? 'bg-purple-100 text-purple-800'
                                        : ($jenis === 'Risalah Penyelesaian'
                                            ? 'bg-pink-100 text-pink-800'
                                            : ($jenis === 'Perjanjian Bersama'
                                                ? 'bg-blue-100 text-blue-800'
                                                : 'bg-yellow-100 text-yellow-800'));
                            @endphp
                            <span
                                class="px-2 py-1 text-xs font-medium rounded-full {{ $badgeClass }}">{{ $jenis }}</span>
                        </td>
                        <td class="px-4 py-4">
                            @php
                                $pengaduan = null;
                                if ($doc instanceof \App\Models\Risalah) {
                                    $pengaduan = optional($doc->jadwal)->pengaduan;
                                } elseif ($doc instanceof \App\Models\PerjanjianBersama) {
                                    $pengaduan = optional(optional($doc->dokumenHI)->pengaduan);
                                } elseif ($doc instanceof \App\Models\Anjuran) {
                                    $pengaduan = optional(optional($doc->dokumenHI)->pengaduan);
                                }
                            @endphp
                            {{ optional(optional($pengaduan)->terlapor)->nama ?? '-' }}
                        </td>
                        <td class="px-4 py-4">
                            {{ optional(optional($pengaduan)->pelapor)->nama ?? '-' }}
                        </td>
                        <td class="px-4 py-4">
                            @if ($doc instanceof \App\Models\PerjanjianBersama)
                                @php
                                    $status = [];
                                    if ($doc->ttd_pekerja) {
                                        $status[] = 'Pelapor ✔';
                                    }
                                    if ($doc->ttd_pengusaha) {
                                        $status[] = 'Terlapor ✔';
                                    }
                                    if ($doc->ttd_mediator) {
                                        $status[] = 'Mediator ✔';
                                    }
                                    $statusText = implode(', ', $status);
                                @endphp
                                {{ $statusText }}
                            @elseif($doc instanceof \App\Models\Risalah)
                                Ditandatangani Mediator
                            @elseif($doc instanceof \App\Models\Anjuran)
                                Ditandatangani Mediator
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center">
                            <button type="button" class="text-blue-600 hover:text-blue-900"
                                onclick="openSignedPreviewModal('{{ $doc->getKey() }}')">Lihat</button>
                            <!-- Modal Preview Dokumen Signed -->
                            <div id="signed-preview-modal-{{ $doc->getKey() }}"
                                class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-40">
                                <div
                                    class="bg-white rounded-lg shadow-lg p-6 w-full max-w-3xl mx-auto relative overflow-y-auto max-h-screen">
                                    <button type="button"
                                        class="absolute top-2 right-2 text-gray-500 hover:text-gray-700"
                                        onclick="closeSignedPreviewModal('{{ $doc->getKey() }}')">&times;</button>
                                    <h3 class="text-lg font-semibold mb-4">Preview Dokumen</h3>
                                    <div class="mb-6 overflow-y-auto max-h-[70vh]">
                                        @if ($doc instanceof \App\Models\Risalah)
                                            @include('components.document-preview.risalah', [
                                                'risalah' => $doc,
                                            ])
                                        @elseif($doc instanceof \App\Models\PerjanjianBersama)
                                            @include('components.document-preview.perjanjian-bersama', [
                                                'perjanjian' => $doc,
                                            ])
                                        @elseif($doc instanceof \App\Models\Anjuran)
                                            @include('components.document-preview.anjuran', [
                                                'anjuran' => $doc,
                                            ])
                                        @endif
                                    </div>
                                    <div class="flex justify-end gap-2">
                                        <button type="button"
                                            onclick="closeSignedPreviewModal('{{ $doc->getKey() }}')"
                                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8" class="text-center text-gray-500 py-4">Belum ada dokumen yang sudah Anda
                        tandatangani namun belum final.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

<script>
    window.openSignedPreviewModal = function(id) {
        var modal = document.getElementById('signed-preview-modal-' + id);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            modal.style.display = '';
        }
    }
    window.closeSignedPreviewModal = function(id) {
        var modal = document.getElementById('signed-preview-modal-' + id);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            modal.style.display = 'none';
        }
    }
</script>
