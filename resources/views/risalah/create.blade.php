<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Buat Risalah {{ ucfirst($jenis_risalah) }}</title>
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
                Buat Risalah {{ ucfirst($jenis_risalah) }}
            </h2>
        </x-slot>
        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-sm p-8">
                    <form method="POST" action="{{ route('risalah.store', [$jadwal->jadwal_id, $jenis_risalah]) }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block font-medium mb-1">Nama Perusahaan</label>
                                <input type="text" name="nama_perusahaan"
                                    class="form-input w-full rounded border-gray-300 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Jenis Usaha</label>
                                <input type="text" name="jenis_usaha"
                                    class="form-input w-full rounded border-gray-300 focus:ring-blue-500" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block font-medium mb-1">Alamat Perusahaan</label>
                                <input type="text" name="alamat_perusahaan"
                                    class="form-input w-full rounded border-gray-300 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Nama Pekerja/Buruh/SP/SB</label>
                                <input type="text" name="nama_pekerja"
                                    class="form-input w-full rounded border-gray-300 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Alamat Pekerja/Buruh/SP/SB</label>
                                <input type="text" name="alamat_pekerja"
                                    class="form-input w-full rounded border-gray-300 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Tanggal Perundingan</label>
                                <input type="date" name="tanggal_perundingan"
                                    class="form-input w-full rounded border-gray-300 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Tempat Perundingan</label>
                                <input type="text" name="tempat_perundingan"
                                    class="form-input w-full rounded border-gray-300 focus:ring-blue-500" required>
                            </div>
                        </div>
                        <div class="mt-6 space-y-4">
                            @if ($jenis_risalah === 'klarifikasi')
                                <div>
                                    <label class="block font-medium mb-1">Pokok Masalah/Alasan Perselisihan</label>
                                    <textarea name="pokok_masalah" class="form-input w-full rounded border-gray-300 focus:ring-blue-500"></textarea>
                                </div>
                                <div>
                                    <label class="block font-medium mb-1">Pendapat Pekerja/Buruh/SP/SB</label>
                                    <textarea name="pendapat_pekerja" class="form-input w-full rounded border-gray-300 focus:ring-blue-500"></textarea>
                                </div>
                                <div>
                                    <label class="block font-medium mb-1">Pendapat Pengusaha</label>
                                    <textarea name="pendapat_pengusaha" class="form-input w-full rounded border-gray-300 focus:ring-blue-500"></textarea>
                                </div>
                                <div>
                                    <label class="block font-medium mb-1">Arahan Mediator</label>
                                    <textarea name="arahan_mediator" class="form-input w-full rounded border-gray-300 focus:ring-blue-500"></textarea>
                                </div>
                                <div>
                                    <label class="block font-medium mb-1">Kesimpulan Klarifikasi</label>
                                    <select name="kesimpulan_klarifikasi"
                                        class="form-input w-full rounded border-gray-300 focus:ring-blue-500" required>
                                        <option value="">-- Pilih Kesimpulan --</option>
                                        <option value="bipartit_lagi">Bipartit Lagi</option>
                                        <option value="lanjut_ke_tahap_mediasi">Lanjut ke Tahap Mediasi</option>
                                    </select>
                                </div>
                            @else
                                <div>
                                    <label class="block font-medium mb-1">Pendapat Pekerja/Buruh/SP/SB</label>
                                    <textarea name="pendapat_pekerja" class="form-input w-full rounded border-gray-300 focus:ring-blue-500"></textarea>
                                </div>
                                <div>
                                    <label class="block font-medium mb-1">Pendapat Pengusaha</label>
                                    <textarea name="pendapat_pengusaha" class="form-input w-full rounded border-gray-300 focus:ring-blue-500"></textarea>
                                </div>
                                <div>
                                    <label class="block font-medium mb-1">Kesimpulan atau Hasil Perundingan</label>
                                    <textarea name="kesimpulan_penyelesaian" class="form-input w-full rounded border-gray-300 focus:ring-blue-500"></textarea>
                                </div>
                            @endif
                        </div>
                        <div class="mt-8 flex justify-end">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2 rounded shadow font-semibold transition">Simpan
                                Risalah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-app-layout>
</body>

</html>
