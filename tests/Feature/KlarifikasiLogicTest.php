<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Jadwal;
use App\Models\Pengaduan;
use App\Models\Mediator;

class KlarifikasiLogicTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function mediator_can_create_risalah_klarifikasi_when_one_party_attends()
    {
        // Arrange: buat mediator + user
        $user = User::factory()->create();
        $user->addRole('mediator');
        $user->setActiveRole('mediator');
        $mediator = Mediator::factory()->create(['user_id' => $user->id]);

        // Buat pengaduan
        $pengaduan = Pengaduan::factory()->create([
            'mediator_id' => $mediator->mediator_id,
            'status' => 'proses',
        ]);

        // Buat jadwal klarifikasi dengan konfirmasi: pelapor hadir, terlapor tidak hadir
        $jadwal = Jadwal::factory()->create([
            'pengaduan_id' => $pengaduan->pengaduan_id,
            'mediator_id' => $mediator->mediator_id,
            'jenis_jadwal' => 'klarifikasi',
            'status_jadwal' => 'dijadwalkan',
            'konfirmasi_pelapor' => 'hadir',
            'konfirmasi_terlapor' => 'tidak_hadir',
            'tanggal' => now()->addDays(1),
            'waktu' => '10:00',
        ]);

        // Act: login sebagai mediator dan akses halaman detail jadwal
        $this->actingAs($user);
        $response = $this->get(route('jadwal.show', $jadwal->jadwal_id));

        // Assert: halaman bisa diakses dan button "Buat Risalah Klarifikasi" muncul
        $response->assertStatus(200);
        $response->assertSee('Klarifikasi Dapat Dilaksanakan');
        $response->assertSee('Buat Risalah Klarifikasi');
        $response->assertSee('Pelapor: Hadir');
        $response->assertSee('Terlapor: Tidak Hadir');
    }

    /** @test */
    public function mediator_cannot_create_risalah_klarifikasi_when_both_parties_absent()
    {
        // Arrange: buat mediator + user
        $user = User::factory()->create();
        $user->addRole('mediator');
        $user->setActiveRole('mediator');
        $mediator = Mediator::factory()->create(['user_id' => $user->id]);

        // Buat pengaduan
        $pengaduan = Pengaduan::factory()->create([
            'mediator_id' => $mediator->mediator_id,
            'status' => 'proses',
        ]);

        // Buat jadwal klarifikasi dengan konfirmasi: kedua pihak tidak hadir
        $jadwal = Jadwal::factory()->create([
            'pengaduan_id' => $pengaduan->pengaduan_id,
            'mediator_id' => $mediator->mediator_id,
            'jenis_jadwal' => 'klarifikasi',
            'status_jadwal' => 'dijadwalkan',
            'konfirmasi_pelapor' => 'tidak_hadir',
            'konfirmasi_terlapor' => 'tidak_hadir',
            'tanggal' => now()->addDays(1),
            'waktu' => '10:00',
        ]);

        // Act: login sebagai mediator dan akses halaman detail jadwal
        $this->actingAs($user);
        $response = $this->get(route('jadwal.show', $jadwal->jadwal_id));

        // Assert: halaman bisa diakses tapi button "Buat Risalah Klarifikasi" tidak muncul
        $response->assertStatus(200);
        $response->assertSee('Kedua Pihak Tidak Hadir');
        $response->assertSee('Buat Jadwal Mediasi ke-1');
        $response->assertDontSee('Buat Risalah Klarifikasi');
        $response->assertSee('Pelapor: Tidak Hadir');
        $response->assertSee('Terlapor: Tidak Hadir');
    }

    /** @test */
    public function mediator_can_create_risalah_klarifikasi_when_both_parties_attend()
    {
        // Arrange: buat mediator + user
        $user = User::factory()->create();
        $user->addRole('mediator');
        $user->setActiveRole('mediator');
        $mediator = Mediator::factory()->create(['user_id' => $user->id]);

        // Buat pengaduan
        $pengaduan = Pengaduan::factory()->create([
            'mediator_id' => $mediator->mediator_id,
            'status' => 'proses',
        ]);

        // Buat jadwal klarifikasi dengan konfirmasi: kedua pihak hadir
        $jadwal = Jadwal::factory()->create([
            'pengaduan_id' => $pengaduan->pengaduan_id,
            'mediator_id' => $mediator->mediator_id,
            'jenis_jadwal' => 'klarifikasi',
            'status_jadwal' => 'dijadwalkan',
            'konfirmasi_pelapor' => 'hadir',
            'konfirmasi_terlapor' => 'hadir',
            'tanggal' => now()->addDays(1),
            'waktu' => '10:00',
        ]);

        // Act: login sebagai mediator dan akses halaman detail jadwal
        $this->actingAs($user);
        $response = $this->get(route('jadwal.show', $jadwal->jadwal_id));

        // Assert: halaman bisa diakses dan button "Buat Risalah Klarifikasi" muncul
        $response->assertStatus(200);
        $response->assertSee('Klarifikasi Siap Dilaksanakan');
        $response->assertSee('Buat Risalah Klarifikasi');
        $response->assertSee('Kedua belah pihak telah mengkonfirmasi kehadiran');
    }

    /** @test */
    public function klarifikasi_status_remains_dijadwalkan_when_one_party_absent()
    {
        // Arrange: buat mediator + user
        $user = User::factory()->create();
        $user->addRole('mediator');
        $user->setActiveRole('mediator');
        $mediator = Mediator::factory()->create(['user_id' => $user->id]);

        // Buat pengaduan
        $pengaduan = Pengaduan::factory()->create([
            'mediator_id' => $mediator->mediator_id,
            'status' => 'proses',
        ]);

        // Buat jadwal klarifikasi
        $jadwal = Jadwal::factory()->create([
            'pengaduan_id' => $pengaduan->pengaduan_id,
            'mediator_id' => $mediator->mediator_id,
            'jenis_jadwal' => 'klarifikasi',
            'status_jadwal' => 'dijadwalkan',
            'konfirmasi_pelapor' => 'hadir',
            'konfirmasi_terlapor' => 'tidak_hadir',
            'tanggal' => now()->addDays(1),
            'waktu' => '10:00',
        ]);

        // Act: login sebagai mediator dan akses halaman detail jadwal
        $this->actingAs($user);
        $response = $this->get(route('jadwal.show', $jadwal->jadwal_id));

        // Assert: status tetap dijadwalkan
        $response->assertStatus(200);
        $this->assertEquals('dijadwalkan', $jadwal->fresh()->status_jadwal);
    }
}
