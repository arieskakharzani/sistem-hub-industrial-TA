<?php

namespace App\Notifications;

use App\Models\Jadwal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KonfirmasiKehadiranNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $jadwal;
    protected $userRole;
    protected $konfirmasi;

    /**
     * Create a new notification instance.
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
        $pengaduan = $this->jadwal->pengaduan;
        $roleText = $this->userRole === 'pelapor' ? 'Pelapor' : 'Terlapor';
        $konfirmasiText = $this->konfirmasi === 'hadir' ? 'AKAN HADIR' : 'TIDAK DAPAT HADIR';
        $jenisJadwal = $this->jadwal->getJenisJadwalLabel();

        $subject = "Konfirmasi Kehadiran {$jenisJadwal} - {$roleText} {$konfirmasiText}";

        return (new MailMessage)
            ->subject($subject)
            ->view('emails.konfirmasi-kehadiran', [
                'jadwal' => $this->jadwal,
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
        $pengaduan = $this->jadwal->pengaduan;
        $roleText = $this->userRole === 'pelapor' ? 'Pelapor' : 'Terlapor';
        $konfirmasiText = $this->konfirmasi === 'hadir' ? 'akan hadir' : 'tidak dapat hadir';

        $title = "✅ Konfirmasi Kehadiran - {$roleText}";
        $message = "{$roleText} telah mengkonfirmasi bahwa mereka {$konfirmasiText} pada jadwal '{$pengaduan->perihal}' tanggal {$this->jadwal->tanggal->format('d F Y')}.";

        return [
            'type' => 'konfirmasi_kehadiran',
            'title' => $title,
            'message' => $message,
            'icon' => $this->konfirmasi === 'hadir' ? '✅' : '❌',
            'action_url' => route('jadwal.show', $this->jadwal->jadwal_id),
            'jadwal_id' => $this->jadwal->jadwal_id,
            'pengaduan_id' => $this->jadwal->pengaduan_id,
            'user_role' => $this->userRole,
            'konfirmasi' => $this->konfirmasi,
            'tanggal' => $this->jadwal->tanggal,
            'waktu' => $this->jadwal->waktu,
        ];
    }
}
