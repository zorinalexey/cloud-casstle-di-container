<?php

declare(strict_types=1);

namespace CloudCastle\DI;

use Psr\Container\ContainerInterface;

/**
 * Container factory with automatic compiled container loading.
 *
 * Automatically loads compiled container if available, falls back to regular container.
 */
class ContainerFactory
{
    /**
     * Create container instance.
     *
     * Automatically loads compiled container if it exists in cache directory.
     *
     * @param string|null $cacheDir Cache directory path (default: project_root/cache)
     * @param string $compiledClass Compiled container class name
     * @param string $compiledNamespace Compiled container namespace
     * @return ContainerInterface
     */
    public static function create(
        ?string $cacheDir = null,
        string $compiledClass = 'CompiledContainer',
        string $compiledNamespace = 'CloudCastle\\DI\\Compiled',
        array $services = []
    ): ContainerInterface {
        // Default cache directory
        if ($cacheDir === null) {
            $cacheDir = dirname(__DIR__) . '/cache';
        }

        // Check if compiled container exists
        $compiledFile = $cacheDir . '/' . $compiledClass . '.php';
        $fullyQualifiedClass = $compiledNamespace . '\\' . $compiledClass;

        if (file_exists($compiledFile)) {
            require_once $compiledFile;

            if (class_exists($fullyQualifiedClass)) {
                return new $fullyQualifiedClass($services);
            }
        }

        // Fall back to regular container
        $container = new Container();
        foreach ($services as $id => $factory) {
            $container->set($id, $factory);
        }
        return $container;
    }

    /**
     * Create and configure container with service definitions.
     *
     * @param callable $configurator Function to configure container
     * @param bool $useCompiled Whether to use compiled container if available
     * @param string|null $cacheDir Cache directory path
     * @return ContainerInterface
     */
    public static function createConfigured(
        callable $configurator,
        bool $useCompiled = true,
        ?string $cacheDir = null
    ): ContainerInterface {
        // Create temporary container to collect services
        $tempContainer = new Container();
        $configurator($tempContainer);

        if ($useCompiled) {
            // Extract services from configured container
            $services = [];
            foreach ($tempContainer->getServiceIds() as $id) {
                // Get the factory (accessing private property through reflection would be complex)
                // For now, we'll just use the configured container
            }

            $container = self::create($cacheDir, services: $services);

            // If it's a compiled container, reconfigure it
            if (!$container instanceof Container) {
                // Compiled container already has services from constructor
                return $container;
            }

            // It's a regular container, use the temp one
            return $tempContainer;
        }

        // Return configured regular container
        return $tempContainer;
    }

    /**
     * Compile container to file.
     *
     * @param Container $container Container to compile
     * @param string|null $cacheDir Cache directory path
     * @param string $compiledClass Compiled container class name
     * @param string $compiledNamespace Compiled container namespace
     * @return string Path to compiled file
     */
    public static function compile(
        Container $container,
        ?string $cacheDir = null,
        string $compiledClass = 'CompiledContainer',
        string $compiledNamespace = 'CloudCastle\\DI\\Compiled'
    ): string {
        // Default cache directory
        if ($cacheDir === null) {
            $cacheDir = dirname(__DIR__) . '/cache';
        }

        // Ensure cache directory exists
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }

        // Compile to file
        $compiledFile = $cacheDir . '/' . $compiledClass . '.php';

        $success = $container->compileToFile(
            $compiledFile,
            $compiledClass,
            $compiledNamespace
        );

        if (!$success) {
            throw new \RuntimeException("Failed to compile container to file: {$compiledFile}");
        }

        return $compiledFile;
    }
}

