<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Perbarui informasi halaman profil Anda.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- Form fields berdasarkan role --}}
        @if ($user->role === 'pelapor')
            {{-- Form untuk Pelapor --}}
            <div>
                <x-input-label for="nama_pelapor" :value="__('Nama Pelapor')" />
                <x-text-input id="nama_pelapor" name="nama_pelapor" type="text" class="mt-1 block w-full"
                    :value="old('nama_pelapor', $profileData->nama_pelapor ?? ($profileName ?? ''))" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('nama_pelapor')" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-input-label for="tempat_lahir" :value="__('Tempat Lahir')" />
                    <x-text-input id="tempat_lahir" name="tempat_lahir" type="text" class="mt-1 block w-full"
                        :value="old('tempat_lahir', $profileData->tempat_lahir ?? '')" />
                    <x-input-error class="mt-2" :messages="$errors->get('tempat_lahir')" />
                </div>

                <div>
                    <x-input-label for="tanggal_lahir" :value="__('Tanggal Lahir')" />
                    <x-text-input id="tanggal_lahir" name="tanggal_lahir" type="date" class="mt-1 block w-full"
                        :value="old('tanggal_lahir', $profileData->tanggal_lahir ?? '')" />
                    <x-input-error class="mt-2" :messages="$errors->get('tanggal_lahir')" />
                </div>
            </div>

            <div>
                <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" />
                <select id="jenis_kelamin" name="jenis_kelamin"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-laki"
                        {{ old('jenis_kelamin', $profileData->jenis_kelamin ?? '') === 'Laki-laki' ? 'selected' : '' }}>
                        Laki-laki</option>
                    <option value="Perempuan"
                        {{ old('jenis_kelamin', $profileData->jenis_kelamin ?? '') === 'Perempuan' ? 'selected' : '' }}>
                        Perempuan</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('jenis_kelamin')" />
            </div>

            <div>
                <x-input-label for="alamat" :value="__('Alamat')" />
                <textarea id="alamat" name="alamat" rows="3"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('alamat', $profileData->alamat ?? '') }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('alamat')" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-input-label for="no_hp" :value="__('No. HP')" />
                    <x-text-input id="no_hp" name="no_hp" type="text" class="mt-1 block w-full"
                        :value="old('no_hp', $profileData->no_hp ?? '')" />
                    <x-input-error class="mt-2" :messages="$errors->get('no_hp')" />
                </div>

                <div>
                    <x-input-label for="npk" :value="__('NPK')" />
                    <x-text-input id="npk" name="npk" type="text" class="mt-1 block w-full"
                        :value="old('npk', $profileData->npk ?? '')" />
                    <x-input-error class="mt-2" :messages="$errors->get('npk')" />
                </div>
            </div>

            <div>
                <x-input-label for="perusahaan" :value="__('Perusahaan')" />
                <x-text-input id="perusahaan" name="perusahaan" type="text" class="mt-1 block w-full"
                    :value="old('perusahaan', $profileData->perusahaan ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('perusahaan')" />
            </div>
        @elseif($user->role === 'mediator')
            {{-- Form untuk Mediator --}}
            <div>
                <x-input-label for="nama_mediator" :value="__('Nama Mediator')" />
                <x-text-input id="nama_mediator" name="nama_mediator" type="text" class="mt-1 block w-full"
                    :value="old('nama_mediator', $profileData->nama_mediator ?? ($profileName ?? ''))" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('nama_mediator')" />
            </div>

            <div>
                <x-input-label for="nip" :value="__('NIP')" />
                <x-text-input id="nip" name="nip" type="text" class="mt-1 block w-full"
                    :value="old('nip', $profileData->nip ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('nip')" />
            </div>
        @elseif($user->role === 'kepala_dinas')
            {{-- Form untuk Kepala Dinas --}}
            <div>
                <x-input-label for="nama_kepala_dinas" :value="__('Nama Kepala Dinas')" />
                <x-text-input id="nama_kepala_dinas" name="nama_kepala_dinas" type="text" class="mt-1 block w-full"
                    :value="old('nama_kepala_dinas', $profileData->nama_kepala_dinas ?? ($profileName ?? ''))" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('nama_kepala_dinas')" />
            </div>

            <div>
                <x-input-label for="nip" :value="__('NIP')" />
                <x-text-input id="nip" name="nip" type="text" class="mt-1 block w-full"
                    :value="old('nip', $profileData->nip ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('nip')" />
            </div>
        @elseif($user->role === 'terlapor')
            {{-- Form untuk Terlapor --}}
            <div>
                <x-input-label for="nama_terlapor" :value="__('Nama Terlapor')" />
                <x-text-input id="nama_terlapor" name="nama_terlapor" type="text" class="mt-1 block w-full"
                    :value="old('nama_terlapor', $profileData->nama_terlapor ?? ($profileName ?? ''))" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('nama_terlapor')" />
            </div>

            <div>
                <x-input-label for="alamat_kantor_cabang" :value="__('Alamat Kantor Cabang')" />
                <textarea id="alamat_kantor_cabang" name="alamat_kantor_cabang" rows="3"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('alamat_kantor_cabang', $profileData->alamat_kantor_cabang ?? '') }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('alamat_kantor_cabang')" />
            </div>
        @else
            {{-- Fallback untuk role yang tidak dikenali --}}
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                    :value="old('name', $profileName ?? ($user->name ?? ''))" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
        @endif

        {{-- Email field (sama untuk semua role) --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $profileData->email ?? ($user->email ?? ''))"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
