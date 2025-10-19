# 🧪 Panduan Unit Testing Project SIPP PHI

## 📋 **Overview**

Project ini menggunakan **Pest PHP** sebagai testing framework utama dengan **PHPUnit** sebagai backend. Pest memberikan syntax yang lebih modern dan readable untuk testing.

## 🚀 **Cara Menjalankan Tests**

### 1. **Menjalankan Semua Tests**

```bash
php artisan test
```

### 2. **Menjalankan Test Suite Tertentu**

```bash
# Unit tests saja
php artisan test --testsuite=Unit

# Feature tests saja
php artisan test --testsuite=Feature

# Test file tertentu
php artisan test tests/Unit/UserTest.php
```

### 3. **Menjalankan Test dengan Coverage**

```bash
php artisan test --coverage
```

### 4. **Menjalankan Test dalam Mode Watch**

```bash
php artisan test --watch
```

## 📁 **Struktur Testing**

```
tests/
├── Unit/                    # Unit tests (komponen individual)
│   ├── ExampleTest.php     # Test dasar
│   ├── UserTest.php        # Test untuk User model
│   └── LaporanServiceTest.php # Test untuk LaporanService
├── Feature/                 # Feature tests (integrasi)
│   ├── ProfileTest.php     # Test untuk fitur profile
│   └── Auth/               # Test untuk autentikasi
├── TestCase.php            # Base test case
└── Pest.php                # Konfigurasi Pest
```

## ✍️ **Cara Menulis Unit Tests**

### 1. **Test untuk Model**

```php
<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can be created with valid data', function () {
    $user = User::create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
    ]);

    expect($user)->toBeInstanceOf(User::class);
    expect($user->name)->toBe('John Doe');
    expect($user->email)->toBe('john@example.com');
});

test('user email must be unique', function () {
    User::factory()->create(['email' => 'test@example.com']);

    expect(function () {
        User::factory()->create(['email' => 'test@example.com']);
    })->toThrow(Illuminate\Database\QueryException::class);
});
```

### 2. **Test untuk Service**

```php
<?php

use App\Services\LaporanService;
use App\Models\Pengaduan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

uses(RefreshDatabase::class);

describe('LaporanService', function () {
    beforeEach(function () {
        $this->service = new LaporanService();
    });

    afterEach(function () {
        Mockery::close();
    });

    test('should generate laporan successfully', function () {
        $pengaduan = Pengaduan::factory()->create();

        $result = $this->service->generateLaporanOtomatis($pengaduan);

        expect($result)->toBeTrue();
    });
});
```

### 3. **Test untuk Controller**

```php
<?php

use App\Http\Controllers\PengaduanController;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('controller should return paginated results', function () {
    $controller = new PengaduanController();
    Pengaduan::factory()->count(5)->create();

    $request = new Request();
    $response = $controller->index($request);

    expect($response)->toBeInstanceOf(JsonResponse::class);
    expect($response->getStatusCode())->toBe(200);
});
```

## 🔧 **Testing Utilities**

### 1. **Factories**

```php
// Menggunakan factory untuk membuat test data
$user = User::factory()->create([
    'name' => 'Test User',
    'email' => 'test@example.com',
]);

// Membuat multiple records
$users = User::factory()->count(5)->create();

// Membuat model tanpa menyimpan ke database
$user = User::factory()->make();
```

### 2. **Mocking**

```php
use Mockery;

// Mock facade
Log::shouldReceive('info')->once();

// Mock service
$mockService = Mockery::mock(SomeService::class);
$mockService->shouldReceive('method')->andReturn('value');
```

### 3. **Database Testing**

```php
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('database operations', function () {
    // Database akan di-refresh setiap test
    $user = User::create(['name' => 'Test']);
    expect(User::count())->toBe(1);
});
```

## 📊 **Best Practices**

### 1. **Naming Convention**

-   Test method: `test('should do something when condition')`
-   Describe blocks: `describe('ClassName', function () {})`
-   Test cases: `test('specific behavior description')`

### 2. **Test Structure**

```php
describe('ClassName', function () {
    beforeEach(function () {
        // Setup code
    });

    afterEach(function () {
        // Cleanup code
    });

    describe('method name', function () {
        test('should do something', function () {
            // Arrange
            // Act
            // Assert
        });
    });
});
```

### 3. **Assertions**

```php
// Basic assertions
expect($value)->toBe('expected');
expect($value)->not->toBe('unexpected');
expect($value)->toBeInstanceOf(Class::class);
expect($value)->toBeNull();
expect($value)->not->toBeNull();

// Exception testing
expect(function () {
    // Code that should throw exception
})->toThrow(ExceptionClass::class);
```

## 🚨 **Troubleshooting**

### 1. **Database Connection Issues**

```bash
# Clear config cache
php artisan config:clear

# Run migrations for testing
php artisan migrate --env=testing
```

### 2. **Factory Issues**

```bash
# Regenerate autoload files
composer dump-autoload
```

### 3. **Test Environment**

Pastikan file `.env.testing` ada dengan konfigurasi database testing yang benar.

## 📈 **Coverage Reports**

Untuk melihat coverage testing:

```bash
# Install Xdebug terlebih dahulu
# Jalankan test dengan coverage
php artisan test --coverage

# Coverage akan ditampilkan di terminal
# File HTML coverage akan dibuat di coverage/ directory
```

## 🔗 **Resources**

-   [Pest PHP Documentation](https://pestphp.com/)
-   [Laravel Testing Documentation](https://laravel.com/docs/testing)
-   [PHPUnit Documentation](https://phpunit.de/)

---

**Happy Testing! 🎉**










