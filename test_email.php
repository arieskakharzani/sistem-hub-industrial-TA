<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Mail;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    Mail::raw('Test email dari SIPPPHI System', function ($message) {
        $message->to('test@example.com')
            ->subject('Test Email - ' . now());
    });
    echo "Email test berhasil dikirim ke log\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
