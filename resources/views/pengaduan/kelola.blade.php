{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kelola Pengaduan - Mediator</title>
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Kelola Pengaduan') }}
                </h2>
            </x-slot>

            <!-- Success Alert -->
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                    role="alert">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Pengaduan</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-full">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Menunggu</p>
                            <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                        </div>
                        <div class="p-3 bg-yellow-50 rounded-full">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Dalam Proses</p>
                            <p class="text-3xl font-bold text-blue-600">{{ $stats['proses'] }}</p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-full">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Selesai</p>
                            <p class="text-3xl font-bold text-green-600">{{ $stats['selesai'] }}</p>
                        </div>
                        <div class="p-3 bg-green-50 rounded-full">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter and Search -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-8">
                <div class="p-6">
                    <form method="GET" action="{{ route('pengaduan.kelola') }}"
                        class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cari Pengaduan</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari berdasarkan nama pelapor, perusahaan, atau kasus..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu
                                </option>
                                <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Dalam
                                    Proses</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai
                                </option>
                            </select>
                        </div>

                        <!-- Perihal Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Perihal</label>
                            <select name="perihal"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                <option value="">Semua Perihal</option>
                                <option value="Perselisihan Hak"
                                    {{ request('perihal') == 'Perselisihan Hak' ? 'selected' : '' }}>Perselisihan Hak
                                </option>
                                <option value="Perselisihan Kepentingan"
                                    {{ request('perihal') == 'Perselisihan Kepentingan' ? 'selected' : '' }}>
                                    Perselisihan Kepentingan</option>
                                <option value="Perselisihan PHK"
                                    {{ request('perihal') == 'Perselisihan PHK' ? 'selected' : '' }}>Perselisihan PHK
                                </option>
                                <option value="Perselisihan antar SP/SB"
                                    {{ request('perihal') == 'Perselisihan antar SP/SB' ? 'selected' : '' }}>
                                    Perselisihan antar SP/SB</option>
                            </select>
                        </div>

                        <!-- Filter Buttons -->
                        <div class="md:col-span-4 flex gap-3">
                            <button type="submit"
                                class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Filter
                            </button>
                            <a href="{{ route('pengaduan.kelola') }}"
                                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Pengaduan List -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Pengaduan</h3>
                        <div class="text-sm text-gray-500">
                            Total: {{ $pengaduans->total() }} pengaduan
                        </div>
                    </div>

                    @if ($pengaduans->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Pelapor & Tanggal
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Perihal & Perusahaan
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Mediator
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($pengaduans as $pengaduan)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div
                                                            class="h-10 w-10 rounded-full bg-primary bg-opacity-10 flex items-center justify-center">
                                                            <span class="text-sm font-medium text-primary">
                                                                {{ substr($pengaduan->pelapor->nama_pelapor, 0, 2) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $pengaduan->pelapor->nama_pelapor }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $pengaduan->tanggal_laporan->format('d M Y') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $pengaduan->perihal }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $pengaduan->nama_perusahaan }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($pengaduan->status == 'pending')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        <svg class="w-2 h-2 mr-1" fill="currentColor"
                                                            viewBox="0 0 8 8">
                                                            <circle cx="4" cy="4" r="3" />
                                                        </svg>
                                                        Menunggu
                                                    </span>
                                                @elseif($pengaduan->status == 'proses')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        <svg class="w-2 h-2 mr-1" fill="currentColor"
                                                            viewBox="0 0 8 8">
                                                            <circle cx="4" cy="4" r="3" />
                                                        </svg>
                                                        Dalam Proses
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <svg class="w-2 h-2 mr-1" fill="currentColor"
                                                            viewBox="0 0 8 8">
                                                            <circle cx="4" cy="4" r="3" />
                                                        </svg>
                                                        Selesai
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if ($pengaduan->mediator)
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-6 w-6">
                                                            <div
                                                                class="h-6 w-6 rounded-full bg-green-100 flex items-center justify-center">
                                                                <svg class="w-3 h-3 text-green-600"
                                                                    fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd"
                                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                        clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <span class="ml-2">{{ $pengaduan->mediator->name }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-gray-400 italic">Belum ditugaskan</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex items-center space-x-3">
                                                    <!-- View Detail -->
                                                    <a href="{{ route('pengaduan.show-mediator', $pengaduan->id) }}"
                                                        class="text-primary hover:text-primary-dark">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                            </path>
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                            </path>
                                                        </svg>
                                                    </a>

                                                    <!-- Assign/Take Action -->
                                                    @if (!$pengaduan->mediator_id)
                                                        <form method="POST"
                                                            action="{{ route('pengaduan.assign', $pengaduan->id) }}"
                                                            class="inline">
                                                            @csrf
                                                            <button type="submit"
                                                                class="text-green-600 hover:text-green-900"
                                                                onclick="return confirm('Ambil pengaduan ini untuk ditangani?')">
                                                                <svg class="w-4 h-4" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <!-- Update Status -->
                                                    <button
                                                        onclick="openStatusModal({{ $pengaduan->id }}, '{{ $pengaduan->status }}', '{{ $pengaduan->catatan_mediator }}')"
                                                        class="text-blue-600 hover:text-blue-900">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $pengaduans->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada pengaduan</h3>
                            <p class="mt-1 text-sm text-gray-500">Belum ada pengaduan yang masuk atau sesuai dengan
                                filter yang dipilih.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Status Update Modal -->
        <div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Update Status Pengaduan</h3>
                    <form id="statusForm" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="statusSelect" name="status" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="pending">Menunggu</option>
                                <option value="proses">Dalam Proses</option>
                                <option value="selesai">Selesai</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Mediator</label>
                            <textarea id="catatanTextarea" name="catatan_mediator" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                                placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeStatusModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark">
                                Update Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-app-layout>

    <script>
        function openStatusModal(pengaduanId, currentStatus, currentCatatan) {
            document.getElementById('statusForm').action = `/pengaduan/${pengaduanId}/update-status`;
            document.getElementById('statusSelect').value = currentStatus;
            document.getElementById('catatanTextarea').value = currentCatatan || '';
            document.getElementById('statusModal').classList.remove('hidden');
        }

        function closeStatusModal() {
            document.getElementById('statusModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('statusModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeStatusModal();
            }
        });
    </script>
</body>

</html> --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kelola Pengaduan</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Pengaduan') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Daftar Pengaduan</h3>
                                <p class="text-sm text-gray-600">Kelola dan pantau semua pengaduan yang masuk</p>
                            </div>
                            <div class="flex space-x-4">
                                <select id="statusFilter" class="border border-gray-300 rounded-lg px-8 py-2 text-sm">
                                    <option value="">Semua Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="proses">Dalam Proses</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                                <select id="perihalFilter" class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
                                    <option value="">Semua Perihal</option>
                                    <option value="Perselisihan Hak">Perselisihan Hak</option>
                                    <option value="Perselisihan Kepentingan">Perselisihan Kepentingan</option>
                                    <option value="Perselisihan PHK">Perselisihan PHK</option>
                                    <option value="Perselisihan antar SP/SB">Perselisihan antar SP/SB</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    @if (isset($pengaduans) && $pengaduans->count() > 0)
                        <!-- Table with Data -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Pelapor
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Perihal
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Perusahaan
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal Laporan
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($pengaduans as $index => $pengaduan)
                                        <tr class="hover:bg-gray-50" data-status="{{ $pengaduan->status }}"
                                            data-perihal="{{ $pengaduan->perihal }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ ($pengaduans->currentPage() - 1) * $pengaduans->perPage() + $index + 1 }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $pengaduan->pelapor->nama_lengkap ?? 'N/A' }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $pengaduan->pelapor->email ?? ($pengaduan->kontak_pekerja ?? 'N/A') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900">
                                                    {{ $pengaduan->perihal }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    Masa Kerja: {{ $pengaduan->masa_kerja }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900 max-w-xs">
                                                    {{ $pengaduan->nama_perusahaan }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $pengaduan->kontak_perusahaan }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusClass = match ($pengaduan->status) {
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'proses' => 'bg-blue-100 text-blue-800',
                                                        'selesai' => 'bg-green-100 text-green-800',
                                                        default => 'bg-gray-100 text-gray-800',
                                                    };
                                                @endphp
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                                    {{ ucfirst($pengaduan->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $pengaduan->tanggal_laporan ? $pengaduan->tanggal_laporan->format('d/m/Y') : 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <!-- ✅ BENAR: Gunakan parameter pengaduan_id -->
                                                    <a href="{{ route('pengaduan.show', $pengaduan->pengaduan_id) }}"
                                                        class="text-primary hover:text-primary-dark transition-colors">
                                                        <svg class="w-4 h-4 inline mr-1" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                            </path>
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                            </path>
                                                        </svg>
                                                        Detail
                                                    </a>

                                                    @if ($pengaduan->status === 'pending')
                                                        <button
                                                            onclick="updateStatus({{ $pengaduan->pengaduan_id }}, 'proses')"
                                                            class="text-blue-600 hover:text-blue-900 transition-colors">
                                                            <svg class="w-4 h-4 inline mr-1" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                </path>
                                                            </svg>
                                                            Proses
                                                        </button>
                                                    @endif

                                                    @if ($pengaduan->status === 'proses')
                                                        <button
                                                            onclick="updateStatus({{ $pengaduan->pengaduan_id }}, 'selesai')"
                                                            class="text-green-600 hover:text-green-900 transition-colors">
                                                            <svg class="w-4 h-4 inline mr-1" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                            Selesai
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if (method_exists($pengaduans, 'links'))
                            <div class="px-6 py-4 border-t border-gray-200">
                                {{ $pengaduans->links() }}
                            </div>
                        @endif
                    @else
                        <!-- Empty State -->
                        <div class="p-12">
                            <div class="text-center">
                                <div
                                    class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>

                                <div class="max-w-md mx-auto">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum ada pengaduan</h3>
                                    <p class="text-gray-600 mb-8 leading-relaxed">
                                        Saat ini belum ada pengaduan yang perlu dikelola. Pengaduan baru akan muncul di
                                        sini.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Statistics Cards -->
                <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="p-3 bg-blue-100 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                        <path fill-rule="evenodd"
                                            d="M4 5a2 2 0 012-2v1a1 1 0 001 1h6a1 1 0 001-1V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-gray-600 text-sm">Total Pengaduan</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_kasus_saya'] ?? 0 }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="p-3 bg-yellow-100 rounded-lg">
                                    <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-gray-600 text-sm">Kasus Aktif</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $stats['kasus_aktif'] ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="p-3 bg-green-100 rounded-lg">
                                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-gray-600 text-sm">Kasus Selesai</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $stats['kasus_selesai'] ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="p-3 bg-purple-100 rounded-lg">
                                    <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-gray-600 text-sm">Laporan Hari Ini</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $stats['jadwal_hari_ini'] ?? 0 }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- JavaScript for actions and filtering -->
        <script>
            function updateStatus(pengaduanId, newStatus) {
                const statusText = {
                    'proses': 'Dalam Proses',
                    'selesai': 'Selesai'
                };

                if (confirm(`Apakah Anda yakin ingin mengubah status menjadi "${statusText[newStatus]}"?`)) {
                    // Create form and submit
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/pengaduan/${pengaduanId}/update-status`;

                    // Add CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (csrfToken) {
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = csrfToken.getAttribute('content');
                        form.appendChild(csrfInput);
                    }

                    // Add status input
                    const statusInput = document.createElement('input');
                    statusInput.type = 'hidden';
                    statusInput.name = 'status';
                    statusInput.value = newStatus;
                    form.appendChild(statusInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            }

            // Client-side filtering
            function filterTable() {
                const statusFilter = document.getElementById('statusFilter').value;
                const perihalFilter = document.getElementById('perihalFilter').value;
                const rows = document.querySelectorAll('tbody tr[data-status]');

                rows.forEach(row => {
                    const rowStatus = row.getAttribute('data-status');
                    const rowPerihal = row.getAttribute('data-perihal');

                    const statusMatch = !statusFilter || rowStatus === statusFilter;
                    const perihalMatch = !perihalFilter || rowPerihal === perihalFilter;

                    if (statusMatch && perihalMatch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            // Attach event listeners
            document.getElementById('statusFilter').addEventListener('change', filterTable);
            document.getElementById('perihalFilter').addEventListener('change', filterTable);
        </script>
    </x-app-layout>
</body>

</html>
