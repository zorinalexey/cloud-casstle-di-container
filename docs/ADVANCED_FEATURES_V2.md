# Advanced Features (v2.0+)

Расширенные возможности CloudCastle DI Container v2.0 и выше.

## 📋 Содержание

1. [PHP 8+ Attributes](#php-8-attributes)
2. [Decorator Priorities](#decorator-priorities)
3. [Service Locator Pattern](#service-locator-pattern)
4. [Container Delegation](#container-delegation)
5. [Scoped Containers](#scoped-containers)
6. [Async Service Initialization](#async-service-initialization)
7. [Compiled Container with Tags](#compiled-container-with-tags)
8. [WeakMap Optimization](#weakmap-optimization)

---

## 1. PHP 8+ Attributes

### Overview

Используйте атрибуты PHP 8+ для декларативной конфигурации сервисов.

### Доступные атрибуты

#### `#[Service]`

Маркирует класс как сервис для автоматической регистрации.

```php
use CloudCastle\DI\Attribute\Service;

#[Service(
    id: 'my.logger',          // Опционально: ID сервиса (по умолчанию - имя класса)
    tags: ['logger', 'core'], // Опционально: теги
    lazy: true,               // Опционально: ленивая загрузка
    priority: 10              // Опционально: приоритет
)]
class MyLogger
{
    public function log(string $message): void
    {
        echo "[LOG] {$message}\n";
    }
}
```

#### `#[Inject]`

Указывает какой сервис инжектировать в параметр.

```php
use CloudCastle\DI\Attribute\Inject;

class UserService
{
    public function __construct(
        #[Inject('my.logger')] private object $logger,
        #[Inject('cache.redis')] private object $cache
    ) {}
}
```

#### `#[Tag]`

Добавляет тег к сервису (можно использовать multiple раз).

```php
use CloudCastle\DI\Attribute\Tag;

#[Service]
#[Tag('middleware', ['priority' => 10])]
#[Tag('security')]
class AuthMiddleware
{
    // ...
}
```

### Регистрация из атрибутов

```php
$container = new Container();
$container->enableAutowiring();

// Регистрация одного класса
$container->registerFromAttribute(MyLogger::class);

// Автоматическое сканирование директории
$container->registerFromDirectory(
    __DIR__ . '/src/Services',
    'App\\Services'
);
```

---

## 2. Decorator Priorities

### Overview

Декораторы теперь поддерживают приоритеты для управления порядком применения.

### Использование

```php
$container = new Container();
$container->set('api', fn() => new ApiClient());

// Priority 10 - применится первым
$container->decorate('api', function($api, $c) {
    return new AuthDecorator($api);
}, 10);

// Priority 5 - применится вторым
$container->decorate('api', function($api, $c) {
    return new RateLimitDecorator($api);
}, 5);

// Priority 1 - применится последним
$container->decorate('api', function($api, $c) {
    return new LoggingDecorator($api);
}, 1);

// Порядок: Auth → RateLimit → Logging
$api = $container->get('api');
```

### Правила

- **Lower priority = applied first** (меньший приоритет применяется раньше)
- По умолчанию priority = 0
- Отрицательные приоритеты допустимы

---

## 3. Service Locator Pattern

### Overview

Service Locator предоставляет ограниченный доступ к подмножеству сервисов.

### Создание локатора

```php
$container = new Container();
$container->set('service1', fn() => new Service1());
$container->set('service2', fn() => new Service2());
$container->set('internal.service', fn() => new InternalService());

// Локатор с ограниченным списком сервисов
$locator = $container->createServiceLocator(['service1', 'service2']);

$locator->has('service1');         // true
$locator->has('internal.service'); // false

$service1 = $locator->get('service1'); // ✓
// $locator->get('internal.service');  // ✗ NotFoundException
```

### Локатор из тега

```php
$container->tag('service1', 'public');
$container->tag('service2', 'public');

$publicLocator = ServiceLocator::fromTag($container, 'public');

// Доступны только сервисы с тегом 'public'
$services = $publicLocator->getServiceIds(); // ['service1', 'service2']
```

### Use Cases

1. **API контроллеров**: ограничить доступ к сервисам
2. **Плагины**: предоставить безопасный доступ к контейнеру
3. **Тестирование**: изолировать компоненты

---

## 4. Container Delegation

### Overview

Делегирование позволяет контейнеру искать сервисы в других контейнерах.

### Использование

```php
$mainContainer = new Container();
$mainContainer->set('app.service', fn() => new AppService());

$pluginContainer = new Container();
$pluginContainer->set('plugin.feature', fn() => new PluginFeature());

// Добавить delegate
$mainContainer->addDelegate($pluginContainer);

// Сервисы доступны из обоих контейнеров
$app = $mainContainer->get('app.service');      // из main
$plugin = $mainContainer->get('plugin.feature'); // из delegate
```

### Порядок разрешения

1. Главный контейнер
2. Delegates (в порядке добавления)
3. Autowiring (если включен)
4. NotFoundException

### Use Cases

1. **Модульная архитектура**: каждый модуль со своим контейнером
2. **Плагины**: изолированные контейнеры плагинов
3. **Микросервисы**: совместное использование сервисов

---

## 5. Scoped Containers

### Overview

Scoped containers управляют lifecycle сервисов по scope (request, session, etc.).

### Создание и использование

```php
$container = new Container();
$container->set('request.data', fn() => new RequestData());
$container->set('global.config', fn() => new Config());

$scopedContainer = new ScopedContainer($container);

// Назначить scope для сервиса
$scopedContainer->setScope('request.data', 'request');

// Начать scope
$scopedContainer->beginScope('request');

$data1 = $scopedContainer->get('request.data');
$data2 = $scopedContainer->get('request.data'); // same instance

// Завершить scope (очистить instances)
$scopedContainer->endScope();

// Новый scope
$scopedContainer->beginScope('request');
$data3 = $scopedContainer->get('request.data'); // новый instance
$scopedContainer->endScope();
```

### Доступные Scopes

Можно использовать любые имена:

- `'request'` - HTTP request lifecycle
- `'session'` - сессия пользователя
- `'transaction'` - database transaction
- Кастомные scopes

### Use Cases

1. **Web приложения**: request-scoped services
2. **Транзакции**: rollback services при ошибке
3. **Тестирование**: изолировать состояние между тестами

---

## 6. Async Service Initialization

### Overview

Generator-based асинхронная инициализация (подготовка к Fibers в PHP 8.1+).

### Использование

```php
$container = new Container();
$container->set('service1', fn() => new HeavyService1());
$container->set('service2', fn() => new HeavyService2());
$container->set('service3', fn() => new HeavyService3());

// Одиночная async инициализация
foreach ($container->getAsync('service1') as $service) {
    // $service готов к использованию
}

// Batch initialization
foreach ($container->batchGetAsync(['service1', 'service2', 'service3']) as $id => $service) {
    echo "Initialized: {$id}\n";
}
```

### Future

В будущих версиях с поддержкой Fibers:

```php
// Гипотетический код для PHP 8.2+
$fiber = new Fiber(function() use ($container) {
    return $container->get('heavy.service');
});

$fiber->start();
// ... другие операции ...
$service = $fiber->getReturn();
```

---

## 7. Compiled Container with Tags

### Overview

Compiled container теперь поддерживает встроенные теги для ultra-fast доступа.

### Компиляция с тегами

```php
$container = new Container();

$container->set('cache', fn() => new RedisCache());
$container->tag('cache', 'infrastructure', ['priority' => 10]);

$container->set('logger', fn() => new FileLogger());
$container->tag('logger', 'infrastructure', ['priority' => 5]);
$container->tag('logger', 'logging');

// Compile
$container->compileToFile(__DIR__ . '/cache/CompiledContainer.php');
```

### Использование compiled container

```php
require __DIR__ . '/cache/CompiledContainer.php';

$container = new \CloudCastle\DI\Compiled\CompiledContainer();

// Теги работают из коробки
$infrastructure = $container->findTaggedServiceIds('infrastructure');
// ['cache', 'logger']

$tags = $container->getServiceTags('logger');
// ['infrastructure', 'logging']

$attributes = $container->getTagAttributes('cache', 'infrastructure');
// ['priority' => 10]
```

### Performance

Compiled container с тегами:

- **0 reflection overhead**
- **Pre-computed tag mappings**
- **Inline match statements**
- **10-100x faster** чем runtime контейнер

---

## 8. WeakMap Optimization

### Overview

LazyProxy теперь использует WeakMap для tracking инициализации без memory leaks.

### Что это значит

```php
$container = new Container();
$container->setLazy('heavy', fn() => new HeavyService());

$proxy = $container->get('heavy'); // LazyProxy

// WeakMap автоматически трекает инициализацию
// Когда $proxy будет собран GC, tracking тоже удалится

unset($proxy); // Автоматическая очистка без memory leaks
```

### Benefits

1. **No memory leaks**: автоматическая очистка
2. **Performance**: O(1) lookup
3. **Compatibility**: PHP 8.0+

---

## Совместное использование

Все фичи могут работать вместе:

```php
// 1. Setup с attributes
#[Service(id: 'api', tags: ['http'], lazy: false)]
#[Tag('infrastructure', ['priority' => 10])]
class ApiClient {}

// 2. Container setup
$container = new Container();
$container->enableAutowiring();
$container->registerFromAttribute(ApiClient::class);

// 3. Decorators с priorities
$container->decorate('api', fn($api) => new AuthDecorator($api), 10);
$container->decorate('api', fn($api) => new LogDecorator($api), 5);

// 4. Scoped
$scoped = new ScopedContainer($container);
$scoped->setScope('api', 'request');

// 5. Compile для production
$container->compileToFile(__DIR__ . '/cache/CompiledContainer.php');
```

---

## Performance Comparison

| Feature | Runtime | Compiled | Improvement |
|---------|---------|----------|-------------|
| Service Get | 0.5µs | 0.05µs | **10x** |
| Tag Lookup | 2.0µs | 0.1µs | **20x** |
| Attributes | 50µs (reflection) | 0µs (pre-compiled) | **∞x** |
| Decorator Apply | 1.5µs | 1.5µs | 1x |
| Scope Check | 0.3µs | 0.3µs | 1x |

---

## Дополнительные ресурсы

- [Базовые возможности](ADVANCED_FEATURES.md)
- [Compilation Guide](COMPILATION.md)
- [Примеры](../examples/advanced-features-example.php)

---

**CloudCastle DI Container v2.0+** - Production-ready DI с enterprise features.

