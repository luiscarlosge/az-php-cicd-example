# Testing Guide

This guide explains how to run unit tests locally for the Azure PHP CI/CD Portal project.

## Prerequisites

Before running tests, ensure you have the following installed:

- PHP 8.0 or higher
- Composer (PHP dependency manager)

## Installation

1. Install project dependencies:

```bash
composer install
```

This will install PHPUnit and other development dependencies defined in `composer.json`.

## Running Tests

### Run All Tests

To run all unit tests:

```bash
composer test
```

Or directly with PHPUnit:

```bash
vendor/bin/phpunit tests/unit
```

### Run Specific Test File

To run a specific test file:

```bash
vendor/bin/phpunit tests/unit/LanguageSystemTest.php
```

### Run Tests with Coverage

To generate code coverage report (HTML format):

```bash
composer test-coverage
```

The coverage report will be generated in the `coverage/` directory. Open `coverage/index.html` in your browser to view the report.

## Test Structure

The test suite is organized as follows:

```
tests/
└── unit/
    ├── ConfigTest.php              # Tests configuration file
    ├── LanguageSystemTest.php      # Tests language management
    ├── PageStructureTest.php       # Tests page structure and includes
    └── TranslationFilesTest.php    # Tests translation file consistency
```

### Test Categories

1. **LanguageSystemTest**: Tests the language management system
   - Language initialization
   - Language switching (Spanish/English)
   - Translation function
   - Session management

2. **ConfigTest**: Tests the configuration file
   - File structure
   - Required constants
   - Language system integration
   - Data arrays (curriculum, faculty)

3. **TranslationFilesTest**: Tests translation files
   - File existence
   - Structure consistency between languages
   - Required sections

4. **PageStructureTest**: Tests page files
   - File existence
   - Required includes
   - Proper structure

## Continuous Integration

Tests are automatically run in the GitHub Actions CI/CD pipeline:

- **On Pull Requests**: Tests must pass before merge
- **On Push to Main**: Tests run before deployment

The pipeline will fail if any test fails, preventing broken code from being deployed.

## Writing New Tests

When adding new functionality, follow these guidelines:

1. Create test files in `tests/unit/`
2. Use descriptive test method names starting with `test`
3. Follow the existing test structure
4. Ensure tests are isolated and don't depend on each other
5. Clean up resources in `tearDown()` method

Example test structure:

```php
<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class MyNewTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Setup code here
    }

    protected function tearDown(): void
    {
        // Cleanup code here
        parent::tearDown();
    }

    public function testSomething(): void
    {
        // Arrange
        $expected = 'value';
        
        // Act
        $actual = someFunction();
        
        // Assert
        $this->assertEquals($expected, $actual);
    }
}
```

## Troubleshooting

### Tests Fail Due to Missing Dependencies

```bash
composer install
```

### Session-Related Test Failures

If you encounter session-related errors, ensure no other PHP processes are holding session locks:

```bash
# Clear PHP session files (Linux/Mac)
rm -rf /tmp/sess_*
```

### Permission Issues

Ensure the test directories have proper permissions:

```bash
chmod -R 755 tests/
```

## PHPUnit Configuration

The PHPUnit configuration is defined in `phpunit.xml` at the project root. Key settings:

- **Bootstrap**: `vendor/autoload.php` - Loads Composer autoloader
- **Test Suite**: `tests/unit` - Location of unit tests
- **Colors**: Enabled for better readability
- **Strict Mode**: Enabled to catch warnings and risky tests

## Best Practices

1. **Run tests before committing**: Always run tests locally before pushing code
2. **Keep tests fast**: Unit tests should run quickly
3. **Test one thing**: Each test should verify a single behavior
4. **Use descriptive names**: Test names should clearly describe what they test
5. **Avoid test interdependencies**: Tests should be able to run in any order

## Additional Resources

- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [PHP Testing Best Practices](https://phpunit.de/manual/current/en/writing-tests-for-phpunit.html)
- [Composer Documentation](https://getcomposer.org/doc/)
