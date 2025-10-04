<?php

use App\Mail\DraftPerjanjianBersamaMail;
use App\Mail\FinalCaseDocumentsMail;
use App\Models\PerjanjianBersama;
use App\Models\DokumenHubunganIndustrial;
use App\Models\Pengaduan;
use App\Models\Anjuran;
use Illuminate\Mail\Mailables\Attachment;
use Carbon\Carbon;

uses(Tests\TestCase::class);

describe('Mailables - Unit Tests', function () {

    test('DraftPerjanjianBersamaMail builds envelope and content correctly', function () {
        $pengaduan = new Pengaduan();
        $pengaduan->nomor_pengaduan = 'PGD-2024-0001';
        $dhi = new DokumenHubunganIndustrial();
        $dhi->setRelation('pengaduan', $pengaduan);
        $pb = new PerjanjianBersama();
        $pb->setRelation('dokumenHI', $dhi);
        $mail = new DraftPerjanjianBersamaMail($pb, 'pelapor', null);
        $envelope = $mail->envelope();
        expect($envelope->subject)->toContain('Draft Perjanjian Bersama - PGD-2024-0001');
        $content = $mail->content();
        expect($content->view)->toBe('emails.draft-perjanjian-bersama');
        expect($content->with)->toHaveKeys(['perjanjianBersama', 'pengaduan', 'pihak']);
        expect($content->with['pihak'])->toBe('pelapor');
    });

    test('DraftPerjanjianBersamaMail attaches PDF when provided', function () {
        $pengaduan = new Pengaduan();
        $pengaduan->nomor_pengaduan = 'PGD-2024-0002';
        $dhi = new DokumenHubunganIndustrial();
        $dhi->setRelation('pengaduan', $pengaduan);
        $pb = new PerjanjianBersama();
        $pb->setRelation('dokumenHI', $dhi);
        $pdf = '%PDF-1.7 fake-content%';
        $mail = new DraftPerjanjianBersamaMail($pb, 'terlapor', $pdf);
        $attachments = $mail->attachments();
        expect($attachments)->toHaveCount(1);
        expect($attachments[0])->toBeInstanceOf(Attachment::class);
    });

    test('FinalCaseDocumentsMail builds envelope, content and attachments', function () {
        // Membuat pohon objek minimal: Pengaduan <- DokumenHI <- Anjuran
        $pengaduan = new Pengaduan();
        $pengaduan->nomor_pengaduan = 'PGD-2024-0003';
        $dhi = new DokumenHubunganIndustrial();
        $dhi->setRelation('pengaduan', $pengaduan);
        $anjuran = new Anjuran();
        $anjuran->setRelation('dokumenHI', $dhi);
        // Membuat mail dengan dua lampiran
        $mail = new FinalCaseDocumentsMail($anjuran, '%PDF-risalah%', '%PDF-anjuran%');
        $envelope = $mail->envelope();
        expect($envelope->subject)->toContain('Dokumen Final Kasus - PGD-2024-0003');
        $content = $mail->content();
        expect($content->view)->toBe('emails.final-case-documents');
        expect($content->with)->toHaveKeys(['anjuran', 'pengaduan']);
        $attachments = $mail->attachments();
        expect($attachments)->toHaveCount(2);
        expect($attachments[0])->toBeInstanceOf(Attachment::class);
        expect($attachments[1])->toBeInstanceOf(Attachment::class);
    });
});
