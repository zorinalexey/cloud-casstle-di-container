<?php

declare(strict_types=1);

/**
 * Container compilation script.
 *
 * Compiles the container to optimized PHP code for production use.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use CloudCastle\DI\Container;

// Configuration
$cacheDir = __DIR__ . '/../cache';
$className = 'CompiledContainer';
$namespace = 'CloudCastle\\DI\\Compiled';

// Ensure cache directory exists
if (!is_dir($cacheDir)) {
    mkdir($cacheDir, 0755, true);
    echo "✓ Created cache directory: {$cacheDir}\n";
}

// Create container with your service definitions
$container = new Container();

// Example services (replace with your actual services in production)
// Load services from config file if exists
$servicesFile = $cacheDir . '/services.php';
if (file_exists($servicesFile)) {
    $services = require $servicesFile;
    foreach ($services as $id => $factory) {
        $container->set($id, $factory);
    }
    echo "✓ Loaded " . count($services) . " services from {$servicesFile}\n";
} else {
    echo "ℹ No services file found, compiling empty container\n";
    echo "💡 Create {$servicesFile} with your service definitions\n\n";
}

echo "🔧 Compiling container...\n";

// Compile container
$filePath = $cacheDir . '/' . $className . '.php';
$success = $container->compileToFile(
    $filePath,
    $className,
    $namespace
);

if (!$success) {
    echo "❌ Failed to compile container!\n";
    exit(1);
}

echo "✅ Container compiled successfully!\n";
echo "📁 File: {$filePath}\n";
echo "📦 Class: {$namespace}\\{$className}\n";
echo "\n";
echo "💡 Usage in production:\n";
echo "   // Load services\n";
echo "   \$services = require '{$servicesFile}';\n";
echo "   \n";
echo "   // Create compiled container\n";
echo "   require_once '{$filePath}';\n";
echo "   \$container = new {$namespace}\\{$className}(\$services);\n";
echo "\n";

