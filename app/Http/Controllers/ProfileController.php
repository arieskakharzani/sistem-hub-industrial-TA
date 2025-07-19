<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Pelapor;
use App\Models\Mediator;
use App\Models\KepalaDinas;
use App\Models\Terlapor;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        // Load profile relationship untuk mencegah N+1 queries
        $user->loadProfile();

        // Ambil data profil berdasarkan role menggunakan primary key yang benar
        $profileData = null;
        $profileName = null;

        switch ($user->active_role) {
            case 'pelapor':
                $profileData = Pelapor::where('user_id', $user->user_id)->first();
                $profileName = $profileData->nama_pelapor ?? null;
                break;

            case 'mediator':
                $profileData = Mediator::where('user_id', $user->user_id)->first();
                $profileName = $profileData->nama_mediator ?? null;
                break;

            case 'kepala_dinas':
                $profileData = KepalaDinas::where('user_id', $user->user_id)->first();
                $profileName = $profileData->nama_kepala_dinas ?? null;
                break;

            case 'terlapor':
                $profileData = Terlapor::where('user_id', $user->user_id)->first();
                $profileName = $profileData->nama_terlapor ?? null;
                break;

            default:
                // Jika role tidak dikenali, gunakan nama dari tabel users (jika ada)
                $profileName = $user->name ?? null;
                break;
        }

        // Fallback: gunakan method getName() dari User model jika nama masih null
        if (!$profileName) {
            $profileName = $user->getName();
        }

        return view('profile.edit', [
            'user' => $user,
            'profileData' => $profileData,
            'profileName' => $profileName,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Update data user (hanya field yang ada di tabel users)
        $userFields = $request->only(['name', 'email', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'alamat', 'no_hp', 'perusahaan', 'npk']);
        $user->fill($userFields);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Update data profil berdasarkan role jika ada data yang perlu diupdate
        $this->updateProfileByRole($request, $user);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update profile data based on user role
     */
    private function updateProfileByRole(ProfileUpdateRequest $request, $user)
    {
        switch ($user->active_role) {
            case 'pelapor':
                $this->updatePelaporProfile($request, $user);
                break;

            case 'mediator':
                $this->updateMediatorProfile($request, $user);
                break;

            case 'kepala_dinas':
                $this->updateKepalaDinasProfile($request, $user);
                break;

            case 'terlapor':
                $this->updateTerlaporProfile($request, $user);
                break;
        }
    }

    /**
     * Update pelapor profile
     */
    private function updatePelaporProfile(ProfileUpdateRequest $request, $user)
    {
        $profile = Pelapor::where('user_id', $user->user_id)->first();

        if ($profile) {
            $pelaporFields = $request->only([
                'nama_pelapor',
                'tempat_lahir',
                'tanggal_lahir',
                'jenis_kelamin',
                'alamat',
                'no_hp',
                'perusahaan',
                'npk',
                'email'
            ]);

            // Filter hanya field yang tidak null/kosong
            $pelaporFields = array_filter($pelaporFields, function ($value) {
                return $value !== null && $value !== '';
            });

            if (!empty($pelaporFields)) {
                $profile->update($pelaporFields);
            }
        }
    }

    /**
     * Update mediator profile
     */
    private function updateMediatorProfile(ProfileUpdateRequest $request, $user)
    {
        $profile = Mediator::where('user_id', $user->user_id)->first();

        if ($profile) {
            $mediatorFields = $request->only(['nama_mediator', 'nip']);

            $mediatorFields = array_filter($mediatorFields, function ($value) {
                return $value !== null && $value !== '';
            });

            if (!empty($mediatorFields)) {
                $profile->update($mediatorFields);
            }
        }
    }

    /**
     * Update kepala dinas profile
     */
    private function updateKepalaDinasProfile(ProfileUpdateRequest $request, $user)
    {
        $profile = KepalaDinas::where('user_id', $user->user_id)->first();

        if ($profile) {
            $kepalaDinasFields = $request->only(['nama_kepala_dinas', 'nip']);

            $kepalaDinasFields = array_filter($kepalaDinasFields, function ($value) {
                return $value !== null && $value !== '';
            });

            if (!empty($kepalaDinasFields)) {
                $profile->update($kepalaDinasFields);
            }
        }
    }

    /**
     * Update terlapor profile
     */
    private function updateTerlaporProfile(ProfileUpdateRequest $request, $user)
    {
        $profile = Terlapor::where('user_id', $user->user_id)->first();

        if ($profile) {
            $terlaporFields = $request->only(['nama_terlapor', 'alamat_kantor_cabang', 'email']);

            $terlaporFields = array_filter($terlaporFields, function ($value) {
                return $value !== null && $value !== '';
            });

            if (!empty($terlaporFields)) {
                $profile->update($terlaporFields);
            }
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Hapus data profil terkait sebelum hapus user
        $this->deleteRelatedProfile($user);

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Delete related profile data based on user role
     */
    private function deleteRelatedProfile($user)
    {
        switch ($user->active_role) {
            case 'pelapor':
                Pelapor::where('user_id', $user->user_id)->delete();
                break;

            case 'mediator':
                Mediator::where('user_id', $user->user_id)->delete();
                break;

            case 'kepala_dinas':
                KepalaDinas::where('user_id', $user->user_id)->delete();
                break;

            case 'terlapor':
                Terlapor::where('user_id', $user->user_id)->delete();
                break;
        }
    }
}
