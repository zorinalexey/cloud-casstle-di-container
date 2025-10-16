# CloudCastle DI Container - Документация

**Версия:** 2.0.0  
**PHP:** 8.1+

---

## 📚 Разделы

1. **[Быстрый старт](01_QUICK_START.md)** - Установка и основы
2. **[Базовое использование](02_BASIC_USAGE.md)** - Регистрация, получение сервисов
3. **[Продвинутые возможности](03_ADVANCED_FEATURES.md)** - Autowiring, Lazy, Decorators, etc.
4. **[Compiled Container](04_COMPILED.md)** - Компиляция для production
5. **[API Reference](05_API.md)** - Полный справочник API

---

## ⚡ Быстрый старт

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

## 🚀 Возможности

✅ PSR-11 compliant  
✅ Autowiring с PHP 8+ attributes  
✅ Lazy loading с WeakMap  
✅ Decorators с priorities  
✅ Compiled container  
✅ Tagged services  
✅ Scoped containers  
✅ Service locator  
✅ Container delegation

---

**Другие языки:** [English](../en/README.md) | [Deutsch](../de/README.md) | [Français](../fr/README.md)
