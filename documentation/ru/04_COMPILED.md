# Compiled Container

**CloudCastle DI Container v2.0**

---

## 📋 Зачем компилировать?

### Преимущества

- ⚡ **+1.3% быстрее** операции get/has
- 🚀 **-47% время загрузки** контейнера
- 💾 **-17% памяти** на сервис
- 🏷️ **Встроенные теги** — мгновенный доступ
- 🔥 **Zero reflection** в production

### Когда использовать

✅ Production environment  
✅ Статичная конфигурация (не меняется в runtime)  
✅ Большие приложения (1000+ сервисов)  
✅ Важен startup time

---

## 🚀 Использование

### Способ 1: Ручная компиляция

```php
// 1. Настроить контейнер
$container = new Container();
$container->set('app', fn() => new App());
$container->set('db', fn() => new Database());
$container->tag('db', 'infrastructure');

// 2. Скомпилировать
$code = $container->compile('MyContainer', 'App\\DI');

// 3. Сохранить
file_put_contents(__DIR__ . '/cache/Container.php', $code);

// 4. Использовать
require __DIR__ . '/cache/Container.php';
$compiled = new \App\DI\MyContainer();
```

### Способ 2: Автоматическая компиляция

```php
use CloudCastle\DI\ContainerFactory;

// Автоматически загрузит compiled, если существует
$container = ContainerFactory::create(
    cacheDir: __DIR__ . '/cache'
);
```

### Способ 3: Через Composer (рекомендуется)

```bash
# Автоматически компилируется после composer install/update
composer install

# Или вручную
composer compile
```

**Конфигурация:** `cache/services.php`

```php
<?php
return [
    'config' => fn() => new Config(),
    'logger' => fn() => new Logger(),
    'database' => fn($c) => new Database($c->get('config')),
];
```

---

## 📦 Структура compiled кода

```php
<?php

namespace CloudCastle\DI\Compiled;

final class CompiledContainer extends \CloudCastle\DI\CompiledContainer
{
    private array $tags = [...];
    private array $tagAttributes = [...];
    
    public function has(string $serviceId): bool
    {
        return match ($serviceId) {
            'config' => true,
            'logger' => true,
            default => false,
        };
    }
    
    public function get(string $serviceId): mixed
    {
        return match ($serviceId) {
            'config' => $this->instances['config'] ??= $this->service0(),
            'logger' => $this->instances['logger'] ??= $this->service1(),
            default => throw new NotFoundException(...),
        };
    }
    
    public function findTaggedServiceIds(string $tag): array
    {
        return $this->tags[$tag] ?? [];
    }
    
    // Private factory methods...
}
```

---

## 📊 Performance

### Benchmarks

| Метрика | Regular | Compiled | Улучшение |
|---------|---------|----------|-----------|
| Get операция | 506,073 оп/с | 512,628 оп/с | **+1.3%** |
| Загрузка | 15 мс | 8 мс | **-47%** |
| Память/сервис | 0.46 КБ | 0.38 КБ | **-17%** |
| Tag lookup | 2.0 μs | 0.1 μs | **-95%** |

### Масштабируемость

| Сервисов | Время компиляции | Размер кода |
|----------|------------------|-------------|
| 100 | 0.58 мс | 36.65 КБ |
| 1,000 | 4.46 мс | 353.94 КБ |
| 10,000 | 46.29 мс | 3,597.11 КБ |
| 100,000 | ~460 мс | ~36 МБ |

---

## 🎯 Best Practices

### ✅ DO

```php
// Компилировать в production
if (getenv('APP_ENV') === 'production') {
    $container = ContainerFactory::create(__DIR__ . '/cache');
} else {
    $container = new Container();
    // Development configuration
}

// Автоматическая компиляция через composer
// См. composer.json: "post-install-cmd": ["@compile"]
```

### ❌ DON'T

```php
// Не компилировать динамические конфигурации
$container->set('time', fn() => time()); // ❌ Меняется каждый раз

// Не компилировать в development
// Используйте regular контейнер для быстрой разработки
```

---

## 💡 Примеры

См. [compiled-container-example.php](../../examples/compiled-container-example.php)

---

Следующее: [API Reference](05_API.md)

