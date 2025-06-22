<?php

declare(strict_types=1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

function startSessionIfNeeded(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function getRequestData(): array
{
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    if (!is_array($data)) {
        throw new Exception('Invalid JSON');
    }
    return $data;
}

function validateUsername(array $data): string
{
    $username = isset($data['username']) ? trim($data['username']) : '';
    if (empty($username) || !preg_match('/^[a-zA-Z0-9_\-]{3,32}$/', $username)) {
        throw new Exception('Invalid username');
    }
    return $username;
}

function registerUser(string $username): void
{
    // Registration logic placeholder
    // For example, save to database or file
    // throw new Exception('Registration failed'); // Uncomment to simulate failure
}

function sendErrorResponse(string $message, int $code = 400): void
{
    http_response_code($code);
    echo json_encode(['error' => $message]);
    exit;
}

function sendSuccessResponse(array $data): void
{
    http_response_code(200);
    echo json_encode($data);
    exit;
}

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

main();
