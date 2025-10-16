# API Reference

**CloudCastle DI Container v2.0**

---

## Container

### Core Methods

#### `set(string $id, callable|object $service): void`
Registers a service.

#### `get(string $id): mixed`
Retrieves a service. Throws `NotFoundException` if not found.

#### `has(string $id): bool`
Checks if service exists.

#### `remove(string $id): void`
Removes a service.

#### `getServiceIds(): array`
Returns all service IDs.

---

### Autowiring

#### `enableAutowiring(): void`
Enables autowiring.

#### `disableAutowiring(): void`
Disables autowiring.

---

### Lazy Loading

#### `setLazy(string $id, callable $factory): void`
Registers lazy service.

---

### Decorators

#### `decorate(string $id, callable $decorator, int $priority = 0): void`
Decorates a service with priority.

---

### Tagged Services

#### `tag(string $id, string $tag, array $attributes = []): void`
Adds tag to service.

#### `findTaggedServiceIds(string $tag): array`
Finds all services with tag.

---

### Compilation

#### `compile(string $className, string $namespace): string`
Compiles container to PHP code.

#### `compileToFile(string $path, string $className, string $namespace): bool`
Compiles and saves to file.

---

### Attributes

#### `registerFromAttribute(string $className): void`
Registers class with #[Service] attribute.

#### `registerFromDirectory(string $dir, string $namespace): void`
Scans directory and registers all #[Service] classes.

---

Back to [contents](README.md)

