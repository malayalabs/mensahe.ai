# Mensahe PHP Backend

This directory contains the PHP server-side logic for the Mensahe chat widget, handling user registration and passkey (WebAuthn) authentication.

## Architecture

The backend uses a service-oriented architecture with the `PassKeyAuthService` class handling all WebAuthn-related operations. This provides better separation of concerns and makes the code more testable and maintainable.

### Key Components

- **`PassKeyAuthService.php`**: Main service class for WebAuthn operations
- **`registerRequest.php`**: HTTP endpoint for initiating passkey registration
- **`test-registration.php`**: Command-line test script for the service
- **`test-frontend.html`**: Web-based test page for frontend integration

## Local Development

To run this backend locally, you will need a PHP development environment (like XAMPP, MAMP, or the built-in PHP server).

### Quick Start

1. **Install Dependencies**:
   ```bash
   cd src/server
   composer install
   ```

2. **Start the Server**:
   ```bash
   # Option 1: Use the convenience script
   ./start-server.sh

   # Option 2: Manual start
   php -S localhost:8080
   ```

3. **Test the Backend**:
   ```bash
   # Test the service directly
   php test-registration.php

   # Test via web interface
   # Open http://localhost:8080/test-frontend.html in your browser
   ```

## Testing

### Command Line Testing

Run the service test to verify everything works:

```bash
php test-registration.php
```

Expected output:
```
Testing PassKeyAuthService...

Testing registration options generation for user: testuser@example.com
✅ Success! Generated registration options object:
- Object type: Webauthn\PublicKeyCredentialCreationOptions
- Object created successfully

Testing with empty username (should fail):
✅ Correctly caught exception: Username not provided
```

### Web-based Testing

1. Start the server: `php -S localhost:8080`
2. Open `http://localhost:8080/test-frontend.html` in your browser
3. Enter a username and click "Test Registration Endpoint"
4. Verify you receive a successful response with registration options

### Extension Integration Testing

1. Build the extension: `npm run build:extension` (from project root)
2. Load the extension in Chrome from `extension-dist/`
3. Navigate to any website
4. Click the Mensahe launcher icon
5. Enter a username and click "Sign In"
6. Verify the status message shows "Registration options received!"

## API Endpoints

### POST /registerRequest.php

Initiates the passkey registration process.

**Request**:
```json
{
  "username": "user@example.com"
}
```

**Response**:
```json
{
  "challenge": "base64-encoded-challenge",
  "rp": {
    "name": "Mensahe",
    "id": "localhost"
  },
  "user": {
    "id": "base64-encoded-user-id",
    "name": "user@example.com",
    "displayName": "user@example.com"
  },
  "timeout": 60000,
  "attestation": "none"
}
```

**Error Response**:
```json
{
  "error": "Error message"
}
```

## File Structure

- `/PassKeyAuthService.php`: Main service class for WebAuthn operations
- `/registerRequest.php`: Generates the challenge and options for a new passkey registration
- `/test-registration.php`: Command-line test script
- `/test-frontend.html`: Web-based test interface
- `/start-server.sh`: Convenience script to start the development server
- `/composer.json`: PHP dependencies and autoloading configuration
- `/vendor/`: Composer dependencies (generated)

## Security Considerations

- CORS headers are configured for development (should be restricted in production)
- Input validation is implemented for all endpoints
- Sessions are used to store challenge data securely
- The WebAuthn library handles cryptographic operations

## Next Steps

- Implement `/register-verify.php` to complete the registration flow
- Add `/login-request.php` and `/login-verify.php` for authentication
- Implement proper database storage for user credentials
- Add production-ready CORS configuration
- Implement rate limiting and additional security measures
