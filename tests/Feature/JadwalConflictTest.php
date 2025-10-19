<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Jadwal;
use App\Models\Pengaduan;
use Illuminate\Support\Carbon;

class JadwalConflictTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function mediator_cannot_create_two_schedules_at_same_datetime()
    {
        // Arrange: buat mediator + user
        $user = User::factory()->create();
        $user->addRole('mediator');
        $user->setActiveRole('mediator');
        $mediator = $user->mediator()->create(['nama_mediator' => 'Tester Mediator']);

        // Dua pengaduan milik mediator yang sama
        $pengaduanA = Pengaduan::factory()->create([
            'mediator_id' => $mediator->mediator_id,
            'status' => 'proses',
        ]);
        $pengaduanB = Pengaduan::factory()->create([
            'mediator_id' => $mediator->mediator_id,
            'status' => 'proses',
        ]);

        $tanggal = Carbon::parse('2025-10-07')->toDateString();
        $waktu = '10:00';

        // Act: login sebagai mediator
        $this->actingAs($user);

        // Buat jadwal pertama (sukses)
        $resp1 = $this->post(route('jadwal.store'), [
            'pengaduan_id' => $pengaduanA->pengaduan_id,
            'jenis_jadwal' => 'klarifikasi',
            'tanggal' => $tanggal,
            'waktu' => $waktu,
            'tempat' => 'Ruang Rapat Dinas',
        ]);
        $resp1->assertRedirect();

        // Assert pre-condition
        $this->assertDatabaseHas('jadwal', [
            'pengaduan_id' => $pengaduanA->pengaduan_id,
            'mediator_id' => $mediator->mediator_id,
            'tanggal' => $tanggal,
            'waktu' => $waktu,
            'status_jadwal' => 'dijadwalkan',
        ]);

        // Coba buat jadwal bentrok pada pengaduan lain, mediator sama, tanggal & waktu sama (harus gagal)
        $resp2 = $this->from(route('jadwal.create', ['jenis_jadwal' => 'klarifikasi']))
            ->post(route('jadwal.store'), [
                'pengaduan_id' => $pengaduanB->pengaduan_id,
                'jenis_jadwal' => 'klarifikasi',
                'tanggal' => $tanggal,
                'waktu' => $waktu,
                'tempat' => 'Ruang Rapat Dinas',
            ]);

        $resp2->assertSessionHasErrors('waktu');

        // Pastikan hanya ada satu jadwal di slot itu
        $this->assertEquals(1, Jadwal::where('mediator_id', $mediator->mediator_id)
            ->whereDate('tanggal', $tanggal)
            ->where('waktu', $waktu)
            ->count());
    }
}

