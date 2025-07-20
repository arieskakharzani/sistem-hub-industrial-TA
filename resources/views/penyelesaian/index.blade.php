<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Penyelesaian Hubungan Industrial') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Risalah Section -->
            @if (isset($dokumenPending['risalah']) && $dokumenPending['risalah']->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Risalah Yang Perlu Ditandatangani</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Jenis</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($dokumenPending['risalah'] as $index => $risalah)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ ucfirst($risalah->jenis_risalah) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $risalah->created_at->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Menunggu Tanda Tangan
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <button
                                                    onclick="showPreviewModal('preview-risalah-{{ $risalah->risalah_id }}')"
                                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                                    Preview & Tanda Tangan
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Preview Modal for Risalah -->
                                        <x-document-preview-modal :id="'preview-risalah-' . $risalah->risalah_id" :title="'Preview Risalah ' . ucfirst($risalah->jenis_risalah)">
                                            <x-document-preview.risalah :risalah="$risalah" />

                                            <div class="mt-8 border-t pt-4">
                                                <h4 class="font-semibold mb-4">Tanda Tangan Digital Mediator:</h4>
                                                <form id="signature-form-risalah-{{ $risalah->risalah_id }}"
                                                    action="{{ route('penyelesaian.sign-document') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="document_type" value="risalah">
                                                    <input type="hidden" name="document_id"
                                                        value="{{ $risalah->risalah_id }}">
                                                    <x-signature-pad :id="'signature-pad-risalah-' . $risalah->risalah_id" />
                                                </form>
                                            </div>

                                            <x-slot name="actions">
                                                <button type="submit"
                                                    form="signature-form-risalah-{{ $risalah->risalah_id }}"
                                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                                    Tanda Tangani Dokumen
                                                </button>
                                            </x-slot>
                                        </x-document-preview-modal>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Perjanjian Bersama Section -->
            @if (isset($dokumenPending['perjanjian_bersama']) && $dokumenPending['perjanjian_bersama']->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Perjanjian Bersama Yang Perlu Ditandatangani</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nomor Perjanjian</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($dokumenPending['perjanjian_bersama'] as $index => $perjanjian)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $perjanjian->nomor_perjanjian ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $perjanjian->created_at->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    {{ $perjanjian->getSignatureStatus() }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <button
                                                    onclick="showPreviewModal('preview-pb-{{ $perjanjian->perjanjian_bersama_id }}')"
                                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                                    Preview & Tanda Tangan
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Preview Modal for Perjanjian Bersama -->
                                        <x-document-preview-modal :id="'preview-pb-' . $perjanjian->perjanjian_bersama_id" :title="'Preview Perjanjian Bersama'">
                                            <x-document-preview.perjanjian-bersama :perjanjian="$perjanjian" />

                                            <div class="mt-8 border-t pt-4">
                                                <h4 class="font-semibold mb-4">Tanda Tangan Digital
                                                    {{ auth()->user()->active_role === 'pelapor' ? 'Pekerja' : (auth()->user()->active_role === 'terlapor' ? 'Pengusaha' : 'Mediator') }}:
                                                </h4>
                                                <form id="signature-form-pb-{{ $perjanjian->perjanjian_bersama_id }}"
                                                    action="{{ route('penyelesaian.sign-document') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="document_type"
                                                        value="perjanjian_bersama">
                                                    <input type="hidden" name="document_id"
                                                        value="{{ $perjanjian->perjanjian_bersama_id }}">
                                                    <x-signature-pad :id="'signature-pad-pb-' . $perjanjian->perjanjian_bersama_id" />
                                                </form>
                                            </div>

                                            <x-slot name="actions">
                                                <button type="submit"
                                                    form="signature-form-pb-{{ $perjanjian->perjanjian_bersama_id }}"
                                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                                    Tanda Tangani Dokumen
                                                </button>
                                            </x-slot>
                                        </x-document-preview-modal>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Anjuran Section -->
            @if (isset($dokumenPending['anjuran']) && $dokumenPending['anjuran']->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Anjuran Yang Perlu Ditandatangani</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nomor Anjuran</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($dokumenPending['anjuran'] as $index => $anjuran)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $anjuran->nomor_anjuran ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $anjuran->created_at->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    {{ $anjuran->getSignatureStatus() }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <button
                                                    onclick="showPreviewModal('preview-anjuran-{{ $anjuran->anjuran_id }}')"
                                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                                    Preview & Tanda Tangan
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Preview Modal for Anjuran -->
                                        <x-document-preview-modal :id="'preview-anjuran-' . $anjuran->anjuran_id" :title="'Preview Anjuran'">
                                            <x-document-preview.anjuran :anjuran="$anjuran" />

                                            <div class="mt-8 border-t pt-4">
                                                <h4 class="font-semibold mb-4">Tanda Tangan Digital
                                                    {{ auth()->user()->active_role === 'kepala_dinas' ? 'Kepala Dinas' : 'Mediator' }}:
                                                </h4>
                                                <form id="signature-form-anjuran-{{ $anjuran->anjuran_id }}"
                                                    action="{{ route('penyelesaian.sign-document') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="document_type" value="anjuran">
                                                    <input type="hidden" name="document_id"
                                                        value="{{ $anjuran->anjuran_id }}">
                                                    <x-signature-pad :id="'signature-pad-anjuran-' . $anjuran->anjuran_id" />
                                                </form>
                                            </div>

                                            <x-slot name="actions">
                                                <button type="submit"
                                                    form="signature-form-anjuran-{{ $anjuran->anjuran_id }}"
                                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                                    Tanda Tangani Dokumen
                                                </button>
                                            </x-slot>
                                        </x-document-preview-modal>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            @if (
                (!isset($dokumenPending['risalah']) || $dokumenPending['risalah']->count() === 0) &&
                    (!isset($dokumenPending['perjanjian_bersama']) || $dokumenPending['perjanjian_bersama']->count() === 0) &&
                    (!isset($dokumenPending['anjuran']) || $dokumenPending['anjuran']->count() === 0))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-gray-500 text-center">Tidak ada dokumen yang perlu ditandatangani saat ini.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
