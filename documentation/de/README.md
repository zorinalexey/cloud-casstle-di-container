# CloudCastle DI Container - Dokumentation

**Version:** 2.0.0  
**PHP:** 8.1+

---

## 📚 Abschnitte

1. **[Schnellstart](01_QUICK_START.md)** — Installation und Grundlagen
2. **[Grundlegende Verwendung](02_BASIC_USAGE.md)** — Registrierung, Service-Abruf
3. **[Erweiterte Funktionen](03_ADVANCED_FEATURES.md)** — Autowiring, Lazy, Decorators
4. **[Compiled Container](04_COMPILED.md)** — Kompilierung für Production
5. **[API-Referenz](05_API.md)** — Vollständige API-Referenz

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
