# CloudCastle DI Container - Documentation

**Version:** 2.0.0  
**PHP:** 8.1+

---

## 📚 Sections

1. **[Quick Start](01_QUICK_START.md)** — Installation and basics
2. **[Basic Usage](02_BASIC_USAGE.md)** — Registration, service retrieval
3. **[Advanced Features](03_ADVANCED_FEATURES.md)** — Autowiring, Lazy, Decorators, etc.
4. **[Compiled Container](04_COMPILED.md)** — Compilation for production
5. **[API Reference](05_API.md)** — Complete API reference

---

## ⚡ Quick Start

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

## 🚀 Features

✅ PSR-11 compliant  
✅ Autowiring with PHP 8+ attributes  
✅ Lazy loading with WeakMap  
✅ Decorators with priorities  
✅ Compiled container  
✅ Tagged services  
✅ Scoped containers  
✅ Service locator  
✅ Container delegation

---

**Other languages:** [Русский](../ru/README.md) | [Deutsch](../de/README.md) | [Français](../fr/README.md)
