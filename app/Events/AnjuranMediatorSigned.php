<?php

namespace App\Events;

use App\Models\Anjuran;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnjuranMediatorSigned
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $anjuran;

    public function __construct(Anjuran $anjuran)
    {
        $this->anjuran = $anjuran;
    }
} 