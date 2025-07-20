<?php

namespace App\Events;

use App\Models\PerjanjianBersama;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PerjanjianBersamaTerlaporSigned
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $perjanjianBersama;

    public function __construct(PerjanjianBersama $perjanjianBersama)
    {
        $this->perjanjianBersama = $perjanjianBersama;
    }
} 