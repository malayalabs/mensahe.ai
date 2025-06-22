<?php

declare(strict_types=1);

namespace Mensahe;

use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialRpEntity;
use Webauthn\PublicKeyCredentialUserEntity;

class PassKeyAuthService
{
    private PublicKeyCredentialRpEntity $rpEntity;

    public function __construct()
    {
        // Relying Party (your application)
        $this->rpEntity = PublicKeyCredentialRpEntity::create(
            'Mensahe', // App name
            'localhost',    // Your domain
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
            'timeout' => 60000,
            'attestation' => 'none',
            'authenticatorSelection' => [
                'authenticatorAttachment' => 'platform',
                'userVerification' => 'preferred'
            ],
            'pubKeyCredParams' => [
                [
                    'type' => 'public-key',
                    'alg' => -7 // ES256
                ]
            ]
        ];
    }
} 