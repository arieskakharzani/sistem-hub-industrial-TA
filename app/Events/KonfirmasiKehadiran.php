<?php

namespace App\Events;

use App\Models\Jadwal;
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

    public $jadwal;
    public $userRole;
    public $konfirmasi;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Jadwal  $jadwal
     * @param  string  $userRole
     * @param  string  $konfirmasi
     * @return void
     */
    public function __construct(Jadwal $jadwal, string $userRole, string $konfirmasi)
    {
        $this->jadwal = $jadwal;
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
