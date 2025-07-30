<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\DB;

try {
    echo "=== Test Queue Worker ===\n";

    // Test 1: Cek konfigurasi queue
    echo "1. Checking queue configuration...\n";
    $queueConfig = config('queue');
    echo "   Default connection: " . $queueConfig['default'] . "\n";

    // Test 2: Cek jobs di database
    echo "\n2. Checking jobs in database...\n";
    $jobsCount = DB::table('jobs')->count();
    echo "   Pending jobs: " . $jobsCount . "\n";

    if ($jobsCount > 0) {
        echo "   Processing jobs...\n";

        // Simulate queue worker
        $jobs = DB::table('jobs')->get();
        foreach ($jobs as $job) {
            echo "   - Processing job ID: " . $job->id . "\n";

            // Decode job payload
            $payload = json_decode($job->payload, true);
            if (isset($payload['displayName'])) {
                echo "     Job type: " . $payload['displayName'] . "\n";
            }
        }
    }

    // Test 3: Cek failed jobs
    echo "\n3. Checking failed jobs...\n";
    $failedJobsCount = DB::table('failed_jobs')->count();
    echo "   Failed jobs: " . $failedJobsCount . "\n";

    if ($failedJobsCount > 0) {
        $failedJobs = DB::table('failed_jobs')->get();
        foreach ($failedJobs as $job) {
            echo "   - Failed job: " . $job->exception . "\n";
        }
    }

    // Test 4: Cek notifications di database
    echo "\n4. Checking notifications in database...\n";
    $notificationsCount = DB::table('notifications')->count();
    echo "   Total notifications: " . $notificationsCount . "\n";

    if ($notificationsCount > 0) {
        $recentNotifications = DB::table('notifications')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($recentNotifications as $notification) {
            echo "   - " . $notification->type . " to " . $notification->notifiable_type . "\n";
            echo "     Created: " . $notification->created_at . "\n";
        }
    }

    echo "\n=== Test completed ===\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
