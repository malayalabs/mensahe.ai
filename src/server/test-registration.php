<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

use Mensahe\PassKeyAuthService;

echo "Testing PassKeyAuthService...\n\n";

try {
    $authService = new PassKeyAuthService();
    
    // Test with a valid username
    $username = 'testuser@example.com';
    echo "Testing registration options generation for user: $username\n";
    
    $creationOptions = $authService->generateRegistrationOptions($username);
    
    echo "✅ Success! Generated registration options object:\n";
    echo "- Object type: " . get_class($creationOptions) . "\n";
    echo "- Object created successfully\n";
    
    // Test with empty username (should throw exception)
    echo "\nTesting with empty username (should fail):\n";
    try {
        $authService->generateRegistrationOptions('');
        echo "❌ Error: Should have thrown an exception\n";
    } catch (Exception $e) {
        echo "✅ Correctly caught exception: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Test failed: " . $e->getMessage() . "\n";
} 