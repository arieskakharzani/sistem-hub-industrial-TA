<?php

namespace App\Mail;

use App\Models\Jadwal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JadwalNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $jadwal;
    public $recipient;
    public $eventType;
    public $additionalData;

    public function __construct(Jadwal $jadwal, array $recipient, string $eventType, array $additionalData = [])
    {
        $this->jadwal = $jadwal;
        $this->recipient = $recipient;
        $this->eventType = $eventType;
        $this->additionalData = $additionalData;
    }

    public function build()
    {
        $subject = $this->getSubject();
        return $this->subject($subject)
                   ->view('emails.jadwal-notification');
    }

    private function getSubject(): string
    {
        $jenis = ucfirst($this->jadwal->jenis_jadwal);
        $status = $this->jadwal->status_jadwal;
        
        switch ($this->eventType) {
            case 'created':
                return "Jadwal {$jenis} Baru Telah Dibuat";
            case 'updated':
                return "Perubahan Jadwal {$jenis}";
            case 'status_updated':
                return "Status Jadwal {$jenis} Diperbarui - {$status}";
            default:
                return "Informasi Jadwal {$jenis}";
        }
    }
} 