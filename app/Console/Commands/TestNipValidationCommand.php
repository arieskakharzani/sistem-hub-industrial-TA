<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Mediator;
use App\Models\User;

class TestNipValidationCommand extends Command
{
    protected $signature = 'test:nip-validation';
    protected $description = 'Test NIP validation for rejected mediators';

    public function handle()
    {
        $this->info('Testing NIP validation...');

        // Cek mediator yang ditolak
        $rejectedMediator = Mediator::where('status', 'rejected')->first();

        if ($rejectedMediator) {
            $this->info('Found rejected mediator:');
            $this->info('NIP: ' . $rejectedMediator->nip);
            $this->info('Email: ' . $rejectedMediator->user->email);
            $this->info('Status: ' . $rejectedMediator->status);

            // Test validasi NIP
            $this->info('Testing NIP validation...');

            // Simulasi validasi seperti di controller
            $existingMediator = Mediator::where('nip', $rejectedMediator->nip)
                ->where('status', '!=', 'rejected')
                ->first();

            if ($existingMediator) {
                $this->error('NIP validation would FAIL - NIP already exists with non-rejected status');
            } else {
                $this->info('NIP validation would PASS - NIP can be reused');
            }

            // Test validasi email
            $this->info('Testing email validation...');

            $existingUser = User::where('email', $rejectedMediator->user->email)->first();

            if ($existingUser) {
                $mediator = Mediator::where('user_id', $existingUser->user_id)->first();

                if (!$mediator || $mediator->status !== 'rejected') {
                    $this->error('Email validation would FAIL - Email already exists with non-rejected status');
                } else {
                    $this->info('Email validation would PASS - Email can be reused');
                }
            } else {
                $this->info('Email validation would PASS - Email not found');
            }
        } else {
            $this->warn('No rejected mediators found');

            // Buat mediator dummy untuk test
            $this->info('Creating test rejected mediator...');

            $user = User::create([
                'user_id' => (string) \Illuminate\Support\Str::uuid(),
                'email' => 'test-rejected@example.com',
                'password' => bcrypt('password'),
                'roles' => ['mediator'],
                'active_role' => 'mediator',
                'is_active' => false,
            ]);

            $mediator = Mediator::create([
                'mediator_id' => (string) \Illuminate\Support\Str::uuid(),
                'user_id' => $user->user_id,
                'nama_mediator' => 'Test Rejected Mediator',
                'nip' => '123456789012345',
                'status' => 'rejected',
                'rejection_reason' => 'Test rejection',
                'rejection_date' => now(),
            ]);

            $this->info('Test rejected mediator created:');
            $this->info('NIP: ' . $mediator->nip);
            $this->info('Email: ' . $user->email);
        }
    }
}
