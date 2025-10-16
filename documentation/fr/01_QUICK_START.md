# Démarrage rapide

**CloudCastle DI Container v2.0**

---

## 📦 Installation

```bash
composer require cloud-castle/di-container
```

---

## ⚡ Premiers pas

### Créer un conteneur

```php
use CloudCastle\DI\Container;

$container = new Container();
```

### Enregistrer des services

```php
$container->set('database', fn() => new Database());
```

### Récupérer des services

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

Suivant: [Utilisation de base](02_BASIC_USAGE.md)
