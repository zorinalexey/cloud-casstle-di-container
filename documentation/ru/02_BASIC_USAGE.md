# Базовое использование

**CloudCastle DI Container v2.0**

---

## 📋 Содержание

1. [Регистрация сервисов](#регистрация-сервисов)
2. [Получение сервисов](#получение-сервисов)
3. [Проверка существования](#проверка-существования)
4. [Удаление сервисов](#удаление-сервисов)
5. [Список сервисов](#список-сервисов)

---

## Регистрация сервисов

### Фабрика (рекомендуется)

```php
$container->set('database', function($container) {
    return new Database('localhost', 'myapp');
});
```

### Готовый объект

```php
$logger = new FileLogger('/var/log/app.log');
$container->set('logger', $logger);
```

### С зависимостями

```php
$container->set('repository', function($c) {
    return new UserRepository(
        $c->get('database'),
        $c->get('logger')
    );
});
```

---

## Получение сервисов

### Базовое получение

```php
$db = $container->get('database');
```

### Singleton паттерн

```php
$db1 = $container->get('database');
$db2 = $container->get('database');

// $db1 === $db2 (тот же экземпляр)
```

### PSR-11 совместимость

```php
use Psr\Container\ContainerInterface;

function myFunction(ContainerInterface $container) {
    $service = $container->get('my_service');
}
```

---

## Проверка существования

```php
if ($container->has('database')) {
    $db = $container->get('database');
} else {
    echo "Service not found";
}
```

---

## Удаление сервисов

```php
$container->remove('database');

$container->has('database'); // false
```

---

## Список сервисов

```php
$services = $container->getServiceIds();
// ['database', 'logger', 'repository', ...]

foreach ($services as $id) {
    echo "Service: {$id}\n";
}
```

---

## 🎯 Best Practices

### ✅ DO

```php
// Использовать фабрики
$container->set('db', fn($c) => new Database());

// Type hint зависимости
class MyService {
    public function __construct(private Database $db) {}
}

// Проверять существование
if ($container->has('service')) {
    // ...
}
```

### ❌ DON'T

```php
// Не создавать сервисы сразу (используйте фабрики)
$container->set('db', new Database()); // ❌

// Не использовать глобальное состояние
global $container; // ❌

// Не полагаться на порядок регистрации
```

---

Следующий шаг: [Продвинутые возможности](03_ADVANCED_FEATURES.md)

