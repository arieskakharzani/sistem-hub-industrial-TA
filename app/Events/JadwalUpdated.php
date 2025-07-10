<?php

namespace App\Events;

use App\Models\Jadwal;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JadwalUpdated
{
    use Dispatchable, SerializesModels;

    public $jadwal;
    public $oldData;

    public function __construct(Jadwal $jadwal, array $oldData)
    {
        $this->jadwal = $jadwal;
        $this->oldData = $oldData;
    }
}
