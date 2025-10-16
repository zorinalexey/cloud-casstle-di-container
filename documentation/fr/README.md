# CloudCastle DI Container - Documentation

**Version:** 2.0.0  
**PHP:** 8.1+

---

## ⚡ Démarrage rapide

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

**Autres langues:** [Русский](../ru/README.md) | [English](../en/README.md) | [Deutsch](../de/README.md)
