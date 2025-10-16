# Basic Usage

**CloudCastle DI Container v2.0**

---

## Service Registration

### Factory (recommended)

```php
$container->set('database', function($container) {
    return new Database('localhost', 'myapp');
});
```

### Ready Object

```php
$logger = new FileLogger('/var/log/app.log');
$container->set('logger', $logger);
```

### With Dependencies

```php
$container->set('repository', function($c) {
    return new UserRepository(
        $c->get('database'),
        $c->get('logger')
    );
});
```

---

## Getting Services

### Basic Retrieval

```php
$db = $container->get('database');
```

### Singleton Pattern

```php
$db1 = $container->get('database');
$db2 = $container->get('database');

// $db1 === $db2 (same instance)
```

---

## Check Existence

```php
if ($container->has('database')) {
    $db = $container->get('database');
}
```

---

## Remove Services

```php
$container->remove('database');
```

---

Next: [Advanced Features](03_ADVANCED_FEATURES.md)

