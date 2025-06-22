# Testing Guide

Comprehensive testing documentation for the Mensahe.ai passkey authentication system.

## 🧪 Overview

This project includes comprehensive testing across all components:
- **Backend (PHP)**: Unit tests with PHPUnit, 90%+ coverage
- **Frontend (HTML/JS)**: Manual testing interface
- **Integration**: End-to-end testing between frontend and backend
- **Extension**: Chrome extension testing

## 🚀 Quick Start

### Backend Testing
```bash
# From project root
cd src/server
./run-tests.sh

# Or run specific test files
./vendor/bin/phpunit tests/ConfigTest.php
./vendor/bin/phpunit tests/PassKeyAuthServiceTest.php
./vendor/bin/phpunit tests/RegisterRequestTest.php
```

### Frontend Testing
```bash
# Start the backend server
./start-server.sh

# Open in browser
open http://localhost:8080/test-frontend.html
```

## 📊 Test Coverage

### Backend Coverage (90%+)
- ✅ **Config Class**: Environment variable handling, defaults, validation
- ✅ **PassKeyAuthService**: Registration options, verification, session management
- ✅ **RegisterRequestLib**: Email validation, request handling, error responses
- ✅ **Integration**: API endpoints, CORS, JSON handling

### Test Categories
1. **Unit Tests**: Individual class and method testing
2. **Integration Tests**: API endpoint testing
3. **Manual Tests**: Frontend interface testing
4. **End-to-End Tests**: Complete authentication flow

## 🔧 Backend Testing

### Test Structure
```
src/server/tests/
├── ConfigTest.php              # Configuration management tests
├── PassKeyAuthServiceTest.php  # WebAuthn service tests
└── RegisterRequestTest.php     # Request handling tests
```

### Running Tests

#### All Tests
```bash
cd src/server
./run-tests.sh
```

#### Individual Test Files
```bash
# Configuration tests
./vendor/bin/phpunit tests/ConfigTest.php

# Authentication service tests
./vendor/bin/phpunit tests/PassKeyAuthServiceTest.php

# Request handling tests
./vendor/bin/phpunit tests/RegisterRequestTest.php
```

#### With Coverage
```bash
./vendor/bin/phpunit --coverage-html coverage/html
open coverage/html/index.html
```

### Test Configuration

#### PHPUnit Configuration (`phpunit.xml`)
```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true"
         processIsolation="false"
         stopOnFailure="false"
         cacheDirectory=".phpunit.cache">
    <testsuites>
        <testsuite name="Mensahe Tests">
            <directory>tests</directory>
        </testsuite>
    </testsuites>
    <coverage>
        <include>
            <directory suffix=".php">lib</directory>
        </include>
    </coverage>
</phpunit>
```

#### Environment Variables for Testing
```bash
# Set for testing environment
export APP_ENV=testing
export APP_NAME=TestApp
export APP_DOMAIN=test.example.com
export WEBAUTHN_TIMEOUT=30000
export WEBAUTHN_ATTESTATION=direct
export WEBAUTHN_USER_VERIFICATION=required
export WEBAUTHN_AUTHENTICATOR_ATTACHMENT=cross-platform
```

### Test Categories

#### 1. Config Tests (`ConfigTest.php`)
Tests environment-driven configuration management:

- **Environment Variable Handling**: Tests reading from `$_ENV`, `$_SERVER`, and `getenv()`
- **Default Values**: Tests fallback to default values when env vars not set
- **Type Conversion**: Tests integer and boolean conversions
- **Environment Detection**: Tests `isProduction()`, `isDevelopment()`, `isTesting()`
- **Reset Functionality**: Tests config reset for test isolation

#### 2. PassKeyAuthService Tests (`PassKeyAuthServiceTest.php`)
Tests WebAuthn authentication service:

- **Registration Options**: Tests generation of WebAuthn registration options
- **Configuration Integration**: Tests use of config values for RP entity
- **Session Management**: Tests challenge and user storage in sessions
- **Verification**: Tests credential verification process
- **Error Handling**: Tests various error conditions
- **Session Cleanup**: Tests proper cleanup after verification

#### 3. RegisterRequestLib Tests (`RegisterRequestTest.php`)
Tests request handling and validation:

- **Email Validation**: Tests various email formats (valid and invalid)
- **JSON Handling**: Tests request data parsing
- **Error Responses**: Tests proper error message formatting
- **Session Management**: Tests session initialization

### Test Best Practices

#### Writing Tests
```php
<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Mensahe\Lib\YourClass;

final class YourClassTest extends TestCase
{
    protected function setUp(): void
    {
        // Setup test environment
        // Reset any global state
        // Set test environment variables
    }

    protected function tearDown(): void
    {
        // Clean up after tests
        // Clear environment variables
        // Reset session data
    }

    public function testMethodName(): void
    {
        // Arrange
        $input = 'test@example.com';
        
        // Act
        $result = YourClass::method($input);
        
        // Assert
        $this->assertSame('expected', $result);
    }
}
```

#### Test Naming Conventions
- Use descriptive test method names
- Follow pattern: `test[What][When][ExpectedResult]`
- Examples:
  - `testValidateUsernameWithValidEmail()`
  - `testGenerateRegistrationOptionsWithEmptyUsername()`
  - `testVerifyRegistrationWithNoSession()`

