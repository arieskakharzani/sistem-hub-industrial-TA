<?php

namespace App\Events;

use App\Models\JadwalMediasi;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JadwalMediasiCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $jadwal;
    public $eventType;

    public function __construct(JadwalMediasi $jadwal)
    {
        $this->jadwal = $jadwal;
        $this->eventType = 'created';
    }
}
