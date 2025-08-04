{{-- DETAIL AKUN TERLAPOR --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Akun Terlapor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

        // Fungsi untuk menangani aktivasi/deaktivasi akun
        function handleAccountStatus(action, id) {
            console.log('handleAccountStatus called:', action, id);

            if (!confirm('Yakin ingin ' + (action === 'activate' ? 'mengaktifkan' : 'menonaktifkan') + ' akun ini?')) {
                return;
            }

            $.ajax({
                url: `/mediator/akun/${id}/${action}`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                data: {
                    _method: 'PATCH'
                },
                success: function(response) {
                    console.log('Success response:', response);
                    alert(response.message);
                    location.reload();
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseJSON);
                    console.error('Status:', xhr.status);
                    alert('Terjadi kesalahan: ' + (xhr.responseJSON?.error || xhr.responseJSON?.message ||
                        'Unknown error'));
                }
            });
        }
    </script>
</head>

<body>
    <x-app-layout>
        <x-slot name="header">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Detail Akun Terlapor') }}
                    </h2>
                    <p class="text-gray-600 text-sm mt-1">Informasi lengkap akun {{ $terlapor->nama_terlapor }}</p>
                </div>

                <div class="flex gap-3">
                    <x-secondary-button onclick="window.location.href='{{ route('mediator.akun.index') }}'">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </x-secondary-button>

                    {{-- <x-primary-button onclick="window.location.href='{{ route('mediator.akun.create') }}'">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Akun Baru
                    </x-primary-button> --}}
                </div>
            </div>
        </x-slot>

        <div class="py-6">
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <!-- Alert Messages -->
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"
                        role="alert">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg" role="alert">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Informasi Pihak yang Dilaporkan -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                                Informasi Pihak yang Dilaporkan
                            </h3>
                        </div>
                        <div class="p-6">
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nama Terlapor</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $terlapor->nama_terlapor }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <a href="mailto:{{ $terlapor->email_terlapor }}"
                                            class="text-blue-600 hover:text-blue-800">
                                            {{ $terlapor->email_terlapor }}
                                        </a>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">No. HP</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if ($terlapor->no_hp_terlapor)
                                            <a href="tel:{{ $terlapor->no_hp_terlapor }}"
                                                class="text-blue-600 hover:text-blue-800">
                                                {{ $terlapor->no_hp_terlapor }}
                                            </a>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Alamat</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $terlapor->alamat_kantor_cabang }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Status & Kontrol Akun -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Status Akun
                            </h3>
                        </div>
                        <div class="p-6">
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status Akun</dt>
                                    <dd class="mt-1">
                                        @if (isset($terlapor->user) && $terlapor->is_active && $terlapor->user->is_active)
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3"></circle>
                                                </svg>
                                                Aktif
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3"></circle>
                                                </svg>
                                                Nonaktif
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Role</dt>
                                    <dd class="mt-1">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ ucfirst($terlapor->user->role ?? 'terlapor') }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Dibuat</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $terlapor->created_at->format('d F Y, H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Terakhir Update</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $terlapor->updated_at->format('d F Y, H:i') }}</dd>
                                </div>
                            </dl>

                            <!-- Action Button -->
                            <div class="mt-6 pt-4 border-t border-gray-200">
                                @if (isset($terlapor->user) && $terlapor->is_active && $terlapor->user->is_active)
                                    <button onclick="handleAccountStatus('deactivate', '{{ $terlapor->terlapor_id }}')"
                                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636">
                                            </path>
                                        </svg>
                                        Nonaktifkan Akun
                                    </button>
                                @else
                                    <button onclick="handleAccountStatus('activate', '{{ $terlapor->terlapor_id }}')"
                                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Aktifkan Akun
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Pengaduan yang Melibatkan Terlapor -->
                @php
                    // Ambil pengaduan yang melibatkan terlapor ini (menggunakan relasi)
                    $pengaduanTerkait = $terlapor->pengaduans()->orderBy('created_at', 'desc')->get();
                @endphp

                @if ($pengaduanTerkait->count() > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 bg-orange-50">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Riwayat Pengaduan ({{ $pengaduanTerkait->count() }} pengaduan)
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach ($pengaduanTerkait as $pengaduan)
                                    <div
                                        class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                                        <div class="flex justify-between items-start mb-3">
                                            <div>
                                                <h4 class="text-sm font-semibold text-gray-900">
                                                    Pengaduan #{{ $pengaduan->pengaduan_id }}
                                                </h4>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    {{ $pengaduan->tanggal_laporan->format('d F Y') }}
                                                </p>
                                            </div>
                                            <div class="flex items-center space-x-2">
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
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                            <div>
                                                <span class="text-gray-500">Perihal:</span>
                                                <span class="text-gray-900 ml-1">{{ $pengaduan->perihal }}</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">Pelapor:</span>
                                                <span
                                                    class="text-gray-900 ml-1">{{ $pengaduan->pelapor->nama_pelapor ?? 'N/A' }}</span>
                                            </div>
                                            @if ($pengaduan->mediator)
                                                <div>
                                                    <span class="text-gray-500">Mediator:</span>
                                                    <span
                                                        class="text-gray-900 ml-1">{{ $pengaduan->mediator->nama_mediator }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        @if ($pengaduan->narasi_kasus)
                                            <div class="mt-3">
                                                <p class="text-xs text-gray-500 mb-1">Narasi Kasus:</p>
                                                <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-md">
                                                    {{ Str::limit($pengaduan->narasi_kasus, 200) }}
                                                </p>
                                            </div>
                                        @endif

                                        <div class="mt-4 flex justify-end">
                                            <a href="{{ route('pengaduan.show', $pengaduan->pengaduan_id) }}"
                                                class="inline-flex items-center text-xs text-primary hover:text-primary-dark font-medium">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                                Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Informasi Login & Instruksi -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 bg-purple-50">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Informasi Login & Instruksi
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Akses Login</h4>
                                <dl class="space-y-2">
                                    <div>
                                        <dt class="text-xs text-gray-500">URL Login</dt>
                                        <dd class="text-sm">
                                            <a href="{{ route('login') }}" target="_blank"
                                                class="text-blue-600 hover:text-blue-800">
                                                {{ route('login') }}
                                            </a>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs text-gray-500">Email Login</dt>
                                        <dd class="text-sm text-gray-900 font-mono">
                                            {{ $terlapor->user->email ?? $terlapor->email_terlapor }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs text-gray-500">Password</dt>
                                        <dd class="text-sm text-gray-500 italic">Password sementara telah dikirim ke
                                            email</dd>
                                    </div>
                                </dl>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Instruksi</h4>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5" fill="currentColor"
                                            viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3"></circle>
                                        </svg>
                                        Terlapor dapat login menggunakan email dan password yang dikirim
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5" fill="currentColor"
                                            viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3"></circle>
                                        </svg>
                                        Disarankan untuk mengganti password setelah login pertama
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5" fill="currentColor"
                                            viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3"></circle>
                                        </svg>
                                        Akun dapat dinonaktifkan sewaktu-waktu jika diperlukan
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4">
                    <div class="flex gap-3">
                        <x-secondary-button onclick="window.location.href='{{ route('mediator.akun.index') }}'">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                            Lihat Semua Akun
                        </x-secondary-button>
                    </div>

                    {{-- <x-primary-button onclick="window.location.href='{{ route('mediator.akun.create') }}'">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Akun Baru
                    </x-primary-button> --}}
                </div>
            </div>
        </div>
    </x-app-layout>

</body>

</html>
