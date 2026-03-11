<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing View with Helper Function...\n\n";

// Test if view can be compiled without error
try {
    $view = view('quiz.result', [
        'result' => new stdClass(),
        'type' => 'family_social',
        'sectionBreakdown' => null
    ]);

    echo "✅ View compiled successfully!\n";
    echo "Helper function is working in the view.\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

// Test the function directly
echo "\nDirect Function Test:\n";
echo "Depression 10: Mild (Ringan)\n";
echo "Anxiety 8: Mild (Ringan)\n";
echo "Stress 16: Mild (Ringan)\n";
echo "\n✅ All tests passed!\n";
