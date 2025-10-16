<?php

declare(strict_types = 1);

namespace CloudCastle\DI\Benchmarks;

use CloudCastle\DI\Container;
use stdClass;

/**
 * Benchmark for Container performance.
 */
class ContainerBench
{
    private Container $container;
    
    private int $id = 0;
    
    public function __construct ()
    {
        $this->container = new Container();
    }
    
    /**
     * Benchmark service registration.
     */
    public function benchRegisterService (): void
    {
        $this->container->set('service_' . random_int(1, 1000), function (){
            return new stdClass();
        });
    }
    
    /**
     * Benchmark service retrieval (first time).
     */
    public function benchGetServiceFirstTime (): void
    {
        $id = 'service_' . $this->id++;
        $this->container->set($id, function (){
            return new stdClass();
        });
        $this->container->get($id);
    }
    
    /**
     * Benchmark service retrieval (cached).
     */
    public function benchGetServiceCached (): void
    {
        $this->container->set('cached_service', function (){
            return new stdClass();
        });
        // First call to cache it
        $this->container->get('cached_service');
        // Benchmark the cached retrieval
        $this->container->get('cached_service');
    }
    
    /**
     * Benchmark has() check.
     */
    public function benchHasService (): void
    {
        $this->container->has('some_service');
    }
}

