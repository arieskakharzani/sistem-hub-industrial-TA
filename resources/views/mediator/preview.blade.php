<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview SK Mediator - SIPPPHI</title>
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
                    Preview SK Mediator
                </h2>
                <div class="flex space-x-2">
                    <a href="{{ route('kepala-dinas.mediator.approval.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm">
                        ← Kembali
                    </a>
                </div>
            </div>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Mediator Info Card -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Mediator</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $mediator->nama_mediator }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">NIP</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $mediator->nip }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $mediator->user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <p class="mt-1 text-sm text-gray-900">
                                @if ($mediator->status === 'pending')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3"></circle>
                                        </svg>
                                        Menunggu Approval
                                    </span>
                                @elseif($mediator->status === 'approved')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3"></circle>
                                        </svg>
                                        Disetujui
                                    </span>
                                @elseif($mediator->status === 'rejected')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3"></circle>
                                        </svg>
                                        Ditolak
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Registrasi</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $mediator->created_at->format('d F Y, H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">File SK</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $mediator->sk_file_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ukuran File</label>
                            <p class="mt-1 text-sm text-gray-900">{{ number_format($mediator->sk_file_size / 1024, 2) }}
                                KB</p>
                        </div>
                    </div>
                </div>

                <!-- SK Preview Card -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Preview Dokumen SK</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('kepala-dinas.mediator.download-sk', $mediator->mediator_id) }}"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm">
                                📥 Download SK
                            </a>
                        </div>
                    </div>

                    <!-- PDF Preview -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        @if ($mediator->sk_file_path && Storage::disk('public')->exists($mediator->sk_file_path))
                            <iframe
                                src="{{ Storage::disk('public')->url($mediator->sk_file_path) }}#toolbar=0&navpanes=0&scrollbar=0"
                                width="100%" height="600px" class="border-0">
                                <p>Browser Anda tidak mendukung preview PDF.
                                    <a href="{{ route('kepala-dinas.mediator.download-sk', $mediator->mediator_id) }}"
                                        class="text-blue-600 hover:text-blue-800">Klik di sini untuk download</a>
                                </p>
                            </iframe>
                        @else
                            <div class="flex items-center justify-center h-64 bg-gray-100">
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">File SK tidak ditemukan</h3>
                                    <p class="mt-1 text-sm text-gray-500">File SK mungkin telah dihapus atau tidak
                                        tersedia.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Tindakan</h3>
                    @if ($mediator->status === 'pending')
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <form method="POST"
                                action="{{ route('kepala-dinas.mediator.approve', $mediator->mediator_id) }}"
                                class="sm:w-48">
                                @csrf
                                <button type="submit"
                                    onclick="return confirm('Apakah Anda yakin ingin menyetujui registrasi mediator ini?')"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Setujui Mediator
                                </button>
                            </form>

                            <button onclick="openRejectModal()"
                                class="sm:w-48 w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Tolak Mediator
                            </button>
                        </div>
                    @elseif($mediator->status === 'approved')
                        <div class="text-center">
                            <div class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Mediator telah disetujui
                            </div>
                            @if ($mediator->approved_at)
                                <p class="mt-2 text-sm text-gray-600">
                                    Disetujui pada: {{ $mediator->approved_at->format('d F Y, H:i') }}
                                </p>
                            @endif
                        </div>
                    @elseif($mediator->status === 'rejected')
                        <div class="text-center">
                            <div class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 rounded-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Mediator telah ditolak
                            </div>
                            @if ($mediator->rejection_date)
                                <p class="mt-2 text-sm text-gray-600">
                                    Ditolak pada: {{ $mediator->rejection_date->format('d F Y, H:i') }}
                                </p>
                            @endif
                            @if ($mediator->rejection_reason)
                                <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                    <p class="text-sm font-medium text-red-800">Alasan Penolakan:</p>
                                    <p class="text-sm text-red-700 mt-1">{{ $mediator->rejection_reason }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </x-app-layout>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Tolak Mediator</h3>
                </div>
                <div class="px-6 py-4">
                    <p class="text-sm text-gray-600 mb-4">Alasan penolakan:</p>
                    <form id="rejectForm" method="POST"
                        action="{{ route('kepala-dinas.mediator.reject', $mediator->mediator_id) }}">
                        @csrf
                        <textarea name="rejection_reason" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                            placeholder="Masukkan alasan penolakan..." required></textarea>
                    </form>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                    <button onclick="closeRejectModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Batal
                    </button>
                    <button onclick="submitReject()"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700">
                        Tolak
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openRejectModal() {
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }

        function submitReject() {
            document.getElementById('rejectForm').submit();
        }
    </script>
</body>

</html>
