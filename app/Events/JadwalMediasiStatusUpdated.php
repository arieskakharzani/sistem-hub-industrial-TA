<?php

namespace App\Events;

use App\Models\JadwalMediasi;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JadwalMediasiStatusUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $jadwal;
    public $eventType;
    public $oldStatus;

    public function __construct(JadwalMediasi $jadwal, string $oldStatus)
    {
        $this->jadwal = $jadwal;
        $this->eventType = 'status_updated';
        $this->oldStatus = $oldStatus;
    }
}
