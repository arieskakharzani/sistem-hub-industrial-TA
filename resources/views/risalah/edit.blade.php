<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Risalah {{ ucfirst($jenis_risalah) }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm p-8">
                <form method="POST" action="{{ route('risalah.update', $risalah) }}">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-medium mb-1">Nama Perusahaan</label>
                            <input type="text" name="nama_perusahaan"
                                class="form-input w-full rounded border-gray-300 focus:ring-blue-500"
                                value="{{ old('nama_perusahaan', $risalah->nama_perusahaan) }}" required>
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Jenis Usaha</label>
                            <input type="text" name="jenis_usaha"
                                class="form-input w-full rounded border-gray-300 focus:ring-blue-500"
                                value="{{ old('jenis_usaha', $risalah->jenis_usaha) }}" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block font-medium mb-1">Alamat Perusahaan</label>
                            <input type="text" name="alamat_perusahaan"
                                class="form-input w-full rounded border-gray-300 focus:ring-blue-500"
                                value="{{ old('alamat_perusahaan', $risalah->alamat_perusahaan) }}" required>
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Nama Pekerja/Buruh/SP/SB</label>
                            <input type="text" name="nama_pekerja"
                                class="form-input w-full rounded border-gray-300 focus:ring-blue-500"
                                value="{{ old('nama_pekerja', $risalah->nama_pekerja) }}" required>
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Alamat Pekerja/Buruh/SP/SB</label>
                            <input type="text" name="alamat_pekerja"
                                class="form-input w-full rounded border-gray-300 focus:ring-blue-500"
                                value="{{ old('alamat_pekerja', $risalah->alamat_pekerja) }}" required>
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Tanggal Perundingan</label>
                            <input type="date" name="tanggal_perundingan"
                                class="form-input w-full rounded border-gray-300 focus:ring-blue-500"
                                value="{{ old('tanggal_perundingan', $risalah->tanggal_perundingan) }}" required>
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Tempat Perundingan</label>
                            <input type="text" name="tempat_perundingan"
                                class="form-input w-full rounded border-gray-300 focus:ring-blue-500"
                                value="{{ old('tempat_perundingan', $risalah->tempat_perundingan) }}" required>
                        </div>
                    </div>
                    <div class="mt-6 space-y-4">
                        @if ($jenis_risalah === 'klarifikasi')
                            <div>
                                <label class="block font-medium mb-1">Pokok Masalah/Alasan Perselisihan</label>
                                <textarea name="pokok_masalah" class="form-input w-full rounded border-gray-300 focus:ring-blue-500">{{ old('pokok_masalah', $risalah->pokok_masalah) }}</textarea>
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Arahan Mediator</label>
                                <textarea name="arahan_mediator" class="form-input w-full rounded border-gray-300 focus:ring-blue-500">{{ old('arahan_mediator', $risalah->arahan_mediator) }}</textarea>
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Kesimpulan Klarifikasi</label>
                                <select name="kesimpulan_klarifikasi"
                                    class="form-input w-full rounded border-gray-300 focus:ring-blue-500" required>
                                    <option value="">-- Pilih Kesimpulan --</option>
                                    <option value="bipartit_lagi"
                                        {{ old('kesimpulan_klarifikasi', $risalah->kesimpulan_klarifikasi) == 'bipartit_lagi' ? 'selected' : '' }}>
                                        Bipartit Lagi</option>
                                    <option value="lanjut_ke_tahap_mediasi"
                                        {{ old('kesimpulan_klarifikasi', $risalah->kesimpulan_klarifikasi) == 'lanjut_ke_tahap_mediasi' ? 'selected' : '' }}>
                                        Lanjut ke Tahap Mediasi</option>
                                </select>
                            </div>
                        @else
                            <div>
                                <label class="block font-medium mb-1">Pendapat Pekerja/Buruh/SP/SB</label>
                                <textarea name="pendapat_pekerja" class="form-input w-full rounded border-gray-300 focus:ring-blue-500">{{ old('pendapat_pekerja', $risalah->pendapat_pekerja) }}</textarea>
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Pendapat Pengusaha</label>
                                <textarea name="pendapat_pengusaha" class="form-input w-full rounded border-gray-300 focus:ring-blue-500">{{ old('pendapat_pengusaha', $risalah->pendapat_pengusaha) }}</textarea>
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Kesimpulan atau Hasil Perundingan</label>
                                <textarea name="kesimpulan_penyelesaian" class="form-input w-full rounded border-gray-300 focus:ring-blue-500">{{ old('kesimpulan_penyelesaian', $risalah->kesimpulan_penyelesaian) }}</textarea>
                            </div>
                        @endif
                    </div>
                    <div class="mt-8 flex justify-end">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2 rounded shadow font-semibold transition">Update
                            Risalah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
