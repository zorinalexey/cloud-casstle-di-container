# API-Referenz

---

## Container

### Hauptmethoden

- `set(string $id, callable|object $service): void`
- `get(string $id): mixed`
- `has(string $id): bool`
- `remove(string $id): void`
- `getServiceIds(): array`

### Autowiring

- `enableAutowiring(): void`
- `disableAutowiring(): void`

### Lazy Loading

- `setLazy(string $id, callable $factory): void`

### Decorators

- `decorate(string $id, callable $decorator, int $priority = 0): void`

### Tags

- `tag(string $id, string $tag, array $attributes = []): void`
- `findTaggedServiceIds(string $tag): array`

---

Zurück zu [Inhalt](README.md)
