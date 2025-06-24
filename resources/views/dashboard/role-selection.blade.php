<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Role - SIPPPHI</title>
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
    <style>
        body {
            background-image: url('/img/background_disnakertrans.png');
            background-position: center;
            background-size: cover;
        }

        .role-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .role-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .role-card.selected {
            border-color: #0000AB;
            box-shadow: 0 0 0 3px rgba(0, 0, 171, 0.1);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-4xl p-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <img src="/img/logo_bungo.png" alt="Logo Kabupaten Bungo" class="w-20 h-20 mx-auto mb-4">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Pilih Role Akses</h1>
            <h2 class="text-lg text-gray-600 mb-2">Selamat datang, {{ $user->getName() }}</h2>
            <p class="text-sm text-gray-500">Akun Anda memiliki akses ke multiple role. Silakan pilih role yang ingin
                digunakan.</p>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Role Selection Form -->
        <form method="POST" action="{{ route('auth.select-role') }}" id="roleForm">
            @csrf
            <input type="hidden" name="role" id="selectedRole" value="">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                @foreach ($accessibleRoles as $role)
                    @php
                        $roleConfig = [
                            'pelapor' => [
                                'title' => 'Pelapor',
                                'description' => 'Melaporkan perselisihan hubungan industrial',
                                'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                                'color' => 'blue',
                                'features' => [
                                    'Buat pengaduan baru',
                                    'Lihat status pengaduan',
                                    'Upload dokumen pendukung',
                                ],
                            ],
                            'terlapor' => [
                                'title' => 'Terlapor',
                                'description' => 'Melihat pengaduan yang melibatkan perusahaan/organisasi',
                                'icon' =>
                                    'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                                'color' => 'yellow',
                                'features' => [
                                    'Lihat pengaduan terhadap perusahaan',
                                    'Pantau status penyelesaian',
                                    'Akses jadwal mediasi',
                                ],
                            ],
                            'mediator' => [
                                'title' => 'Mediator',
                                'description' => 'Mengelola dan memediasi penyelesaian hubungan industrial',
                                'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                'color' => 'green',
                                'features' => ['Kelola semua pengaduan', 'Jadwalkan mediasi', 'Buat akun terlapor'],
                            ],
                            'kepala_dinas' => [
                                'title' => 'Kepala Dinas',
                                'description' => 'Mengawasi dan mengelola seluruh sistem',
                                'icon' =>
                                    'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                                'color' => 'purple',
                                'features' => ['Dashboard statistik', 'Laporan komprehensif', 'Pengawasan sistem'],
                            ],
                        ];

                        $config = $roleConfig[$role] ?? [
                            'title' => ucfirst($role),
                            'description' => 'Role ' . $role,
                            'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                            'color' => 'gray',
                            'features' => ['Akses sistem'],
                        ];

                        $colorClasses = [
                            'blue' => 'border-blue-200 hover:border-blue-400 bg-blue-50',
                            'yellow' => 'border-yellow-200 hover:border-yellow-400 bg-yellow-50',
                            'green' => 'border-green-200 hover:border-green-400 bg-green-50',
                            'purple' => 'border-purple-200 hover:border-purple-400 bg-purple-50',
                            'gray' => 'border-gray-200 hover:border-gray-400 bg-gray-50',
                        ];
                    @endphp

                    <div class="role-card border-2 rounded-lg p-6 {{ $colorClasses[$config['color']] }}"
                        onclick="selectRole('{{ $role }}')">
                        <div class="text-center mb-4">
                            <div
                                class="w-16 h-16 mx-auto mb-3 bg-white rounded-full flex items-center justify-center shadow-md">
                                <svg class="w-8 h-8 text-{{ $config['color'] }}-600" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ $config['icon'] }}"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $config['title'] }}</h3>
                            <p class="text-sm text-gray-600 mb-4">{{ $config['description'] }}</p>
                        </div>

                        <div class="space-y-2">
                            @foreach ($config['features'] as $feature)
                                <div class="flex items-center text-sm text-gray-700">
                                    <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    {{ $feature }}
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 text-center">
                            <span
                                class="inline-block bg-white px-3 py-1 rounded-full text-xs font-medium text-{{ $config['color'] }}-600 shadow-sm">
                                Pilih Role Ini
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center">
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-gray-500 hover:text-gray-700 text-sm underline">
                        Logout
                    </button>
                </form>

                <button type="submit" id="submitBtn"
                    class="bg-primary hover:bg-primary-dark text-white font-bold py-3 px-8 rounded-lg transition duration-300 disabled:bg-gray-400 disabled:cursor-not-allowed"
                    disabled>
                    Masuk dengan Role Terpilih
                </button>
            </div>
        </form>

        <!-- Info Box -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        clip-rule="evenodd"></path>
                </svg>
                <div>
                    <h4 class="text-sm font-medium text-blue-800">Informasi Penting</h4>
                    <div class="mt-1 text-sm text-blue-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Anda dapat mengganti role kapan saja setelah login</li>
                            <li>Setiap role memiliki akses dan fitur yang berbeda</li>
                            <li>Role yang dipilih akan disimpan untuk login berikutnya</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function selectRole(role) {
            // Remove selected class from all cards
            document.querySelectorAll('.role-card').forEach(card => {
                card.classList.remove('selected');
            });

            // Add selected class to clicked card
            event.currentTarget.classList.add('selected');

            // Set the selected role
            document.getElementById('selectedRole').value = role;

            // Enable submit button
            document.getElementById('submitBtn').disabled = false;
        }

        // Auto-submit on role selection (optional)
        function selectRole(role) {
            document.getElementById('selectedRole').value = role;
            document.getElementById('roleForm').submit();
        }
    </script>
</body>

</html>
