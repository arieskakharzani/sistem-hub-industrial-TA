<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Akun Pelapor</title>
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
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Detail Akun Pelapor') }}
                    </h2>
                    <p class="text-gray-600 text-sm mt-1">Informasi lengkap akun pelapor</p>
                </div>

                <div class="flex gap-3">
                    <x-secondary-button onclick="window.location.href='{{ route('mediator.akun.index') }}'">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </x-secondary-button>

                    @if ($pelapor->user && $pelapor->user->is_active)
                        <x-danger-button onclick="togglePelaporStatus({{ $pelapor->pelapor_id }}, 'deactivate')">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Nonaktifkan Akun
                        </x-danger-button>
                    @else
                        <x-primary-button class="bg-green-600 hover:bg-green-700"
                            onclick="togglePelaporStatus({{ $pelapor->pelapor_id }}, 'activate')">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Aktifkan Akun
                        </x-primary-button>
                    @endif
                </div>
            </div>
        </x-slot>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

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

                <!-- Account Status Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900">Status Akun</h3>
                            @if ($pelapor->user && $pelapor->user->is_active)
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3"></circle>
                                    </svg>
                                    Akun Aktif
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3"></circle>
                                    </svg>
                                    Akun Nonaktif
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Personal Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Informasi Pribadi</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-600">Nama Lengkap</label>
                                <p class="text-gray-900 font-medium">{{ $pelapor->nama_pelapor }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Tempat Lahir</label>
                                    <p class="text-gray-900">{{ $pelapor->tempat_lahir }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Tanggal Lahir</label>
                                    <p class="text-gray-900">{{ $pelapor->tanggal_lahir->format('d M Y') }}</p>
                                </div>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-600">Jenis Kelamin</label>
                                <p class="text-gray-900">{{ $pelapor->jenis_kelamin }}</p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-600">Alamat</label>
                                <p class="text-gray-900">{{ $pelapor->alamat }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact & Work Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Kontak & Pekerjaan</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-600">Email</label>
                                <p class="text-gray-900">{{ $pelapor->email }}</p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-600">No. HP</label>
                                <p class="text-gray-900">{{ $pelapor->no_hp }}</p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-600">Perusahaan</label>
                                <p class="text-gray-900 font-medium">{{ $pelapor->perusahaan }}</p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-600">NPK (Nomor Pokok Karyawan)</label>
                                <p class="text-gray-900">{{ $pelapor->npk }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Informasi Akun</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="text-sm font-medium text-gray-600">Akun Dibuat</label>
                                <p class="text-gray-900">{{ $pelapor->created_at->format('d M Y H:i') }}</p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-600">Terakhir Diperbarui</label>
                                <p class="text-gray-900">{{ $pelapor->updated_at->format('d M Y H:i') }}</p>
                            </div>

                            {{-- @if ($pelapor->user)
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Login Terakhir</label>
                                    <p class="text-gray-900">
                                        {{ $pelapor->user->last_login_at ? $pelapor->user->last_login_at->format('d M Y H:i') : 'Belum pernah login' }}
                                    </p>
                                </div>
                            @endif --}}
                        </div>
                    </div>
                </div>

                <!-- Related Pengaduan -->
                @if ($pelapor->pengaduan && $pelapor->pengaduan->count() > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Riwayat Pengaduan</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nomor Pengaduan</th>
                                        {{-- <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Subjek</th> --}}
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($pelapor->pengaduan as $pengaduan)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                #{{ $pengaduan->nomor_pengaduan ?? $pengaduan->pengaduan_id }}
                                                | {{ $pengaduan->perihal }}
                                            </td>
                                            {{-- <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ Str::limit($pengaduan->subjek_pengaduan, 50) }}
                                            </td> --}}
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    @if ($pengaduan->status === 'selesai') bg-green-100 text-green-800 
                                                    @elseif($pengaduan->status === 'proses') bg-blue-100 text-blue-800
                                                    @elseif($pengaduan->status === 'pending') bg-yellow-100 text-yellow-800 
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ ucfirst($pengaduan->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $pengaduan->created_at->format('d M Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('pengaduan.show', $pengaduan->pengaduan_id) }}"
                                                    class="text-indigo-600 hover:text-indigo-900">Detail</a>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <script>
            // Toggle Status for Pelapor
            async function togglePelaporStatus(pelaporId, action) {
                if (!confirm(
                        `Apakah Anda yakin ingin ${action === 'activate' ? 'mengaktifkan' : 'menonaktifkan'} akun pelapor ini?`
                    )) {
                    return;
                }

                try {
                    const url = `{{ url('mediator/akun') }}/pelapor/${pelaporId}/${action}`;
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-HTTP-Method-Override': 'PATCH'
                        }
                    });

                    if (response.ok) {
                        alert(`Akun pelapor berhasil ${action === 'activate' ? 'diaktifkan' : 'dinonaktifkan'}.`);
                        location.reload();
                    } else {
                        const result = await response.json();
                        alert('Error: ' + (result.message || 'Terjadi kesalahan'));
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengubah status');
                }
            }
        </script>
    </x-app-layout>

</body>

</html>
