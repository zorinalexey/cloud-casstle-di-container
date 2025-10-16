# Быстрый старт

**CloudCastle DI Container v2.0**

---

## 📦 Установка

```bash
composer require cloud-castle/di-container
```

**Требования:**
- PHP 8.1 или выше
- ext-json
- ext-mbstring

---

## ⚡ Первые шаги

### 1. Создать контейнер

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use CloudCastle\DI\Container;

$container = new Container();
```

### 2. Зарегистрировать сервисы

```php
// Простая регистрация
$container->set('config', fn() => [
    'db_host' => 'localhost',
    'db_name' => 'myapp'
]);

// С зависимостями
$container->set('database', function($c) {
    $config = $c->get('config');
    return new PDO(
        "mysql:host={$config['db_host']};dbname={$config['db_name']}"
    );
});

// Регистрация объекта
$logger = new Logger();
$container->set('logger', $logger);
```

### 3. Получить сервисы

```php
$config = $container->get('config');
$db = $container->get('database');
$logger = $container->get('logger');
```

### 4. Проверить наличие

```php
if ($container->has('database')) {
    $db = $container->get('database');
}
```

---

## 🤖 Autowiring (автоматическое разрешение)

```php
// Включить autowiring
$container->enableAutowiring();

// Класс с зависимостями
class UserService {
    public function __construct(
        private Database $db,
        private Logger $logger
    ) {}
    
    public function getUser(int $id): User {
        $this->logger->log("Getting user {$id}");
        return $this->db->find('users', $id);
    }
}

// Просто получите - зависимости разрешатся автоматически!
$userService = $container->get(UserService::class);
```

---

## 🔄 Lazy Loading

```php
// Сервис не создаётся сразу
$container->setLazy('heavy_service', function($c) {
    return new HeavyService(); // Создастся при первом использовании
});

// Получить lazy proxy
$service = $container->get('heavy_service'); // Ещё не создан

// Инициализируется при первом вызове
$result = $service->process(); // Теперь создан
```

---

## 🏷️ Теги

```php
// Зарегистрировать с тегами
$container->set('redis', fn() => new RedisCache());
$container->tag('redis', 'cache');

$container->set('memcached', fn() => new MemcachedCache());
$container->tag('memcached', 'cache');

// Найти все сервисы с тегом
$caches = $container->findTaggedServiceIds('cache');
// ['redis', 'memcached']
```

---

## ⚡ Compiled Container (для production)

```php
// Development: настроить контейнер
$container = new Container();
$container->set('app', fn() => new App());
$container->set('db', fn() => new Database());

// Скомпилировать
$container->compileToFile(__DIR__ . '/cache/CompiledContainer.php');

// Production: использовать compiled
require __DIR__ . '/cache/CompiledContainer.php';
$container = new \CloudCastle\DI\Compiled\CompiledContainer();

// На 47% быстрее загрузка, на 1.3% быстрее операции!
```

---

## 📚 Что дальше?

- [Базовое использование](02_BASIC_USAGE.md) — детальное руководство
- [Продвинутые возможности](03_ADVANCED_FEATURES.md) — полный функционал
- [Compiled Container](04_COMPILED.md) — оптимизация для production
- [API Reference](05_API.md) — справочник по API

---

## 💡 Быстрые примеры

### Пример 1: Web Application

```php
$container = new Container();
$container->enableAutowiring();

// Сервисы
$container->set('router', fn() => new Router());
$container->set('request', fn() => Request::createFromGlobals());

// Контроллеры автоматически получат зависимости
class HomeController {
    public function __construct(
        private Router $router,
        private Request $request
    ) {}
}

$controller = $container->get(HomeController::class);
```

### Пример 2: API Client

```php
$container = new Container();

$container->set('http', fn() => new GuzzleClient());
$container->set('api', fn($c) => new ApiClient($c->get('http')));

// Добавить декораторы
$container->decorate('api', fn($api) => new CachedApi($api));
$container->decorate('api', fn($api) => new RetryApi($api));

$api = $container->get('api'); // RetryApi -> CachedApi -> ApiClient
```

---

✅ **Готовы начать!** Переходите к [полной документации](02_BASIC_USAGE.md).

