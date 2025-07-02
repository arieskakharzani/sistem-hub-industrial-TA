<?php

namespace App\Events;

use App\Models\JadwalMediasi;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JadwalMediasiRescheduleNeeded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $jadwal;
    public $eventType;
    public $absentParty; // 'pelapor', 'terlapor', or 'both'
    public $reason;

    /**
     * Create a new event instance.
     *
     * @param JadwalMediasi $jadwal
     * @param string $absentParty
     * @param string $reason
     */
    public function __construct(JadwalMediasi $jadwal, string $absentParty, string $reason = '')
    {
        $this->jadwal = $jadwal;
        $this->eventType = 'reschedule_needed';
        $this->absentParty = $absentParty;
        $this->reason = $reason;
    }
}
