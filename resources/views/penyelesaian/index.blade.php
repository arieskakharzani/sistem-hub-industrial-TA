<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Penyelesaian Hubungan Industrial</title>
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
                {{ __('Penyelesaian Hubungan Industrial') }}
            </h2>
            <p class="text-gray-600 text-sm">Berikut adalah daftar dokumen yang perlu ditandatangani dan yang telah
                selesai ditandatangani oleh mediator maupun pihak lain.</p>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif



                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="mb-6">
                        <form method="GET" action="" class="flex items-center gap-4">
                            <label for="jenis_dokumen" class="block text-sm font-medium text-gray-700">Filter Jenis
                                Dokumen:</label>
                            <select name="jenis_dokumen" id="jenis_dokumen" onchange="this.form.submit()"
                                class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                                <option value="">Semua</option>
                                <option value="Risalah Klarifikasi"
                                    {{ request('jenis_dokumen') == 'Risalah Klarifikasi' ? 'selected' : '' }}>Risalah
                                    Klarifikasi</option>
                                <option value="Risalah Penyelesaian"
                                    {{ request('jenis_dokumen') == 'Risalah Penyelesaian' ? 'selected' : '' }}>Risalah
                                    Penyelesaian</option>
                                <option value="Perjanjian Bersama"
                                    {{ request('jenis_dokumen') == 'Perjanjian Bersama' ? 'selected' : '' }}>Perjanjian
                                    Bersama</option>
                                <option value="Anjuran" {{ request('jenis_dokumen') == 'Anjuran' ? 'selected' : '' }}>
                                    Anjuran</option>
                            </select>
                        </form>
                    </div>
                    <h3 class="text-lg font-semibold mb-4 mt-8">Dokumen yang Belum Ditandatangani</h3>
                    @include('penyelesaian.partials.tabel-pending', ['pending' => $dokumenPending])

                    <h3 class="text-lg font-semibold mb-4 mt-8">Dokumen yang Sudah Ditandatangani Anda (Menunggu Pihak
                        Lain)</h3>
                    @include('penyelesaian.partials.tabel-signed-by-user', [
                        'signedByUser' => $dokumenSignedByUser,
                    ])

                    <h3 class="text-lg font-semibold mb-4 mt-8">Dokumen Final (Sudah Ditandatangani Semua Pihak)</h3>
                    @include('penyelesaian.partials.tabel-final', ['final' => $dokumenSigned])
                </div>
            </div>
        </div>
    </x-app-layout>

    <script>
        function showPreviewModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closePreviewModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }
    </script>
</body>

</html>
