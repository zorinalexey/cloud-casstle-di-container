# Erweiterte Funktionen

---

## 🤖 Autowiring

```php
$container->enableAutowiring();
$service = $container->get(MyClass::class);
```

## 🔄 Lazy Loading

```php
$container->setLazy('heavy', fn() => new HeavyService());
```

## 🎨 Decorators

```php
$container->decorate('api', fn($api) => new AuthDecorator($api), 10);
```

## 🏷️ PHP 8+ Attributes

```php
#[Service(id: 'logger', tags: ['logging'])]
class Logger {}
```

---

Weiter: [Compiled Container](04_COMPILED.md)
