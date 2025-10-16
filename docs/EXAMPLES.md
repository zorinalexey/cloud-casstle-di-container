# Usage Examples

## Basic Usage

### Creating a Container

```php
use CloudCastle\DI\Container;

$container = new Container();
```

### Registering Services

#### As an Object

```php
$container->set('config', new Config(['debug' => true]));
```

#### As a Factory

```php
$container->set('database', function(Container $c) {
    $config = $c->get('config');
    return new Database(
        $config['db_host'],
        $config['db_name']
    );
});
```

### Retrieving Services

```php
$db = $container->get('database');
```

### Checking if Service Exists

```php
if ($container->has('cache')) {
    $cache = $container->get('cache');
}
```

## Advanced Examples

### Service Dependencies

```php
// Register configuration
$container->set('config', new Config([
    'db_host' => 'localhost',
    'db_name' => 'myapp'
]));

// Register database (depends on config)
$container->set('database', function(Container $c) {
    $config = $c->get('config');
    return new Database(
        $config['db_host'],
        $config['db_name']
    );
});

// Register repository (depends on database)
$container->set('user_repository', function(Container $c) {
    return new UserRepository($c->get('database'));
});

// Use the service
$users = $container->get('user_repository')->findAll();
```

### Service Removal

```php
// Remove a service
$container->remove('old_service');

// Re-register with new implementation
$container->set('old_service', new NewImplementation());
```

### Listing Services

```php
$serviceIds = $container->getServiceIds();
foreach ($serviceIds as $id) {
    echo "Registered: $id\n";
}
```

## Real-World Example

```php
use CloudCastle\DI\Container;

// Bootstrap
$container = new Container();

// Configuration
$container->set('config', function() {
    return new Config(__DIR__ . '/config.php');
});

// Logger
$container->set('logger', function(Container $c) {
    $config = $c->get('config');
    return new FileLogger($config['log_path']);
});

// Database
$container->set('database', function(Container $c) {
    $config = $c->get('config');
    return new PDO(
        $config['db_dsn'],
        $config['db_user'],
        $config['db_pass']
    );
});

// Cache
$container->set('cache', function(Container $c) {
    return new RedisCache($c->get('config')['redis_host']);
});

// Services
$container->set('user_service', function(Container $c) {
    return new UserService(
        $c->get('database'),
        $c->get('cache'),
        $c->get('logger')
    );
});

// Application
$container->set('app', function(Container $c) {
    return new Application($c);
});

// Run
$app = $container->get('app');
$app->run();
```

## Error Handling

```php
use CloudCastle\DI\Exception\NotFoundException;
use CloudCastle\DI\Exception\ContainerException;

try {
    $service = $container->get('undefined_service');
} catch (NotFoundException $e) {
    echo "Service not found: " . $e->getMessage();
} catch (ContainerException $e) {
    echo "Container error: " . $e->getMessage();
}
```

