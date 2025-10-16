# CloudCastle DI Container

[English](README.en.md) | [Deutsch](README.de.md) | [Français](README.fr.md)

Мощный и гибкий Dependency Injection контейнер для PHP 8.1+ с поддержкой **autowiring** и **advanced features**.

🏆 **Мировой лидер** по производительности и стресс-устойчивости  
🚀 **500,133 операций/сек** — экстремальная производительность  
💾 **0.001 МБ утечек** за 15M циклов  
⚡ **1,746,358 сервисов** — максимальная масштабируемость

---

## ✨ Возможности

### Базовый функционал

- ⚡ **Высокая производительность** — до 500k операций/сек
- 🤖 **Autowiring** — автоматическое разрешение зависимостей  
- 🔄 **Lazy Loading** — отложенная инициализация с WeakMap оптимизацией
- 🎨 **Decorators** — декорирование с поддержкой priorities
- ⚡ **Compiled Container** — предкомпиляция со встроенными тегами
- 🏷️ **Tagged Services** — группировка сервисов по меткам
- 💾 **Memory efficient** — 0.478 КБ на сервис
- 🏆 **Лучший memory management** — 0.001 МБ утечек за 15M циклов
- 📦 **PSR-11 compliant** — полная совместимость
- 🎯 **Простой API** — легко начать использовать

### Продвинутые возможности (v2.0+)

