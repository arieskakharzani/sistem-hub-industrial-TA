<div class="bg-white rounded-lg shadow-sm p-6 mb-8">
    {{-- <h4 class="font-semibold mb-4">Dokumen Final (Sudah Ditandatangani Semua Pihak)</h4> --}}
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
                $allFinal = collect([]);
                if (isset($final['risalah'])) {
                    $allFinal = $allFinal->concat($final['risalah']);
                }
                if (isset($final['perjanjian_bersama'])) {
                    $allFinal = $allFinal->concat($final['perjanjian_bersama']);
                }
                if (isset($final['anjuran'])) {
                    $allFinal = $allFinal->concat($final['anjuran']);
                }
            @endphp
            @if ($allFinal->count() > 0)
                @foreach ($allFinal as $index => $doc)
                    <tr>
                        <td class="px-4 py-4">{{ $index + 1 }}</td>
                        <td class="px-4 py-4"></td>
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
                                Final (Semua pihak sudah tanda tangan)
                            @elseif($doc instanceof \App\Models\Risalah)
                                Final (Sudah ditandatangani Mediator)
                            @elseif($doc instanceof \App\Models\Anjuran)
                                Final (Sudah ditandatangani Kepala Dinas)
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center">
                            <button type="button" class="text-blue-600 hover:text-blue-900"
                                onclick="openFinalPreviewModal('{{ $doc->getKey() }}')">Lihat</button>
                            @if ($doc instanceof \App\Models\PerjanjianBersama)
                                <a href="{{ route('dokumen.perjanjian-bersama.pdf', ['id' => $doc->perjanjian_bersama_id]) }}"
                                    target="_blank" class="ml-2 text-green-600 hover:text-green-900">Cetak PDF</a>
                            @elseif($doc instanceof \App\Models\Risalah)
                                <a href="{{ route('risalah.pdf', ['risalah' => $doc->risalah_id]) }}" target="_blank"
                                    class="ml-2 text-green-600 hover:text-green-900">Cetak PDF</a>
                            @elseif($doc instanceof \App\Models\Anjuran)
                                <a href="{{ route('dokumen.anjuran.pdf', ['id' => $doc->anjuran_id]) }}" target="_blank"
                                    class="ml-2 text-green-600 hover:text-green-900">Cetak PDF</a>
                            @endif
                            <!-- Modal Preview Dokumen Final -->
                            <div id="final-preview-modal-{{ $doc->getKey() }}"
                                class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-40">
                                <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-3xl mx-auto relative">
                                    <button type="button"
                                        class="absolute top-2 right-2 text-gray-500 hover:text-gray-700"
                                        onclick="closeFinalPreviewModal('{{ $doc->getKey() }}')">&times;</button>
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
                                        <button type="button" onclick="closeFinalPreviewModal('{{ $doc->getKey() }}')"
                                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8" class="text-center text-gray-500 py-4">Belum ada dokumen final.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

@push('scripts')
    <script>
        function openFinalPreviewModal(id) {
            document.getElementById('final-preview-modal-' + id).classList.remove('hidden');
            document.getElementById('final-preview-modal-' + id).classList.add('flex');
        }

        function closeFinalPreviewModal(id) {
            document.getElementById('final-preview-modal-' + id).classList.add('hidden');
            document.getElementById('final-preview-modal-' + id).classList.remove('flex');
        }
    </script>
@endpush
