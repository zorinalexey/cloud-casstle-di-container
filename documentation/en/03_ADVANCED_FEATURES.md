# Advanced Features

**CloudCastle DI Container v2.0**

---

## 🤖 Autowiring

Automatic dependency resolution based on type hints.

```php
$container->enableAutowiring();

class EmailService {
    public function __construct(
        private Mailer $mailer,
        private Logger $logger
    ) {}
}

$email = $container->get(EmailService::class);
```

---

## 🔄 Lazy Loading

Deferred initialization with WeakMap optimization (no memory leaks).

```php
$container->setLazy('analytics', fn() => new Analytics());

$proxy = $container->get('analytics'); // LazyProxy
$proxy->track('event'); // Now initialized
```

---

## 🎨 Decorators

Decoration with priority support.

```php
$container->set('api', fn() => new ApiClient());

$container->decorate('api', fn($api) => new AuthDecorator($api), 10);
$container->decorate('api', fn($api) => new LogDecorator($api), 5);

// Order: AuthDecorator -> LogDecorator -> ApiClient
```

---

## 🏷️ PHP 8+ Attributes

Declarative configuration.

```php
use CloudCastle\DI\Attribute\{Service, Tag, Inject};

#[Service(id: 'app.logger', tags: ['logging'])]
#[Tag('infrastructure', ['priority' => 10])]
class Logger {}

$container->registerFromAttribute(Logger::class);
```

---

## 🔍 Service Locator

Limited service access.

```php
$locator = $container->createServiceLocator(['service1', 'service2']);
$locator->has('service1'); // true
```

---

## 🔗 Container Delegation

Multi-container search.

```php
$main = new Container();
$plugins = new Container();

$main->addDelegate($plugins);
$plugin = $main->get('plugin.service'); // Found in delegate!
```

---

## 🔄 Scoped Containers

Lifecycle management for request/session.

```php
use CloudCastle\DI\ScopedContainer;

$scoped = new ScopedContainer($container);
$scoped->setScope('request.data', 'request');

$scoped->beginScope('request');
$data = $scoped->get('request.data');
$scoped->endScope();
```

---

Next: [Compiled Container](04_COMPILED.md)

