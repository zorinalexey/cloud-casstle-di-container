<?php

declare(strict_types=1);

namespace CloudCastle\DI\Tests\Unit;

use CloudCastle\DI\Container;
use CloudCastle\DI\Exception\ContainerException;
use CloudCastle\DI\Exception\NotFoundException;
use CloudCastle\DI\LazyProxy;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \CloudCastle\DI\Container
 */
class ContainerTest extends TestCase
{
    private Container $container;

    protected function setUp(): void
    {
        parent::setUp();
        $this->container = new Container();
    }

    public function testCanSetAndGetService(): void
    {
        $service = new stdClass();
        $service->name = 'test';

        $this->container->set('test', $service);

        $this->assertTrue($this->container->has('test'));
        $this->assertSame($service, $this->container->get('test'));
    }

    public function testCanSetAndGetServiceWithFactory(): void
    {
        $this->container->set('test', function (): \stdClass {
            $obj = new stdClass();
            $obj->name = 'factory';

            return $obj;
        });

        $service = $this->container->get('test');

        $this->assertInstanceOf(stdClass::class, $service);
        $this->assertSame('factory', $service->name);
    }

    public function testServiceIsSingleton(): void
    {
        $this->container->set('test', fn(): \stdClass => new stdClass());

        $instance1 = $this->container->get('test');
        $instance2 = $this->container->get('test');

        $this->assertSame($instance1, $instance2);
    }

    public function testHasReturnsFalseForUnregisteredService(): void
    {
        $this->assertFalse($this->container->has('nonexistent'));
    }

    public function testGetThrowsExceptionForUnregisteredService(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage("Service 'nonexistent' not found in container");

        $this->container->get('nonexistent');
    }

    public function testCanRemoveService(): void
    {
        $this->container->set('test', new stdClass());

        $this->assertTrue($this->container->has('test'));

        $this->container->remove('test');

        $this->assertFalse($this->container->has('test'));
    }

    public function testGetServiceIds(): void
    {
        $this->container->set('service1', new stdClass());
        $this->container->set('service2', new stdClass());

        $ids = $this->container->getServiceIds();

        $this->assertCount(2, $ids);
        $this->assertContains('service1', $ids);
        $this->assertContains('service2', $ids);
    }

    public function testFactoryReceivesContainer(): void
    {
        $this->container->set('test', function (Container $c): \stdClass {
            $this->assertInstanceOf(Container::class, $c);

            return new stdClass();
        });

        $this->container->get('test');
    }

    public function testAutowiringIsDisabledByDefault(): void
    {
        $this->assertFalse($this->container->isAutowiringEnabled());
    }

    public function testCanEnableAutowiring(): void
    {
        $this->container->enableAutowiring();
        $this->assertTrue($this->container->isAutowiringEnabled());

        $this->container->enableAutowiring(false);
        $this->assertFalse($this->container->isAutowiringEnabled());
    }

    public function testAutowiringSimpleClass(): void
    {
        $this->container->enableAutowiring();

        $service = $this->container->get(SimpleService::class);

        $this->assertInstanceOf(SimpleService::class, $service);
    }

    public function testAutowiringWithDependencies(): void
    {
        $this->container->enableAutowiring();

        $service = $this->container->get(ServiceWithDependency::class);

        $this->assertInstanceOf(ServiceWithDependency::class, $service);
        $this->assertInstanceOf(SimpleService::class, $service->dependency);
    }

    public function testAutowiringCachesInstances(): void
    {
        $this->container->enableAutowiring();

        $instance1 = $this->container->get(SimpleService::class);
        $instance2 = $this->container->get(SimpleService::class);

        $this->assertSame($instance1, $instance2);
    }

    public function testAutowiringWithRegisteredDependencies(): void
    {
        $this->container->enableAutowiring();

        $customDep = new SimpleService();
        $customDep->value = 'custom';
        $this->container->set(SimpleService::class, $customDep);

        $service = $this->container->get(ServiceWithDependency::class);

        $this->assertInstanceOf(ServiceWithDependency::class, $service);
        $this->assertSame($customDep, $service->dependency);
        $this->assertSame('custom', $service->dependency->value);
    }

    public function testAutowiringDetectsCircularDependencies(): void
    {
        $this->container->enableAutowiring();

        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage("Circular dependency detected");

        $this->container->get(CircularA::class);
    }

    public function testAutowiringThrowsForNonInstantiableClass(): void
    {
        $this->container->enableAutowiring();

        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage("is not instantiable");

        $this->container->get(AbstractService::class);
    }

    public function testAutowiringWithDefaultValues(): void
    {
        $this->container->enableAutowiring();

        $service = $this->container->get(ServiceWithDefaults::class);

        $this->assertInstanceOf(ServiceWithDefaults::class, $service);
        $this->assertSame('default', $service->name);
        $this->assertSame(42, $service->value);
    }

