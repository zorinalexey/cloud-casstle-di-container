<?php

declare(strict_types=1);

namespace CloudCastle\DI;

use CloudCastle\DI\Exception\ContainerException;
use Psr\Container\ContainerInterface;

/**
 * Container with scope support for lifecycle management.
 *
 * Allows creating services with different lifetimes (request, session, etc.)
 */
class ScopedContainer implements ContainerInterface
{
    /**
     * @var array<string, array<string, object>> Scoped instances (scope => [serviceId => instance])
     */
    private array $scopedInstances = [];

    /**
     * @var string|null Current active scope
     */
    private ?string $currentScope = null;

    /**
     * @var array<string, string> Service scope assignments (serviceId => scopeName)
     */
    private array $serviceScopes = [];

    /**
     * @param Container $container Base container
     */
    public function __construct(
        private readonly Container $container
    ) {
    }

    /**
     * Begin a new scope.
     *
     * @param string $scopeName Scope identifier (e.g., 'request', 'session')
     */
    public function beginScope(string $scopeName): void
    {
        if ($this->currentScope !== null) {
            throw new ContainerException("Cannot begin scope '{$scopeName}': scope '{$this->currentScope}' is already active");
        }

        $this->currentScope = $scopeName;

        if (!isset($this->scopedInstances[$scopeName])) {
            $this->scopedInstances[$scopeName] = [];
        }
    }

    /**
     * End current scope and clear scoped instances.
     */
    public function endScope(): void
    {
        if ($this->currentScope === null) {
            throw new ContainerException("No active scope to end");
        }

        // Clear instances for this scope
        unset($this->scopedInstances[$this->currentScope]);
        $this->currentScope = null;
    }

    /**
     * Register a service with a scope.
     *
     * @param string $serviceId Service identifier
     * @param string $scope Scope name
     */
    public function setScope(string $serviceId, string $scope): void
    {
        $this->serviceScopes[$serviceId] = $scope;
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $id): mixed
    {
        // Check if service has a scope
        $scope = $this->serviceScopes[$id] ?? null;

        if ($scope !== null) {
            // Service is scoped
            if ($scope !== $this->currentScope) {
                throw new ContainerException(
                    sprintf("Service '%s' requires scope '%s', but current scope is '%s'", $id, $scope, $this->currentScope ?? 'none')
                );
            }

            // Return scoped instance if exists
            if (isset($this->scopedInstances[$scope][$id])) {
                return $this->scopedInstances[$scope][$id];
            }

            // Create and cache in scope
            $instance = $this->container->get($id);
            $this->scopedInstances[$scope][$id] = $instance;

            return $instance;
        }

        // Regular service from base container
        return $this->container->get($id);
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $id): bool
    {
        return $this->container->has($id);
    }

    /**
     * Get current scope name.
     *
     * @return string|null
     */
    public function getCurrentScope(): ?string
    {
        return $this->currentScope;
    }
}

