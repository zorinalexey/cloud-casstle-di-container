<?php

declare(strict_types=1);

namespace CloudCastle\DI;

use CloudCastle\DI\Exception\ContainerException;
use CloudCastle\DI\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

/**
 * Dependency Injection Container.
 *
 * Manages service definitions and their instantiation.
 */
final class Container implements ContainerInterface
{
    /**
     * @var array<string, callable(self): object|object>
     */
    private array $services = [];

    /**
     * @var array<string, object>
     */
    private array $instances = [];

    /**
     * @var bool Enable autowiring
     */
    private bool $autowiring = false;

    /**
     * @var array<string, true> Track resolving services to prevent circular dependencies
     */
    private array $resolving = [];

    /**
     * @var array<string, array<int, callable(object, self): object>> Service decorators
     */
    private array $decorators = [];

    /**
     * @var array<string, array<int, string>> Service tags (tag => [serviceId1, serviceId2, ...])
     */
    private array $tags = [];

    /**
     * @var array<string, array<string, mixed>> Service tag attributes (serviceId => [tag => attributes])
     */
    private array $tagAttributes = [];

    /**
     * Register a service in the container.
     *
     * @param string $serviceId Service identifier
     * @param callable(self): object|object $service Service factory or instance
     */
    public function set(string $serviceId, callable|object $service): void
    {
        $this->services[$serviceId] = $service;

        // Clear cached instance if exists
        unset($this->instances[$serviceId]);
    }

    /**
     * Register a lazy-loaded service in the container.
     *
     * The service will not be instantiated until first access.
     *
     * @param string $serviceId Service identifier
     * @param callable(self): object $factory Service factory
     * @return LazyProxy Lazy proxy object
     */
    public function setLazy(string $serviceId, callable $factory): LazyProxy
    {
        $proxy = new LazyProxy(fn(): object => $factory($this));
        $this->set($serviceId, $proxy);

        return $proxy;
    }

    /**
     * Decorate a service with additional functionality.
     *
     * Decorators are applied in the order they are registered.
     *
     * @param string $serviceId Service identifier to decorate
     * @param callable(object, self): object $decorator Decorator function
     * @throws NotFoundException If service doesn't exist
     */
    public function decorate(string $serviceId, callable $decorator): void
    {
        if (!$this->has($serviceId)) {
            throw new NotFoundException(sprintf("Cannot decorate non-existent service '%s'", $serviceId));
        }

        if (!isset($this->decorators[$serviceId])) {
            $this->decorators[$serviceId] = [];
        }

        $this->decorators[$serviceId][] = $decorator;

        // Clear cached instance to force re-creation with decorators
        unset($this->instances[$serviceId]);
    }

