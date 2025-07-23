@props(['id' => 'document-preview-modal', 'title' => 'Preview Dokumen'])

<div id="{{ $id }}" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog"
    aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div
            class="fixed z-50 left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-lg overflow-y-auto shadow-xl transform transition-all sm:max-w-4xl w-full max-h-screen">
            <!-- Tombol Close X -->
            <button type="button" onclick="closePreviewModal('{{ $id }}')"
                class="absolute right-4 top-4 text-gray-400 hover:text-gray-700 focus:outline-none" aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div>
                    <div class="mt-3 text-center w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            {{ $title }}
                        </h3>
                        <div class="mt-4">
                            <!-- Document preview area -->
                            <div class="bg-gray-50 p-4 rounded-lg min-h-[500px] overflow-y-auto">
                                {{ $slot }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                {{ $actions ?? '' }}
                <button type="button" onclick="closePreviewModal('{{ $id }}')"
                    class="w-full sm:w-auto inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Tutup
                </button>
            </div>
            @stack('scripts')
        </div>
    </div>
</div>
