<?php

declare(strict_types=1);

namespace Mensahe\Lib;

use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialRpEntity;
use Webauthn\PublicKeyCredentialUserEntity;
use Webauthn\PublicKeyCredentialSource;
use Webauthn\AuthenticatorAttestationResponse;
use Webauthn\PublicKeyCredentialLoader;
use Webauthn\AuthenticatorAttestationResponseValidator;
use Webauthn\PublicKeyCredentialSourceRepository;
use Webauthn\PublicKeyCredentialDescriptor;

class PassKeyAuthService
{
    private PublicKeyCredentialRpEntity $rpEntity;
    private PublicKeyCredentialLoader $loader;
    private AuthenticatorAttestationResponseValidator $validator;

    public function __construct()
    {
        // Relying Party (your application)
        $this->rpEntity = PublicKeyCredentialRpEntity::create(
            'Mensahe', // App name
            'localhost',    // Your domain
            null
        );

        // Initialize WebAuthn components
        $this->loader = new PublicKeyCredentialLoader();
        $this->validator = new AuthenticatorAttestationResponseValidator(
            new class implements PublicKeyCredentialSourceRepository {
                public function findOneByCredentialId(string $publicKeyCredentialId): ?PublicKeyCredentialSource
                {
                    // In a real implementation, this would query a database
                    // For now, return null (no existing credentials)
                    return null;
                }

                public function findAllForUserEntity(PublicKeyCredentialUserEntity $publicKeyCredentialUserEntity): array
                {
                    // In a real implementation, this would query a database
                    // For now, return empty array
                    return [];
                }

                public function saveCredentialSource(PublicKeyCredentialSource $publicKeyCredentialSource): void
                {
                    // In a real implementation, this would save to a database
                    // For now, just log or store in session
                    $_SESSION['saved_credential'] = $publicKeyCredentialSource;
                }
            },
            null, // No attestation object loader for now
            null, // No token binding handler for now
            null  // No extension output checker for now
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

    public function verifyRegistration(array $credentialData): array
    {
        try {
            // Check if we have the required session data
            if (!isset($_SESSION['challenge']) || !isset($_SESSION['user'])) {
                return ['success' => false, 'error' => 'No registration session found'];
            }

            $challenge = $_SESSION['challenge'];
            $userEntity = $_SESSION['user'];

            // Convert the credential data back to the format expected by the library
            $credential = $this->loader->load(json_encode($credentialData));

            // Verify the attestation response
            $publicKeyCredentialSource = $this->validator->check(
                $credential->getResponse(),
                PublicKeyCredentialCreationOptions::create(
                    $this->rpEntity,
                    $userEntity,
                    $challenge
                ),
                null // No HTTP request for now
            );

            // If we get here, verification was successful
            // In a real implementation, you would save the credential source to a database
            $_SESSION['verified_credential'] = $publicKeyCredentialSource;

            // Clean up session data
            unset($_SESSION['challenge']);
            unset($_SESSION['user']);

            return ['success' => true, 'credential' => $publicKeyCredentialSource];

        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
} 