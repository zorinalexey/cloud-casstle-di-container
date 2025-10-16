<?php

declare(strict_types=1);

/**
 * Advanced Features Examples.
 *
 * Demonstrates all advanced features of CloudCastle DI Container.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use CloudCastle\DI\Attribute\Inject;
use CloudCastle\DI\Attribute\Service;
use CloudCastle\DI\Attribute\Tag;
use CloudCastle\DI\Container;
use CloudCastle\DI\DelegatingContainer;
use CloudCastle\DI\ScopedContainer;
use CloudCastle\DI\ServiceLocator;

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║              Advanced Features Examples                        ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

// ============================================================================
// 1. PHP 8+ Attributes for Autowiring
// ============================================================================

echo "1️⃣ PHP 8+ Attributes for Autowiring\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

#[Service(id: 'my.logger', tags: ['logger', 'core'], lazy: false)]
#[Tag('infrastructure', ['priority' => 10])]
class AttributeLogger {
    public function log(string $message): void {
        echo "[LOG] {$message}\n";
    }
}

#[Service]
class AttributeService {
    public function __construct(
        #[Inject('my.logger')] private object $logger
    ) {}
    
    public function doSomething(): void {
        $this->logger->log('Doing something with attributes!');
    }
}

$container = new Container();
$container->enableAutowiring();

// Register from attribute
$container->registerFromAttribute(AttributeLogger::class);
echo "✓ Registered AttributeLogger from #[Service] attribute\n";
echo "  ID: my.logger\n";
echo "  Tags: " . implode(', ', $container->getServiceTags('my.logger')) . "\n\n";

// ============================================================================
// 2. Decorator Priorities
// ============================================================================

echo "2️⃣ Decorator Priorities\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$container2 = new Container();
$container2->set('processor', fn() => (object)['steps' => []]);

// Add decorators with priorities (lower priority = applied first)
$container2->decorate('processor', function($p) {
    $p->steps[] = 'Step 1 (priority 10)';
    return $p;
}, 10);

$container2->decorate('processor', function($p) {
    $p->steps[] = 'Step 2 (priority 5)';
    return $p;
}, 5);

$container2->decorate('processor', function($p) {
    $p->steps[] = 'Step 3 (priority 15)';
    return $p;
}, 15);

$processor = $container2->get('processor');
echo "✓ Decorators applied in priority order:\n";
foreach ($processor->steps as $step) {
    echo "  - {$step}\n";
}
echo "\n";

// ============================================================================
// 3. Service Locator Pattern
// ============================================================================

echo "3️⃣ Service Locator Pattern\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$container3 = new Container();
$container3->set('service1', fn() => (object)['name' => 'Service 1']);
$container3->set('service2', fn() => (object)['name' => 'Service 2']);
$container3->set('service3', fn() => (object)['name' => 'Service 3']);

// Create locator with limited services
$locator = $container3->createServiceLocator(['service1', 'service2']);

echo "✓ Created ServiceLocator\n";
echo "  Has service1: " . ($locator->has('service1') ? 'yes' : 'no') . "\n";
echo "  Has service3: " . ($locator->has('service3') ? 'yes' : 'no') . " (not included)\n\n";

// Locator from tag
$container3->tag('service1', 'public');
$container3->tag('service2', 'public');

$publicLocator = ServiceLocator::fromTag($container3, 'public');
echo "✓ Created ServiceLocator from tag 'public'\n";
echo "  Available services: " . count($publicLocator->getServiceIds()) . "\n\n";

// ============================================================================
// 4. Container Delegation
// ============================================================================

echo "4️⃣ Container Delegation\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$mainContainer = new Container();
$mainContainer->set('main.service', fn() => (object)['source' => 'main']);

$delegateContainer1 = new Container();
$delegateContainer1->set('delegate.service1', fn() => (object)['source' => 'delegate1']);

$delegateContainer2 = new Container();
$delegateContainer2->set('delegate.service2', fn() => (object)['source' => 'delegate2']);

// Add delegates to main container
$mainContainer->addDelegate($delegateContainer1);
$mainContainer->addDelegate($delegateContainer2);

echo "✓ Created DelegatingContainer setup\n";
$service1 = $mainContainer->get('main.service');
echo "  main.service source: {$service1->source}\n";

$service2 = $mainContainer->get('delegate.service1');
echo "  delegate.service1 source: {$service2->source}\n";

$service3 = $mainContainer->get('delegate.service2');
echo "  delegate.service2 source: {$service3->source}\n\n";

// ============================================================================
// 5. Scoped Containers
// ============================================================================

echo "5️⃣ Scoped Containers\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$baseContainer = new Container();
$baseContainer->set('request.data', fn() => (object)['id' => uniqid()]);
$baseContainer->set('global.service', fn() => (object)['type' => 'global']);

$scopedContainer = new ScopedContainer($baseContainer);
$scopedContainer->setScope('request.data', 'request');

echo "✓ Created ScopedContainer\n";

// First request scope
$scopedContainer->beginScope('request');
$data1 = $scopedContainer->get('request.data');
echo "  Request 1 ID: {$data1->id}\n";

$data1Again = $scopedContainer->get('request.data');
echo "  Request 1 ID again: {$data1Again->id} (same instance)\n";

$scopedContainer->endScope();

// Second request scope
$scopedContainer->beginScope('request');
$data2 = $scopedContainer->get('request.data');
echo "  Request 2 ID: {$data2->id} (different instance)\n";

$scopedContainer->endScope();

// Global service works across scopes
$global = $scopedContainer->get('global.service');
echo "  Global service type: {$global->type}\n\n";

// ============================================================================
// 6. Async Service Initialization (Generator-based)
// ============================================================================

echo "6️⃣ Async Service Initialization\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$asyncContainer = new Container();
$asyncContainer->set('async1', fn() => (object)['name' => 'Async Service 1']);
$asyncContainer->set('async2', fn() => (object)['name' => 'Async Service 2']);
$asyncContainer->set('async3', fn() => (object)['name' => 'Async Service 3']);

// Batch async initialization
echo "✓ Batch async initialization:\n";
foreach ($asyncContainer->batchGetAsync(['async1', 'async2', 'async3']) as $id => $service) {
    echo "  {$id}: {$service->name}\n";
}
echo "\n";

// ============================================================================
// 7. Combined Example: All Features Together
// ============================================================================

echo "7️⃣ Combined Example: All Features Together\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$advancedContainer = new Container();
$advancedContainer->enableAutowiring();

// Register services with tags
$advancedContainer->set('cache', fn() => (object)['type' => 'redis']);
$advancedContainer->tag('cache', 'infrastructure', ['priority' => 10]);

// Lazy service
$advancedContainer->setLazy('heavy.service', fn() => (object)['loaded' => time()]);
$advancedContainer->tag('heavy.service', 'lazy.loaded');

// Service with decorators (with priorities)
$advancedContainer->set('api', fn() => (object)['calls' => []]);
$advancedContainer->decorate('api', function($api) {
    $api->calls[] = 'logging';
    return $api;
}, 1);
$advancedContainer->decorate('api', function($api) {
    $api->calls[] = 'auth';
    return $api;
}, 10);  // Applied first
$advancedContainer->decorate('api', function($api) {
    $api->calls[] = 'rate-limit';
    return $api;
}, 5);

$api = $advancedContainer->get('api');
echo "✓ API service with decorated calls (ordered by priority):\n";
foreach ($api->calls as $call) {
    echo "  - {$call}\n";
}

echo "\n✓ Tagged services:\n";
$infrastructure = $advancedContainer->findTaggedServiceIds('infrastructure');
echo "  Infrastructure: " . implode(', ', $infrastructure) . "\n";

$lazyServices = $advancedContainer->findTaggedServiceIds('lazy.loaded');
echo "  Lazy loaded: " . implode(', ', $lazyServices) . "\n";

echo "\n✅ All advanced features working together!\n\n";

// ============================================================================
// Summary
// ============================================================================

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║                        SUMMARY                                 ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

echo "Demonstrated features:\n";
echo "  ✓ PHP 8+ Attributes (#[Service], #[Inject], #[Tag])\n";
echo "  ✓ Decorator Priorities (ordered application)\n";
echo "  ✓ Service Locator Pattern (limited service access)\n";
echo "  ✓ Container Delegation (multi-container support)\n";
echo "  ✓ Scoped Containers (request/session lifecycle)\n";
echo "  ✓ Async Service Initialization (generator-based)\n";
echo "  ✓ Combined usage (all features together)\n\n";

echo "✅ All advanced features examples completed!\n";

