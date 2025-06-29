<?php
// app/Http/Controllers/Debug/EmailTestController.php
// Controller khusus untuk test email di web environment

namespace App\Http\Controllers\Debug;

use App\Http\Controllers\Controller;
use App\Models\JadwalMediasi;
use App\Events\JadwalMediasiCreated;
use App\Services\JadwalNotificationService;
use App\Notifications\JadwalMediasiNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class EmailTestController extends Controller
{
    /**
     * Test email system via web interface
     */
    public function testEmail(Request $request)
    {
        $results = [];
        $results['timestamp'] = now()->format('Y-m-d H:i:s');
        $results['environment'] = app()->environment();

        try {
            // 1. Check configuration
            $results['config'] = [
                'queue_driver' => config('queue.default'),
                'mail_driver' => config('mail.default'),
                'mail_host' => config('mail.mailers.smtp.host'),
                'app_debug' => config('app.debug'),
            ];

            // 2. Check if we have jadwal data
            $jadwal = JadwalMediasi::with(['pengaduan.pelapor', 'pengaduan.terlapor', 'mediator'])->first();

            if (!$jadwal) {
                $results['error'] = 'No jadwal found in database for testing';
                return response()->json($results, 400);
            }

            $results['jadwal'] = [
                'id' => $jadwal->jadwal_id,
                'pengaduan_id' => $jadwal->pengaduan_id,
                'has_pelapor' => $jadwal->pengaduan->pelapor ? true : false,
                'pelapor_email' => $jadwal->pengaduan->pelapor?->email,
                'nama_terlapor' => $jadwal->pengaduan->nama_terlapor,
                'terlapor_id' => $jadwal->pengaduan->terlapor_id,
                'email_terlapor_field' => $jadwal->pengaduan->email_terlapor,
            ];

            // 3. Test notification service
            $service = app(JadwalNotificationService::class);
            $recipients = $service->getRecipients($jadwal);

            $results['recipients'] = [
                'count' => count($recipients),
                'list' => $recipients
            ];

            // 4. Force sync queue for testing
            $originalQueue = config('queue.default');
            config(['queue.default' => 'sync']);

            $results['queue_forced_sync'] = true;

            // 5. Test manual email sending
            if (count($recipients) > 0) {
                $emailResults = [];

                foreach ($recipients as $recipient) {
                    try {
                        Mail::to($recipient['email'])->send(
                            new JadwalMediasiNotification($jadwal, $recipient, 'created')
                        );

                        $emailResults[] = [
                            'role' => $recipient['role'],
                            'email' => $recipient['email'],
                            'status' => 'sent',
                            'error' => null
                        ];
                    } catch (\Exception $e) {
                        $emailResults[] = [
                            'role' => $recipient['role'],
                            'email' => $recipient['email'],
                            'status' => 'failed',
                            'error' => $e->getMessage()
                        ];
                    }
                }

                $results['manual_email_test'] = $emailResults;
            }

            // 6. Test event triggering
            try {
                Log::info('🧪 [EMAIL TEST] Triggering JadwalMediasiCreated event via web');

                event(new JadwalMediasiCreated($jadwal));

                $results['event_test'] = [
                    'status' => 'triggered',
                    'event' => 'JadwalMediasiCreated',
                    'error' => null
                ];

                Log::info('✅ [EMAIL TEST] Event triggered successfully');
            } catch (\Exception $e) {
                $results['event_test'] = [
                    'status' => 'failed',
                    'event' => 'JadwalMediasiCreated',
                    'error' => $e->getMessage()
                ];

                Log::error('❌ [EMAIL TEST] Event failed: ' . $e->getMessage());
            }

            // 7. Restore original queue
            config(['queue.default' => $originalQueue]);

            // 8. Check queue status
            if (config('queue.default') === 'database') {
                try {
                    $results['queue_status'] = [
                        'pending_jobs' => DB::table('jobs')->count(),
                        'failed_jobs' => DB::table('failed_jobs')->count(),
                    ];
                } catch (\Exception $e) {
                    $results['queue_status'] = ['error' => $e->getMessage()];
                }
            }

            // 9. Summary and recommendations
            $results['summary'] = $this->generateSummary($results);

            return response()->json($results, 200);
        } catch (\Exception $e) {
            $results['fatal_error'] = $e->getMessage();
            $results['trace'] = $e->getTraceAsString();

            Log::error('❌ [EMAIL TEST] Fatal error: ' . $e->getMessage());

            return response()->json($results, 500);
        }
    }

    /**
     * Test hanya event trigger tanpa email
     */
    public function testEventOnly(Request $request)
    {
        try {
            $jadwal = JadwalMediasi::with(['pengaduan.pelapor', 'pengaduan.terlapor', 'mediator'])->first();

            if (!$jadwal) {
                return response()->json(['error' => 'No jadwal found'], 400);
            }

            // Force sync
            config(['queue.default' => 'sync']);

            Log::info('🧪 [EVENT ONLY TEST] Starting event test', [
                'jadwal_id' => $jadwal->jadwal_id,
                'queue_driver' => config('queue.default')
            ]);

            // Trigger event
            event(new JadwalMediasiCreated($jadwal));

            Log::info('✅ [EVENT ONLY TEST] Event completed');

            return response()->json([
                'status' => 'success',
                'message' => 'Event triggered successfully. Check logs for details.',
                'jadwal_id' => $jadwal->jadwal_id,
                'queue_driver' => config('queue.default')
            ]);
        } catch (\Exception $e) {
            Log::error('❌ [EVENT ONLY TEST] Failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Test basic email sending tanpa event
     */
    public function testBasicEmail(Request $request)
    {
        try {
            $testEmail = $request->input('email', 'test@example.com');

            Mail::raw('Test email dari web interface - ' . now(), function ($message) use ($testEmail) {
                $message->to($testEmail)->subject('Test Email Web Interface');
            });

            return response()->json([
                'status' => 'success',
                'message' => "Test email sent to {$testEmail}",
                'mail_driver' => config('mail.default'),
                'timestamp' => now()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'mail_config' => [
                    'driver' => config('mail.default'),
                    'host' => config('mail.mailers.smtp.host'),
                    'port' => config('mail.mailers.smtp.port'),
                ]
            ], 500);
        }
    }

    private function generateSummary($results)
    {
        $summary = [];

        // Check recipients
        $recipientCount = $results['recipients']['count'] ?? 0;
        if ($recipientCount >= 2) {
            $summary[] = "✅ Recipients: {$recipientCount}/2 found";
        } else {
            $summary[] = "❌ Recipients: Only {$recipientCount}/2 found";
        }

        // Check email tests
        if (isset($results['manual_email_test'])) {
            $sentCount = 0;
            $failedCount = 0;
            foreach ($results['manual_email_test'] as $test) {
                if ($test['status'] === 'sent') $sentCount++;
                else $failedCount++;
            }

            if ($failedCount === 0) {
                $summary[] = "✅ Manual Email: {$sentCount}/{$sentCount} sent";
            } else {
                $summary[] = "❌ Manual Email: {$sentCount}/" . ($sentCount + $failedCount) . " sent";
            }
        }

        // Check event test
        if (isset($results['event_test'])) {
            if ($results['event_test']['status'] === 'triggered') {
                $summary[] = "✅ Event: Triggered successfully";
            } else {
                $summary[] = "❌ Event: Failed - " . $results['event_test']['error'];
            }
        }

        // Check queue
        $queueDriver = $results['config']['queue_driver'] ?? 'unknown';
        if ($queueDriver === 'sync') {
            $summary[] = "✅ Queue: Sync (immediate processing)";
        } else {
            $pendingJobs = $results['queue_status']['pending_jobs'] ?? 0;
            if ($pendingJobs > 0) {
                $summary[] = "⚠️  Queue: {$pendingJobs} pending jobs (worker needed)";
            } else {
                $summary[] = "✅ Queue: {$queueDriver} (no pending jobs)";
            }
        }

        return $summary;
    }
}
