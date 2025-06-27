<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Halaman Notifikasi</title>
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
    {{-- resources/views/notifications/index.blade.php --}}
    <x-app-layout>
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    🔔 {{ __('Notifikasi') }}
                </h2>
                <div class="flex space-x-3">
                    <button onclick="markAllAsRead()"
                        class="bg-primary hover:bg-primary-dark text-white font-semibold py-2 px-4 rounded-lg text-sm transition-colors duration-200 shadow-md hover:shadow-lg">
                        ✓ Tandai Semua Dibaca
                    </button>
                    <button onclick="clearAllNotifications()"
                        class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg text-sm transition-colors duration-200 shadow-md hover:shadow-lg">
                        🗑️ Hapus Semua
                    </button>
                </div>
            </div>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl">
                    <div class="p-8 text-gray-900">

                        {{-- Statistics --}}
                        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div
                                class="bg-gradient-to-br from-primary/10 to-primary-light/10 border border-primary/20 p-6 rounded-xl">
                                <div class="flex items-center">
                                    <div class="p-3 bg-primary rounded-xl">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-semibold text-primary-dark">Total Notifikasi</p>
                                        <p class="text-2xl font-bold text-primary">{{ $notifications->total() }}</p>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="bg-gradient-to-br from-yellow-50 to-orange-50 border border-yellow-200 p-6 rounded-xl">
                                <div class="flex items-center">
                                    <div class="p-3 bg-yellow-500 rounded-xl">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-semibold text-yellow-800">Belum Dibaca</p>
                                        <p class="text-2xl font-bold text-yellow-600">{{ $unreadCount }}</p>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 p-6 rounded-xl">
                                <div class="flex items-center">
                                    <div class="p-3 bg-green-500 rounded-xl">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-semibold text-green-800">Sudah Dibaca</p>
                                        <p class="text-2xl font-bold text-green-600">
                                            {{ $notifications->total() - $unreadCount }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Notifications List --}}
                        @if ($notifications->count() > 0)
                            <div class="space-y-4">
                                @foreach ($notifications as $notification)
                                    <div
                                        class="notification-item border rounded-xl p-6 transition-all duration-200 hover:shadow-lg {{ $notification->read_at ? 'bg-white border-gray-200' : 'bg-gradient-to-r from-primary/5 to-primary-light/5 border-primary/20 shadow-md' }}">
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-start space-x-4 flex-1">
                                                {{-- Notification Icon --}}
                                                <div class="flex-shrink-0">
                                                    @if ($notification->data['type'] ?? '' === 'pengaduan_baru')
                                                        <div
                                                            class="w-12 h-12 bg-gradient-to-br from-primary to-primary-light rounded-xl flex items-center justify-center shadow-lg">
                                                            <svg class="w-6 h-6 text-white" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                                </path>
                                                            </svg>
                                                        </div>
                                                    @else
                                                        <div
                                                            class="w-12 h-12 bg-gradient-to-br from-gray-400 to-gray-500 rounded-xl flex items-center justify-center shadow-lg">
                                                            <svg class="w-6 h-6 text-white" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                                                </path>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>

                                                {{-- Notification Content --}}
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center space-x-3 mb-2">
                                                        <h3 class="text-base font-bold text-gray-900">
                                                            {{ $notification->data['title'] ?? 'Notifikasi' }}
                                                        </h3>
                                                        @if (!$notification->read_at)
                                                            <span
                                                                class="w-3 h-3 bg-primary rounded-full animate-pulse"></span>
                                                        @endif
                                                    </div>
                                                    <p class="text-gray-600 leading-relaxed mb-3">
                                                        {{ $notification->data['message'] ?? '' }}
                                                    </p>
                                                    <div class="flex items-center justify-between">
                                                        <p class="text-sm text-gray-500 font-medium">
                                                            📅 {{ $notification->created_at->diffForHumans() }}
                                                        </p>
                                                        @if ($notification->data['action_url'] ?? false)
                                                            <a href="{{ $notification->data['action_url'] }}"
                                                                onclick="markAsRead('{{ $notification->id }}')"
                                                                class="inline-flex items-center text-sm text-primary hover:text-primary-dark font-semibold transition-colors duration-200 bg-primary/10 hover:bg-primary/20 px-3 py-2 rounded-lg">
                                                                <span class="mr-1">👁️</span>
                                                                {{ $notification->data['action_text'] ?? 'Lihat Detail' }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Actions --}}
                                            <div class="flex items-center space-x-3 ml-4">
                                                @if (!$notification->read_at)
                                                    <button onclick="markAsRead('{{ $notification->id }}')"
                                                        class="text-primary hover:text-primary-dark text-sm font-semibold transition-colors duration-200 bg-primary/10 hover:bg-primary/20 px-3 py-2 rounded-lg">
                                                        ✓ Tandai Dibaca
                                                    </button>
                                                @endif
                                                <button onclick="deleteNotification('{{ $notification->id }}')"
                                                    class="text-red-600 hover:text-red-800 text-sm font-semibold transition-colors duration-200 bg-red-50 hover:bg-red-100 px-3 py-2 rounded-lg">
                                                    Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Pagination --}}
                            <div class="mt-8">
                                {{ $notifications->links() }}
                            </div>
                        @else
                            {{-- Empty State --}}
                            <div class="text-center py-16">
                                <div class="mb-6">
                                    <svg class="w-20 h-20 mx-auto text-gray-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-3">Belum Ada Notifikasi</h3>
                                <p class="text-gray-600 mb-6 max-w-md mx-auto">
                                    Notifikasi akan muncul di sini ketika ada pengaduan baru atau update penting lainnya
                                    dari sistem.
                                </p>
                                <a href="{{ route('pengaduan.kelola') }}"
                                    class="inline-flex items-center text-primary hover:text-primary-dark font-semibold transition-colors duration-200 bg-primary/10 hover:bg-primary/20 px-6 py-3 rounded-lg">
                                    {{-- <span class="mr-2">📋</span> --}}
                                    Lihat Pengaduan
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <script>
            function markAsRead(notificationId) {
                fetch(`/notifications/${notificationId}/read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    });
            }

            function deleteNotification(notificationId) {
                if (confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')) {
                    fetch(`/notifications/${notificationId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json'
                            }
                        }).then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            }
                        });
                }
            }

            function markAllAsRead() {
                fetch('/notifications/mark-all-read', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    });
            }

            function clearAllNotifications() {
                if (confirm('Apakah Anda yakin ingin menghapus semua notifikasi?')) {
                    fetch('/notifications/clear-all', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json'
                            }
                        }).then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            }
                        });
                }
            }
        </script>
    </x-app-layout>
</body>

</html>
