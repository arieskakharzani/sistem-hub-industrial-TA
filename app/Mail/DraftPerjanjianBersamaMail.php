<?php

namespace App\Mail;

use App\Models\Pengaduan;
use App\Models\PerjanjianBersama;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class DraftPerjanjianBersamaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pengaduan;
    public $perjanjianBersama;
    public $pihak;

    /**
     * Create a new message instance.
     */
    public function __construct(Pengaduan $pengaduan, PerjanjianBersama $perjanjianBersama, $pihak)
    {
        $this->pengaduan = $pengaduan;
        $this->perjanjianBersama = $perjanjianBersama;
        $this->pihak = $pihak;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $pihakText = $this->pihak === 'pelapor' ? 'Pelapor' : 'Terlapor';

        return new Envelope(
            subject: "Draft Perjanjian Bersama - {$this->pengaduan->nomor_pengaduan}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.draft-perjanjian-bersama',
            with: [
                'pengaduan' => $this->pengaduan,
                'perjanjianBersama' => $this->perjanjianBersama,
                'pihak' => $this->pihak,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        try {
            // Generate PDF for Perjanjian Bersama
            $pdf = Pdf::loadView('dokumen.pdf.perjanjian-bersama', [
                'perjanjian' => $this->perjanjianBersama
            ]);

            return [
                \Illuminate\Mail\Mailables\Attachment::fromData(
                    fn() => $pdf->output(),
                    'Draft_Perjanjian_Bersama_' . $this->pengaduan->nomor_pengaduan . '.pdf'
                )
                    ->withMime('application/pdf')
            ];
        } catch (\Exception $e) {
            \Log::error('Error generating PDF attachment: ' . $e->getMessage());
            return [];
        }
    }
}
