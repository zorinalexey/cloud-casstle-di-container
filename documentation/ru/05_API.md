# API Reference

**CloudCastle DI Container v2.0**

---

## Container

### Основные методы

#### `set(string $id, callable|object $service): void`

Регистрирует сервис в контейнере.

```php
$container->set('logger', fn() => new Logger());
```

#### `get(string $id): mixed`

Получает сервис из контейнера.

```php
$logger = $container->get('logger');
```

**Throws:** `NotFoundException` если сервис не найден.

#### `has(string $id): bool`

Проверяет существование сервиса.

```php
if ($container->has('logger')) {
    // ...
}
```

#### `remove(string $id): void`

Удаляет сервис из контейнера.

```php
$container->remove('logger');
```

#### `getServiceIds(): array`

Возвращает список всех ID сервисов.

```php
$ids = $container->getServiceIds();
```

---

### Autowiring

#### `enableAutowiring(): void`

Включает autowiring.

```php
$container->enableAutowiring();
```

#### `disableAutowiring(): void`

Выключает autowiring.

```php
$container->disableAutowiring();
```

---

### Lazy Loading

#### `setLazy(string $id, callable $factory): void`

Регистрирует lazy сервис.

```php
$container->setLazy('heavy', fn() => new HeavyService());
```

---

### Decorators

#### `decorate(string $id, callable $decorator, int $priority = 0): void`

Декорирует сервис.

```php
$container->decorate('logger', fn($l) => new CachedLogger($l), 10);
```

**Parameters:**
- `$id` — ID сервиса
- `$decorator` — функция декоратора
- `$priority` — приоритет (lower = applied first)

---

### Tagged Services

#### `tag(string $id, string $tag, array $attributes = []): void`

Добавляет тег к сервису.

```php
$container->tag('redis', 'cache', ['priority' => 10]);
```

#### `findTaggedServiceIds(string $tag): array`

Находит все сервисы с тегом.

```php
$caches = $container->findTaggedServiceIds('cache');
```

#### `getServiceTags(string $id): array`

Получает все теги сервиса.

```php
$tags = $container->getServiceTags('redis');
```

#### `getAllTags(): array`

Получает все теги.

```php
$allTags = $container->getAllTags();
```

#### `untag(string $id, string $tag): void`

Удаляет тег.

```php
$container->untag('redis', 'cache');
```

---

### Compilation

#### `compile(string $className, string $namespace): string`

Компилирует контейнер в PHP код.

```php
$code = $container->compile('MyContainer', 'App\\DI');
```

#### `compileToFile(string $path, string $className, string $namespace): bool`

Компилирует и сохраняет в файл.

```php
$container->compileToFile(
    __DIR__ . '/cache/Container.php',
    'Container',
    'App\\DI'
);
```

---

### Attributes

#### `registerFromAttribute(string $className): void`

Регистрирует класс с атрибутом #[Service].

```php
$container->registerFromAttribute(MyService::class);
```

#### `registerFromDirectory(string $dir, string $namespace): void`

Сканирует директорию и регистрирует все классы с #[Service].

```php
$container->registerFromDirectory(
    __DIR__ . '/src/Services',
    'App\\Services'
);
```

---

### Service Locator

#### `createServiceLocator(array $serviceIds): ServiceLocator`

Создаёт service locator.

```php
$locator = $container->createServiceLocator(['s1', 's2']);
```

#### `createServiceLocatorFromTag(string $tag): ServiceLocator`

Создаёт locator из тега.

```php
$locator = $container->createServiceLocatorFromTag('public');
```

---

### Delegation

#### `addDelegate(ContainerInterface $delegate): void`

Добавляет delegate контейнер.

```php
$container->addDelegate($otherContainer);
```

---

### Scoped Services

#### `setScoped(string $id, string $scope, callable $factory): void`

Регистрирует scoped сервис.

```php
$container->setScoped('request.data', 'request', fn() => new RequestData());
```

#### `beginScope(string $name): void`

Начинает scope.

```php
$container->beginScope('request');
```

#### `endScope(): void`

Завершает scope.

```php
$container->endScope();
```

---

### Async

#### `getAsync(string $id): \Generator`

Асинхронное получение сервиса.

```php
foreach ($container->getAsync('service') as $s) {
    // Use $s
}
```

#### `batchGetAsync(array $ids): \Generator`

Batch async loading.

```php
foreach ($container->batchGetAsync(['s1', 's2']) as $id => $service) {
    echo "{$id} loaded\n";
}
```

---

## Attributes

### #[Service]

```php
#[Service(
    id: 'my.service',    // Опционально
    tags: ['tag1'],      // Опционально
    lazy: false,         // Опционально
    priority: 0          // Опционально
)]
class MyService {}
```

### #[Inject]

```php
public function __construct(
    #[Inject('my.logger')] private object $logger
) {}
```

### #[Tag]

```php
#[Tag('infrastructure', ['priority' => 10])]
class MyService {}
```

---

## Exceptions

### `ContainerException`

Базовое исключение контейнера.

### `NotFoundException`

Сервис не найден (PSR-11).

---

Вернуться к [содержанию](README.md)

