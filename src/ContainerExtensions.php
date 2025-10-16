<?php

declare(strict_types=1);

namespace CloudCastle\DI;

use CloudCastle\DI\Attribute\Inject;
use CloudCastle\DI\Attribute\Service;
use CloudCastle\DI\Attribute\Tag;
use CloudCastle\DI\Exception\ContainerException;
use Psr\Container\ContainerInterface;

/**
 * Container extensions trait with advanced features.
 */
trait ContainerExtensions
{
    /**
     * Register service from class with attributes.
     *
     * @param string $className Class name to register
     * @throws \ReflectionException
     */
    public function registerFromAttribute(string $className): void
    {
        $reflection = new \ReflectionClass($className);
        $attributes = $reflection->getAttributes(Service::class);

        if (empty($attributes)) {
            throw new ContainerException("Class {$className} has no #[Service] attribute");
        }

        /** @var Service $serviceAttr */
        $serviceAttr = $attributes[0]->newInstance();
        $serviceId = $serviceAttr->id ?? $className;

        // Register service
        if ($serviceAttr->lazy) {
            $this->setLazy($serviceId, fn($c) => $c->autowire($className));
        } else {
            $this->set($serviceId, fn($c) => $c->autowire($className));
        }

        // Apply tags
        foreach ($serviceAttr->tags as $tag) {
            $this->tag($serviceId, $tag, ['priority' => $serviceAttr->priority]);
        }

        // Apply class-level tags
        $tagAttributes = $reflection->getAttributes(Tag::class);
        foreach ($tagAttributes as $tagAttr) {
            /** @var Tag $tag */
            $tag = $tagAttr->newInstance();
            $this->tag($serviceId, $tag->name, $tag->attributes);
        }
    }

    /**
     * Scan directory for classes with #[Service] attribute.
     *
     * @param string $directory Directory to scan
     * @param string $namespace Base namespace
     */
    public function registerFromDirectory(string $directory, string $namespace): void
    {
        if (!is_dir($directory)) {
            throw new ContainerException("Directory {$directory} does not exist");
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory)
        );

        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $relativePath = str_replace($directory . '/', '', $file->getPathname());
            $className = $namespace . '\\' . str_replace(['/', '.php'], ['\\', ''], $relativePath);

            if (!class_exists($className)) {
                continue;
            }

            try {
                $reflection = new \ReflectionClass($className);
                if ($reflection->getAttributes(Service::class)) {
                    $this->registerFromAttribute($className);
                }
            } catch (\Throwable) {
                // Skip classes that can't be reflected
                continue;
            }
        }
    }

    /**
     * Add a delegate container.
     *
     * @param ContainerInterface $delegate Delegate container
     */
    public function addDelegate(ContainerInterface $delegate): void
    {
        $this->delegates[] = $delegate;
    }

    /**
     * Get from delegates if not found in main container.
     *
     * @param string $id Service identifier
     * @return mixed
     */
    private function getFromDelegates(string $id): mixed
    {
        foreach ($this->delegates as $delegate) {
            if ($delegate->has($id)) {
                return $delegate->get($id);
            }
        }

        return null;
    }

    /**
     * Set a scoped service.
     *
     * @param string $serviceId Service identifier
     * @param string $scope Scope name (e.g., 'request', 'session')
     * @param callable $factory Service factory
     */
    public function setScoped(string $serviceId, string $scope, callable $factory): void
    {
        $this->set($serviceId, $factory);
        $this->scopedServices[$serviceId] = $scope;
    }

    /**
     * Begin a new scope.
     *
     * @param string $scopeName Scope name
     */
    public function beginScope(string $scopeName): void
    {
        if ($this->currentScope !== null) {
            throw new ContainerException("Scope '{$this->currentScope}' is already active");
        }

        $this->currentScope = $scopeName;

        if (!isset($this->scopedInstances[$scopeName])) {
            $this->scopedInstances[$scopeName] = [];
        }
    }

    /**
     * End current scope.
     */
    public function endScope(): void
    {
        if ($this->currentScope === null) {
            throw new ContainerException("No active scope");
        }

        unset($this->scopedInstances[$this->currentScope]);
        $this->currentScope = null;
    }

    /**
     * Create a service locator for specific services.
     *
     * @param array<int, string> $serviceIds Allowed service IDs
     * @return ServiceLocator
     */
    public function createServiceLocator(array $serviceIds): ServiceLocator
    {
        return new ServiceLocator($this, $serviceIds);
    }

    /**
     * Create a service locator from a tag.
     *
     * @param string $tag Tag name
     * @return ServiceLocator
     */
    public function createServiceLocatorFromTag(string $tag): ServiceLocator
    {
        return ServiceLocator::fromTag($this, $tag);
    }

    /**
     * Initialize service asynchronously (if supported).
     *
     * @param string $serviceId Service identifier
     * @return \Generator
     */
    public function getAsync(string $serviceId): \Generator
    {
        // For now, this is a placeholder for future Fiber/async support
        // Returns a generator that yields the service
        yield $this->get($serviceId);
    }

    /**
     * Batch initialize multiple services asynchronously.
     *
     * @param array<int, string> $serviceIds Service identifiers
     * @return \Generator<string, object>
     */
    public function batchGetAsync(array $serviceIds): \Generator
    {
        foreach ($serviceIds as $serviceId) {
            yield $serviceId => $this->get($serviceId);
        }
    }
}

