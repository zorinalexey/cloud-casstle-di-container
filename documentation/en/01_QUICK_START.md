# Quick Start

**CloudCastle DI Container v2.0**

---

## 📦 Installation

```bash
composer require cloud-castle/di-container
```

**Requirements:**
- PHP 8.1 or higher
- ext-json
- ext-mbstring

---

## ⚡ First Steps

### 1. Create Container

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use CloudCastle\DI\Container;

$container = new Container();
```

### 2. Register Services

```php
// Simple registration
$container->set('config', fn() => [
    'db_host' => 'localhost',
    'db_name' => 'myapp'
]);

// With dependencies
$container->set('database', function($c) {
    $config = $c->get('config');
    return new PDO(
        "mysql:host={$config['db_host']};dbname={$config['db_name']}"
    );
});
```

### 3. Get Services

```php
$config = $container->get('config');
$db = $container->get('database');
```

---

## 🤖 Autowiring

```php
// Enable autowiring
$container->enableAutowiring();

// Class with dependencies
class UserService {
    public function __construct(
        private Database $db,
        private Logger $logger
    ) {}
}

// Just get it - dependencies auto-resolved!
$userService = $container->get(UserService::class);
```

---

## 🔄 Lazy Loading

```php
// Service not created yet
$container->setLazy('heavy_service', fn($c) => new HeavyService());

// Get lazy proxy
$service = $container->get('heavy_service'); // Not created yet

// Initialized on first call
$result = $service->process(); // Now created
```

---

## 🏷️ Tags

```php
$container->set('redis', fn() => new RedisCache());
$container->tag('redis', 'cache');

// Find all services with tag
$caches = $container->findTaggedServiceIds('cache');
```

---

Next: [Basic Usage](02_BASIC_USAGE.md)

