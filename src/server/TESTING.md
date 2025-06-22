# Backend Testing

For comprehensive testing documentation covering all components (backend, frontend, integration), see the main [TESTING.md](../../TESTING.md).

## Quick Reference

### Running Tests
```bash
# All tests
./run-tests.sh

# Specific test files
./vendor/bin/phpunit tests/ConfigTest.php
./vendor/bin/phpunit tests/PassKeyAuthServiceTest.php
./vendor/bin/phpunit tests/RegisterRequestTest.php
```

### Test Files
- `tests/ConfigTest.php` - Configuration management tests
- `tests/PassKeyAuthServiceTest.php` - WebAuthn service tests  
- `tests/RegisterRequestTest.php` - Request handling tests

### Coverage
- **Config Class**: 100%
- **PassKeyAuthService**: 95%+
- **RegisterRequestLib**: 90%+
- **Overall**: 90%+

For detailed testing guides, examples, and troubleshooting, see the main [TESTING.md](../../TESTING.md). 