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

    <div class="relative" id="notification-dropdown">
        <!-- Notification Bell Button -->
        <button type="button"
            class="relative p-2 text-gray-600 hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 rounded-full transition-colors duration-200"
            onclick="toggleNotificationDropdown()">
            <!-- Bell Icon -->
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                </path>
            </svg>

            <!-- Notification Badge -->
            <span id="notification-badge"
                class="absolute top-0 right-0 hidden w-3 h-3 bg-red-500 rounded-full animate-pulse"></span>
            <span id="notification-count"
                class="absolute -top-1 -right-1 hidden min-w-[20px] h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center px-1 font-semibold animate-pulse"></span>
        </button>

        <!-- Dropdown Menu -->
        <div id="notification-menu"
            class="hidden absolute right-0 mt-2 w-96 bg-white rounded-xl shadow-2xl border border-gray-100 z-50 transform transition-all duration-200 opacity-0 scale-95">

            <!-- Header -->
            <div
                class="flex items-center justify-between p-6 border-b border-gray-100 bg-gradient-to-r from-primary to-primary-light text-white rounded-t-xl">
                <h3 class="text-lg font-semibold">🔔 Notifikasi</h3>
                <button onclick="markAllAsRead()"
                    class="text-sm text-white hover:text-white focus:outline-none transition-colors duration-200 bg-white/20 hover:bg-white/30 px-3 py-1 rounded-lg font-medium">
                    Tandai Semua Dibaca
                </button>
            </div>

            <!-- Notifications List -->
            <div id="notifications-list" class="max-h-96 overflow-y-auto">
                <!-- Loading State -->
                <div id="notifications-loading" class="p-8 text-center text-gray-500">
                    <div class="inline-flex items-center">
                        <svg class="animate-spin h-5 w-5 mr-3 text-primary" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span class="text-sm font-medium">Memuat notifikasi...</span>
                    </div>
                </div>

                <!-- Empty State -->
                <div id="notifications-empty" class="hidden p-12 text-center text-gray-500">
                    <div class="mb-4">
                        <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-700 mb-1">Tidak ada notifikasi</p>
                    <p class="text-xs text-gray-500">Notifikasi akan muncul di sini</p>
                </div>

                <!-- Notifications will be populated here -->
            </div>

            <!-- Footer -->
            <div class="p-4 border-t border-gray-100 bg-gray-50 rounded-b-xl">
                <a href="{{ route('notifications.index') }}"
                    class="block w-full text-center text-sm text-primary hover:text-primary-dark py-3 font-medium transition-colors duration-200 hover:bg-primary/5 rounded-lg">
                    📋 Lihat Semua Notifikasi
                </a>
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
