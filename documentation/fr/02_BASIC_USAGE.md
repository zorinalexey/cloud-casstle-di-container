# Utilisation de base

---

## Enregistrement de services

```php
$container->set('logger', fn() => new Logger());
```

## Récupération de services

```php
$logger = $container->get('logger');
```

## Vérification d'existence

```php
if ($container->has('logger')) {
    // ...
}
```

---

Suivant: [Fonctionnalités avancées](03_ADVANCED_FEATURES.md)
