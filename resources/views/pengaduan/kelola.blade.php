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
