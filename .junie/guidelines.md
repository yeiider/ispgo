# Development Guidelines for ISP Go

This document provides essential information for developers working on the ISP Go project. It includes build/configuration instructions, testing information, and additional development guidelines.

## Build/Configuration Instructions

### Prerequisites

- PHP 8.2 or higher
- Required PHP extensions: dom, json, libxml, mbstring, tokenizer, xml, xmlwriter
- Composer
- Node.js and npm
- MySQL 8.0
- Redis

### Setup

1. **Clone the repository**

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   ```

4. **Environment Configuration**
   - Copy `.env.example` to `.env`
   - Configure your database and other services in the `.env` file
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database Setup**
   ```bash
   php artisan migrate
   php artisan db:seed  # Optional, if you want to seed the database
   ```

6. **Build frontend assets**
   ```bash
   npm run dev  # For development
   npm run build  # For production
   ```

### Docker Setup

The project includes Laravel Sail for Docker-based development:

```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
```

The Docker setup includes:
- PHP 8.3 runtime
- MySQL 8.0
- Redis
- Meilisearch
- Mailpit for email testing
- Selenium for browser testing

## Testing Information

### Testing Framework

The project uses PHPUnit for testing. Tests are organized into:
- **Unit Tests**: Located in `tests/Unit/` - For testing isolated components
- **Feature Tests**: Located in `tests/Feature/` - For testing application behavior

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature

# Run specific test file
php artisan test --filter=ExampleServiceTest

# Run with coverage report (requires Xdebug)
php artisan test --coverage
```

### Creating New Tests

1. **Create a test file**
   ```bash
   php artisan make:test ExampleTest  # Creates a feature test
   php artisan make:test ExampleTest --unit  # Creates a unit test
   ```

2. **Test Structure**

   Unit tests should extend `PHPUnit\Framework\TestCase`:
   ```php
   <?php
   
   namespace Tests\Unit;
   
   use PHPUnit\Framework\TestCase;
   
   class ExampleServiceTest extends TestCase
   {
       /**
        * A basic test example for a service.
        */
       public function test_example_service_returns_expected_value(): void
       {
           // Arrange
           $expectedValue = 'expected result';
           
           // Act
           $actualValue = $this->getExampleServiceResult();
           
           // Assert
           $this->assertEquals($expectedValue, $actualValue);
       }
       
       /**
        * Helper method that simulates a service call.
        */
       private function getExampleServiceResult(): string
       {
           // In a real test, you would instantiate and call an actual service
           return 'expected result';
       }
   }
   ```

   Feature tests should extend `Tests\TestCase`:
   ```php
   <?php
   
   namespace Tests\Feature;
   
   use Tests\TestCase;
   
   class ExampleTest extends TestCase
   {
       public function test_the_application_returns_a_successful_response(): void
       {
           $response = $this->get('/');
           
           $response->assertStatus(200);
       }
   }
   ```

3. **Database Testing**

   For tests that interact with the database, use the `RefreshDatabase` trait:
   ```php
   use Illuminate\Foundation\Testing\RefreshDatabase;
   
   class ExampleTest extends TestCase
   {
       use RefreshDatabase;
       
       // Test methods...
   }
   ```

## Additional Development Information

### Code Style

The project uses Laravel Pint for code style enforcement, with the following guidelines:
- 4-space indentation for PHP, HTML, and most files
- 2-space indentation for YAML, Vue, TypeScript, and TSX files
- UTF-8 encoding
- LF line endings
- Final newline at the end of files
- No trailing whitespace

To check and fix code style:
```bash
./vendor/bin/pint  # Fix code style issues
./vendor/bin/pint --test  # Check for code style issues without fixing
```

### Laravel Nova

The project uses Laravel Nova for administration. Nova components are located in:
- `app/Nova/` - Nova resources
- `nova-components/` - Custom Nova tools and fields

### Custom Packages

The project includes several custom packages:
- `ispgo/ckeditor` - CKEditor integration
- `ispgo/mikrotik` - MikroTik integration
- `ispgo/settings-manager` - Settings management
- `ispgo/siigo` - Siigo integration
- `ispgo/smartolt` - SmartOLT integration
- `ispgo/wiivo` - Wiivo integration

These packages are included as local path repositories in the `composer.json` file.

### Debugging

For debugging, you can use:
- Laravel Telescope (if installed)
- Laravel Ignition error pages
- Laravel Sail includes Xdebug which can be enabled by setting `SAIL_XDEBUG_MODE=develop,debug` in your `.env` file

### API Documentation

The project uses L5-Swagger for API documentation. To generate API documentation:
```bash
php artisan l5-swagger:generate
```

The API documentation will be available at `/api/documentation`.
