<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Mensahe\Lib\RegisterRequestLib;
use Mensahe\Lib\PassKeyAuthService;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

function main(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        RegisterRequestLib::sendErrorResponse('Method not allowed', 405);
    }

    try {
        RegisterRequestLib::startSessionIfNeeded();
        $data = RegisterRequestLib::getRequestData();
        
        // Validate required fields
        if (!isset($data['username']) || !isset($data['credential'])) {
            RegisterRequestLib::sendErrorResponse('Missing required fields: username and credential');
        }

        $username = RegisterRequestLib::validateUsername(['username' => $data['username']]);
        
        // Initialize the WebAuthn service
        $authService = new PassKeyAuthService();
        
        // Verify the credential
        $verificationResult = $authService->verifyRegistration($data['credential']);
        
        if ($verificationResult['success']) {
            // Store the verified credential (in a real app, save to database)
            // For now, we'll just return success
            RegisterRequestLib::sendSuccessResponse([
                'success' => true,
                'message' => 'Passkey registration completed successfully',
                'username' => $username
            ]);
        } else {
            RegisterRequestLib::sendErrorResponse('Credential verification failed: ' . $verificationResult['error']);
        }
        
    } catch (\Exception $e) {
        RegisterRequestLib::sendErrorResponse('Verification failed: ' . $e->getMessage());
    }
}

// Only run main if this file is called directly
if (php_sapi_name() !== 'cli' && basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    main();
} 