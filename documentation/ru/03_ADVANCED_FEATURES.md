# Продвинутые возможности

**CloudCastle DI Container v2.0**

---

## 📋 Содержание

1. [Autowiring](#autowiring)
2. [Lazy Loading](#lazy-loading)
3. [Decorators](#decorators)
4. [Tagged Services](#tagged-services)
5. [PHP 8+ Attributes](#php-8-attributes)
6. [Service Locator](#service-locator)
7. [Container Delegation](#container-delegation)
8. [Scoped Containers](#scoped-containers)

---

## 🤖 Autowiring

Автоматическое разрешение зависимостей на основе type hints.

```php
$container->enableAutowiring();

class EmailService {
    public function __construct(
        private Mailer $mailer,
        private Logger $logger
    ) {}
}

// Все зависимости разрешатся автоматически
$email = $container->get(EmailService::class);
```

### С атрибутом #[Inject]

```php
use CloudCastle\DI\Attribute\Inject;

class Service {
    public function __construct(
        #[Inject('my.logger')] private object $logger
    ) {}
}
```

---

## 🔄 Lazy Loading

Отложенная инициализация с WeakMap оптимизацией (нет утечек памяти).

```php
$container->setLazy('analytics', fn() => new Analytics());

$proxy = $container->get('analytics'); // LazyProxy
// Analytics ещё не создан

$proxy->track('event'); // Теперь создан
```

---

## 🎨 Decorators

Декорирование с поддержкой приоритетов.

```php
$container->set('api', fn() => new ApiClient());

// Priority 10 - применится первым
$container->decorate('api', fn($api) => new AuthDecorator($api), 10);

// Priority 5 - применится вторым  
$container->decorate('api', fn($api) => new LogDecorator($api), 5);

$api = $container->get('api');
// Порядок: AuthDecorator -> LogDecorator -> ApiClient
```

---

## 🏷️ Tagged Services

Группировка и поиск сервисов.

```php
$container->set('redis', fn() => new RedisCache());
$container->tag('redis', 'cache', ['priority' => 10]);

$container->set('file', fn() => new FileCache());
$container->tag('file', 'cache', ['priority' => 5]);

// Найти все
$caches = $container->findTaggedServiceIds('cache');

// С атрибутами
$attrs = $container->getTagAttributes('redis', 'cache');
// ['priority' => 10]
```

---

## 🏷️ PHP 8+ Attributes

Декларативная конфигурация.

```php
use CloudCastle\DI\Attribute\{Service, Tag, Inject};

#[Service(id: 'app.logger', tags: ['logging'], lazy: false)]
#[Tag('infrastructure', ['priority' => 10])]
class Logger {}

$container->registerFromAttribute(Logger::class);

// Или сканировать директорию
$container->registerFromDirectory(__DIR__ . '/src/Services', 'App\\Services');
```

---

## 🔍 Service Locator

Ограниченный доступ к сервисам.

```php
// Создать локатор для определённых сервисов
$locator = $container->createServiceLocator(['service1', 'service2']);

$locator->has('service1'); // true
$locator->has('service3'); // false

// Из тега
$publicLocator = $container->createServiceLocatorFromTag('public');
```

---

## 🔗 Container Delegation

Поиск в нескольких контейнерах.

```php
$main = new Container();
$main->set('app', fn() => new App());

$plugins = new Container();
$plugins->set('plugin', fn() => new Plugin());

// Добавить delegate
$main->addDelegate($plugins);

$plugin = $main->get('plugin'); // Найдено в delegate!
```

---

## 🔄 Scoped Containers

Lifecycle management для request/session.

```php
use CloudCastle\DI\ScopedContainer;

$container = new Container();
$container->set('request.data', fn() => new RequestData());

$scoped = new ScopedContainer($container);
$scoped->setScope('request.data', 'request');

// Request 1
$scoped->beginScope('request');
$data1 = $scoped->get('request.data');
$scoped->endScope();

// Request 2
$scoped->beginScope('request');
$data2 = $scoped->get('request.data'); // Новый instance
$scoped->endScope();
```

---

## ⚡ Async Initialization

Generator-based batch loading.

```php
// Одиночная async инициализация
foreach ($container->getAsync('service') as $s) {
    // $s готов
}

// Batch loading
foreach ($container->batchGetAsync(['s1', 's2', 's3']) as $id => $service) {
    echo "Loaded: {$id}\n";
}
```

---

## 🎯 Следующие шаги

- [Compiled Container](04_COMPILED.md) — оптимизация для production
- [API Reference](05_API.md) — полный справочник
- [Примеры](../../examples/ru/) — рабочие примеры кода

