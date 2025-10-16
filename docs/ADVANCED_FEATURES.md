# Advanced Features

CloudCastle DI Container поддерживает расширенные возможности для построения сложных приложений.

## Autowiring

Автоматическое разрешение зависимостей через reflection API.

```php
$container = new Container();
$container->enableAutowiring();

class Database {
    public function __construct(
        private string $host = 'localhost',
        private int $port = 3306
    ) {}
}

class UserRepository {
    public function __construct(
        private Database $database
    ) {}
}

// Все зависимости разрешаются автоматически
$repo = $container->get(UserRepository::class);
```

### Особенности

- Поддержка nullable параметров
- Поддержка default values
- Обнаружение циклических зависимостей
- Кэширование autowired сервисов

## Lazy Loading

Отложенная инициализация сервисов с использованием прокси.

```php
// Создаем lazy proxy
$container->setLazy('heavy_service', function($container) {
    // Этот код выполнится только при первом обращении к сервису
    return new HeavyService(
        $container->get('dependency1'),
        $container->get('dependency2')
    );
});

// Сервис еще не создан
$proxy = $container->get('heavy_service');

// Сервис создается при первом обращении
$result = $proxy->process(); 
```

### Преимущества

- Экономия памяти для редко используемых сервисов
- Ускорение загрузки приложения
- Прозрачная работа через magic methods

## Decorators

Декорирование сервисов дополнительной функциональностью.

```php
// Базовый сервис
$container->set('logger', fn() => new FileLogger('/var/log/app.log'));

// Добавляем кэширование
$container->decorate('logger', function($logger, $container) {
    return new CachedLogger($logger, $container->get('cache'));
});

// Добавляем сбор метрик
$container->decorate('logger', function($logger, $container) {
    return new MetricsLogger($logger, $container->get('metrics'));
});

// Добавляем фильтрацию
$container->decorate('logger', function($logger, $container) {
    return new FilteredLogger($logger, ['password', 'token']);
});

// Получаем полностью декорированный сервис
$logger = $container->get('logger');
// FilteredLogger -> MetricsLogger -> CachedLogger -> FileLogger
```

### Применение

- Логирование
- Кэширование
- Метрики и мониторинг
- Авторизация
- Валидация

## Compiled Container

Предкомпиляция контейнера для максимальной производительности в production.

```php
// Development: собираем контейнер
$container = new Container();
$container->set('config', fn() => new Config(__DIR__ . '/config'));
$container->set('database', fn($c) => new Database($c->get('config')));
$container->set('cache', fn() => new RedisCache());
$container->set('logger', fn() => new FileLogger());

// Компилируем в файл
$container->compileToFile(
    __DIR__ . '/var/cache/container.php',
    'AppContainer',
    'App\\DI'
);

// Production: используем скомпилированный контейнер
require_once __DIR__ . '/var/cache/container.php';
$container = new \App\DI\AppContainer();

// Доступ к сервисам через оптимизированный код
$db = $container->get('database');
```

### Генерируемый код

```php
<?php

declare(strict_types=1);

namespace App\DI;

use CloudCastle\DI\CompiledContainer;
use CloudCastle\DI\Exception\NotFoundException;

final class AppContainer extends CompiledContainer
{
    public function has(string $serviceId): bool
    {
        return match ($serviceId) {
            case 'config':
            case 'database':
            case 'cache':
            case 'logger':
                return true;
            default => false,
        };
    }

    public function get(string $serviceId): mixed
    {
        return match ($serviceId) {
            case 'config' => $this->instances['config'] ??= $this->service0(),
            case 'database' => $this->instances['database'] ??= $this->service1(),
            case 'cache' => $this->instances['cache'] ??= $this->service2(),
            case 'logger' => $this->instances['logger'] ??= $this->service3(),
            default => throw new NotFoundException("Service '$serviceId' not found"),
        };
    }
}
```

### Преимущества

- Максимальная производительность
- Отсутствие overhead контейнера
- Статическая оптимизация PHP opcache
- Простая отладка (читаемый PHP код)

## Tagged Services

Группировка сервисов по меткам для пакетной обработки.

```php
// Регистрируем обработчики событий
$container->set('email.handler', fn() => new EmailEventHandler());
$container->set('sms.handler', fn() => new SmsEventHandler());
$container->set('push.handler', fn() => new PushEventHandler());
$container->set('webhook.handler', fn() => new WebhookEventHandler());

// Помечаем тегами с приоритетами
$container->tag('email.handler', 'event.handler', ['priority' => 100]);
$container->tag('sms.handler', 'event.handler', ['priority' => 80]);
$container->tag('push.handler', 'event.handler', ['priority' => 60]);
$container->tag('webhook.handler', 'event.handler', ['priority' => 40]);

// Получаем все обработчики
$handlers = $container->findByTag('event.handler');

// Сортируем по приоритету
$serviceIds = $container->findTaggedServiceIds('event.handler');
usort($serviceIds, function($a, $b) use ($container) {
    $priorityA = $container->getTagAttributes($a, 'event.handler')['priority'] ?? 0;
    $priorityB = $container->getTagAttributes($b, 'event.handler')['priority'] ?? 0;
    return $priorityB <=> $priorityA;
});

// Обрабатываем событие всеми обработчиками
foreach ($serviceIds as $serviceId) {
    $handler = $container->get($serviceId);
    $handler->handle($event);
}
```

### Применение

- Системы плагинов
- Обработчики событий  
- Middleware chains
- Валидаторы
- Преобразователи данных
- Command handlers (CQRS)
- Коллекторы метрик

### Множественные теги

```php
// Сервис может иметь несколько тегов
$container->set('logger', fn() => new DatabaseLogger());
$container->tag('logger', ['logger', 'database.dependent', 'high.memory']);

// Проверка наличия тега
if ($container->hasTag('logger', 'high.memory')) {
    // Специальная обработка для сервисов с высоким потреблением памяти
}

// Получение всех тегов сервиса
$tags = $container->getServiceTags('logger'); // ['logger', 'database.dependent', 'high.memory']

// Получение всех тегов в контейнере
$allTags = $container->getAllTags();

// Удаление тега
$container->untag('logger', 'high.memory');
```

## Комбинирование возможностей

Все возможности можно комбинировать для максимальной гибкости:

```php
$container = new Container();
$container->enableAutowiring();

// Lazy service с декораторами и тегами
$container->setLazy('logger', fn() => new FileLogger());
$container->decorate('logger', fn($l, $c) => new CachedLogger($l, $c->get('cache')));
$container->tag('logger', ['logger', 'cacheable'], ['priority' => 10]);

// Компиляция для production
if (getenv('APP_ENV') === 'production') {
    $container->compileToFile(__DIR__ . '/cache/container.php');
}
```

## Best Practices

1. **Autowiring**: Используйте для быстрой разработки, но явно регистрируйте критичные сервисы
2. **Lazy Loading**: Применяйте для тяжелых, редко используемых сервисов
3. **Decorators**: Идеально для добавления cross-cutting concerns (логирование, кэширование)
4. **Compiled Container**: Обязательно используйте в production
5. **Tagged Services**: Отлично для расширяемых систем с плагинами

## Производительность

| Возможность | Overhead | Применение |
|------------|----------|------------|
| Autowiring | ~5-10% | Development |
| Lazy Loading | ~2-3% | Первое обращение |
| Decorators | ~1-2% за декоратор | Всегда |
| Compiled Container | **0%** | Production |
| Tagged Services | ~1% | Поиск по тегам |

