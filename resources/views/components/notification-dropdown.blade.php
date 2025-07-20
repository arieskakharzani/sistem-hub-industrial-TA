@if (Auth::check() && Auth::user()->role === 'mediator')
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

    <div class="relative">
        <button @click="$store.notifications.open = !$store.notifications.open"
            class="relative p-2 text-gray-600 hover:text-gray-900 focus:outline-none">
            <span class="sr-only">View notifications</span>
            <!-- Bell Icon -->
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                </path>
            </svg>

            <!-- Notification Badge -->
            @if (auth()->user()->unreadNotifications->count() > 0)
                <span
                    class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                    {{ auth()->user()->unreadNotifications->count() }}
                </span>
            @endif
        </button>

        <!-- Notification Panel -->
        <div x-show="$store.notifications.open" @click.away="$store.notifications.open = false"
            class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg overflow-hidden z-50"
            style="display: none;">
            <div class="py-2">
                <!-- Header -->
                <div class="px-4 py-2 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                    <h3 class="text-sm font-semibold text-gray-700">Notifikasi</h3>
                    @if (auth()->user()->unreadNotifications->count() > 0)
                        <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-xs text-blue-600 hover:text-blue-800">
                                Tandai Semua Dibaca
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Notification List -->
                <div class="max-h-64 overflow-y-auto">
                    @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                        <div
                            class="relative px-4 py-3 hover:bg-gray-50 {{ $notification->read_at ? 'opacity-75' : '' }}">
                            <a href="{{ route('notifications.show', $notification->id) }}" class="block">
                                <div class="flex items-start">
                                    <!-- Icon -->
                                    <div class="flex-shrink-0 mt-1">
                                        @switch($notification->data['type'] ?? '')
                                            @case('konfirmasi_kehadiran')
                                                <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @break

                                            @case('jadwal_created')
                                                <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            @break

                                            @case('jadwal_updated')
                                                <svg class="h-5 w-5 text-yellow-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            @break

                                            @default
                                                <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                                </svg>
                                        @endswitch
                                    </div>

                                    <!-- Content -->
                                    <div class="ml-3 w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $notification->data['title'] ?? 'Notifikasi Baru' }}
                                        </p>
                                        <p class="mt-1 text-sm text-gray-500 line-clamp-2">
                                            {{ $notification->data['message'] ?? 'Anda memiliki notifikasi baru' }}
                                        </p>
                                        <p class="mt-1 text-xs text-gray-400">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </a>

                            <!-- Unread indicator -->
                            @unless ($notification->read_at)
                                <div class="absolute top-3 right-4 h-2 w-2 bg-blue-500 rounded-full"></div>
                            @endunless
                        </div>
                        @empty
                            <div class="px-4 py-6 text-center text-gray-500">
                                <p>Tidak ada notifikasi</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Footer -->
                    @if (auth()->user()->notifications->count() > 5)
                        <div class="py-2 text-center border-t border-gray-200 bg-gray-50">
                            <a href="{{ route('notifications.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                                Lihat Semua Notifikasi
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <script>
            let notificationDropdownOpen = false;

            // Toggle dropdown with animation
            function toggleNotificationDropdown() {
                const menu = document.getElementById('notification-menu');
                notificationDropdownOpen = !notificationDropdownOpen;

                if (notificationDropdownOpen) {
                    menu.classList.remove('hidden');
                    // Trigger animation
                    setTimeout(() => {
                        menu.classList.remove('opacity-0', 'scale-95');
                        menu.classList.add('opacity-100', 'scale-100');
                    }, 10);
                    loadNotifications();
                } else {
                    menu.classList.remove('opacity-100', 'scale-100');
                    menu.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => {
                        menu.classList.add('hidden');
                    }, 200);
                }
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const dropdown = document.getElementById('notification-dropdown');
                if (!dropdown.contains(event.target)) {
                    const menu = document.getElementById('notification-menu');
                    menu.classList.remove('opacity-100', 'scale-100');
                    menu.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => {
                        menu.classList.add('hidden');
                    }, 200);
                    notificationDropdownOpen = false;
                }
            });

            // Load notifications
            function loadNotifications() {
                const loadingEl = document.getElementById('notifications-loading');
                const emptyEl = document.getElementById('notifications-empty');
                const listEl = document.getElementById('notifications-list');

                loadingEl.classList.remove('hidden');
                emptyEl.classList.add('hidden');

                fetch('/notifications/recent')
                    .then(response => response.json())
                    .then(data => {
                        loadingEl.classList.add('hidden');

                        if (data.notifications.length === 0) {
                            emptyEl.classList.remove('hidden');
                            return;
                        }

                        const notificationsHTML = data.notifications.map(notification => `
                <div class="notification-item p-4 border-b border-gray-50 hover:bg-gradient-to-r hover:from-primary/5 hover:to-primary-light/5 cursor-pointer transition-all duration-200 ${!notification.read_at ? 'bg-blue-50 border-l-4 border-l-primary' : ''}"
                     onclick="handleNotificationClick('${notification.id}', '${notification.action_url}')">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 pt-1">
                            <div class="w-3 h-3 bg-primary rounded-full ${notification.read_at ? 'opacity-0' : 'animate-pulse'}"></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-2 mb-1">
                                <p class="text-sm font-semibold text-gray-900 truncate">${notification.title}</p>
                                ${!notification.read_at ? '<span class="w-2 h-2 bg-primary rounded-full animate-pulse"></span>' : ''}
                            </div>
                            <p class="text-sm text-gray-600 leading-relaxed">${notification.message}</p>
                            <div class="flex items-center justify-between mt-2">
                                <p class="text-xs text-gray-400 font-medium">${notification.created_at}</p>
                                <span class="text-xs text-primary hover:text-primary-dark font-semibold">Lihat →</span>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');

                        // Update the list content
                        const existingItems = listEl.querySelectorAll('.notification-item');
                        existingItems.forEach(item => item.remove());

                        listEl.insertAdjacentHTML('afterbegin', notificationsHTML);
                    })
                    .catch(error => {
                        console.error('Error loading notifications:', error);
                        loadingEl.classList.add('hidden');
                    });
            }

            // Handle notification click
            function handleNotificationClick(notificationId, actionUrl) {
                // Mark as read
                fetch(`/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                }).then(() => {
                    updateNotificationCount();
                });

                // Redirect to action URL
                if (actionUrl && actionUrl !== '#') {
                    window.location.href = actionUrl;
                }
            }

            // Mark all as read
            function markAllAsRead() {
                fetch('/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                }).then(() => {
                    updateNotificationCount();
                    loadNotifications(); // Refresh notifications
                });
            }

            // Update notification count
            function updateNotificationCount() {
                fetch('/notifications/unread-count')
                    .then(response => response.json())
                    .then(data => {
                        const badge = document.getElementById('notification-badge');
                        const count = document.getElementById('notification-count');

                        if (data.count > 0) {
                            badge.classList.remove('hidden');
                            count.classList.remove('hidden');
                            count.textContent = data.count > 99 ? '99+' : data.count;
                        } else {
                            badge.classList.add('hidden');
                            count.classList.add('hidden');
                        }
                    });
            }

            // Initial load of notification count
            document.addEventListener('DOMContentLoaded', function() {
                updateNotificationCount();

                // Auto-refresh notification count every 30 seconds
                setInterval(updateNotificationCount, 30000);
            });
        </script>
    @endif
