# Testing Documentation

This document covers testing setup, guidelines, and best practices for the PHP server components.

## Table of Contents

- [Setup](#setup)
- [Running Tests](#running-tests)
- [Test Structure](#test-structure)
- [Best Practices](#best-practices)
- [Test Coverage](#test-coverage)

## Setup

### Prerequisites

- PHP 8.0+
- Composer
- PHPUnit (installed via Composer)

### Installation

1. Install dependencies:
   ```bash
   composer install
   ```

2. Verify PHPUnit is working:
   ```bash
   ./vendor/bin/phpunit --version
   ```

## Running Tests

### Quick Test Run

Use our custom test runner for a visual overview:
```bash
./run-tests.sh
```

This script provides:
- Progress bar showing passing/failing tests
- Color-coded test counts
- Full PHPUnit output

### Standard PHPUnit Commands

Run all tests:
```bash
./vendor/bin/phpunit
```

Run specific test file:
```bash
./vendor/bin/phpunit tests/RegisterRequestTest.php
```

Run with verbose output:
```bash
./vendor/bin/phpunit --verbose
```

Run with coverage (requires Xdebug):
```bash
./vendor/bin/phpunit --coverage-html coverage/html
```

## Test Structure

### File Organization

```
src/server/
├── tests/                    # Test files
│   └── RegisterRequestTest.php
├── lib/                      # Core library classes
│   ├── RegisterRequestLib.php    # Core logic (testable)
│   └── PassKeyAuthService.php    # WebAuthn service
├── registerRequest.php       # HTTP entry point
├── registerVerify.php        # HTTP verification endpoint
├── phpunit.xml              # PHPUnit configuration
└── run-tests.sh             # Custom test runner
```

### Test File Naming

- Test files should end with `Test.php`
- Test files should be in the `tests/` directory
- Test class names should match the file name

### Test Class Structure

```php
<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class YourClassTest extends TestCase
{
    public function testMethodName(): void
    {
        // Test implementation
    }
}
```

## Best Practices

### Code Organization

1. **Separate Logic from HTTP**: Core business logic should be in separate files in the `lib/` directory (like `RegisterRequestLib.php`) that can be tested without HTTP side effects.

2. **Dependency Injection**: Functions should accept parameters for external dependencies (like `getRequestData(?string $input = null)`) to make testing easier.

3. **Pure Functions**: Keep functions pure when possible - same input should always produce same output.

### Test Writing

1. **Test Naming**: Use descriptive test method names that explain the scenario being tested.

2. **Arrange-Act-Assert**: Structure tests in three parts:
   ```php
   public function testValidateUsernameValid(): void
   {
       // Arrange
       $data = ['username' => 'valid_user123'];
       
       // Act
       $result = validateUsername($data);
       
       // Assert
       $this->assertSame('valid_user123', $result);
   }
   ```

3. **Test One Thing**: Each test should verify one specific behavior.

4. **Use Descriptive Assertions**: Choose the most specific assertion for your test case.

### Avoiding Common Issues

1. **No HTTP Side Effects**: Don't include files that send headers or output in tests.

2. **Mock External Dependencies**: Use dependency injection to avoid mocking complex external systems.

3. **Clean State**: Each test should be independent and not rely on state from other tests.

## Test Coverage

### Current Coverage

Run coverage analysis:
```bash
./vendor/bin/phpunit --coverage-html coverage/html
```

View coverage report:
```bash
open coverage/html/index.html
```

### Coverage Goals

- Aim for at least 80% code coverage
- Focus on critical business logic
- Don't test trivial getters/setters

## Configuration

### PHPUnit Configuration

The `phpunit.xml` file configures:
- Test discovery (looks in `tests/` directory)
- Coverage reporting
- Bootstrap file (autoloader)
- Environment variables

### Environment Variables

Set for testing:
```bash
export APP_ENV=testing
```

## Troubleshooting

### Common Issues

1. **Headers Already Sent**: Ensure test files don't include HTTP entry points that send headers.

2. **Permission Denied**: Make sure `run-tests.sh` is executable:
   ```bash
   chmod +x run-tests.sh
   ```

3. **Coverage Not Working**: Install and configure Xdebug for coverage reports.

### Getting Help

- Check PHPUnit documentation: https://phpunit.de/
- Review existing tests in `tests/` directory
- Run tests with `--verbose` flag for more details 