<?php

require_once 'vendor/autoload.php';

use App\Http\Controllers\Dokumen\AnjuranController;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST CONTROLLER ===\n\n";

try {
    // Test controller creation
    echo "1. Testing controller creation:\n";
    $controller = new AnjuranController();
    echo "✅ Controller berhasil dibuat\n\n";

    // Test method exists
    echo "2. Testing method exists:\n";
    if (method_exists($controller, 'pendingApproval')) {
        echo "✅ Method pendingApproval ada\n\n";
    } else {
        echo "❌ Method pendingApproval tidak ada\n\n";
    }

    // Test method accessibility
    echo "3. Testing method accessibility:\n";
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('pendingApproval');
    echo "✅ Method accessible\n\n";

    // Test method parameters
    echo "4. Testing method parameters:\n";
    $parameters = $method->getParameters();
    echo "Jumlah parameter: " . count($parameters) . "\n";
    foreach ($parameters as $param) {
        echo "- Parameter: " . $param->getName() . " (required: " . ($param->isOptional() ? 'no' : 'yes') . ")\n";
    }
    echo "\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "=== SELESAI ===\n";
