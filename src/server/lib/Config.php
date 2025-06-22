<?php

declare(strict_types=1);

namespace Mensahe\Lib;

class Config
{
    private static array $config = [];
    private static bool $initialized = false;

    public static function init(): void
    {
        self::$config = [
            'app_name' => self::getEnv('APP_NAME', 'Mensahe'),
            'app_env' => self::getEnv('APP_ENV', 'development'),
            'app_debug' => self::getEnv('APP_DEBUG', 'false') === 'true',
            'app_url' => self::getEnv('APP_URL', 'http://localhost:8080'),
            'app_domain' => self::getEnv('APP_DOMAIN', 'localhost'),
            'session_driver' => self::getEnv('SESSION_DRIVER', 'file'),
            'session_lifetime' => (int) self::getEnv('SESSION_LIFETIME', '120'),
            'webauthn_timeout' => (int) self::getEnv('WEBAUTHN_TIMEOUT', '60000'),
            'webauthn_attestation' => self::getEnv('WEBAUTHN_ATTESTATION', 'none'),
            'webauthn_user_verification' => self::getEnv('WEBAUTHN_USER_VERIFICATION', 'preferred'),
            'webauthn_authenticator_attachment' => self::getEnv('WEBAUTHN_AUTHENTICATOR_ATTACHMENT', 'platform'),
            'cors_allowed_origins' => self::getEnv('CORS_ALLOWED_ORIGINS', '*'),
            'cors_allowed_methods' => self::getEnv('CORS_ALLOWED_METHODS', 'GET,POST,OPTIONS'),
            'cors_allowed_headers' => self::getEnv('CORS_ALLOWED_HEADERS', 'Content-Type'),
        ];
        self::$initialized = true;
    }

    public static function get(string $key, $default = null)
    {
        if (!self::$initialized) {
            self::init();
        }
        
        return self::$config[$key] ?? $default;
    }

    public static function all(): array
    {
        if (!self::$initialized) {
            self::init();
        }
        
        return self::$config;
    }

    private static function getEnv(string $key, string $default = ''): string
    {
        // Check $_ENV first (for .env files), then $_SERVER, then getenv()
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
        return $value !== false ? $value : $default;
    }

    public static function isProduction(): bool
    {
        return self::get('app_env') === 'production';
    }

    public static function isDevelopment(): bool
    {
        return self::get('app_env') === 'development';
    }

    public static function isTesting(): bool
    {
        return self::get('app_env') === 'testing';
    }

    /**
     * Reset configuration for testing purposes
     */
    public static function reset(): void
    {
        self::$config = [];
        self::$initialized = false;
    }
} 