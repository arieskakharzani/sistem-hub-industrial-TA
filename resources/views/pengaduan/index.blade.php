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
    </script>
</head>

<body>
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pengaduan Saya') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Main Content -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <!-- Empty State -->
                    <div class="p-12">
                        <div class="text-center">
                            <!-- Empty State Illustration -->
                            <div
                                class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>

                            <!-- Empty State Content -->
                            <div class="max-w-md mx-auto">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Anda belum membuat laporan apapun
                                </h3>
                                <p class="text-gray-600 mb-8 leading-relaxed">
                                    Mulai dengan membuat pengaduan pertama Anda untuk menyelesaikan perselisihan
                                    hubungan industrial yang sedang Anda hadapi.
                                </p>

                                <!-- Primary CTA Button -->
                                <div class="space-y-4">
                                    <a href="{{ route('pengaduan.create') }}"
                                        class="inline-flex items-center px-6 py-3 bg-primary border border-transparent rounded-lg font-semibold text-sm text-white tracking-wide hover:bg-primary-dark active:bg-primary-dark focus:outline-none focus:border-primary-dark focus:ring focus:ring-primary-light transform hover:-translate-y-0.5 transition-all duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Buat Laporan Pengaduan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Help Section -->
                    <div class="border-t border-gray-200 bg-gray-50 px-6 py-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Help Item 1 -->
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-8 h-8 bg-primary bg-opacity-10 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900">Panduan Pengaduan</h4>
                                    <p class="text-sm text-gray-600">Pelajari cara mengisi form pengaduan dengan benar
                                    </p>
                                </div>
                            </div>

                            <!-- Help Item 2 -->
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-8 h-8 bg-primary bg-opacity-10 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z">
                                            </path>
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900">Bantuan Support</h4>
                                    <p class="text-sm text-gray-600">Hubungi tim support untuk bantuan lebih lanjut</p>
                                </div>
                            </div>

                            <!-- Help Item 3 -->
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-8 h-8 bg-primary bg-opacity-10 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900">Proses Mediasi</h4>
                                    <p class="text-sm text-gray-600">Pelajari tahapan proses mediasi yang akan
                                        dilakukan</p>
                                </div>
                            </div>
                        </div>
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
                                        <p class="text-sm font-medium text-gray-900">Isi Form Pengaduan</p>
                                        <p class="text-xs text-gray-600">Lengkapi data dan detail perselisihan</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <div
                                        class="w-6 h-6 bg-gray-300 text-white rounded-full flex items-center justify-center text-xs font-bold">
                                        2</div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Proses Mediasi</p>
                                        <p class="text-xs text-gray-500">Tim mediator akan meninjau dan menjadwalkan
                                        </p>
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
        </div>
    </x-app-layout>

    {{-- <script>
        // Add click handlers for buttons
        document.querySelectorAll('a[href*="pengaduan.create"]').forEach(button => {
            button.addEventListener('click', function(e) {
                // You can add any additional logic here before navigation
                console.log('Navigating to create pengaduan form...');
            });
        });

        // Add hover effects for help items
        document.querySelectorAll('.flex.items-start.space-x-3').forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.classList.add('bg-gray-50', 'rounded-lg', 'p-2', '-m-2');
            });

            item.addEventListener('mouseleave', function() {
                this.classList.remove('bg-gray-50', 'rounded-lg', 'p-2', '-m-2');
            });
        });
    </script> --}}


    {{-- </x-app-layout> --}}
</body>

</html>
