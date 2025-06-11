<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login SIPPPHI</title>
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
    <x-guest-layout>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="flex items-start justify-between mb-8">
                <div class="flex-1">
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">Login Akun</h2>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Sistem Informasi Pengadaan dan<br>
                        Penyelesaian Hubungan Industrial<br>
                        Kabupaten Bungo
                    </p>
                </div>
                <div class="ml-4">
                    <!-- Logo Placeholder - You can replace this with actual logo -->
                    <div class="w-16 h-16 bg-white-600 rounded-lg flex items-center justify-center">
                        <img src="img/logo_bungo.png" alt="logo-kab-bungo">
                    </div>
                </div>
            </div>

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                    required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />

                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                    autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    {{-- <input id="remember_me" type="checkbox"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span> --}}
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        href="{{ route('register') }}">
                        {{ __('Belum punya akun?') }}
                    </a>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        href="{{ route('password.request') }}">
                        {{ __('Lupa Password?') }}
                    </a>
                @endif

                <x-primary-button class="ms-3">
                    {{ __('Login') }}
                </x-primary-button>
            </div>
        </form>
    </x-guest-layout>
</body>

</html>

{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login SIPPPHI</title>
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

<body class="bg-gray-100 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">

    <div class="max-w-md w-full">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <!-- Header Section -->
            <div class="flex items-start justify-between mb-8">
                <div class="flex-1">
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">Login Akun</h2>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Sistem Informasi Pengadaan dan<br>
                        Penyelesaian Hubungan Industrial<br>
                        Kabupaten Bungo
                    </p>
                </div>
                <div class="ml-4">
                    <!-- Logo Placeholder - You can replace this with actual logo -->
                    <div class="w-16 h-16 bg-green-600 rounded-lg flex items-center justify-center">
                        <svg class="w-10 h-10 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V7l-10-5z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="#">
                <!-- Email Field -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input id="email" name="email" type="email" required autofocus autocomplete="username"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                </div>

                <!-- Password Field -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <input id="password" name="password" type="password" required autocomplete="current-password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                </div>

                <!-- Bottom Section -->
                <div class="flex items-center justify-between">
                    <a href="#" class="text-sm text-gray-500 hover:text-gray-700 underline">
                        Lupa Password?
                    </a>
                    <button type="submit"
                        class="bg-primary hover:bg-primary-dark text-white font-medium py-2 px-8 rounded-md transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        LOGIN
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>

</html> --}}
