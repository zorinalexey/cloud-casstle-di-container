# Schnellstart

**CloudCastle DI Container v2.0**

---

## 📦 Installation

```bash
composer require cloud-castle/di-container
```

---

## ⚡ Erste Schritte

### Container erstellen

```php
use CloudCastle\DI\Container;

$container = new Container();
```

### Services registrieren

```php
$container->set('database', fn() => new Database());
```

### Services abrufen

```php
$db = $container->get('database');
```

---

## 🤖 Autowiring

```php
$container->enableAutowiring();

class UserService {
    public function __construct(
        private Database $db,
        private Logger $logger
    ) {}
}

$service = $container->get(UserService::class);
```

---

Weiter: [Grundlegende Verwendung](02_BASIC_USAGE.md)
