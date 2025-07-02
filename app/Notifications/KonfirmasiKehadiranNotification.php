<?php

namespace App\Notifications;

use App\Models\JadwalMediasi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KonfirmasiKehadiranNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $jadwalMediasi;
    protected $userRole;
    protected $konfirmasi;

    /**
     * Create a new notification instance.
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
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $pengaduan = $this->jadwalMediasi->pengaduan;
        $roleText = $this->userRole === 'pelapor' ? 'Pelapor' : 'Terlapor';
        $konfirmasiText = $this->konfirmasi === 'hadir' ? 'AKAN HADIR' : 'TIDAK DAPAT HADIR';

        $subject = "Konfirmasi Kehadiran Mediasi - {$roleText} {$konfirmasiText}";

        return (new MailMessage)
            ->subject($subject)
            ->view('emails.konfirmasi-kehadiran', [
                'jadwal' => $this->jadwalMediasi,
                'pengaduan' => $pengaduan,
                'userRole' => $this->userRole,
                'konfirmasi' => $this->konfirmasi,
                'roleText' => $roleText,
                'konfirmasiText' => $konfirmasiText,
                'mediator' => $notifiable
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $pengaduan = $this->jadwalMediasi->pengaduan;
        $roleText = $this->userRole === 'pelapor' ? 'Pelapor' : 'Terlapor';
        $konfirmasiText = $this->konfirmasi === 'hadir' ? 'akan hadir' : 'tidak dapat hadir';

        $title = "✅ Konfirmasi Kehadiran - {$roleText}";
        $message = "{$roleText} telah mengkonfirmasi bahwa mereka {$konfirmasiText} pada jadwal mediasi '{$pengaduan->perihal}' tanggal {$this->jadwalMediasi->tanggal_mediasi->format('d F Y')}.";

        return [
            'type' => 'konfirmasi_kehadiran',
            'title' => $title,
            'message' => $message,
            'icon' => $this->konfirmasi === 'hadir' ? '✅' : '❌',
            'action_url' => route('jadwal.show', $this->jadwalMediasi->jadwal_id),
            'jadwal_id' => $this->jadwalMediasi->jadwal_id,
            'pengaduan_id' => $this->jadwalMediasi->pengaduan_id,
            'user_role' => $this->userRole,
            'konfirmasi' => $this->konfirmasi,
            'tanggal_mediasi' => $this->jadwalMediasi->tanggal_mediasi,
            'waktu_mediasi' => $this->jadwalMediasi->waktu_mediasi,
        ];
    }
}
