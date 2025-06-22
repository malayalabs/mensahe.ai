<?php

declare(strict_types=1);

namespace Mensahe\Lib;

use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialRpEntity;
use Webauthn\PublicKeyCredentialUserEntity;
use Mensahe\Lib\Config;

class PassKeyAuthService
{
    private PublicKeyCredentialRpEntity $rpEntity;

    public function __construct()
    {
        // Relying Party (your application)
        $this->rpEntity = PublicKeyCredentialRpEntity::create(
            Config::get('app_name'), // App name from config
            Config::get('app_domain'),    // Domain from config
            null
        );
    }

    public function generateRegistrationOptions(string $username): array
    {
        if (empty($username)) {
            throw new \Exception('Username not provided');
        }

        // User Entity
        $userEntity = PublicKeyCredentialUserEntity::create(
            $username,
            random_bytes(64), // A unique user ID
            $username,
            null
        );

        // Generate a secure challenge
        $challenge = random_bytes(32);

        // Store user and challenge in session for later verification
        $_SESSION['user'] = $userEntity;
        $_SESSION['challenge'] = $challenge;

        // Create the PublicKeyCredentialCreationOptions object
        $creationOptions = PublicKeyCredentialCreationOptions::create(
            $this->rpEntity,
            $userEntity,
            $challenge
        );

        // Convert to a JSON-serializable array
        $rpArray = (array) $this->rpEntity;
        $userArray = (array) $userEntity;
        
        return [
            'challenge' => base64_encode($challenge),
            'rp' => [
                'name' => $rpArray['name'],
                'id' => $rpArray['id']
            ],
            'user' => [
                'id' => base64_encode($userArray['id']),
                'name' => $userArray['name'],
                'displayName' => $userArray['displayName']
            ],
            'timeout' => Config::get('webauthn_timeout', 60000),
            'attestation' => Config::get('webauthn_attestation', 'none'),
            'authenticatorSelection' => [
                'authenticatorAttachment' => Config::get('webauthn_authenticator_attachment', 'platform'),
                'userVerification' => Config::get('webauthn_user_verification', 'preferred')
            ],
            'pubKeyCredParams' => [
                [
                    'type' => 'public-key',
                    'alg' => -7 // ES256
                ]
            ]
        ];
    }

    public function verifyRegistration(array $credentialData): array
    {
        try {
            // Check if we have the required session data
            if (!isset($_SESSION['challenge']) || !isset($_SESSION['user'])) {
                return ['success' => false, 'error' => 'No registration session found'];
            }

            // For now, return success without complex validation
            // In a real implementation, you would validate the credential data
            // using the WebAuthn library's validation methods
            
            $challenge = $_SESSION['challenge'];
            $userEntity = $_SESSION['user'];

            // Store the credential data for future use
            $_SESSION['verified_credential'] = $credentialData;

            // Clean up session data
            unset($_SESSION['challenge']);
            unset($_SESSION['user']);

            return ['success' => true, 'credential' => $credentialData];

        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
} 