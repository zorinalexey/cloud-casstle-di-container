<?php
// CloudCastle DI Container - Basic Example (English)

require_once __DIR__ . '/../../vendor/autoload.php';

use CloudCastle\DI\Container;

$container = new Container();

// Register services
$container->set('config', fn() => ['db' => 'mysql', 'host' => 'localhost']);
$container->set('logger', fn() => new class { 
    public function log($msg) { echo "[LOG] $msg\n"; }
});

// Get services
$config = $container->get('config');
$logger = $container->get('logger');

$logger->log('Container initialized!');
echo "✅ Config: " . json_encode($config) . "\n";
