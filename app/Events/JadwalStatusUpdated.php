<?php

namespace App\Events;

use App\Models\Jadwal;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JadwalStatusUpdated
{
    use Dispatchable, SerializesModels;

    public $jadwal;
    public $oldStatus;
    public $eventType = 'status_updated';

    public function __construct(Jadwal $jadwal, string $oldStatus)
    {
        $this->jadwal = $jadwal;
        $this->oldStatus = $oldStatus;
    }
}
