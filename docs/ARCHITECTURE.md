# Architecture

## Overview

CloudCastle DI is a simple yet powerful Dependency Injection container that implements the PSR-11 Container Interface.

## Core Components

### Container

The main `Container` class manages service definitions and instances:

- **Service Registration**: Services can be registered as objects or factory callables
- **Lazy Loading**: Services are instantiated only when requested
- **Singleton Pattern**: Instances are cached and reused
- **PSR-11 Compliance**: Implements `ContainerInterface`

### Exception Hierarchy

```
Exception
└── ContainerException (implements ContainerExceptionInterface)
    └── NotFoundException (implements NotFoundExceptionInterface)
```

## Design Patterns

### Singleton Registry

Each service is instantiated once and cached for subsequent requests:

```php
$container->set('db', function() {
    return new Database();
});

$db1 = $container->get('db'); // Creates instance
$db2 = $container->get('db'); // Returns cached instance
// $db1 === $db2 is true
```

### Factory Pattern

Services are defined using factory callables:

```php
$container->set('logger', function(Container $c) {
    return new Logger($c->get('config'));
});
```

## Service Lifecycle

1. **Registration**: `set(string $id, callable|object $service)`
2. **Resolution**: `get(string $id): mixed`
   - Check if service exists
   - Check cache
   - Invoke factory if callable
   - Cache instance
   - Return instance

## Future Enhancements

Potential features for future versions:

- Autowiring
- Constructor injection
- Service providers
- Tagged services
- Lazy proxies
- Scoped containers
- Compiled containers for production

