<?php

namespace App\Events;

use App\Models\Risalah;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RisalahSigned
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $risalah;

    public function __construct(Risalah $risalah)
    {
        $this->risalah = $risalah;
    }
} 