    public function testAutowiringWithNullableParameters(): void
    {
        // Fresh container to avoid cached SimpleService
        $container = new Container();
        $container->enableAutowiring();

        $service = $container->get(ServiceWithNullable::class);

        $this->assertInstanceOf(ServiceWithNullable::class, $service);
        // When SimpleService is not registered and not in cache, it will be autowired
        // So it won't be null, it will be autowired instance
        $this->assertInstanceOf(SimpleService::class, $service->optional);
    }

    public function testAutowiringThrowsWhenDisabled(): void
    {
        $this->expectException(NotFoundException::class);

        $this->container->get(SimpleService::class);
    }

    public function testLazyLoadingReturnsProxy(): void
    {
        $proxy = $this->container->setLazy('lazy_service', fn(): \CloudCastle\DI\Tests\Unit\SimpleService => new SimpleService());

        $this->assertInstanceOf(LazyProxy::class, $proxy);
        $this->assertFalse($proxy->isInitialized());
    }

    public function testLazyServiceIsNotInitializedUntilAccessed(): void
    {
        $initialized = false;

        $proxy = $this->container->setLazy('lazy', function () use (&$initialized): \CloudCastle\DI\Tests\Unit\SimpleService {
            $initialized = true;
            return new SimpleService();
        });

        $this->assertFalse($initialized);
        $this->assertFalse($proxy->isInitialized());

        // Get the service (returns proxy)
        $service = $this->container->get('lazy');

        // Still not initialized until we actually use it
        $this->assertFalse($initialized);

        // Now access it - this triggers initialization
        $service->value;

        $this->assertTrue($initialized);
        $this->assertTrue($proxy->isInitialized());
    }

    public function testLazyServiceProxyMethodCalls(): void
    {
        $this->container->setLazy('lazy', fn(): \CloudCastle\DI\Tests\Unit\ServiceWithMethod => new ServiceWithMethod());

        $service = $this->container->get('lazy');

        $this->assertInstanceOf(LazyProxy::class, $service);
        $this->assertSame('result', $service->doSomething());
    }

    public function testLazyServiceProxyPropertyAccess(): void
    {
        $this->container->setLazy('lazy', fn(): \CloudCastle\DI\Tests\Unit\SimpleService => new SimpleService());

        $service = $this->container->get('lazy');

        $this->assertInstanceOf(LazyProxy::class, $service);
        $this->assertSame('test', $service->value);

        $service->value = 'modified';
        $this->assertSame('modified', $service->value);
    }

    public function testLazyServiceCachedAfterInitialization(): void
    {
        $callCount = 0;

        $this->container->setLazy('lazy', function () use (&$callCount): \CloudCastle\DI\Tests\Unit\SimpleService {
            $callCount++;
            return new SimpleService();
        });

        $service1 = $this->container->get('lazy');
        $service2 = $this->container->get('lazy');

        // Proxies are the same
        $this->assertSame($service1, $service2);

        // Access to trigger initialization
        $service1->value;

        $this->assertSame(1, $callCount, 'Factory should be called only once');
    }

    public function testDecorateService(): void
    {
        $this->container->set('service', fn(): \CloudCastle\DI\Tests\Unit\SimpleService => new SimpleService());

        $this->container->decorate('service', function ($service, $container): object {
            $service->value = 'decorated';
            return $service;
        });

        $service = $this->container->get('service');

        $this->assertSame('decorated', $service->value);
    }

    public function testMultipleDecorators(): void
    {
        $this->container->set('service', fn(): \CloudCastle\DI\Tests\Unit\SimpleService => new SimpleService());

        $this->container->decorate('service', function ($service): object {
            $service->value .= '_dec1';
            return $service;
        });

        $this->container->decorate('service', function ($service): object {
            $service->value .= '_dec2';
            return $service;
        });

        $service = $this->container->get('service');

        $this->assertSame('test_dec1_dec2', $service->value);
    }

    public function testDecorateThrowsForNonExistentService(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage("Cannot decorate non-existent service");

        $this->container->decorate('nonexistent', fn($s): object => $s);
    }

    public function testCompileGeneratesValidCode(): void
    {
        $this->container->set('test1', fn(): \CloudCastle\DI\Tests\Unit\SimpleService => new SimpleService());
        $this->container->set('test2', fn(): \CloudCastle\DI\Tests\Unit\SimpleService => new SimpleService());

        $code = $this->container->compile('TestContainer', 'Test\\Namespace');

        $this->assertStringContainsString('namespace Test\\Namespace;', $code);
        $this->assertStringContainsString('class TestContainer extends CompiledContainer', $code);
        $this->assertStringContainsString('public function has(', $code);
        $this->assertStringContainsString('public function get(', $code);
        $this->assertStringContainsString('public function getServiceIds(', $code);
    }

