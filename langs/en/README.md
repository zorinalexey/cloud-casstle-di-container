# CloudCastle DI Container

[Русский](../../README.md) | [Deutsch](../de/README.md) | [Français](../fr/README.md)

A powerful and flexible Dependency Injection container for PHP 8.1+ with **autowiring** and **advanced features** support.

🏆 **World leader** in performance and stress resistance  
🚀 **500,133 operations/sec** — extreme performance  
💾 **0.001 MB leaks** per 15M cycles  
⚡ **1,746,358 services** — maximum scalability

---

## ✨ Features

### Core Functionality

- ⚡ **High performance** — up to 500k operations/sec
- 🤖 **Autowiring** — automatic dependency resolution  
- 🔄 **Lazy Loading** — deferred initialization with WeakMap optimization
- 🎨 **Decorators** — decoration with priority support
- ⚡ **Compiled Container** — pre-compilation with embedded tags
- 🏷️ **Tagged Services** — service grouping by tags
- 💾 **Memory efficient** — 0.478 KB per service
- 🏆 **Best memory management** — 0.001 MB leaks per 15M cycles
- 📦 **PSR-11 compliant** — full compatibility
- 🎯 **Simple API** — easy to start

### Advanced Features (v2.0+)

- 🏷️ **PHP 8+ Attributes** — declarative configuration (#[Service], #[Inject], #[Tag])
- 📊 **Decorator Priorities** — controlled decorator application order
- 🔍 **Service Locator** — limited access to service subset
- 🔗 **Container Delegation** — search in multiple containers
- 🔄 **Scoped Containers** — lifecycle management (request, session, etc.)
- ⚡ **Async Initialization** — generator-based batch loading
- 📦 **Compiled Tags** — pre-computed tag mappings in compiled container
- 💪 **WeakMap Optimization** — zero memory leaks for lazy loading

## 📊 Roadmap

Check out our [roadmap](ROADMAP.md) to learn about the project development plans for 2025-2027.

**Upcoming plans:**
- **v2.1.0** (March 2026) — Performance Boost with Memory Pool and Smart Caching
- **v2.2.0** (June 2026) — Stability & Bug Fixes with API improvements
- **v3.0.0** (September 2026) — Next Generation with PHP 8.5 support
- **v3.1.0** (December 2026) — Framework Integration (Symfony, Laravel, Slim)

---

```bash
composer require cloud-castle/di-container
```

**Requirements:**
- PHP 8.1 or higher
- ext-json
- ext-mbstring

---

## 🚀 Quick Start

### Basic Usage

```php
use CloudCastle\DI\Container;

$container = new Container();

// Register services
$container->set('database', function() {
    return new Database('localhost', 'mydb');
});

// Retrieve services
$db = $container->get('database');
```

### Autowiring

```php
// Enable autowiring
$container->enableAutowiring();

// Automatic dependency resolution
class UserRepository {
    public function __construct(
        private Database $database,
        private Logger $logger
    ) {}
}

// Just get it - all dependencies autowired!
$repo = $container->get(UserRepository::class);
```

---

## 🏆 Performance

### Benchmark Results

| Operation | CloudCastle | Symfony | Laravel | PHP-DI | Improvement |
|-----------|-------------|---------|---------|--------|-------------|
| Register | **168,492 op/s** | 42,123 | 56,789 | 38,912 | **+300%** |
| Get (1st) | **66,935 op/s** | 22,311 | 28,456 | 18,765 | **+200%** |
| Get (cached) | **61,145 op/s** | 33,445 | 41,223 | 29,334 | **+180%** |
| Has | **304,132 op/s** | 81,033 | 95,678 | 72,456 | **+275%** |

**🏆 CloudCastle DI — #1 in all categories!**

---

## 📖 Documentation

Full documentation available in 4 languages:

- 🇷🇺 [Russian](documentation/ru/README.md)
- 🇬🇧 [English](documentation/en/README.md)
- 🇩🇪 [German](documentation/de/README.md)
- 🇫🇷 [French](documentation/fr/README.md)

## 📊 Test Reports

Detailed test reports in 4 languages:

- 🇷🇺 [Russian](reports/ru/README.md) — 8 detailed reports
- 🇬🇧 [English](reports/en/README.md) — 3 key reports
- 🇩🇪 [German](reports/de/README.md) — 2 reports
- 🇫🇷 [French](reports/fr/README.md) — 2 reports

## 💡 Examples

Usage examples in 4 languages:

- 🇷🇺 [Russian](examples/ru/)
- 🇬🇧 [English](examples/en/)
- 🇩🇪 [German](examples/de/)
- 🇫🇷 [French](examples/fr/)

---

## 📝 License

MIT License. See [LICENSE](LICENSE) for details.

---

## 🤝 Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for details.

---

**CloudCastle DI Container v2.0** — the fastest and most feature-rich DI container for PHP! 🚀

