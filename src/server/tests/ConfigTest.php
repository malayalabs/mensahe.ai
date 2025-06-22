<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Mensahe\Lib\Config;

final class ConfigTest extends TestCase
{
    protected function setUp(): void
    {
        // Reset configuration for each test
        Config::reset();
        
        // Clear any existing environment variables for testing
        putenv('APP_NAME');
        putenv('APP_ENV');
        putenv('APP_DEBUG');
        putenv('APP_URL');
        putenv('APP_DOMAIN');
        putenv('WEBAUTHN_TIMEOUT');
    }

    public function testGetWithDefaultValue(): void
    {
        $value = Config::get('nonexistent_key', 'default_value');
        $this->assertSame('default_value', $value);
    }

    public function testGetAppNameWithDefault(): void
    {
        $appName = Config::get('app_name');
        $this->assertSame('Mensahe', $appName);
    }

    public function testGetAppNameFromEnvironment(): void
    {
        putenv('APP_NAME=TestApp');
        Config::reset(); // Reset to pick up new env var
        $appName = Config::get('app_name');
        $this->assertSame('TestApp', $appName);
    }

    public function testGetAppEnvWithDefault(): void
    {
        putenv('APP_ENV'); // Unset to ensure default is used
        Config::reset();
        $appEnv = Config::get('app_env');
        $this->assertSame('testing', $appEnv); // PHPUnit sets APP_ENV=testing
    }

    public function testGetAppEnvFromEnvironment(): void
    {
        putenv('APP_ENV=production');
        $_ENV['APP_ENV'] = 'production';
        Config::reset();
        $appEnv = Config::get('app_env');
        $this->assertSame('production', $appEnv);
    }

    public function testGetWebAuthnTimeoutWithDefault(): void
    {
        $timeout = Config::get('webauthn_timeout');
        $this->assertSame(60000, $timeout);
    }

    public function testGetWebAuthnTimeoutFromEnvironment(): void
    {
        putenv('WEBAUTHN_TIMEOUT=30000');
        Config::reset(); // Reset to pick up new env var
        $timeout = Config::get('webauthn_timeout');
        $this->assertSame(30000, $timeout);
    }

    public function testIsProductionReturnsTrueWhenSet(): void
    {
        putenv('APP_ENV=production');
        $_ENV['APP_ENV'] = 'production';
        Config::reset();
        $this->assertTrue(Config::isProduction());
    }

    public function testIsDevelopmentReturnsTrueByDefault(): void
    {
        putenv('APP_ENV'); // Unset to ensure default is used
        Config::reset();
        $this->assertFalse(Config::isDevelopment()); // Should be false in 'testing'
    }

    public function testAllReturnsCompleteConfig(): void
    {
        $config = Config::all();
        $this->assertIsArray($config);
        $this->assertArrayHasKey('app_name', $config);
        $this->assertArrayHasKey('app_env', $config);
        $this->assertArrayHasKey('webauthn_timeout', $config);
        $this->assertArrayHasKey('cors_allowed_origins', $config);
    }

    public function testGetAppDebugWithDefault(): void
    {
        $debug = Config::get('app_debug');
        $this->assertFalse($debug);
    }

    public function testGetAppDebugFromEnvironment(): void
    {
        putenv('APP_DEBUG=true');
        Config::reset(); // Reset to pick up new env var
        $debug = Config::get('app_debug');
        $this->assertTrue($debug);
    }
} 