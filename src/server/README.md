# Mensahe PHP Backend

A secure, environment-driven PHP backend for passkey authentication with comprehensive testing and modern API design.

## 🚀 Quick Start

### Prerequisites
- PHP 8.0+ with Composer
- Modern web browser with WebAuthn support

### Installation
```bash
# Install dependencies
composer install

# Start the development server
./start-server.sh
# Or from project root:
# ./start-server.sh
```

The server will be available at `http://localhost:8080`

### Test the Integration
1. Open `http://localhost:8080/test-frontend.html` in your browser
2. Enter an email address
3. Click "Register with passkey"
4. Verify the backend returns registration options

## 🏗️ Architecture

The backend uses a service-oriented architecture with environment-driven configuration:

### Core Components
- **`PassKeyAuthService.php`**: WebAuthn authentication service
- **`RegisterRequestLib.php`**: Core utility functions and validation
- **`Config.php`**: Environment-driven configuration management
- **`registerRequest.php`**: Registration endpoint
- **`registerVerify.php`**: Verification endpoint

### Key Features
- **Environment-driven configuration** - All settings via environment variables
- **Email validation** - Server-side email format validation
- **Session management** - Secure challenge storage
- **CORS support** - Configurable cross-origin policies
- **Comprehensive testing** - 90%+ test coverage

## 🔧 Configuration

All configuration is managed through environment variables:

```bash
# App Configuration
APP_NAME=Mensahe
APP_ENV=development
APP_DEBUG=false
APP_URL=http://localhost:8080
APP_DOMAIN=localhost

# WebAuthn Configuration
WEBAUTHN_TIMEOUT=60000
WEBAUTHN_ATTESTATION=none
WEBAUTHN_USER_VERIFICATION=preferred
WEBAUTHN_AUTHENTICATOR_ATTACHMENT=platform

# CORS Configuration
CORS_ALLOWED_ORIGINS=*
CORS_ALLOWED_METHODS=GET,POST,OPTIONS
CORS_ALLOWED_HEADERS=Content-Type

# Session Configuration
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

## 🧪 Testing

For comprehensive testing documentation, see [TESTING.md](../../TESTING.md).

### Quick Test Run

```bash
# Run all tests
./run-tests.sh

# Run specific test files
./vendor/bin/phpunit tests/ConfigTest.php
./vendor/bin/phpunit tests/PassKeyAuthServiceTest.php
./vendor/bin/phpunit tests/RegisterRequestTest.php
```

### Test Coverage
Current test coverage includes:
- ✅ Config class (environment variable handling)
- ✅ PassKeyAuthService (registration and verification)
- ✅ RegisterRequestLib (validation and utilities)
- ✅ Email validation (various formats)
- ✅ Session management
- ✅ Error handling

## 📡 API Endpoints

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
  "attestation": "none",
  "authenticatorSelection": {
    "authenticatorAttachment": "platform",
    "userVerification": "preferred"
  },
  "pubKeyCredParams": [
    {
      "type": "public-key",
      "alg": -7
    }
  ]
}
```

**Error Response**:
```json
{
  "error": "Invalid email address"
}
```

### POST /registerVerify.php

Verifies the passkey registration after credential creation.

**Request**:
```json
{
  "username": "user@example.com",
  "credential": {
    "id": "credential-id",
    "type": "public-key",
    "rawId": "base64-encoded-raw-id",
    "response": {
      "clientDataJSON": "base64-encoded-client-data",
      "attestationObject": "base64-encoded-attestation-object"
    }
  }
}
```

**Response**:
```json
{
  "success": true,
  "message": "Passkey registration completed successfully",
  "username": "user@example.com"
}
```

**Error Response**:
```json
{
  "error": "No registration session found"
}
```

## 📁 File Structure

```
src/server/
├── lib/                    # Core library classes
│   ├── Config.php         # Environment-driven configuration
│   ├── PassKeyAuthService.php  # WebAuthn authentication service
│   └── RegisterRequestLib.php  # Core utility functions
├── tests/                  # PHPUnit test files
│   ├── ConfigTest.php     # Configuration tests
│   ├── PassKeyAuthServiceTest.php  # Authentication service tests
│   └── RegisterRequestTest.php     # Utility function tests
├── registerRequest.php     # Registration endpoint
├── registerVerify.php      # Verification endpoint
├── test-frontend.html      # Test interface
├── start-server.sh         # Development server script
├── run-tests.sh           # Test runner script
├── composer.json          # PHP dependencies
├── phpunit.xml           # PHPUnit configuration
├── README.md             # This file
└── TESTING.md            # Comprehensive testing guide
```

## 🔐 Security Considerations

- **Email validation** - Server-side validation of email format
- **Session security** - Secure challenge storage in sessions
- **CORS headers** - Configurable cross-origin policies
- **Input validation** - All inputs validated before processing
- **Error handling** - Secure error responses without information leakage
- **Environment configuration** - No hardcoded secrets or URLs

## 🚀 Development

### Adding New Features
1. Create feature branch
2. Add comprehensive tests
3. Implement the feature
4. Ensure all tests pass
5. Update documentation
6. Submit pull request

### Code Standards
- Follow PSR-12 coding standards
- All code must have unit tests
- Use environment variables for configuration
- Document all public methods and classes

### Testing Best Practices
- Test both success and error cases
- Mock external dependencies
- Use descriptive test names
- Maintain test isolation

## 📚 Additional Documentation

- [Testing Guide](TESTING.md) - Comprehensive testing documentation
- [API Examples](test-frontend.html) - Interactive test interface

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Add tests for new functionality
4. Implement the feature
5. Ensure all tests pass
6. Update documentation
7. Submit a pull request

## 📄 License

This project is licensed under the MIT License.
