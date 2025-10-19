<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Jadwal;
use App\Models\Pengaduan;
use App\Models\Mediator;

class MixedAttendanceFailureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function mediator_can_create_anjuran_for_mixed_attendance_failure()
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

        // Buat 3 jadwal mediasi dengan mixed attendance pattern
        $jadwal1 = Jadwal::factory()->create([
            'pengaduan_id' => $pengaduan->pengaduan_id,
            'mediator_id' => $mediator->mediator_id,
            'jenis_jadwal' => 'mediasi',
            'sidang_ke' => 1,
            'status_jadwal' => 'selesai',
            'konfirmasi_pelapor' => 'hadir',
            'konfirmasi_terlapor' => 'tidak_hadir',
            'tanggal' => now()->subDays(10),
            'waktu' => '10:00',
        ]);

        $jadwal2 = Jadwal::factory()->create([
            'pengaduan_id' => $pengaduan->pengaduan_id,
            'mediator_id' => $mediator->mediator_id,
            'jenis_jadwal' => 'mediasi',
            'sidang_ke' => 2,
            'status_jadwal' => 'selesai',
            'konfirmasi_pelapor' => 'tidak_hadir',
            'konfirmasi_terlapor' => 'hadir',
            'tanggal' => now()->subDays(5),
            'waktu' => '10:00',
        ]);

        $jadwal3 = Jadwal::factory()->create([
            'pengaduan_id' => $pengaduan->pengaduan_id,
            'mediator_id' => $mediator->mediator_id,
            'jenis_jadwal' => 'mediasi',
            'sidang_ke' => 3,
            'status_jadwal' => 'selesai',
            'konfirmasi_pelapor' => 'hadir',
            'konfirmasi_terlapor' => 'pending',
            'tanggal' => now()->subDays(1),
            'waktu' => '10:00',
        ]);

        // Act: login sebagai mediator dan akses halaman pengaduan
        $this->actingAs($user);
        $response = $this->get(route('pengaduan.show', $pengaduan->pengaduan_id));

        // Assert: halaman bisa diakses dan button anjuran muncul
        $response->assertStatus(200);
        $response->assertSee('Mediasi Gagal - Buat Anjuran');
        $response->assertSee('Buat Anjuran');

        // Assert: pengaduan memenuhi syarat untuk membuat anjuran
        $this->assertTrue($pengaduan->canCreateAnjuran());
        $this->assertTrue($pengaduan->canCreateAnjuranDueToMixedAttendanceFailure());
        $this->assertFalse($pengaduan->canCreateAnjuranDueToTerlaporUnresponsiveness());
    }

    /** @test */
    public function mediator_can_create_anjuran_for_terlapor_unresponsiveness()
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

        // Buat 3 jadwal mediasi dengan terlapor tidak responsif
        $jadwal1 = Jadwal::factory()->create([
            'pengaduan_id' => $pengaduan->pengaduan_id,
            'mediator_id' => $mediator->mediator_id,
            'jenis_jadwal' => 'mediasi',
            'sidang_ke' => 1,
            'status_jadwal' => 'selesai',
            'konfirmasi_pelapor' => 'hadir',
            'konfirmasi_terlapor' => 'tidak_hadir',
            'tanggal' => now()->subDays(10),
            'waktu' => '10:00',
        ]);

        $jadwal2 = Jadwal::factory()->create([
            'pengaduan_id' => $pengaduan->pengaduan_id,
            'mediator_id' => $mediator->mediator_id,
            'jenis_jadwal' => 'mediasi',
            'sidang_ke' => 2,
            'status_jadwal' => 'selesai',
            'konfirmasi_pelapor' => 'hadir',
            'konfirmasi_terlapor' => 'tidak_hadir',
            'tanggal' => now()->subDays(5),
            'waktu' => '10:00',
        ]);

        $jadwal3 = Jadwal::factory()->create([
            'pengaduan_id' => $pengaduan->pengaduan_id,
            'mediator_id' => $mediator->mediator_id,
            'jenis_jadwal' => 'mediasi',
            'sidang_ke' => 3,
            'status_jadwal' => 'selesai',
            'konfirmasi_pelapor' => 'hadir',
            'konfirmasi_terlapor' => 'tidak_hadir',
            'tanggal' => now()->subDays(1),
            'waktu' => '10:00',
        ]);

        // Act: login sebagai mediator dan akses halaman pengaduan
        $this->actingAs($user);
        $response = $this->get(route('pengaduan.show', $pengaduan->pengaduan_id));

        // Assert: halaman bisa diakses dan button anjuran muncul
        $response->assertStatus(200);
        $response->assertSee('Mediasi Gagal - Buat Anjuran');
        $response->assertSee('Buat Anjuran');

        // Assert: pengaduan memenuhi syarat untuk membuat anjuran
        $this->assertTrue($pengaduan->canCreateAnjuran());
        $this->assertFalse($pengaduan->canCreateAnjuranDueToMixedAttendanceFailure());
        $this->assertTrue($pengaduan->canCreateAnjuranDueToTerlaporUnresponsiveness());
    }

    /** @test */
    public function mediator_cannot_create_anjuran_when_not_eligible()
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

        // Buat hanya 1 jadwal mediasi (belum mencapai maksimal)
        $jadwal1 = Jadwal::factory()->create([
            'pengaduan_id' => $pengaduan->pengaduan_id,
            'mediator_id' => $mediator->mediator_id,
            'jenis_jadwal' => 'mediasi',
            'sidang_ke' => 1,
            'status_jadwal' => 'selesai',
            'konfirmasi_pelapor' => 'hadir',
            'konfirmasi_terlapor' => 'tidak_hadir',
            'tanggal' => now()->subDays(10),
            'waktu' => '10:00',
        ]);

        // Act: login sebagai mediator dan akses halaman pengaduan
        $this->actingAs($user);
        $response = $this->get(route('pengaduan.show', $pengaduan->pengaduan_id));

        // Assert: halaman bisa diakses tapi button anjuran tidak muncul
        $response->assertStatus(200);
        $response->assertDontSee('Mediasi Gagal - Buat Anjuran');
        $response->assertDontSee('Buat Anjuran');

        // Assert: pengaduan tidak memenuhi syarat untuk membuat anjuran
        $this->assertFalse($pengaduan->canCreateAnjuran());
        $this->assertFalse($pengaduan->canCreateAnjuranDueToMixedAttendanceFailure());
        $this->assertFalse($pengaduan->canCreateAnjuranDueToTerlaporUnresponsiveness());
    }
}
