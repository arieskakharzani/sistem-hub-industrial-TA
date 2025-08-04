<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pengaduan Saya</title>
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

        // Auto-refresh untuk status update
        @if ($pengaduans->count() > 0)
            // Cek apakah ada pengaduan yang belum selesai
            const hasUnfinishedPengaduan = @json($pengaduans->where('status', '!=', 'selesai')->count() > 0);
            if (hasUnfinishedPengaduan) {
                setTimeout(function() {
                    window.location.reload();
                }, 30000); // Refresh setiap 30 detik jika ada pengaduan belum selesai
            }
        @endif
    </script>
</head>

<body>
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pengaduan Saya') }}
            </h2>
        </x-slot>

        <div class="max-w-6xl mx-auto px-5 py-8">
            <!-- Main Content Grid -->
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Main Content Section - Takes 2 columns -->
                <div class="lg:col-span-3">

                    @if ($pengaduans->count() == 0)
                        <!-- Empty State - Belum Ada Pengaduan -->
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                            <!-- Empty State Illustration -->
                            <div class="text-center py-16 px-8 bg-gradient-to-br from-blue-50 to-indigo-50">
                                <div class="text-8xl mb-6 opacity-70">📋</div>
                                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Belum Ada Pengaduan</h3>
                                <p class="text-gray-600 mb-8 max-w-md mx-auto leading-relaxed">
                                    Anda belum memiliki pengaduan yang aktif. Mulai dengan membuat pengaduan pertama
                                    Anda untuk menyelesaikan perselisihan hubungan industrial.
                                </p>
                                <a href="{{ route('pengaduan.create') }}"
                                    class="inline-flex items-center gap-3 bg-primary text-white px-8 py-3 rounded-xl font-medium hover:bg-primary-dark transform hover:-translate-y-1 transition-all duration-300">
                                    <span>➕</span>
                                    <span>Buat Pengaduan</span>
                                </a>
                            </div>

                            <!-- Process Steps Preview -->
                            <div class="p-8">
                                <h4 class="text-xl font-semibold text-gray-800 mb-6">Proses Mediasi - 3 Langkah Mudah
                                </h4>

                                <div class="space-y-6">
                                    <!-- Step 1 -->
                                    <div
                                        class="flex items-start gap-5 p-6 bg-gradient-to-r from-primary to-primary-light rounded-xl text-white">
                                        <div
                                            class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-lg font-bold flex-shrink-0">
                                            1</div>
                                        <div>
                                            <h5 class="text-lg font-semibold mb-2">Isi Form Pengaduan</h5>
                                            <p class="text-white text-opacity-90 text-sm">
                                                Lengkapi formulir dengan data diri, informasi perusahaan, dan detail
                                                perselisihan
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Step 2 -->
                                    <div class="flex items-start gap-5 p-6 border-2 border-gray-200 rounded-xl">
                                        <div
                                            class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-lg font-bold text-gray-500 flex-shrink-0">
                                            2</div>
                                        <div>
                                            <h5 class="text-lg font-semibold text-gray-800 mb-2">Proses Mediasi</h5>
                                            <p class="text-gray-600 text-sm">
                                                Tim mediator akan meninjau dan menjadwalkan sesi mediasi secara
                                                profesional
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Step 3 -->
                                    <div class="flex items-start gap-5 p-6 border-2 border-gray-200 rounded-xl">
                                        <div
                                            class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-lg font-bold text-gray-500 flex-shrink-0">
                                            3</div>
                                        <div>
                                            <h5 class="text-lg font-semibold text-gray-800 mb-2">Penyelesaian</h5>
                                            <p class="text-gray-600 text-sm">
                                                Hasil berupa Perjanjian Bersama atau Anjuran Tertulis sesuai kesepakatan
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Riwayat Pengaduan -->
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                            <div class="p-6 border-b border-gray-200">
                                <h3 class="text-xl font-semibold text-gray-800">Riwayat Pengaduan Saya</h3>
                                <p class="text-gray-600 mt-1">Daftar semua pengaduan yang pernah Anda ajukan</p>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                No. Pengaduan
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Perihal
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Terlapor
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tanggal
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Dokumen (Jika Selesai)
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
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $pengaduan->nomor_pengaduan ?? $pengaduan->pengaduan_id }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    <div class="max-w-xs truncate" title="{{ $pengaduan->perihal }}">
                                                        {{ $pengaduan->perihal }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $pengaduan->nama_terlapor }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $pengaduan->tanggal_laporan->format('d M Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    @php
                                                        $statusClass = match ($pengaduan->status) {
                                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                                            'proses' => 'bg-blue-100 text-blue-800',
                                                            'selesai' => 'bg-green-100 text-green-800',
                                                            default => 'bg-gray-100 text-gray-800',
                                                        };
                                                    @endphp
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                                        {{ ucfirst($pengaduan->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    @if ($pengaduan->status === 'selesai')
                                                        @php
                                                            $dokumenHI = $pengaduan->dokumenHI()->first();
                                                            $perjanjianBersama = $dokumenHI
                                                                ? $dokumenHI->perjanjianBersama()->first()
                                                                : null;
                                                            $anjuran = $dokumenHI
                                                                ? $dokumenHI->anjuran()->first()
                                                                : null;
                                                        @endphp

                                                        <div class="flex flex-wrap gap-1">
                                                            @if ($perjanjianBersama)
                                                                <a href="{{ route('dokumen.show-perjanjian-bersama', $perjanjianBersama->perjanjian_bersama_id) }}"
                                                                    class="inline-flex items-center gap-1 bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs font-medium">
                                                                    📄 PB
                                                                </a>
                                                            @endif

                                                            @if ($anjuran)
                                                                <a href="{{ route('anjuran-response.show', $anjuran->anjuran_id) }}"
                                                                    class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs font-medium">
                                                                    📋 Anjuran
                                                                </a>
                                                            @endif

                                                            @php
                                                                // Cek apakah kasus melalui mediasi atau langsung selesai di klarifikasi
                                                                $hasMediasiJadwal = $pengaduan
                                                                    ->jadwal()
                                                                    ->where('jenis_jadwal', 'mediasi')
                                                                    ->exists();
                                                                $hasKlarifikasiJadwal = $pengaduan
                                                                    ->jadwal()
                                                                    ->where('jenis_jadwal', 'klarifikasi')
                                                                    ->exists();
                                                                $hasKlarifikasiRisalah = $pengaduan
                                                                    ->jadwal()
                                                                    ->whereHas('risalah', function ($q) {
                                                                        $q->where('jenis_risalah', 'klarifikasi');
                                                                    })
                                                                    ->exists();
                                                            @endphp

                                                            @if ($hasMediasiJadwal)
                                                                <a href="{{ route('laporan.hasil-mediasi.show', $pengaduan->pengaduan_id) }}"
                                                                    class="inline-flex items-center gap-1 bg-purple-600 hover:bg-purple-700 text-white px-2 py-1 rounded text-xs font-medium">
                                                                    📊 Laporan Hasil Mediasi
                                                                </a>
                                                            @elseif($hasKlarifikasiRisalah)
                                                                <a href="{{ route(
                                                                    'risalah.show',
                                                                    $pengaduan->jadwal()->whereHas('risalah', function ($q) {
                                                                            $q->where('jenis_risalah', 'klarifikasi');
                                                                        })->first()->risalah()->where('jenis_risalah', 'klarifikasi')->first(),
                                                                ) }}"
                                                                    class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs font-medium">
                                                                    📋 Risalah Klarifikasi
                                                                </a>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <span class="text-gray-400 text-xs">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <a href="{{ route('pengaduan.show', $pengaduan->pengaduan_id) }}"
                                                        class="bg-primary hover:bg-primary-dark text-white px-3 py-1 rounded text-xs font-medium transition-colors">
                                                        Lihat Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            @if ($pengaduans->hasPages())
                                <div class="px-6 py-4 border-t border-gray-200">
                                    {{ $pengaduans->links() }}
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Information Cards -->
            <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Process Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div
                                class="w-8 h-8 bg-primary bg-opacity-10 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Proses Pengaduan</h3>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-start space-x-3">
                                <div
                                    class="w-6 h-6 bg-primary text-white rounded-full flex items-center justify-center text-xs font-bold">
                                    1</div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Submit Pengaduan</p>
                                    <p class="text-xs text-gray-500">Isi form pengaduan lengkap</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div
                                    class="w-6 h-6 bg-gray-300 text-white rounded-full flex items-center justify-center text-xs font-bold">
                                    2</div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Review Mediator</p>
                                    <p class="text-xs text-gray-500">Tim mediator meninjau pengaduan</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div
                                    class="w-6 h-6 bg-gray-300 text-white rounded-full flex items-center justify-center text-xs font-bold">
                                    3</div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Penyelesaian</p>
                                    <p class="text-xs text-gray-500">Hasil mediasi dalam bentuk PB/AT</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div
                                class="w-8 h-8 bg-primary bg-opacity-10 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Kontak Bantuan</h3>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Call Center</p>
                                    <p class="text-xs text-gray-600">(021) 1500-123</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-3 h-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z">
                                        </path>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Email Support</p>
                                    <p class="text-xs text-gray-600">mediasi@disnaker.go.id</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-orange-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-3 h-3 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Jam Layanan</p>
                                    <p class="text-xs text-gray-600">Senin-Jumat 08:00-16:00</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
</body>

</html>
