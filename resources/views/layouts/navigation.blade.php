<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                {{-- Menu Berdasarkan Role --}}
                @if (Auth::user()->active_role === 'pelapor')
                    {{-- Menu untuk Pelapor --}}
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('dashboard.pelapor')" :active="request()->routeIs('dashboard.pelapor')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('pengaduan.index')" :active="request()->routeIs('pengaduan.index')">
                            {{ __('Pengaduan Saya') }}
                        </x-nav-link>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('laporan.index')" :active="request()->routeIs('laporan.*')">
                            {{ __('Laporan') }}
                        </x-nav-link>
                    </div>
                @elseif(Auth::user()->active_role === 'terlapor')
                    {{-- Menu untuk Terlapor --}}
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('dashboard.terlapor')" :active="request()->routeIs('dashboard.terlapor')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    </div>

                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('pengaduan.index-terlapor')" :active="request()->routeIs('pengaduan.index-terlapor')">
                            {{ __('Pengaduan terhadap Saya') }}
                        </x-nav-link>
                    </div>

                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('laporan.hasil-mediasi')" :active="request()->routeIs('laporan.*')">
                            {{ __('Laporan') }}
                        </x-nav-link>
                    </div>
                @elseif(Auth::user()->active_role === 'mediator')
                    {{-- Menu untuk Mediator --}}
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('dashboard.mediator')" :active="request()->routeIs('dashboard.mediator')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('pengaduan.kelola')" :active="request()->routeIs('pengaduan.kelola')">
                            {{ __('Kelola Pengaduan') }}
                        </x-nav-link>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('jadwal.index')" :active="request()->routeIs('jadwal.index')">
                            {{ __('Kelola Jadwal') }}
                        </x-nav-link>
                    </div>

                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('dokumen.index')" :active="request()->routeIs('dokumen.index')">
                            {{ __('Kelola Dokumen HI') }}
                        </x-nav-link>
                    </div>

                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('laporan.index')" :active="request()->routeIs('laporan.*')">
                            {{ __('Laporan') }}
                        </x-nav-link>
                    </div>

                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('mediator.akun.index')" :active="request()->routeIs('mediator.akun.index')">
                            {{ __('Manajemen Akun') }}
                        </x-nav-link>
                    </div>
                @elseif(Auth::user()->active_role === 'kepala_dinas')
                    {{-- Menu untuk Kepala Dinas --}}
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('dashboard.kepala-dinas')" :active="request()->routeIs('dashboard.kepala-dinas')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('laporan.index')" :active="request()->routeIs('laporan.*')">
                            {{ __('Laporan') }}
                        </x-nav-link>
                    </div>
                    {{-- <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href=#>
                            {{ __('Laporan') }}
                        </x-nav-link>
                    </div> --}}
                @endif
            </div>


            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6 space-x-4">
                <!-- Notifications -->
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
                            <div
                                class="px-4 py-2 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                                <h3 class="text-sm font-semibold text-gray-700">Notifikasi</h3>
                                @if (auth()->user()->unreadNotifications->count() > 0)
                                    <form action="{{ route('notifications.markAllAsRead') }}" method="POST"
                                        class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs text-blue-600 hover:text-blue-800">
                                            Tandai Semua Dibaca
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <!-- Notification List -->
                            <div class="max-h-64 overflow-y-auto">
                                @forelse(auth()->user()->notifications()->take(5)->get() as $notification)
                                    <div
                                        class="relative px-4 py-3 hover:bg-gray-50 {{ $notification->read_at ? 'opacity-75' : '' }}">
                                        <a href="{{ route('notifications.show', $notification->id) }}" class="block">
                                            <div class="flex items-start">
                                                <!-- Icon -->
                                                <div class="flex-shrink-0 mt-1">
                                                    @switch($notification->data['icon'] ?? '')
                                                        @case('calendar-plus')
                                                            <svg class="h-5 w-5 text-blue-500" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                        @break

                                                        @case('calendar-edit')
                                                            <svg class="h-5 w-5 text-yellow-500" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        @break

                                                        @case('calendar-check')
                                                            <svg class="h-5 w-5 text-green-500" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5v2m6-2v2M9 19h6" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M9 12l2 2 4-4" />
                                                            </svg>
                                                        @break

                                                        @default
                                                            <svg class="h-5 w-5 text-gray-500" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                                            </svg>
                                                    @endswitch
                                                </div>

                                                <!-- Content -->
                                                <div class="ml-3 w-0 flex-1">
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $notification->data['title'] ?? 'Notifikasi' }}
                                                    </p>
                                                    <p class="mt-1 text-sm text-gray-500 line-clamp-2">
                                                        {{ $notification->data['message'] ?? '' }}
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
                                        <a href="{{ route('notifications.index') }}"
                                            class="text-sm text-blue-600 hover:text-blue-800">
                                            Lihat Semua Notifikasi
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Profile dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->getName() }}</div>

                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Hamburger -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <!-- Responsive Notifications -->
                <div class="px-4 py-2">
                    <x-notification-dropdown />
                </div>

                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->getName() }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                                this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
    </nav>
