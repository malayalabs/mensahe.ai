<?php

declare(strict_types=1);

namespace Mensahe\Lib;

class RegisterRequestLib
{
    public static function startSessionIfNeeded(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function getRequestData(?string $input = null): array
    {
        if ($input === null) {
            $input = file_get_contents('php://input');
        }
        $data = json_decode($input, true);
        if (!is_array($data)) {
            throw new \Exception('Invalid JSON');
        }
        return $data;
    }

    public static function validateUsername(array $data): string
    {
        $username = isset($data['username']) ? trim($data['username']) : '';
        if (empty($username) || !preg_match('/^[a-zA-Z0-9_\-]{3,32}$/', $username)) {
            throw new \Exception('Invalid username');
        }
        return $username;
    }

    public static function registerUser(string $username): void
    {
        // Registration logic placeholder
        // For example, save to database or file
        // throw new \Exception('Registration failed'); // Uncomment to simulate failure
    }

    public static function sendErrorResponse(string $message, int $code = 400): void
    {
        http_response_code($code);
        echo json_encode(['error' => $message]);
        exit;
    }

    public static function sendSuccessResponse(array $data): void
    {
        http_response_code(200);
        echo json_encode($data);
        exit;
    }
} 