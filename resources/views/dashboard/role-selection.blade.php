<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pilih Role') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Silakan pilih role yang ingin Anda gunakan:</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach (auth()->user()->roles as $role)
                            <div class="border rounded-lg p-4 hover:bg-gray-50">
                                <form method="POST" action="{{ route('dashboard.set-role') }}">
                                    @csrf
                                    <input type="hidden" name="role" value="{{ $role }}">
                                    <button type="submit" class="w-full text-left">
                                        <div class="font-medium text-lg mb-2">
                                            @switch($role)
                                                @case('pelapor')
                                                    Pelapor
                                                @break

                                                @case('terlapor')
                                                    Terlapor
                                                @break

                                                @case('mediator')
                                                    Mediator
                                                @break

                                                @case('kepala_dinas')
                                                    Kepala Dinas
                                                @break
                                            @endswitch
                                        </div>
                                        <p class="text-gray-600">
                                            @switch($role)
                                                @case('pelapor')
                                                    Akses sebagai pelapor untuk membuat pengaduan
                                                @break

                                                @case('terlapor')
                                                    Akses sebagai terlapor untuk menanggapi pengaduan
                                                @break

                                                @case('mediator')
                                                    Akses sebagai mediator untuk mengelola mediasi
                                                @break

                                                @case('kepala_dinas')
                                                    Akses sebagai kepala dinas untuk monitoring
                                                @break
                                            @endswitch
                                        </p>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
