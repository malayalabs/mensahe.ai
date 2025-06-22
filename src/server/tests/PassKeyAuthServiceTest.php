<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Mensahe\Lib\PassKeyAuthService;
use Mensahe\Lib\Config;

final class PassKeyAuthServiceTest extends TestCase
{
    private PassKeyAuthService $authService;

    protected function setUp(): void
    {
        // Reset config for testing
        Config::reset();
        
        // Set test environment variables
        putenv('APP_NAME=TestApp');
        putenv('APP_DOMAIN=test.example.com');
        putenv('WEBAUTHN_TIMEOUT=30000');
        putenv('WEBAUTHN_ATTESTATION=direct');
        putenv('WEBAUTHN_USER_VERIFICATION=required');
        putenv('WEBAUTHN_AUTHENTICATOR_ATTACHMENT=cross-platform');
        
        $this->authService = new PassKeyAuthService();
    }

    protected function tearDown(): void
    {
        // Clean up session data
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        
        // Clear environment variables
        putenv('APP_NAME');
        putenv('APP_DOMAIN');
        putenv('WEBAUTHN_TIMEOUT');
        putenv('WEBAUTHN_ATTESTATION');
        putenv('WEBAUTHN_USER_VERIFICATION');
        putenv('WEBAUTHN_AUTHENTICATOR_ATTACHMENT');
    }

    public function testConstructorUsesConfigValues(): void
    {
        $options = $this->authService->generateRegistrationOptions('test@example.com');
        
        $this->assertSame('TestApp', $options['rp']['name']);
        $this->assertSame('test.example.com', $options['rp']['id']);
        $this->assertSame(30000, $options['timeout']);
        $this->assertSame('direct', $options['attestation']);
        $this->assertSame('required', $options['authenticatorSelection']['userVerification']);
        $this->assertSame('cross-platform', $options['authenticatorSelection']['authenticatorAttachment']);
    }

    public function testGenerateRegistrationOptionsWithValidEmail(): void
    {
        $options = $this->authService->generateRegistrationOptions('user@example.com');
        
        $this->assertIsArray($options);
        $this->assertArrayHasKey('challenge', $options);
        $this->assertArrayHasKey('rp', $options);
        $this->assertArrayHasKey('user', $options);
        $this->assertArrayHasKey('timeout', $options);
        $this->assertArrayHasKey('attestation', $options);
        $this->assertArrayHasKey('authenticatorSelection', $options);
        $this->assertArrayHasKey('pubKeyCredParams', $options);
        
        // Check challenge is base64 encoded
        $this->assertIsString($options['challenge']);
        $this->assertNotEmpty($options['challenge']);
        
        // Check user data
        $this->assertSame('user@example.com', $options['user']['name']);
        $this->assertSame('user@example.com', $options['user']['displayName']);
        $this->assertIsString($options['user']['id']);
        $this->assertNotEmpty($options['user']['id']);
        
        // Check RP data
        $this->assertSame('TestApp', $options['rp']['name']);
        $this->assertSame('test.example.com', $options['rp']['id']);
        
        // Check pubKeyCredParams
        $this->assertIsArray($options['pubKeyCredParams']);
        $this->assertCount(1, $options['pubKeyCredParams']);
        $this->assertSame('public-key', $options['pubKeyCredParams'][0]['type']);
        $this->assertSame(-7, $options['pubKeyCredParams'][0]['alg']);
    }

    public function testGenerateRegistrationOptionsWithEmptyUsername(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Username not provided');
        
        $this->authService->generateRegistrationOptions('');
    }

    public function testGenerateRegistrationOptionsStoresSessionData(): void
    {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->authService->generateRegistrationOptions('user@example.com');
        
        $this->assertArrayHasKey('challenge', $_SESSION);
        $this->assertArrayHasKey('user', $_SESSION);
        $this->assertIsString($_SESSION['challenge']);
        $this->assertNotEmpty($_SESSION['challenge']);
    }

    public function testVerifyRegistrationWithNoSession(): void
    {
        // Ensure no session is active and clear any session data
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        
        // Clear any session data that might persist
        $_SESSION = [];
        
        $result = $this->authService->verifyRegistration([
            'id' => 'test-id',
            'type' => 'public-key',
            'rawId' => 'test-raw-id',
            'response' => [
                'clientDataJSON' => 'test-client-data',
                'attestationObject' => 'test-attestation'
            ]
        ]);
        
        $this->assertIsArray($result);
        $this->assertFalse($result['success']);
        $this->assertSame('No registration session found', $result['error']);
    }

    public function testVerifyRegistrationWithValidSession(): void
    {
        // Start session and create registration data
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->authService->generateRegistrationOptions('user@example.com');
        
        $credentialData = [
            'id' => 'test-id',
            'type' => 'public-key',
            'rawId' => 'test-raw-id',
            'response' => [
                'clientDataJSON' => 'test-client-data',
                'attestationObject' => 'test-attestation'
            ]
        ];
        
        $result = $this->authService->verifyRegistration($credentialData);
        
        $this->assertIsArray($result);
        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('credential', $result);
        $this->assertSame($credentialData, $result['credential']);
        
        // Check that session data was cleaned up
        $this->assertArrayNotHasKey('challenge', $_SESSION);
        $this->assertArrayNotHasKey('user', $_SESSION);
        $this->assertArrayHasKey('verified_credential', $_SESSION);
    }

    public function testVerifyRegistrationCleansUpSession(): void
    {
        // Start session and create registration data
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->authService->generateRegistrationOptions('user@example.com');
        
        // Verify session data exists
        $this->assertArrayHasKey('challenge', $_SESSION);
        $this->assertArrayHasKey('user', $_SESSION);
        
        $result = $this->authService->verifyRegistration([
            'id' => 'test-id',
            'type' => 'public-key',
            'rawId' => 'test-raw-id',
            'response' => [
                'clientDataJSON' => 'test-client-data',
                'attestationObject' => 'test-attestation'
            ]
        ]);
        
        // Check that session data was cleaned up
        $this->assertArrayNotHasKey('challenge', $_SESSION);
        $this->assertArrayNotHasKey('user', $_SESSION);
        $this->assertArrayHasKey('verified_credential', $_SESSION);
    }

    public function testUsesDefaultConfigValuesWhenNotSet(): void
    {
        // Clear environment variables to test defaults
        putenv('WEBAUTHN_TIMEOUT');
        putenv('WEBAUTHN_ATTESTATION');
        putenv('WEBAUTHN_USER_VERIFICATION');
        putenv('WEBAUTHN_AUTHENTICATOR_ATTACHMENT');
        
        Config::reset();
        $authService = new PassKeyAuthService();
        
        $options = $authService->generateRegistrationOptions('user@example.com');
        
        $this->assertSame(60000, $options['timeout']);
        $this->assertSame('none', $options['attestation']);
        $this->assertSame('preferred', $options['authenticatorSelection']['userVerification']);
        $this->assertSame('platform', $options['authenticatorSelection']['authenticatorAttachment']);
    }
} 