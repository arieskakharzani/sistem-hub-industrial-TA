<?php

namespace App\Events;

use App\Models\JadwalMediasi;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JadwalMediasiUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $jadwal;
    public $eventType;
    public $oldData;

    public function __construct(JadwalMediasi $jadwal, array $oldData = [])
    {
        $this->jadwal = $jadwal;
        $this->eventType = 'updated';
        $this->oldData = $oldData;
    }
}