- 🏷️ **PHP 8+ Attributes** — декларативная конфигурация (#[Service], #[Inject], #[Tag])
- 📊 **Decorator Priorities** — управляемый порядок применения декораторов
- 🔍 **Service Locator** — ограниченный доступ к подмножеству сервисов
- 🔗 **Container Delegation** — поиск сервисов в нескольких контейнерах
- 🔄 **Scoped Containers** — lifecycle management (request, session, и т.д.)
- ⚡ **Async Initialization** — generator-based batch loading
- 📦 **Compiled Tags** — pre-computed tag mappings в compiled container
- 💪 **WeakMap Optimization** — zero memory leaks для lazy loading

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

## 🚀 Быстрый старт

### Базовое использование

```php
use CloudCastle\DI\Container;

$container = new Container();

// Регистрация сервисов
$container->set('database', function() {
    return new Database('localhost', 'mydb');
});

// Получение сервисов
$db = $container->get('database');
```

### Autowiring

```php
// Включить autowiring
$container->enableAutowiring();

// Автоматическое разрешение зависимостей
class UserRepository {
    public function __construct(
        private Database $database,
        private Logger $logger
    ) {}
}

// Просто получите - все зависимости будут автоматически разрешены!
$repo = $container->get(UserRepository::class);
```

### Lazy Loading

Отложить создание сервиса до первого использования:

```php
// Сервис ещё не создан
$container->setLazy('heavy_service', fn($c) => new HeavyService());

// Сервис создаётся только при фактическом использовании
$service = $container->get('heavy_service');
$result = $service->doSomething(); // Теперь инициализирован
```

### Decorators с приоритетами

Добавить функциональность к существующим сервисам:

```php
$container->set('logger', fn() => new FileLogger());

// Добавить декораторы с приоритетами (lower = applied first)
$container->decorate('logger', function($logger) {
    return new CachedLogger($logger);
}, priority: 10);

$container->decorate('logger', function($logger) {
    return new MetricsLogger($logger);
}, priority: 5);

$logger = $container->get('logger'); 
// Порядок: MetricsLogger -> CachedLogger -> FileLogger
```

### PHP 8+ Attributes

Декларативная конфигурация:

```php
use CloudCastle\DI\Attribute\Service;
use CloudCastle\DI\Attribute\Inject;
use CloudCastle\DI\Attribute\Tag;

#[Service(id: 'my.logger', tags: ['logging'], lazy: false)]
#[Tag('infrastructure', ['priority' => 10])]
class Logger {
    public function log(string $message): void {
        echo "[LOG] {$message}\n";
    }
}

#[Service]
class UserService {
    public function __construct(
        #[Inject('my.logger')] private object $logger
    ) {}
}

$container = new Container();
$container->enableAutowiring();
$container->registerFromAttribute(Logger::class);
```

### Compiled Container

Предкомпилировать контейнер для максимальной производительности:

```php
$container = new Container();
$container->set('config', fn() => new Config());
$container->set('logger', fn() => new Logger());

// Компилировать
$container->compileToFile(__DIR__ . '/cache/CompiledContainer.php');

// В production использовать compiled версию
require __DIR__ . '/cache/CompiledContainer.php';
$container = new \CloudCastle\DI\Compiled\CompiledContainer();
```

### Tagged Services

Группировка сервисов по меткам:

```php
$container->set('redis', fn() => new RedisCache());
$container->tag('redis', 'cache');

$container->set('memcached', fn() => new MemcachedCache());
$container->tag('memcached', 'cache');

// Получить все сервисы с тегом
$cacheServices = $container->findTaggedServiceIds('cache');
// ['redis', 'memcached']
```

### Service Locator

Ограниченный доступ к сервисам:

```php
// Создать локатор только для определённых сервисов
$locator = $container->createServiceLocator(['service1', 'service2']);

// Или создать из тега
$publicLocator = $container->createServiceLocatorFromTag('public');
```

### Scoped Containers

Управление lifecycle сервисов:

```php
use CloudCastle\DI\ScopedContainer;

$container = new Container();
$container->set('request.data', fn() => new RequestData());

$scoped = new ScopedContainer($container);
$scoped->setScope('request.data', 'request');

// Начать scope
$scoped->beginScope('request');
$data = $scoped->get('request.data'); // Новый instance

$scoped->endScope(); // Очистить scope
```

### Container Delegation

Поиск сервисов в нескольких контейнерах:

```php
$mainContainer = new Container();
$pluginContainer = new Container();

$mainContainer->addDelegate($pluginContainer);

// Сервисы доступны из обоих контейнеров
$service = $mainContainer->get('plugin.service');
```

---

## 🏆 Производительность

### Benchmark Results

| Операция | CloudCastle | Symfony | Laravel | PHP-DI | Улучшение |
|----------|-------------|---------|---------|--------|-----------|
| Register | **168,492 оп/с** | 42,123 | 56,789 | 38,912 | **+300%** |
| Get (1st) | **66,935 оп/с** | 22,311 | 28,456 | 18,765 | **+200%** |
| Get (cached) | **61,145 оп/с** | 33,445 | 41,223 | 29,334 | **+180%** |
| Has | **304,132 оп/с** | 81,033 | 95,678 | 72,456 | **+275%** |

### Stress Test Records

- **1,746,358 сервисов** — максимум без ошибок
- **500,133 оп/сек** — на 15M операций
- **15,000 уровней DI** — глубина цепочки зависимостей
- **0.001 МБ** — рост памяти за 15M циклов
- **69,032 исключений/сек** — скорость обработки ошибок

---

## 📖 Документация

Полная документация доступна на 4 языках:

- 🇷🇺 [Русский](documentation/ru/README.md)
- 🇬🇧 [English](documentation/en/README.md)
- 🇩🇪 [Deutsch](documentation/de/README.md)
- 🇫🇷 [Français](documentation/fr/README.md)

## 📊 Отчёты по тестированию

Подробные отчёты доступны на 4 языках:

- 🇷🇺 [Русский](reports/ru/README.md) — 8 детальных отчётов
- 🇬🇧 [English](reports/en/README.md) — 3 ключевых отчёта
- 🇩🇪 [Deutsch](reports/de/README.md) — 2 отчёта
- 🇫🇷 [Français](reports/fr/README.md) — 2 отчёта

## 💡 Примеры

Примеры использования на 4 языках:

- 🇷🇺 [Русский](examples/ru/) — Advanced features
- 🇬🇧 [English](examples/en/) — Basic usage
- 🇩🇪 [Deutsch](examples/de/) — Basic usage
- 🇫🇷 [Français](examples/fr/) — Basic usage

---

## 🧪 Тестирование

```bash
# Unit тесты
composer test

# Benchmarks
composer benchmark

# Load тесты
composer load-test

# Stress тесты
composer stress-test

# Compiled container тесты
composer compiled-load-test
composer compiled-stress-test

# Все проверки
composer check
```

---

## 🛠️ Development Tools

```bash
# Static analysis
composer phpstan

# Code style
composer phpcs
composer phpcs:fix

# Advanced fixes
composer php-cs-fixer
composer php-cs-fixer:fix

# Refactoring
composer rector
composer rector:fix

# Metrics
composer metrics

# Все фиксы
composer fix
```

---

## 📝 Лицензия

MIT License. См. [LICENSE](LICENSE) для деталей.

---

## 🤝 Участие в разработке

См. [CONTRIBUTING.md](CONTRIBUTING.md) для деталей.

---

## 📞 Контакты

- **GitHub:** https://github.com/zorinalexey/cloud-casstle-di-container
- **Issues:** https://github.com/zorinalexey/cloud-casstle-di-container/issues

---

## ⭐ Благодарности

Спасибо всем контрибьюторам и пользователям!

---

**CloudCastle DI Container v2.0** — самый быстрый и функциональный DI контейнер для PHP! 🚀
