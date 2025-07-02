<?php

namespace App\Events;

use App\Models\JadwalMediasi;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class KonfirmasiKehadiran
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $jadwalMediasi;
    public $userRole;
    public $konfirmasi;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\JadwalMediasi  $jadwalMediasi
     * @param  string  $userRole
     * @param  string  $konfirmasi
     * @return void
     */
    public function __construct(JadwalMediasi $jadwalMediasi, string $userRole, string $konfirmasi)
    {
        $this->jadwalMediasi = $jadwalMediasi;
        $this->userRole = $userRole;
        $this->konfirmasi = $konfirmasi;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
