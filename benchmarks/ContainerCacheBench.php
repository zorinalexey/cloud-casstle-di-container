<?php

declare(strict_types=1);

namespace CloudCastle\DI\Benchmarks;

use CloudCastle\DI\Container;

/**
 * Benchmark for Container cache performance.
 */
class ContainerCacheBench
{
    private Container $container;

    private int $id = 0;

    public function __construct()
    {
        $this->container = new Container();
        
        // Pre-register services for cache tests
        $this->container->set('service1', function () {
            return new \stdClass();
        });
        $this->container->set('service2', function () {
            return new \stdClass();
        });
        $this->container->set('service3', function () {
            return new \stdClass();
        });
    }

    /**
     * Benchmark: First access (with instantiation).
     */
    public function benchFirstAccess(): void
    {
        $this->container->get('service1');
    }

    /**
     * Benchmark: Cached access (from cache).
     */
    public function benchCachedAccess(): void
    {
        // First call to populate cache (not measured in revs)
        static $initialized = false;
        if (!$initialized) {
            $this->container->get('service2');
            $initialized = true;
        }
        
        // Measured: cached access
        $this->container->get('service2');
    }

    /**
     * Benchmark: Multiple services access.
     */
    public function benchMultipleServicesAccess(): void
    {
        $this->container->get('service1');
        $this->container->get('service2');
        $this->container->get('service3');
    }

    /**
     * Benchmark: Checking and getting service.
     */
    public function benchHasAndGet(): void
    {
        if ($this->container->has('service1')) {
            $this->container->get('service1');
        }
    }

    /**
     * Benchmark: Full workflow (register + get).
     */
    public function benchFullWorkflow(): void
    {
        $id = 'temp_service_' . $this->id++;
        $this->container->set($id, function () {
            return new \stdClass();
        });
        $this->container->get($id);
        $this->container->remove($id);
    }
}


