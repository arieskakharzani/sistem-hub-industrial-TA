<?php

namespace App\Mail;

use App\Models\Pengaduan;
use App\Models\Risalah;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class KasusSelesaiKlarifikasiMail extends Mailable
{
    use SerializesModels;

    public function __construct(
        public Pengaduan $pengaduan,
        public Risalah $risalah
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kasus Selesai - Klarifikasi Bipartit Lagi - ' . $this->pengaduan->nomor_pengaduan,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.kasus-selesai-klarifikasi',
            with: [
                'pengaduan' => $this->pengaduan,
                'risalah' => $this->risalah,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        // Attach risalah klarifikasi PDF
        try {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('risalah.pdf', [
                'risalah' => $this->risalah,
                'detail' => $this->risalah->detailKlarifikasi
            ]);

            $attachments[] = Attachment::fromData(
                fn() => $pdf->output(),
                'risalah_klarifikasi.pdf'
            )->withMime('application/pdf');
        } catch (\Exception $e) {
            \Log::error('Error creating PDF attachment for risalah klarifikasi: ' . $e->getMessage());
        }

        return $attachments;
    }
}
