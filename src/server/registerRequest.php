<?php

declare(strict_types=1);

require_once __DIR__ . '/RegisterRequestLib.php';

function main(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        sendErrorResponse('Method not allowed', 405);
    }

    try {
        startSessionIfNeeded();
        $data = getRequestData();
        $username = validateUsername($data);
        registerUser($username);
        sendSuccessResponse(['success' => true, 'username' => $username]);
    } catch (Exception $e) {
        sendErrorResponse('Invalid request');
    }
}

// Only run main if this file is called directly (not included by tests)
if (php_sapi_name() !== 'cli' && basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    main();
}
