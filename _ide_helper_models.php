<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $jadwal_id
 * @property int $pengaduan_id
 * @property int $mediator_id
 * @property \Illuminate\Support\Carbon $tanggal_mediasi
 * @property \Illuminate\Support\Carbon $waktu_mediasi
 * @property string $tempat_mediasi
 * @property string $status_jadwal
 * @property string|null $catatan_jadwal
 * @property string|null $hasil_mediasi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Mediator $mediator
 * @property-read \App\Models\Pengaduan $pengaduan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalMediasi byMediator($mediatorId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalMediasi byStatus($status)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalMediasi hariIni()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalMediasi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalMediasi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalMediasi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalMediasi whereCatatanJadwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalMediasi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalMediasi whereHasilMediasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalMediasi whereJadwalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalMediasi whereMediatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalMediasi wherePengaduanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalMediasi whereStatusJadwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalMediasi whereTanggalMediasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalMediasi whereTempatMediasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalMediasi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalMediasi whereWaktuMediasi($value)
 */
	class JadwalMediasi extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $kepala_dinas_id
 * @property int $user_id
 * @property string $nama_kepala_dinas
 * @property string $nip
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KepalaDinas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KepalaDinas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KepalaDinas query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KepalaDinas whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KepalaDinas whereKepalaDinasId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KepalaDinas whereNamaKepalaDinas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KepalaDinas whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KepalaDinas whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KepalaDinas whereUserId($value)
 */
	class KepalaDinas extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $mediator_id
 * @property int $user_id
 * @property string $nama_mediator
 * @property string $nip
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JadwalMediasi> $jadwalMediasi
 * @property-read int|null $jadwal_mediasi_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pengaduan> $pengaduans
 * @property-read int|null $pengaduans_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mediator newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mediator newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mediator query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mediator whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mediator whereMediatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mediator whereNamaMediator($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mediator whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mediator whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mediator whereUserId($value)
 */
	class Mediator extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $pelapor_id
 * @property int $user_id
 * @property string $nama_pelapor
 * @property string $tempat_lahir
 * @property \Illuminate\Support\Carbon $tanggal_lahir
 * @property string $jenis_kelamin
 * @property string $alamat
 * @property string $no_hp
 * @property string $perusahaan
 * @property string $npk
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pengaduan> $pengaduan
 * @property-read int|null $pengaduan_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelapor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelapor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelapor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelapor whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelapor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelapor whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelapor whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelapor whereNamaPelapor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelapor whereNoHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelapor whereNpk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelapor wherePelaporId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelapor wherePerusahaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelapor whereTanggalLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelapor whereTempatLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelapor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelapor whereUserId($value)
 */
	class Pelapor extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $pengaduan_id
 * @property int $pelapor_id
 * @property int|null $terlapor_id
 * @property \Illuminate\Support\Carbon $tanggal_laporan
 * @property string $perihal
 * @property string $masa_kerja
 * @property string $nama_terlapor
 * @property string $email_terlapor
 * @property string|null $no_hp_terlapor
 * @property string|null $alamat_kantor_cabang
 * @property string $narasi_kasus
 * @property string|null $catatan_tambahan
 * @property array<array-key, mixed>|null $lampiran
 * @property string $status
 * @property int|null $mediator_id
 * @property string|null $catatan_mediator
 * @property \Illuminate\Support\Carbon|null $assigned_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $lampiran_files
 * @property-read mixed $perihal_text
 * @property-read mixed $status_badge_class
 * @property-read mixed $status_text
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JadwalMediasi> $jadwalMediasi
 * @property-read int|null $jadwal_mediasi_count
 * @property-read \App\Models\Mediator|null $mediator
 * @property-read \App\Models\Pelapor $pelapor
 * @property-read \App\Models\Terlapor|null $terlapor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan byPerihal($perihal)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan byStatus($status)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan today()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereAlamatKantorCabang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereAssignedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereCatatanMediator($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereCatatanTambahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereEmailTerlapor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereLampiran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereMasaKerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereMediatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereNamaTerlapor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereNarasiKasus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereNoHpTerlapor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan wherePelaporId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan wherePengaduanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan wherePerihal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereTanggalLaporan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereTerlaporId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereUpdatedAt($value)
 */
	class Pengaduan extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $terlapor_id
 * @property int $user_id
 * @property string $nama_terlapor
 * @property string $alamat_kantor_cabang
 * @property string $email_terlapor
 * @property string|null $no_hp_terlapor
 * @property int|null $created_by_mediator_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Mediator|null $mediator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pengaduan> $pengaduans
 * @property-read int|null $pengaduans_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Terlapor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Terlapor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Terlapor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Terlapor whereAlamatKantorCabang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Terlapor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Terlapor whereCreatedByMediatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Terlapor whereEmailTerlapor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Terlapor whereNamaTerlapor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Terlapor whereNoHpTerlapor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Terlapor whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Terlapor whereTerlaporId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Terlapor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Terlapor whereUserId($value)
 */
	class Terlapor extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $user_id
 * @property string $email
 * @property string|null $email_verified_at
 * @property string $password
 * @property string $role
 * @property bool $is_active
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\KepalaDinas|null $kepalaDinas
 * @property-read \App\Models\Mediator|null $mediator
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Pelapor|null $pelapor
 * @property-read \App\Models\Terlapor|null $terlapor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User byRole($role)
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUserId($value)
 */
	class User extends \Eloquent {}
}

