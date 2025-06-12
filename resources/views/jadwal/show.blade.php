<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detail Jadwal Mediasi
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Pengaduan #{{ $jadwal->pengaduan->pengaduan_id }} - {{ $jadwal->pengaduan->perihal }}
                </p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('jadwal.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg">
                    Kembali
                </a>
                @if (!in_array($jadwal->status_jadwal, ['selesai', 'dibatalkan']))
                    <a href="{{ route('jadwal.edit', $jadwal) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                        Edit Jadwal
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            {{-- Pesan sukses/error --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Konten Utama --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Informasi Jadwal --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Jadwal</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                                    <p class="text-sm text-gray-900">{{ $jadwal->tanggal_mediasi->format('d F Y') }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Waktu</label>
                                    <p class="text-sm text-gray-900">{{ $jadwal->waktu_mediasi->format('H:i') }} WIB</p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tempat</label>
                                    <p class="text-sm text-gray-900">{{ $jadwal->tempat_mediasi }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $jadwal->getStatusBadgeClass() }}">
                                        {!! $jadwal->getStatusIcon() !!}
                                        <span class="ml-1">{{ ucfirst($jadwal->status_jadwal) }}</span>
                                    </span>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Mediator</label>
                                    <p class="text-sm text-gray-900">{{ $jadwal->mediator->nama_mediator }}</p>
                                </div>
                            </div>

                            @if ($jadwal->catatan_jadwal)
                                <div class="mt-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Jadwal</label>
                                    <div class="bg-blue-50 p-4 rounded-md">
                                        <p class="text-sm text-blue-800">{{ $jadwal->catatan_jadwal }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($jadwal->hasil_mediasi)
                                <div class="mt-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Hasil Mediasi</label>
                                    <div class="bg-green-50 p-4 rounded-md">
                                        <p class="text-sm text-green-800">{{ $jadwal->hasil_mediasi }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Informasi Pengaduan --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Pengaduan</h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">ID Pengaduan</label>
                                    <p class="text-sm text-gray-900">#{{ $jadwal->pengaduan->pengaduan_id }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Perihal</label>
                                    <p class="text-sm text-gray-900">{{ $jadwal->pengaduan->perihal }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Laporan</label>
                                    <p class="text-sm text-gray-900">
                                        {{ $jadwal->pengaduan->tanggal_laporan->format('d F Y') }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Perusahaan</label>
                                    <p class="text-sm text-gray-900">{{ $jadwal->pengaduan->nama_perusahaan }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Narasi Kasus</label>
                                    <div class="bg-gray-50 p-4 rounded-md">
                                        <p class="text-sm text-gray-700">{{ $jadwal->pengaduan->narasi_kasus }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">
                    {{-- Informasi Pelapor --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pelapor</h3>

                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                                    <p class="text-sm text-gray-900">{{ $jadwal->pengaduan->pelapor->nama_pelapor }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <p class="text-sm text-gray-900">{{ $jadwal->pengaduan->pelapor->email }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                                    <p class="text-sm text-gray-900">{{ $jadwal->pengaduan->pelapor->no_hp }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Perusahaan</label>
                                    <p class="text-sm text-gray-900">{{ $jadwal->pengaduan->pelapor->perusahaan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Aksi Cepat --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>

                            <div class="space-y-3">
                                <form id="statusForm">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="status_jadwal" class="block text-sm font-medium text-gray-700 mb-1">
                                            Ubah Status
                                        </label>
                                        <select name="status_jadwal" id="status_jadwal"
                                            class="w-full rounded-md border-gray-300">
                                            @foreach (\App\Models\JadwalMediasi::getStatusOptions() as $key => $label)
                                                <option value="{{ $key }}"
                                                    {{ $jadwal->status_jadwal == $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="catatan_jadwal"
                                            class="block text-sm font-medium text-gray-700 mb-1">
                                            Catatan
                                        </label>
                                        <textarea name="catatan_jadwal" id="catatan_jadwal" rows="3" class="w-full rounded-md border-gray-300"
                                            placeholder="Tambahkan catatan...">{{ $jadwal->catatan_jadwal }}</textarea>
                                    </div>

                                    <button type="submit"
                                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                                        Update Status
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('statusForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route('jadwal.updateStatus', $jadwal) }}', {
                    method: 'PATCH',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Status berhasil diperbarui!');
                        location.reload();
                    } else {
                        alert('Terjadi kesalahan: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memperbarui status');
                });
        });
    </script>
</x-app-layout>
