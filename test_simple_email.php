<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;

try {
    echo "=== Test Simple Email ===\n";

    // Test email sederhana
    $toEmail = 'daarsyaaa@gmail.com'; // Email mediator pertama
    $subject = 'Test Email SIPPPHI - ' . date('Y-m-d H:i:s');
    $message = 'Ini adalah test email dari sistem SIPPPHI untuk memverifikasi bahwa konfigurasi SMTP sudah bekerja dengan baik.';

    echo "Sending test email to: " . $toEmail . "\n";
    echo "Subject: " . $subject . "\n";

    Mail::raw($message, function ($mail) use ($toEmail, $subject) {
        $mail->to($toEmail)
            ->subject($subject);
    });

    echo "✅ Email sent successfully!\n";
    echo "Check inbox: " . $toEmail . "\n";
} catch (Exception $e) {
    echo "❌ Error sending email: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
