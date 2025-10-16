# CloudCastle DI Container - Documentation

**Version:** 2.0.0  
**PHP:** 8.1+

---

## 📚 Sections

1. **[Démarrage rapide](01_QUICK_START.md)** — Installation et bases
2. **[Utilisation de base](02_BASIC_USAGE.md)** — Enregistrement, récupération
3. **[Fonctionnalités avancées](03_ADVANCED_FEATURES.md)** — Autowiring, Lazy, Decorators
4. **[Conteneur compilé](04_COMPILED.md)** — Compilation pour production
5. **[Référence API](05_API.md)** — Référence API complète

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
