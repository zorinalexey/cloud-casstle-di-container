# CloudCastle DI Container - Dokumentation

**Version:** 2.0.0  
**PHP:** 8.1+

---

## ⚡ Schnellstart

```bash
composer require cloud-castle/di-container
```

```php
use CloudCastle\DI\Container;

$container = new Container();
$container->set('db', fn() => new Database());
$db = $container->get('db');
```

---

**Andere Sprachen:** [Русский](../ru/README.md) | [English](../en/README.md) | [Français](../fr/README.md)
