<div class="bg-white rounded-lg shadow-sm p-6 mb-8">
    @if ($dokumenSigned->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            No
                        </th>
                        <th
                            class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Jenis Dokumen
                        </th>
                        <th
                            class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Nomor
                        </th>
                        <th
                            class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th
                            class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Nama Perusahaan
                        </th>
                        <th
                            class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Nama Pekerja
                        </th>
                        <th
                            class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th
                            class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($dokumenSigned as $index => $doc)
                        @php
                            $namaPerusahaan = '-';
                            $namaPekerja = '-';
                            if ($doc instanceof \App\Models\Risalah) {
                                $namaPerusahaan = $doc->nama_perusahaan ?? '-';
                                $namaPekerja = $doc->nama_pekerja ?? '-';
                            } elseif ($doc instanceof \App\Models\PerjanjianBersama) {
                                $namaPerusahaan = $doc->perusahaan_pengusaha ?? '-';
                                $namaPekerja = $doc->nama_pekerja ?? '-';
                            } elseif ($doc instanceof \App\Models\Anjuran) {
                                $namaPerusahaan = $doc->nama_perusahaan ?? '-';
                                $namaPekerja = $doc->nama_pekerja ?? '-';
                            }
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td
                                class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 border-b border-gray-200">
                                {{ $index + 1 }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 border-b border-gray-200">
                                @if ($doc instanceof \App\Models\Risalah)
                                    Risalah {{ ucfirst($doc->jenis_risalah) }}
                                @elseif ($doc instanceof \App\Models\PerjanjianBersama)
                                    Perjanjian Bersama
                                @elseif ($doc instanceof \App\Models\Anjuran)
                                    Anjuran
                                @endif
                            </td>
                            <td
                                class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 border-b border-gray-200">
                                @if ($doc instanceof \App\Models\Risalah)
                                    {{ $doc->risalah_id }}
                                @elseif ($doc instanceof \App\Models\PerjanjianBersama)
                                    {{ $doc->nomor_perjanjian ?? '-' }}
                                @elseif ($doc instanceof \App\Models\Anjuran)
                                    {{ $doc->nomor_anjuran ?? '-' }}
                                @endif
                            </td>
                            <td
                                class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 border-b border-gray-200">
                                @if ($doc instanceof \App\Models\Risalah)
                                    {{ $doc->tanggal_perundingan ? $doc->tanggal_perundingan->format('d/m/Y') : '-' }}
                                @elseif ($doc instanceof \App\Models\PerjanjianBersama)
                                    {{ $doc->tanggal_perjanjian ? $doc->tanggal_perjanjian->format('d/m/Y') : '-' }}
                                @elseif ($doc instanceof \App\Models\Anjuran)
                                    {{ $doc->tanggal_anjuran ? $doc->tanggal_anjuran->format('d/m/Y') : '-' }}
                                @endif
                            </td>
                            <td
                                class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 border-b border-gray-200">
                                {{ $namaPerusahaan }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 border-b border-gray-200">
                                {{ $namaPekerja }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 border-b border-gray-200">
                                @if ($doc instanceof \App\Models\Risalah)
                                    @if ($doc->jenis_risalah === 'mediasi')
                                        Catatan Internal
                                    @else
                                        Final
                                    @endif
                                @elseif ($doc instanceof \App\Models\PerjanjianBersama)
                                    Final
                                @elseif ($doc instanceof \App\Models\Anjuran)
                                    Final
                                @endif
                            </td>
                            <td
                                class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 border-b border-gray-200">
                                <div class="flex space-x-2">
                                    @if ($doc instanceof \App\Models\Risalah)
                                        <a href="{{ route('risalah.show', $doc->risalah_id) }}"
                                            class="text-blue-600 hover:text-blue-900">Lihat</a>
                                        <a href="{{ route('risalah.pdf', $doc->risalah_id) }}"
                                            class="text-green-600 hover:text-green-900">PDF</a>
                                    @elseif ($doc instanceof \App\Models\PerjanjianBersama)
                                        <a href="{{ route('perjanjian-bersama.show', $doc->perjanjian_bersama_id) }}"
                                            class="text-blue-600 hover:text-blue-900">Lihat</a>
                                        <a href="{{ route('perjanjian-bersama.pdf', $doc->perjanjian_bersama_id) }}"
                                            class="text-green-600 hover:text-green-900">PDF</a>
                                    @elseif ($doc instanceof \App\Models\Anjuran)
                                        <a href="{{ route('anjuran.show', $doc->anjuran_id) }}"
                                            class="text-blue-600 hover:text-blue-900">Lihat</a>
                                        <a href="{{ route('anjuran.pdf', $doc->anjuran_id) }}"
                                            class="text-green-600 hover:text-green-900">PDF</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-8">
            <p class="text-gray-500">Tidak ada dokumen final.</p>
        </div>
    @endif
</div>
