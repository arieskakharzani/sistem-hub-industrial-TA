<?php

namespace App\Events;

use App\Models\Jadwal;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JadwalCreated
{
    use Dispatchable, SerializesModels;

    public $jadwal;
    public $eventType = 'created';

    public function __construct(Jadwal $jadwal)
    {
        $this->jadwal = $jadwal;
    }
}
