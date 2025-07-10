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

    public function __construct(Jadwal $jadwal, $oldStatus)
    {
        $this->jadwal = $jadwal;
        $this->oldStatus = $oldStatus;
    }
}
