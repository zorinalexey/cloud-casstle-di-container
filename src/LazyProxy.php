<?php

declare(strict_types=1);

namespace CloudCastle\DI;

use CloudCastle\DI\Exception\ContainerException;

/**
 * Lazy loading proxy.
 *
 * Delays service instantiation until first method call.
 */
final class LazyProxy
{
    private ?object $instance = null;

    private bool $initialized = false;

    /**
     * @param callable(): object $factory Factory to create the real instance
     */
    public function __construct(
        private $factory
    ) {
    }

    /**
     * Get the real instance, creating it if needed.
     *
     * @return object The real service instance
     */
    public function getInstance(): object
    {
        if (!$this->initialized) {
            $this->instance = ($this->factory)();
            $this->initialized = true;
        }

        assert($this->instance !== null);
        return $this->instance;
    }

    /**
     * Check if instance is initialized.
     */
    public function isInitialized(): bool
    {
        return $this->initialized;
    }

    /**
     * Magic method to proxy method calls to the real instance.
     *
     * @param string $method Method name
     * @param array<int, mixed> $arguments Method arguments
     * @return mixed Method result
     * @phpstan-return mixed
     */
    public function __call(string $method, array $arguments): mixed
    {
        $instance = $this->getInstance();
        return $instance->$method(...$arguments); // @phpstan-ignore-line
    }

    /**
     * Magic method to proxy property access to the real instance.
     *
     * @param string $name Property name
     * @return mixed Property value
     * @phpstan-return mixed
     */
    public function __get(string $name): mixed
    {
        $instance = $this->getInstance();
        return $instance->$name; // @phpstan-ignore-line
    }

    /**
     * Magic method to proxy property setting to the real instance.
     *
     * @param string $name Property name
     * @param mixed $value Property value
     */
    public function __set(string $name, mixed $value): void
    {
        $instance = $this->getInstance();
        $instance->$name = $value; // @phpstan-ignore-line
    }

    /**
     * Magic method to proxy property existence check.
     *
     * @param string $name Property name
     */
    public function __isset(string $name): bool
    {
        $instance = $this->getInstance();
        return isset($instance->$name); // @phpstan-ignore-line
    }

    /**
     * Magic method to proxy property unsetting.
     *
     * @param string $name Property name
     */
    public function __unset(string $name): void
    {
        $instance = $this->getInstance();
        unset($instance->$name); // @phpstan-ignore-line
    }
}
