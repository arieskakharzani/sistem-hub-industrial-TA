<?php

namespace App\Events;

use App\Models\Jadwal;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JadwalRescheduleNeeded
{
    use Dispatchable, SerializesModels;

    public $jadwal;
    public $absentParty;
    public $reason;

    public function __construct(Jadwal $jadwal, string $absentParty, string $reason = '')
    {
        $this->jadwal = $jadwal;
        $this->absentParty = $absentParty;
        $this->reason = $reason;
    }
}
