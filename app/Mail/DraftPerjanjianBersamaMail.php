<?php

namespace App\Mail;

use App\Models\PerjanjianBersama;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class DraftPerjanjianBersamaMail extends Mailable
{
    use SerializesModels;

    public function __construct(
        public PerjanjianBersama $perjanjianBersama,
        public string $pihak,
        public ?string $perjanjianPdfContent = null
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $pengaduan = $this->perjanjianBersama->dokumenHI->pengaduan;
        return new Envelope(
            subject: 'Draft Perjanjian Bersama - ' . $pengaduan->nomor_pengaduan,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $pengaduan = $this->perjanjianBersama->dokumenHI->pengaduan;
        return new Content(
            view: 'emails.draft-perjanjian-bersama',
            with: [
                'perjanjianBersama' => $this->perjanjianBersama,
                'pengaduan' => $pengaduan,
                'pihak' => $this->pihak,
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

        // Attach perjanjian bersama PDF
        if ($this->perjanjianPdfContent) {
            \Log::info('Attaching PDF to email, content size: ' . strlen($this->perjanjianPdfContent) . ' bytes');
            $attachments[] = Attachment::fromData(
                fn() => $this->perjanjianPdfContent,
                'draft_perjanjian_bersama.pdf'
            )->withMime('application/pdf');

            \Log::info('PDF attachment created successfully');
        } else {
            \Log::warning('PDF content is null or empty');
        }

        \Log::info('Total attachments: ' . count($attachments));
        return $attachments;
    }
}
