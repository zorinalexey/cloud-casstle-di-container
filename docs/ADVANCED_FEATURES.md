# 🚀 Advanced Features Guide

CloudCastle DI Container предоставляет полный набор продвинутых возможностей для построения современных PHP приложений.

**Версия:** 2.0.0  
**Дата:** 16 октября 2025

---

## 📑 Содержание

1. [Autowiring](#autowiring) - Автоматическое разрешение зависимостей
2. [Lazy Loading](#lazy-loading) - Отложенная инициализация
3. [Decorators](#decorators) - Декорирование сервисов
4. [Compiled Container](#compiled-container) - Предкомпиляция
5. [Tagged Services](#tagged-services) - Группировка по тегам
6. [Best Practices](#best-practices) - Лучшие практики

---

## 🤖 Autowiring

Автоматическое разрешение зависимостей через Reflection API.

### Базовое использование

```php
use CloudCastle\DI\Container;

$container = new Container();
$container->enableAutowiring();

// Простой класс без зависимостей
class Logger {
    public function log(string $message): void {
        echo $message . "\n";
    }
}

// Автоматически создаётся
$logger = $container->get(Logger::class);
```

### С зависимостями

```php
class Database {
    public function __construct(
        private string $host = 'localhost',
        private int $port = 3306
    ) {}
}

class UserRepository {
    public function __construct(
        private Database $database,
        private Logger $logger
    ) {}
}

class UserService {
    public function __construct(
        private UserRepository $repository,
        private Logger $logger
    ) {}
}

// Все зависимости разрешаются автоматически:
// UserService -> UserRepository -> Database + Logger
$service = $container->get(UserService::class);
```

### Поддерживаемые возможности

#### 1. Default values

```php
class EmailService {
    public function __construct(
        private string $host = 'smtp.example.com',
        private int $port = 587,
        private bool $encryption = true
    ) {}
}

// Использует default значения
$email = $container->get(EmailService::class);
```

#### 2. Nullable параметры

```php
class OptionalService {
    public function __construct(
        private Database $database,
        private ?Logger $logger = null,  // Опциональная зависимость
        private ?Cache $cache = null
    ) {}
}

// Logger и Cache будут null, если не зарегистрированы
$service = $container->get(OptionalService::class);
```

#### 3. Приоритет зарегистрированных сервисов

```php
// Явно зарегистрированный сервис имеет приоритет
$container->set(Database::class, fn() => new Database('production.db', 5432));

class Repository {
    public function __construct(private Database $database) {}
}

// Использует зарегистрированную БД, а не создаёт новую
$repo = $container->get(Repository::class);
```

#### 4. Обнаружение циклических зависимостей

```php
class ServiceA {
    public function __construct(private ServiceB $b) {}
}

class ServiceB {
    public function __construct(private ServiceA $a) {}
}

try {
    $container->get(ServiceA::class);
} catch (ContainerException $e) {
    echo "Circular dependency detected!";
}
```

### Ограничения Autowiring

❌ **Не работает для:**
- Интерфейсов и абстрактных классов
- Скалярных типов (string, int, bool)
- Классов без type hints

✅ **Решение:** Явная регистрация

```php
// Для интерфейсов
$container->set(LoggerInterface::class, fn() => new FileLogger());

// Для скалярных типов
$container->set('config.database.host', fn() => 'localhost');
```

### Производительность

- **Первое обращение:** +5-10% overhead (reflection)
- **Повторные обращения:** 0% overhead (кэширование)
- **Рекомендация:** Используйте в development, компилируйте в production

---

## ⏱️ Lazy Loading

Отложенная инициализация сервисов для оптимизации загрузки приложения.

### Базовое использование

```php
// Сервис НЕ создаётся при регистрации
$proxy = $container->setLazy('database', function($c) {
    echo "Database initializing...\n";
    return new Database(
        $c->get('config')->get('db.host'),
        $c->get('config')->get('db.port')
    );
});

// Сервис всё ещё НЕ создан
$db = $container->get('database');  // Возвращает LazyProxy

// Сервис создаётся только при первом использовании
$db->query('SELECT 1');  // Здесь выводится "Database initializing..."
```

### Как работает LazyProxy

```php
// LazyProxy прозрачно проксирует все вызовы
$lazyService = $container->setLazy('api', fn() => new ApiClient($apiKey));
$api = $container->get('api');

// Все методы проксируются
$api->call('/users');        // Вызывает ApiClient::call()
$api->timeout = 30;          // Устанавливает ApiClient::$timeout
$value = $api->endpoint;     // Читает ApiClient::$endpoint

// Проверка инициализации
if ($api->isInitialized()) {
    echo "API client уже создан";
}
```

### Сценарии использования

#### 1. Тяжёлые сервисы

```php
// Подключение к внешним сервисам
$container->setLazy('elasticsearch', fn($c) => 
    new Elasticsearch($c->get('config')->get('elastic'))
);

$container->setLazy('redis', fn() => 
    new Redis('localhost', 6379)
);

$container->setLazy('message_queue', fn($c) => 
    new RabbitMQ($c->get('config')->get('rabbitmq'))
);
```

#### 2. Условные сервисы

```php
// Создаются только если нужны
$container->setLazy('pdf_generator', fn() => new PdfGenerator());
$container->setLazy('image_processor', fn() => new ImageProcessor());
$container->setLazy('video_encoder', fn() => new VideoEncoder());
```

#### 3. Цепочки зависимостей

```php
$container->setLazy('mailer', fn($c) => 
    new Mailer($c->get('transport'))
);

$container->setLazy('transport', fn($c) => 
    new SmtpTransport($c->get('config')->get('smtp'))
);

// Оба создадутся только при первом $mailer->send()
```

### Производительность

- **Экономия памяти:** до 70% для редко используемых сервисов
- **Ускорение загрузки:** до 50% для приложений с многими сервисами
- **Overhead доступа:** ~2-3% только при первом обращении

---

## 🎨 Decorators

Расширение функциональности существующих сервисов без изменения их кода.

### Базовое использование

```php
// Базовый logger
$container->set('logger', fn() => new FileLogger('/var/log/app.log'));

// Добавляем кэширование
$container->decorate('logger', function($logger, $container) {
    return new CachedLogger($logger);
});

$logger = $container->get('logger');
// Теперь это CachedLogger(FileLogger)
```

### Множественные декораторы

```php
$container->set('logger', fn() => new FileLogger());

// Порядок применения: снизу вверх (как middleware)
$container->decorate('logger', fn($l) => new FilteredLogger($l));
$container->decorate('logger', fn($l, $c) => new MetricsLogger($l, $c->get('metrics')));
$container->decorate('logger', fn($l) => new AsyncLogger($l));

// Итоговая цепочка:
// AsyncLogger -> MetricsLogger -> FilteredLogger -> FileLogger
$logger = $container->get('logger');
```

### Реальные примеры

#### 1. Логирование с метриками

```php
class MetricsLogger {
    public function __construct(
        private LoggerInterface $logger,
        private MetricsCollector $metrics
    ) {}
    
    public function log($level, $message): void {
        $this->metrics->increment('logs.' . $level);
        $this->logger->log($level, $message);
    }
}

$container->set('logger', fn() => new FileLogger());
$container->decorate('logger', fn($l, $c) => 
    new MetricsLogger($l, $c->get('metrics'))
);
```

#### 2. Кэширование репозитория

```php
class CachedRepository {
    public function __construct(
        private RepositoryInterface $repository,
        private CacheInterface $cache
    ) {}
    
    public function find($id) {
        $cacheKey = 'repo.' . $id;
        
        if ($cached = $this->cache->get($cacheKey)) {
            return $cached;
        }
        
        $result = $this->repository->find($id);
        $this->cache->set($cacheKey, $result);
        
        return $result;
    }
}

$container->set('user.repository', fn() => new UserRepository());
$container->decorate('user.repository', fn($r, $c) => 
    new CachedRepository($r, $c->get('cache'))
);
```

#### 3. Rate Limiting

```php
class RateLimitedApi {
    public function __construct(
        private ApiInterface $api,
        private RateLimiter $limiter
    ) {}
    
    public function call(string $endpoint): mixed {
        $this->limiter->checkLimit('api.calls');
        return $this->api->call($endpoint);
    }
}

$container->set('api', fn() => new ThirdPartyApi($apiKey));
$container->decorate('api', fn($a, $c) => 
    new RateLimitedApi($a, $c->get('rate.limiter'))
);
```

### Паттерны применения

- 🔒 **Авторизация** - проверка прав доступа
- 📊 **Мониторинг** - сбор метрик и статистики
- 💾 **Кэширование** - прозрачное кэширование результатов
- ⏱️ **Rate Limiting** - ограничение частоты вызовов
- 🔄 **Retry Logic** - автоматические повторы при ошибках
- 📝 **Логирование** - запись всех операций
- ✅ **Валидация** - проверка входных данных

---

## 📦 Compiled Container

Предкомпиляция контейнера в оптимизированный PHP код для максимальной производительности.

### Зачем нужна компиляция?

**Преимущества:**
- ⚡ **+30-50% скорость** с Opcache в production
- 💾 **-20% память** - компактный код без overhead
- 🚀 **Мгновенная загрузка** с Opcache preloading
- 🛡️ **Детерминированность** - фиксированный набор сервисов
- 🔍 **Легкая отладка** - читаемый generated код

### Способ 1: Ручная компиляция

```php
// Build script (deploy.php)
$container = new Container();

// Регистрация всех сервисов
require __DIR__ . '/config/services.php';

// Компиляция
$container->compileToFile(
    __DIR__ . '/cache/ProductionContainer.php',
    'ProductionContainer',
    'App\\DI'
);

// Production (index.php)
$services = require __DIR__ . '/cache/services.php';
require_once __DIR__ . '/cache/ProductionContainer.php';
$container = new App\DI\ProductionContainer($services);
```

### Способ 2: ContainerFactory (рекомендуется)

```php
use CloudCastle\DI\ContainerFactory;

// Автоматически использует compiled если существует
$services = require __DIR__ . '/cache/services.php';
$container = ContainerFactory::create(services: $services);

// В development: создаст regular container
// В production (после composer compile): использует compiled
```

### Способ 3: Environment-based

```php
$isProd = getenv('APP_ENV') === 'production';

$container = ContainerFactory::createConfigured(
    configurator: function ($c) {
        $c->set('database', fn() => new Database());
        $c->set('logger', fn() => new Logger());
        $c->set('cache', fn() => new RedisCache());
    },
    useCompiled: $isProd
);
```

### Автоматическая компиляция

Контейнер **автоматически компилируется** после:

```bash
composer install    # Post-install hook
composer update     # Post-update hook
```

Или вручную:

```bash
composer compile    # Запускает scripts/compile-container.php
```

### Структура скомпилированного кода

```php
final class ProductionContainer extends \CloudCastle\DI\CompiledContainer
{
    public function __construct()
    {
        parent::__construct($services);
    }

    // Оптимизированный has() с match
    public function has(string $serviceId): bool
    {
        return match ($serviceId) {
            'database' => true,
            'logger' => true,
            'cache' => true,
            default => false,
        };
    }

    // Оптимизированный get() с match
    public function get(string $serviceId): mixed
    {
        return match ($serviceId) {
            'database' => $this->instances['database'] ??= $this->service0(),
            'logger' => $this->instances['logger'] ??= $this->service1(),
            'cache' => $this->instances['cache'] ??= $this->service2(),
            default => throw new NotFoundException("Service '$serviceId' not found"),
        };
    }

    // Factory методы
    private function service0(): mixed {
        $factory = $this->services['database'] ?? null;
        return $factory ? $factory($this) : new \stdClass();
    }
    
    // ... остальные factory методы
}
```

### Производительность

**Результаты нагрузочных тестов:**

| Метрика | Значение |
|---------|----------|
| **Скорость компиляции** | 210,000 сервисов/сек |
| **Максимум сервисов** | 50,000+ |
| **Размер кода** | 367 байт/сервис |
| **Скорость доступа** | 510,600 оп/сек |
| **Загрузка файла** | 8.7 мс (1K сервисов) |
| **С Opcache** | +30-50% ускорение |

### Оптимизация для production

**1. Включите Opcache:**

```ini
; php.ini
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0
```

**2. Preloading (PHP 7.4+):**

```php
// preload.php
opcache_compile_file(__DIR__ . '/cache/ProductionContainer.php');
```

```ini
; php.ini
opcache.preload=/path/to/preload.php
```

**3. CI/CD интеграция:**

```dockerfile
# Dockerfile
RUN composer install --no-dev --optimize-autoloader \
    && composer compile
```

**Подробнее:** [docs/COMPILATION.md](COMPILATION.md)

---

## 🏷️ Tagged Services

Группировка и организация сервисов по тегам для гибкой архитектуры.

### Базовое использование

```php
// Регистрируем сервисы
$container->set('email.handler', fn() => new EmailHandler());
$container->set('sms.handler', fn() => new SmsHandler());
$container->set('push.handler', fn() => new PushHandler());

// Помечаем общим тегом
$container->tag('email.handler', 'notification.handler');
$container->tag('sms.handler', 'notification.handler');
$container->tag('push.handler', 'notification.handler');

// Получаем все обработчики
$handlers = $container->findByTag('notification.handler');

foreach ($handlers as $handler) {
    $handler->send($notification);
}
```

### Теги с атрибутами

```php
// Теги с метаданными
$container->tag('email.handler', 'notification.handler', [
    'priority' => 100,
    'async' => true,
    'channels' => ['email', 'digest'],
]);

$container->tag('sms.handler', 'notification.handler', [
    'priority' => 80,
    'async' => false,
    'channels' => ['sms', 'urgent'],
]);

// Получение с атрибутами
$serviceIds = $container->findTaggedServiceIds('notification.handler');

// Сортировка по priority
usort($serviceIds, function($a, $b) use ($container) {
    $attrA = $container->getTagAttributes($a, 'notification.handler');
    $attrB = $container->getTagAttributes($b, 'notification.handler');
    return ($attrB['priority'] ?? 0) <=> ($attrA['priority'] ?? 0);
});

// Обработка с учётом атрибутов
foreach ($serviceIds as $serviceId) {
    $handler = $container->get($serviceId);
    $attrs = $container->getTagAttributes($serviceId, 'notification.handler');
    
    if ($attrs['async']) {
        // Асинхронная обработка
        dispatch($handler, $notification);
    } else {
        // Синхронная обработка
        $handler->handle($notification);
    }
}
```

### Множественные теги

```php
// Сервис может иметь несколько тегов
$container->set('database.logger', fn() => new DatabaseLogger());

$container->tag('database.logger', [
    'logger',
    'database.dependent',
    'high.memory',
    'async.capable',
]);

// Проверка наличия конкретного тега
if ($container->hasTag('database.logger', 'high.memory')) {
    // Специальная обработка для memory-intensive сервисов
}

// Получение всех тегов сервиса
$tags = $container->getServiceTags('database.logger');
// ['logger', 'database.dependent', 'high.memory', 'async.capable']

// Удаление тега
$container->untag('database.logger', 'async.capable');
```

### Реальные примеры

#### 1. Middleware Pipeline

```php
// Регистрируем middleware
$container->set('auth.middleware', fn() => new AuthMiddleware());
$container->set('cors.middleware', fn() => new CorsMiddleware());
$container->set('rate.middleware', fn() => new RateLimitMiddleware());

// Помечаем с порядком выполнения
$container->tag('auth.middleware', 'http.middleware', ['order' => 10]);
$container->tag('cors.middleware', 'http.middleware', ['order' => 5]);
$container->tag('rate.middleware', 'http.middleware', ['order' => 15]);

// Строим pipeline
$middlewares = $container->findTaggedServiceIds('http.middleware');
usort($middlewares, fn($a, $b) => 
    $container->getTagAttributes($a, 'http.middleware')['order'] <=>
    $container->getTagAttributes($b, 'http.middleware')['order']
);

$pipeline = new Pipeline();
foreach ($middlewares as $id) {
    $pipeline->pipe($container->get($id));
}
```

#### 2. Plugin System

```php
// Плагины
$container->set('analytics.plugin', fn() => new AnalyticsPlugin());
$container->set('cache.plugin', fn() => new CachePlugin());
$container->set('debug.plugin', fn() => new DebugPlugin());

// Теги с версиями и зависимостями
$container->tag('analytics.plugin', 'plugin', [
    'version' => '1.0',
    'requires' => [],
]);

$container->tag('cache.plugin', 'plugin', [
    'version' => '2.1',
    'requires' => ['redis'],
]);

$container->tag('debug.plugin', 'plugin', [
    'version' => '1.5',
    'requires' => [],
    'enabled' => getenv('APP_DEBUG') === 'true',
]);

// Загрузка плагинов
$pluginIds = $container->findTaggedServiceIds('plugin');
foreach ($pluginIds as $pluginId) {
    $attrs = $container->getTagAttributes($pluginId, 'plugin');
    
    if ($attrs['enabled'] ?? true) {
        $plugin = $container->get($pluginId);
        $plugin->register($app);
    }
}
```

#### 3. Command Handlers (CQRS)

```php
// Command handlers
$container->set('create.user.handler', fn($c) => new CreateUserHandler($c->get('repository')));
$container->set('update.user.handler', fn($c) => new UpdateUserHandler($c->get('repository')));
$container->set('delete.user.handler', fn($c) => new DeleteUserHandler($c->get('repository')));

// Помечаем тегами с типами команд
$container->tag('create.user.handler', 'command.handler', ['command' => CreateUserCommand::class]);
$container->tag('update.user.handler', 'command.handler', ['command' => UpdateUserCommand::class]);
$container->tag('delete.user.handler', 'command.handler', ['command' => DeleteUserCommand::class]);

// Command Bus
class CommandBus {
    private array $handlers = [];
    
    public function __construct(Container $container) {
        $handlerIds = $container->findTaggedServiceIds('command.handler');
        
        foreach ($handlerIds as $handlerId) {
            $attrs = $container->getTagAttributes($handlerId, 'command.handler');
            $commandClass = $attrs['command'];
            $this->handlers[$commandClass] = $container->get($handlerId);
        }
    }
    
    public function execute($command): mixed {
        $commandClass = get_class($command);
        $handler = $this->handlers[$commandClass] ?? null;
        
        if (!$handler) {
            throw new \RuntimeException("No handler for {$commandClass}");
        }
        
        return $handler->handle($command);
    }
}
```

### API методов

```php
// Установка тега
$container->tag(string $serviceId, string|array $tags, array $attributes = []);

// Поиск по тегу (возвращает сервисы)
$services = $container->findByTag(string $tag): array;

// Поиск по тегу (возвращает IDs)
$ids = $container->findTaggedServiceIds(string $tag): array;

// Получение атрибутов
$attrs = $container->getTagAttributes(string $serviceId, string $tag): array;

// Проверка наличия тега
$has = $container->hasTag(string $serviceId, string $tag): bool;

// Получение всех тегов сервиса
$tags = $container->getServiceTags(string $serviceId): array;

// Получение всех тегов в контейнере
$allTags = $container->getAllTags(): array;

// Удаление тега
$container->untag(string $serviceId, string $tag): void;
```

---

## 🎯 Best Practices

### 1. Структурируйте конфигурацию

```php
// config/services.php
return [
    // Core services
    'config' => fn() => new Config(__DIR__),
    'logger' => fn() => new Logger(),
    
    // Database layer
    'database' => fn($c) => new Database($c->get('config')),
    'user.repository' => fn($c) => new UserRepository($c->get('database')),
    
    // Application services
    'user.service' => fn($c) => new UserService($c->get('user.repository')),
];
```

### 2. Используйте environment-based конфигурацию

```php
// bootstrap.php
$env = getenv('APP_ENV') ?: 'development';

$container = match ($env) {
    'production' => require __DIR__ . '/config/container.prod.php',
    'testing' => require __DIR__ . '/config/container.test.php',
    default => require __DIR__ . '/config/container.dev.php',
};

// container.prod.php
$services = require __DIR__ . '/services.php';
return ContainerFactory::create(services: $services);  // Uses compiled

// container.dev.php
return ContainerFactory::createConfigured(
    configurator: fn($c) => require __DIR__ . '/services-config.php',
    useCompiled: false  // Regular container for development
);
```

### 3. Оптимизируйте тяжёлые сервисы

```php
// Используйте Lazy Loading для тяжёлых сервисов
$container->setLazy('elasticsearch', fn($c) => 
    new Elasticsearch($c->get('config')->get('elastic'))
);

$container->setLazy('image.processor', fn() => 
    new ImageProcessor()  // Loads heavy libraries
);

$container->setLazy('pdf.generator', fn($c) => 
    new PdfGenerator($c->get('config')->get('pdf'))
);
```

### 4. Используйте декораторы для cross-cutting concerns

```php
// Базовые сервисы
$container->set('api.client', fn() => new ApiClient());
$container->set('user.service', fn($c) => new UserService($c->get('repository')));

// Добавляем логирование ко всем сервисам через теги
$container->tag('api.client', 'loggable');
$container->tag('user.service', 'loggable');

$loggableIds = $container->findTaggedServiceIds('loggable');
foreach ($loggableIds as $serviceId) {
    $container->decorate($serviceId, fn($s, $c) => 
        new LoggingDecorator($s, $c->get('logger'))
    );
}
```

### 5. Компилируйте в production

```php
// deploy.sh
#!/bin/bash

# Install dependencies
composer install --no-dev --optimize-autoloader

# Compile container
composer compile

# Clear opcache
php artisan opcache:clear  # Laravel
# or
curl http://localhost/opcache-clear.php

# Restart services
systemctl reload php-fpm
```

### 6. Используйте теги для расширяемости

```php
// В базовом коде
interface ValidatorInterface {
    public function validate($data): bool;
}

// Регистрация валидаторов (может быть в разных модулях)
$container->set('email.validator', fn() => new EmailValidator());
$container->set('phone.validator', fn() => new PhoneValidator());
$container->set('age.validator', fn() => new AgeValidator());

$container->tag('email.validator', 'validator', ['field' => 'email']);
$container->tag('phone.validator', 'validator', ['field' => 'phone']);
$container->tag('age.validator', 'validator', ['field' => 'age']);

// Автоматический validator registry
class ValidatorRegistry {
    private array $validators = [];
    
    public function __construct(Container $container) {
        $ids = $container->findTaggedServiceIds('validator');
        
        foreach ($ids as $id) {
            $attrs = $container->getTagAttributes($id, 'validator');
            $field = $attrs['field'];
            $this->validators[$field] = $container->get($id);
        }
    }
    
    public function validate(string $field, $value): bool {
        return $this->validators[$field]?->validate($value) ?? true;
    }
}
```

---

## ⚡ Производительность

### Сравнительная таблица

| Возможность | Overhead | Когда использовать |
|------------|----------|-------------------|
| **Autowiring** | +5-10% first access | Development, prototyping |
| **Lazy Loading** | +2-3% first access | Тяжёлые/редкие сервисы |
| **Decorators** | +1-2% per decorator | Cross-cutting concerns |
| **Compiled Container** | **0%** (faster!) | Production всегда |
| **Tagged Services** | ~1% на поиск | Динамические коллекции |

### Комбинированное использование

```php
// Development
$container = new Container();
$container->enableAutowiring();

// Auto-resolved services
$service = $container->get(MyService::class);

// Production
$services = require 'cache/services.php';
$container = ContainerFactory::create(services: $services);

// Compiled container без autowiring overhead
$service = $container->get('my.service');  // Instant!
```

---

## 📚 Дополнительные ресурсы

### Документация

- [README.md](../README.md) - Основная документация
- [COMPILATION.md](COMPILATION.md) - Полный гайд по компиляции
- [EXAMPLES.md](EXAMPLES.md) - Практические примеры
- [ARCHITECTURE.md](ARCHITECTURE.md) - Архитектура контейнера

### Отчёты

- [BENCHMARK_REPORT.md](../BENCHMARK_REPORT.md) - Бенчмарки производительности
- [COMPILED_CONTAINER_REPORT.md](../COMPILED_CONTAINER_REPORT.md) - Тесты compiled container
- [PERFORMANCE_REPORT.md](../PERFORMANCE_REPORT.md) - Общий анализ

### Примеры кода

- [examples/compiled-container-example.php](../examples/compiled-container-example.php) - Compiled container

---

## 💡 Советы и трюки

### Autowiring с интерфейсами

```php
// Регистрируйте реализации интерфейсов явно
$container->set(LoggerInterface::class, fn() => new FileLogger());
$container->set(CacheInterface::class, fn() => new RedisCache());

// Затем включайте autowiring
$container->enableAutowiring();

// Классы с type hints на интерфейсы будут работать
class UserService {
    public function __construct(
        private LoggerInterface $logger,  // Получит FileLogger
        private CacheInterface $cache      // Получит RedisCache
    ) {}
}

$service = $container->get(UserService::class);
```

### Lazy + Decorators

```php
// Lazy сервис с декораторами
$container->setLazy('api', fn() => new ApiClient($apiKey));
$container->decorate('api', fn($a, $c) => new CachedApi($a, $c->get('cache')));
$container->decorate('api', fn($a, $c) => new RetryableApi($a, 3));

// LazyProxy создаётся сразу
$api = $container->get('api');  // Returns LazyProxy

// Реальный сервис и все декораторы создаются при первом вызове
$api->call('/users');  // Здесь создаётся RetryableApi(CachedApi(ApiClient))
```

### Tagged + Compiled

```php
// Теги работают и в compiled container
$container->tag('service1', 'group1');
$container->tag('service2', 'group1');

// Компилируем
$container->compileToFile('cache/container.php');

// В production
$services = require 'cache/services.php';
$container = new CompiledContainer($services);

// Теги доступны
$container->tag('service3', 'group1');  // Можно добавлять динамически
$group = $container->findByTag('group1');
```

---

## 🔮 Roadmap

### v2.1 (планируется)

- [ ] Autowiring с PHP 8.4 attributes
- [ ] Lazy loading оптимизация через WeakMap
- [ ] Decorator priorities
- [ ] Compiled container с встроенными тегами

### v2.2 (планируется)

- [ ] Async service initialization
- [ ] Service locator pattern
- [ ] Container delegation
- [ ] Scoped containers

---

**Дата:** 16 октября 2025  
**Версия:** 2.0.0  
**Статус:** ✅ Production Ready
