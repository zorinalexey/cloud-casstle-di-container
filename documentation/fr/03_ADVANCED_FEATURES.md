# Fonctionnalités avancées

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

## 🏷️ Attributes PHP 8+

```php
#[Service(id: 'logger', tags: ['logging'])]
class Logger {}
```

---

Suivant: [Conteneur compilé](04_COMPILED.md)
