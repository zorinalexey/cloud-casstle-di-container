<?php

declare(strict_types=1);

/**
 * Example: Using Compiled Container for maximum performance.
 *
 * This example demonstrates:
 * 1. How to compile container to optimized PHP code
 * 2. How to use ContainerFactory for automatic loading
 * 3. Performance benefits of compiled container
 */

require_once __DIR__ . '/../vendor/autoload.php';

use CloudCastle\DI\Container;
use CloudCastle\DI\ContainerFactory;

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║          Compiled Container Example                            ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

// ============================================================================
// Method 1: Manual compilation (for build/deployment scripts)
// ============================================================================

echo "📦 Method 1: Manual Compilation\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Create and configure container
$container = new Container();

// Register your services
$container->set('config', fn() => (object)['env' => 'production', 'debug' => false]);
$container->set('logger', fn() => (object)['name' => 'app.log']);
$container->set('database', fn($c) => (object)[
    'config' => $c->get('config'),
    'logger' => $c->get('logger'),
]);

// Compile to file
$startTime = microtime(true);
$compiledFile = ContainerFactory::compile($container);
$compileTime = (microtime(true) - $startTime) * 1000;

echo "✓ Compiled in " . number_format($compileTime, 2) . " ms\n";
echo "✓ File: {$compiledFile}\n\n";

// ============================================================================
// Method 2: Using ContainerFactory (automatic loading)
// ============================================================================

echo "🚀 Method 2: ContainerFactory (Automatic)\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Load services
$servicesFile = __DIR__ . '/../cache/services.php';
$services = file_exists($servicesFile) ? require $servicesFile : [];

// ContainerFactory automatically loads compiled container if exists
$startTime = microtime(true);
$autoContainer = ContainerFactory::create(services: $services);
$loadTime = (microtime(true) - $startTime) * 1000;

echo "✓ Loaded in " . number_format($loadTime, 2) . " ms\n";
echo "✓ Type: " . get_class($autoContainer) . "\n";
echo "✓ Services: " . count($services) . "\n\n";

// Use the container
if ($autoContainer->has('config')) {
    $config = $autoContainer->get('config');
    echo "✓ Config loaded: env={$config->env}, debug=" . ($config->debug ? 'true' : 'false') . "\n\n";
} else {
    echo "⚠️ No services loaded (create cache/services.php)\n\n";
}

// ============================================================================
// Method 3: Configure and use
// ============================================================================

echo "⚙️ Method 3: Configure with Factory\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$configuredContainer = ContainerFactory::createConfigured(
    configurator: function (Container $c) {
        $c->set('app', fn() => (object)['name' => 'MyApp', 'version' => '1.0']);
        $c->set('cache', fn() => (object)['driver' => 'redis']);
    },
    useCompiled: false  // Use regular container for dynamic configuration
);

$app = $configuredContainer->get('app');
echo "✓ App loaded: {$app->name} v{$app->version}\n";
echo "✓ Cache driver: " . $configuredContainer->get('cache')->driver . "\n\n";

// ============================================================================
// Performance Comparison
// ============================================================================

echo "⚡ Performance Comparison\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Benchmark regular container
$regularContainer = new Container();
$regularContainer->set('service', fn() => new stdClass());

$iterations = 100000;

$startTime = microtime(true);
for ($i = 0; $i < $iterations; $i++) {
    $regularContainer->get('service');
}
$regularTime = (microtime(true) - $startTime) * 1000;
$regularOps = $iterations / ($regularTime / 1000);

echo "Regular Container:\n";
echo "  Time: " . number_format($regularTime, 2) . " ms\n";
echo "  Speed: " . number_format($regularOps, 0) . " op/sec\n\n";

// Benchmark compiled container
$compiledContainer = ContainerFactory::create();

$startTime = microtime(true);
for ($i = 0; $i < $iterations; $i++) {
    $compiledContainer->get('config');
}
$compiledTime = (microtime(true) - $startTime) * 1000;
$compiledOps = $iterations / ($compiledTime / 1000);

echo "Compiled Container:\n";
echo "  Time: " . number_format($compiledTime, 2) . " ms\n";
echo "  Speed: " . number_format($compiledOps, 0) . " op/sec\n\n";

$speedup = ($regularOps > 0) ? ($compiledOps / $regularOps - 1) * 100 : 0;
echo "🚀 Speedup: " . number_format($speedup, 1) . "%\n\n";

// ============================================================================
// Production Usage Pattern
// ============================================================================

echo "💡 Recommended Production Pattern\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo <<<'PHP'
// 1. In your bootstrap/container configuration file:
$container = ContainerFactory::createConfigured(
    configurator: function (Container $c) {
        // Register all your services here
        $c->set('database', fn() => new Database());
        $c->set('logger', fn() => new Logger());
        // ... more services
    },
    useCompiled: getenv('APP_ENV') === 'production'
);

// 2. In your deployment script:
composer compile  # Automatically compiles after install/update

// 3. The compiled container is automatically used in production!

PHP;

echo "\n\n✅ All examples completed!\n";