#### Test Isolation
- Each test should be independent
- Use `setUp()` and `tearDown()` for cleanup
- Reset configuration between tests
- Clear session data when needed

#### Mocking and Stubbing
```php
// Mock external dependencies
$mockService = $this->createMock(ExternalService::class);
$mockService->method('call')->willReturn('mocked result');

// Test with dependency injection
$service = new YourService($mockService);
```

## 🌐 Frontend Testing

### Manual Testing Interface
The frontend includes a comprehensive test interface at `http://localhost:8080/test-frontend.html`

#### Features
- **Registration Testing**: Test passkey registration flow
- **Sign-in Testing**: Test authentication flow (placeholder)
- **Email Validation**: Client-side email format validation
- **Error Handling**: Test various error conditions
- **UI Testing**: Test responsive design and interactions

#### Test Scenarios
1. **Valid Registration**:
   - Enter valid email (e.g., `user@example.com`)
   - Click "Register with passkey"
   - Verify backend returns registration options

2. **Invalid Email**:
   - Enter invalid email (e.g., `invalid-email`)
   - Verify client-side validation error

3. **Empty Email**:
   - Leave email field empty
   - Click "Register with passkey"
   - Verify validation error

4. **Network Error**:
   - Stop backend server
   - Try to register
   - Verify network error message

### Browser Testing
Test across different browsers:
- **Chrome**: Full WebAuthn support
- **Firefox**: Full WebAuthn support
- **Safari**: Full WebAuthn support
- **Edge**: Full WebAuthn support

## 🔗 Integration Testing

### API Endpoint Testing

#### Registration Endpoint
```bash
# Test registration request
curl -X POST http://localhost:8080/registerRequest.php \
  -H "Content-Type: application/json" \
  -d '{"username":"user@example.com"}' \
  | jq .
```

Expected Response:
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

#### Verification Endpoint
```bash
# Test verification (requires active session)
curl -X POST http://localhost:8080/registerVerify.php \
  -H "Content-Type: application/json" \
  -d '{"username":"user@example.com","credential":{"id":"test","type":"public-key","rawId":"test","response":{"clientDataJSON":"test","attestationObject":"test"}}}' \
  | jq .
```

### CORS Testing
```bash
# Test CORS headers
curl -H "Origin: http://localhost:8080" \
  -H "Access-Control-Request-Method: POST" \
  -H "Access-Control-Request-Headers: Content-Type" \
  -X OPTIONS http://localhost:8080/registerRequest.php \
  -v
```

## 🔧 Test Automation

### Continuous Integration
```yaml
# Example GitHub Actions workflow
name: Tests
on: [push, pull_request]
jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.1'
      - name: Install dependencies
        run: |
          cd src/server
          composer install
      - name: Run tests
        run: |
          cd src/server
          ./run-tests.sh
```

### Pre-commit Hooks
```bash
#!/bin/bash
# .git/hooks/pre-commit
cd src/server
./run-tests.sh
if [ $? -ne 0 ]; then
    echo "Tests failed. Commit aborted."
    exit 1
fi
```

## 🐛 Troubleshooting

### Common Issues

#### 1. Tests Failing Due to Environment
```bash
# Clear environment variables
unset APP_ENV APP_NAME APP_DOMAIN

# Reset configuration
cd src/server
./vendor/bin/phpunit tests/ConfigTest.php
```

#### 2. Session Issues
```php
// In test setup
if (session_status() === PHP_SESSION_ACTIVE) {
    session_destroy();
}
```

#### 3. Permission Issues
```bash
# Make test runner executable
chmod +x src/server/run-tests.sh
```

#### 4. Coverage Not Working
```bash
# Install Xdebug
pecl install xdebug

# Configure PHP
echo "xdebug.mode=coverage" >> php.ini
```

### Debug Mode
```bash
# Run tests with verbose output
./vendor/bin/phpunit --verbose tests/

# Run specific test with debug
./vendor/bin/phpunit --debug tests/ConfigTest.php::testGetAppNameWithDefault
```

## 📈 Coverage Goals

### Current Coverage
- **Config Class**: 100%
- **PassKeyAuthService**: 95%+
- **RegisterRequestLib**: 90%+
- **Overall**: 90%+

### Coverage Targets
- **Unit Tests**: 95%+ line coverage
- **Integration Tests**: 100% endpoint coverage
- **Manual Tests**: All user flows covered

## 📚 Additional Resources

### Documentation
- [PHPUnit Documentation](https://phpunit.de/)
- [WebAuthn Testing Guide](https://webauthn.guide/)
- [Chrome Extension Testing](https://developer.chrome.com/docs/extensions/mv3/tut_testing/)

### Tools
- **PHPUnit**: PHP testing framework
- **Xdebug**: Code coverage tool
- **cURL**: API testing
- **Browser DevTools**: Frontend debugging

### Best Practices
- Write tests before implementing features (TDD)
- Keep tests simple and focused
- Use descriptive test names
- Maintain test isolation
- Test both success and failure cases
- Mock external dependencies
- Use environment variables for configuration 