    public function testCompileToFile(): void
    {
        $this->container->set('service1', fn(): \CloudCastle\DI\Tests\Unit\SimpleService => new SimpleService());

        $tempFile = sys_get_temp_dir() . '/compiled_container_test.php';

        $result = $this->container->compileToFile($tempFile);

        $this->assertTrue($result);
        $this->assertFileExists($tempFile);

        $content = file_get_contents($tempFile);
        $this->assertStringContainsString('<?php', $content);

        unlink($tempFile);
    }

    public function testTagService(): void
    {
        $this->container->set('service1', fn(): \CloudCastle\DI\Tests\Unit\SimpleService => new SimpleService());
        $this->container->tag('service1', 'my_tag');

        $this->assertTrue($this->container->hasTag('service1', 'my_tag'));
        $this->assertSame(['service1'], $this->container->findTaggedServiceIds('my_tag'));
    }

    public function testTagMultipleServices(): void
    {
        $this->container->set('service1', fn(): \CloudCastle\DI\Tests\Unit\SimpleService => new SimpleService());
        $this->container->set('service2', fn(): \CloudCastle\DI\Tests\Unit\SimpleService => new SimpleService());

        $this->container->tag('service1', 'handler');
        $this->container->tag('service2', 'handler');

        $tagged = $this->container->findTaggedServiceIds('handler');

        $this->assertCount(2, $tagged);
        $this->assertContains('service1', $tagged);
        $this->assertContains('service2', $tagged);
    }

    public function testTagWithMultipleTags(): void
    {
        $this->container->set('service', fn(): \CloudCastle\DI\Tests\Unit\SimpleService => new SimpleService());
        $this->container->tag('service', ['tag1', 'tag2', 'tag3']);

        $this->assertTrue($this->container->hasTag('service', 'tag1'));
        $this->assertTrue($this->container->hasTag('service', 'tag2'));
        $this->assertTrue($this->container->hasTag('service', 'tag3'));
        $this->assertSame(['tag1', 'tag2', 'tag3'], $this->container->getServiceTags('service'));
    }

    public function testTagWithAttributes(): void
    {
        $this->container->set('service', fn(): \CloudCastle\DI\Tests\Unit\SimpleService => new SimpleService());
        $this->container->tag('service', 'handler', ['priority' => 10, 'type' => 'async']);

        $attributes = $this->container->getTagAttributes('service', 'handler');

        $this->assertSame(10, $attributes['priority']);
        $this->assertSame('async', $attributes['type']);
    }

    public function testFindByTag(): void
    {
        $this->container->set('service1', fn(): \CloudCastle\DI\Tests\Unit\SimpleService => new SimpleService());
        $this->container->set('service2', fn(): \CloudCastle\DI\Tests\Unit\SimpleService => new SimpleService());

        $this->container->tag('service1', 'processor');
        $this->container->tag('service2', 'processor');

        $services = $this->container->findByTag('processor');

        $this->assertCount(2, $services);
        $this->assertContainsOnlyInstancesOf(SimpleService::class, $services);
    }

    public function testGetAllTags(): void
    {
        $this->container->set('service1', fn(): \CloudCastle\DI\Tests\Unit\SimpleService => new SimpleService());
        $this->container->set('service2', fn(): \CloudCastle\DI\Tests\Unit\SimpleService => new SimpleService());

        $this->container->tag('service1', 'tag1');
        $this->container->tag('service2', 'tag2');

        $tags = $this->container->getAllTags();

        $this->assertCount(2, $tags);
        $this->assertContains('tag1', $tags);
        $this->assertContains('tag2', $tags);
    }

    public function testUntagService(): void
    {
        $this->container->set('service', fn(): \CloudCastle\DI\Tests\Unit\SimpleService => new SimpleService());
        $this->container->tag('service', 'my_tag');

        $this->assertTrue($this->container->hasTag('service', 'my_tag'));

        $this->container->untag('service', 'my_tag');

        $this->assertFalse($this->container->hasTag('service', 'my_tag'));
        $this->assertEmpty($this->container->findTaggedServiceIds('my_tag'));
    }

    public function testTagThrowsForNonExistentService(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage("Cannot tag non-existent service");

        $this->container->tag('nonexistent', 'tag');
    }

    public function testFindByTagReturnsEmptyForUnknownTag(): void
    {
        $services = $this->container->findByTag('unknown_tag');

        $this->assertIsArray($services);
        $this->assertEmpty($services);
    }
}

// Test classes for autowiring

class SimpleService
{
    public string $value = 'test';
}

class ServiceWithMethod
{
    public function doSomething(): string
    {
        return 'result';
    }
}

class ServiceWithDependency
{
    public function __construct(
        public SimpleService $dependency
    ) {
    }
}

class ServiceWithDefaults
{
    public function __construct(
        public string $name = 'default',
        public int $value = 42
    ) {
    }
}

class ServiceWithNullable
{
    public function __construct(
        public ?SimpleService $optional = null
    ) {
    }
}

abstract class AbstractService
{
}

class CircularA
{
    public function __construct(public CircularB $b)
    {
    }
}

class CircularB
{
    public function __construct(public CircularA $a)
    {
    }
}