    /**
     * Apply all registered decorators to an instance.
     *
     * @param string $serviceId Service identifier
     * @param object $instance Original instance
     * @return object Decorated instance
     */
    private function applyDecorators(string $serviceId, object $instance): object
    {
        if (!isset($this->decorators[$serviceId])) {
            return $instance;
        }

        foreach ($this->decorators[$serviceId] as $decorator) {
            $instance = $decorator($instance, $this);

            if (!is_object($instance)) {
                throw new ContainerException(sprintf("Decorator for '%s' must return an object", $serviceId));
            }
        }

        return $instance;
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $serviceId): mixed
    {
        // Return cached instance if exists
        if (isset($this->instances[$serviceId])) {
            return $this->instances[$serviceId];
        }

        // Check if service is registered
        if (!$this->has($serviceId)) {
            // Try autowiring if enabled
            if ($this->autowiring && class_exists($serviceId)) {
                return $this->autowire($serviceId);
            }

            throw new NotFoundException(sprintf("Service '%s' not found in container", $serviceId));
        }

        $service = $this->services[$serviceId];

        try {
            // If it's a callable, invoke it
            $instance = is_callable($service)
                ? $service($this)
                : $service;

            if (!is_object($instance)) {
                throw new ContainerException(sprintf("Service factory must return an object for '%s'", $serviceId));
            }

            // Apply decorators if any
            $instance = $this->applyDecorators($serviceId, $instance);

            // Cache the instance
            $this->instances[$serviceId] = $instance;

            return $instance;
        } catch (\Throwable $throwable) {
            throw new ContainerException(
                sprintf("Error while retrieving service '%s': %s", $serviceId, $throwable->getMessage()),
                0,
                $throwable
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $serviceId): bool
    {
        return isset($this->services[$serviceId]);
    }

    /**
     * Remove a service from the container.
     *
     * @param string $serviceId Service identifier
     */
    public function remove(string $serviceId): void
    {
        unset($this->services[$serviceId], $this->instances[$serviceId]);
    }

    /**
     * Get all registered service identifiers.
     *
     * @return array<int, string>
     */
    public function getServiceIds(): array
    {
        return array_keys($this->services);
    }

    /**
     * Compile container to optimized PHP code.
     *
     * @param string $className Generated class name
     * @param string $namespace Generated class namespace
     * @return string Generated PHP code
     */
    public function compile(string $className = 'CompiledDIContainer', string $namespace = 'App\\DI'): string
    {
        $serviceIds = $this->getServiceIds();

        // Generate has() method
        $hasMethod = $this->generateHasMethod($serviceIds);

        // Generate get() method
        $getMethod = $this->generateGetMethod($serviceIds);

        // Generate getServiceIds() method
        $getIdsMethod = $this->generateGetServiceIdsMethod($serviceIds);

        return <<<PHP
<?php

declare(strict_types=1);

namespace {$namespace};

use CloudCastle\DI\CompiledContainer;
use CloudCastle\DI\Exception\ContainerException;
use CloudCastle\DI\Exception\NotFoundException;

/**
 * Compiled DI Container for maximum performance.
 * 
 * Generated at: {$this->getCurrentDateTime()}
 * Services: {$this->formatNumber(count($serviceIds))}
 */
final class {$className} extends CompiledContainer
{
{$hasMethod}

{$getMethod}

{$getIdsMethod}
}

PHP;
    }

    /**
     * Compile and save to file.
     *
     * @param string $filePath Output file path
     * @param string $className Generated class name
     * @param string $namespace Generated class namespace
     * @return bool Success status
     */
    public function compileToFile(
        string $filePath,
        string $className = 'CompiledDIContainer',
        string $namespace = 'App\\DI'
    ): bool {
        $code = $this->compile($className, $namespace);
        return file_put_contents($filePath, $code) !== false;
    }

    /**
     * Generate has() method code.
     *
     * @param array<int, string> $serviceIds Service IDs
     * @return string Generated method code
     */
    private function generateHasMethod(array $serviceIds): string
    {
        $cases = [];
        foreach ($serviceIds as $id) {
            $escapedId = addslashes($id);
            $cases[] = "            case '{$escapedId}':\n                return true;";
        }

        $casesCode = implode("\n", $cases);

        return <<<PHP
    public function has(string \$serviceId): bool
    {
        return match (\$serviceId) {
{$casesCode}
            default => false,
        };
    }
PHP;
    }

    /**
     * Generate get() method code.
     *
     * @param array<int, string> $serviceIds Service IDs
     * @return string Generated method code
     */
    private function generateGetMethod(array $serviceIds): string
    {
        $cases = [];
        $serviceCounter = 0;

        foreach ($serviceIds as $id) {
            $escapedId = addslashes($id);
            $varName = 'service' . $serviceCounter++;

            $cases[] = <<<CASE
            case '{$escapedId}':
                return \$this->instances['{$escapedId}'] ??= \$this->{$varName}();
CASE;
        }

        $casesCode = implode("\n", $cases);

        return <<<PHP
    public function get(string \$serviceId): mixed
    {
        return match (\$serviceId) {
{$casesCode}
            default => throw new NotFoundException(sprintf("Service '%s' not found", \$serviceId)),
        };
    }
PHP;
    }

    /**
     * Generate getServiceIds() method.
     *
     * @param array<int, string> $serviceIds Service IDs
     * @return string Generated method code
     */
    private function generateGetServiceIdsMethod(array $serviceIds): string
    {
        $idsCode = var_export($serviceIds, true);

        return <<<PHP
    public function getServiceIds(): array
    {
        return {$idsCode};
    }
PHP;
    }

    /**
     * Get current date/time for compilation timestamp.
     *
     * @return string Formatted date/time
     */
    private function getCurrentDateTime(): string
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * Format number with thousands separator.
     *
     * @param int $number Number to format
     * @return string Formatted number
     */
    private function formatNumber(int $number): string
    {
        return number_format($number, 0, '.', ',');
    }

    /**
     * Tag a service with one or more tags.
     *
     * @param string $serviceId Service identifier
     * @param string|array<int, string> $tags Tag or array of tags
     * @param array<string, mixed> $attributes Optional tag attributes
     * @throws NotFoundException If service doesn't exist
     */
    public function tag(string $serviceId, string|array $tags, array $attributes = []): void
    {
        if (!$this->has($serviceId)) {
            throw new NotFoundException(sprintf("Cannot tag non-existent service '%s'", $serviceId));
        }

        $tags = is_array($tags) ? $tags : [$tags];

        foreach ($tags as $tag) {
            if (!isset($this->tags[$tag])) {
                $this->tags[$tag] = [];
            }

            if (!in_array($serviceId, $this->tags[$tag], true)) {
                $this->tags[$tag][] = $serviceId;
            }

            // Store attributes if provided
            if ($attributes !== []) {
                if (!isset($this->tagAttributes[$serviceId])) {
                    $this->tagAttributes[$serviceId] = [];
                }

                $this->tagAttributes[$serviceId][$tag] = $attributes;
            }
        }
    }

    /**
     * Get all service IDs with a specific tag.
     *
     * @param string $tag Tag name
     * @return array<int, string> Array of service IDs
     */
    public function findTaggedServiceIds(string $tag): array
    {
        return $this->tags[$tag] ?? [];
    }

    /**
     * Get all services with a specific tag.
     *
     * @param string $tag Tag name
     * @return array<int, object> Array of service instances
     */
    public function findByTag(string $tag): array
    {
        $serviceIds = $this->findTaggedServiceIds($tag);
        $services = [];

        foreach ($serviceIds as $serviceId) {
            $services[] = $this->get($serviceId);
        }

        return $services;
    }

    /**
     * Get tag attributes for a service.
     *
     * @param string $serviceId Service identifier
     * @param string $tag Tag name
     * @return array<string, mixed> Tag attributes
     */
    public function getTagAttributes(string $serviceId, string $tag): array
    {
        return $this->tagAttributes[$serviceId][$tag] ?? [];
    }

    /**
     * Check if a service has a specific tag.
     *
     * @param string $serviceId Service identifier
     * @param string $tag Tag name
     */
    public function hasTag(string $serviceId, string $tag): bool
    {
        return isset($this->tags[$tag]) && in_array($serviceId, $this->tags[$tag], true);
    }

    /**
     * Get all tags for a service.
     *
     * @param string $serviceId Service identifier
     * @return array<int, string> Array of tags
     */
    public function getServiceTags(string $serviceId): array
    {
        $serviceTags = [];

        foreach ($this->tags as $tag => $serviceIds) {
            if (in_array($serviceId, $serviceIds, true)) {
                $serviceTags[] = $tag;
            }
        }

        return $serviceTags;
    }

    /**
     * Get all tags.
     *
     * @return array<int, string> Array of all tag names
     */
    public function getAllTags(): array
    {
        return array_keys($this->tags);
    }

    /**
     * Remove a tag from a service.
     *
     * @param string $serviceId Service identifier
     * @param string $tag Tag name
     */
    public function untag(string $serviceId, string $tag): void
    {
        if (isset($this->tags[$tag])) {
            $this->tags[$tag] = array_values(
                array_filter($this->tags[$tag], fn($id): bool => $id !== $serviceId)
            );

            if (empty($this->tags[$tag])) {
                unset($this->tags[$tag]);
            }
        }

        if (isset($this->tagAttributes[$serviceId][$tag])) {
            unset($this->tagAttributes[$serviceId][$tag]);

            if (empty($this->tagAttributes[$serviceId])) {
                unset($this->tagAttributes[$serviceId]);
            }
        }
    }

    /**
     * Enable or disable autowiring.
     *
     * @param bool $enabled Enable autowiring
     */
    public function enableAutowiring(bool $enabled = true): void
    {
        $this->autowiring = $enabled;
    }

    /**
     * Check if autowiring is enabled.
     */
    public function isAutowiringEnabled(): bool
    {
        return $this->autowiring;
    }

    /**
     * Autowire a class by resolving its dependencies.
     *
     * @param string $className Class name to autowire
     * @phpstan-param class-string $className
     * @return object Instantiated object
     * @throws ContainerException If autowiring fails
     */
    private function autowire(string $className): object
    {
        // Check for circular dependencies
        if (isset($this->resolving[$className])) {
            throw new ContainerException(sprintf("Circular dependency detected for '%s'", $className));
        }

        $this->resolving[$className] = true;

        try {
            /** @phpstan-var \ReflectionClass<object> $reflection */
            $reflection = new \ReflectionClass($className);

            if (!$reflection->isInstantiable()) {
                throw new ContainerException(sprintf("Class '%s' is not instantiable", $className));
            }

            $constructor = $reflection->getConstructor();

            // If no constructor, just instantiate
            if ($constructor === null) {
                $instance = new $className();
                $this->instances[$className] = $instance;
                unset($this->resolving[$className]);
                return $instance;
            }

            // Resolve constructor parameters
            $parameters = $constructor->getParameters();
            $dependencies = [];

            foreach ($parameters as $parameter) {
                $type = $parameter->getType();

                // Handle union types and null
                if ($type === null) {
                    if ($parameter->isDefaultValueAvailable()) {
                        $dependencies[] = $parameter->getDefaultValue();
                        continue;
                    }

                    throw new ContainerException(
                        sprintf("Cannot autowire parameter '%s' in '%s': no type hint", $parameter->getName(), $className)
                    );
                }

                // Handle named types
                if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
                    $dependencyClass = $type->getName();

                    try {
                        $dependencies[] = $this->get($dependencyClass);
                    } catch (NotFoundException $e) {
                        if ($parameter->isDefaultValueAvailable()) {
                            $dependencies[] = $parameter->getDefaultValue();
                        } elseif ($type->allowsNull()) {
                            $dependencies[] = null;
                        } else {
                            throw new ContainerException(
                                sprintf("Cannot resolve dependency '%s' for '%s'", $dependencyClass, $className),
                                0,
                                $e
                            );
                        }
                    }

                    continue;
                }

                // Handle built-in types with default values
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                    continue;
                }

                if ($type->allowsNull()) {
                    $dependencies[] = null;
                    continue;
                }

                throw new ContainerException(
                    sprintf("Cannot autowire parameter '%s' in '%s'", $parameter->getName(), $className)
                );
            }

            // Create instance with dependencies
            $instance = $reflection->newInstanceArgs($dependencies);
            $this->instances[$className] = $instance;

            unset($this->resolving[$className]);

            return $instance;
        } catch (\ReflectionException $e) {
            unset($this->resolving[$className]);
            throw new ContainerException(
                sprintf("Reflection error for '%s': %s", $className, $e->getMessage()),
                0,
                $e
            );
        } catch (\Throwable $throwable) {
            unset($this->resolving[$className]);
            throw new ContainerException(
                sprintf("Error while autowiring '%s': %s", $className, $throwable->getMessage()),
                0,
                $throwable
            );
        }
    }
}
