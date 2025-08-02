<?php

namespace App\Mail;

use App\Models\Anjuran;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class FinalCaseDocumentsMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Anjuran $anjuran,
        public ?string $risalahPdfContent = null,
        public ?string $anjuranPdfContent = null
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Dokumen Final Kasus - ' . $this->anjuran->dokumenHI->pengaduan->nomor_pengaduan,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.final-case-documents',
            with: [
                'anjuran' => $this->anjuran,
                'pengaduan' => $this->anjuran->dokumenHI->pengaduan,
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

        // Attach risalah penyelesaian PDF
        if ($this->risalahPdfContent) {
            $attachments[] = Attachment::fromData(
                fn() => $this->risalahPdfContent,
                'risalah_penyelesaian.pdf'
            )->withMime('application/pdf');
        }

        // Attach anjuran PDF
        if ($this->anjuranPdfContent) {
            $attachments[] = Attachment::fromData(
                fn() => $this->anjuranPdfContent,
                'anjuran.pdf'
            )->withMime('application/pdf');
        }

        return $attachments;
    }
}
