<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

use Mensahe\PassKeyAuthService;

// Enable CORS for frontend communication
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

session_start();

try {
    // Get the username from the POST request
    $request_body = file_get_contents('php://input');
    if (empty($request_body)) {
        throw new Exception('Request body is empty');
    }
    
    $data = json_decode($request_body, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON in request body');
    }
    
    $username = $data['username'] ?? '';

    $authService = new PassKeyAuthService();
    $creationOptions = $authService->generateRegistrationOptions($username);

    echo json_encode($creationOptions);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
} 