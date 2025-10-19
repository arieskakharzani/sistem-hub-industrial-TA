<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Mediator;

class TestDirectEmailCommand extends Command
{
    protected $signature = 'test:direct-email';
    protected $description = 'Test direct email sending';

    public function handle()
    {
        $this->info('Testing direct email sending...');

        try {
            // Cari Kepala Dinas
            $kepalaDinas = User::whereJsonContains('roles', 'kepala_dinas')
                ->where('is_active', true)
                ->first();

            if (!$kepalaDinas) {
                $this->error('No Kepala Dinas found!');
                return;
            }

            $this->info('Found Kepala Dinas: ' . $kepalaDinas->email);

            // Cari mediator real
            $mediator = Mediator::with('user')->first();
            if (!$mediator) {
                $this->error('No mediator found!');
                return;
            }

            $this->info('Found mediator: ' . $mediator->nama_mediator);
            $this->info('Mediator email: ' . $mediator->user->email);

            // Test 1: Email ke Kepala Dinas
            $this->info('Test 1: Sending email to Kepala Dinas...');
            try {
                Mail::send('emails.new-mediator-registration', [
                    'mediator' => $mediator,
                    'actionUrl' => route('kepala-dinas.mediator.approval.index')
                ], function ($message) use ($kepalaDinas) {
                    $message->to($kepalaDinas->email)
                        ->subject('Test - Mediator Baru Mendaftar - SIPPPHI');
                });
                $this->info('Email to Kepala Dinas sent successfully!');
            } catch (\Exception $e) {
                $this->error('Failed to send email to Kepala Dinas: ' . $e->getMessage());
            }

            // Test 2: Email ke Mediator (rejection)
            $this->info('Test 2: Sending rejection email to Mediator...');
            try {
                Mail::send('emails.mediator-rejected', [
                    'mediator' => $mediator,
                    'registerUrl' => route('mediator.register')
                ], function ($message) use ($mediator) {
                    $message->to($mediator->user->email)
                        ->subject('Test - Registrasi Mediator Ditolak - SIPPPHI');
                });
                $this->info('Rejection email to Mediator sent successfully!');
            } catch (\Exception $e) {
                $this->error('Failed to send rejection email to Mediator: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile() . ':' . $e->getLine());
        }
    }
}
