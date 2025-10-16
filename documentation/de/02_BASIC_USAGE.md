# Grundlegende Verwendung

---

## Service-Registrierung

```php
$container->set('logger', fn() => new Logger());
```

## Service-Abruf

```php
$logger = $container->get('logger');
```

## Existenzprüfung

```php
if ($container->has('logger')) {
    // ...
}
```

---

Weiter: [Erweiterte Funktionen](03_ADVANCED_FEATURES.md)
