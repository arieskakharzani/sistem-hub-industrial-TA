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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Notifikasi') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Header with Mark All as Read button -->
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold">Daftar Notifikasi</h3>
                            @if ($unreadCount > 0)
                                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Tandai Semua Dibaca ({{ $unreadCount }})
                                    </button>
                                </form>
                            @endif
                        </div>

                        <!-- Notifications List -->
                        <div class="space-y-4">
                            @forelse($notifications as $notification)
                                <div
                                    class="relative flex items-start p-4 {{ $notification->read_at ? 'bg-gray-50' : 'bg-white' }} border rounded-lg hover:shadow-md transition-shadow duration-200">
                                    <!-- Icon -->
                                    <div class="flex-shrink-0 mt-1">
                                        @switch($notification->data['icon'] ?? '')
                                            @case('calendar-plus')
                                                <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            @break

                                            @case('calendar-edit')
                                                <svg class="h-6 w-6 text-yellow-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            @break

                                            @case('calendar-check')
                                                <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5v2m6-2v2M9 19h6" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4" />
                                                </svg>
                                            @break

                                            @default
                                                <svg class="h-6 w-6 text-gray-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                                </svg>
                                        @endswitch
                                    </div>

                                    <!-- Content -->
                                    <div class="ml-4 flex-1">
                                        <div class="flex justify-between">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $notification->data['title'] ?? 'Notifikasi' }}
                                            </p>
                                            <span class="text-sm text-gray-500">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-600">
                                            {{ $notification->data['message'] ?? '' }}
                                        </p>

                                        <!-- Action Buttons -->
                                        <div class="mt-3 flex space-x-3">
                                            @if ($notification->data['jadwal_id'] ?? false)
                                                @if (auth()->user()->active_role === 'mediator')
                                                    <a href="{{ route('jadwal.show', $notification->data['jadwal_id']) }}"
                                                        class="text-sm text-blue-600 hover:text-blue-800">
                                                        Lihat Jadwal
                                                    </a>
                                                @elseif (in_array(auth()->user()->active_role, ['pelapor', 'terlapor']))
                                                    <a href="{{ route('konfirmasi.show', $notification->data['jadwal_id']) }}"
                                                        class="text-sm text-blue-600 hover:text-blue-800">
                                                        Konfirmasi Jadwal
                                                    </a>
                                                @endif
                                            @endif

                                            @unless ($notification->read_at)
                                                <form action="{{ route('notifications.markAsRead', $notification->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="text-sm text-gray-600 hover:text-gray-800">
                                                        Tandai Dibaca
                                                    </button>
                                                </form>
                                            @endunless
                                        </div>
                                    </div>

                                    <!-- Unread indicator -->
                                    @unless ($notification->read_at)
                                        <div class="absolute top-4 right-4 h-3 w-3 bg-blue-500 rounded-full"></div>
                                    @endunless
                                </div>
                                @empty
                                    <div class="text-center py-12">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                            </path>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada notifikasi</h3>
                                        <p class="mt-1 text-sm text-gray-500">Notifikasi akan muncul di sini saat ada
                                            pembaruan.</p>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Pagination -->
                            <div class="mt-6">
                                {{ $notifications->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-app-layout>
    </body>

    </html>
