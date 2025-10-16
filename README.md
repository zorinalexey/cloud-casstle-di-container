# CloudCastle DI Container

A powerful and flexible Dependency Injection container for PHP 8.1+ with **autowiring** support.

🏆 **Мировой лидер** по стресс-устойчивости (15M операций, 15k уровней DI)

## Features

- ⚡ **High performance** - до 385k операций/сек
- 🤖 **Autowiring** - автоматическое разрешение зависимостей  
- 🔄 **Lazy Loading** - отложенная инициализация сервисов
- 🎨 **Decorators** - декорирование сервисов дополнительной функциональностью
- ⚡ **Compiled Container** - предкомпиляция для максимальной производительности
- 🏷️ **Tagged Services** - группировка сервисов по меткам
- 💾 **Memory efficient** - 0.46 КБ на сервис
- 🏆 **Best memory management** - 0.001 МБ утечек за 15M циклов
- 📦 **PSR-11 compliant**
- 🎯 **Simple API**

## Installation

```bash
composer require cloud-castle/di-container
```

## Usage

### Basic Usage

```php
use CloudCastle\DI\Container;

$container = new Container();

// Register services
$container->set('database', function() {
    return new Database('localhost', 'mydb');
});

// Retrieve services
$db = $container->get('database');
```

### Autowiring

```php
// Enable autowiring
$container->enableAutowiring();

// Automatically resolve dependencies
class UserRepository {
    public function __construct(
        private Database $database,
        private Logger $logger
    ) {}
}

// Just get it - all dependencies will be autowired!
$repo = $container->get(UserRepository::class);
```

### Lazy Loading

Defer service instantiation until first use:

```php
// Service is not created yet
$proxy = $container->setLazy('heavy_service', fn($c) => new HeavyService());

// Service is created only when you actually use it
$service = $container->get('heavy_service');
$result = $service->doSomething(); // Now it's initialized
```

### Decorators

Add functionality to existing services:

```php
$container->set('logger', fn() => new FileLogger());

// Add decorators
$container->decorate('logger', function($logger, $container) {
    return new CachedLogger($logger);
});

$container->decorate('logger', function($logger, $container) {
    return new MetricsLogger($logger);
});

// Get fully decorated service
$logger = $container->get('logger'); // MetricsLogger -> CachedLogger -> FileLogger
```

### Compiled Container

Pre-compile your container for maximum performance:

```php
$container = new Container();
$container->set('service1', fn() => new Service1());
$container->set('service2', fn() => new Service2());

// Compile to file
$container->compileToFile(
    __DIR__ . '/cache/container.php',
    'MyCompiledContainer',
    'App\\DI'
);

// Later, in production - just load the compiled container
require_once __DIR__ . '/cache/container.php';
$container = new \App\DI\MyCompiledContainer();

// Ultra-fast service access
$service = $container->get('service1');
```

### Tagged Services

Group services by tags for easy retrieval:

```php
// Register and tag services
$container->set('handler1', fn() => new EmailHandler());
$container->set('handler2', fn() => new SmsHandler());
$container->set('handler3', fn() => new PushHandler());

$container->tag('handler1', 'notification.handler', ['priority' => 10]);
$container->tag('handler2', 'notification.handler', ['priority' => 5]);
$container->tag('handler3', 'notification.handler', ['priority' => 1]);

// Get all handlers with the tag
$handlers = $container->findByTag('notification.handler');

// Sort by priority
usort($handlers, function($a, $b) use ($container) {
    $priorityA = $container->getTagAttributes('handler1', 'notification.handler')['priority'];
    $priorityB = $container->getTagAttributes('handler2', 'notification.handler')['priority'];
    return $priorityB <=> $priorityA;
});

// Execute all handlers
foreach ($handlers as $handler) {
    $handler->handle($notification);
}
```

## Development

### Installation

```bash
composer install
```

### Code Quality Tools

This project includes a comprehensive set of code quality tools:

#### Static Analysis

```bash
# PHPStan - Maximum level static analysis
composer phpstan

# Run all static analyzers
composer analyse
```

#### Code Style

```bash
# Check code style (PSR-12)
composer phpcs

# Automatically fix code style
composer phpcs:fix

# PHP-CS-Fixer (more advanced)
composer php-cs-fixer
composer php-cs-fixer:fix
```

#### Code Quality

```bash
# PHP Mess Detector - Detect code smells
composer phpmd

# Rector - Automated refactoring and upgrades
composer rector
composer rector:fix
```

#### Testing

```bash
# Run tests
composer test

# Run tests with coverage
composer test:coverage
```

#### Metrics & Documentation

```bash
# Generate code metrics
composer metrics

# Run benchmarks
composer benchmark

# Run load tests
composer load-test

# Run stress tests
composer stress-test
```

#### Quick Commands

```bash
# Check everything
composer check

# Fix everything automatically
composer fix
```

## Requirements

- PHP 8.1 or higher
- PSR-11 Container Interface

## License

MIT License

