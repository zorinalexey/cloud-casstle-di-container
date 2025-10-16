<?php

declare(strict_types=1);

namespace CloudCastle\DI;

use CloudCastle\DI\Exception\ContainerException;
use CloudCastle\DI\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

/**
 * Compiled container for maximum performance.
 *
 * Pre-compiled container with hardcoded service creation logic.
 */
abstract class CompiledContainer implements ContainerInterface
{
    /**
     * @var array<string, object> Cached service instances
     */
    protected array $instances = [];

    /**
     * {@inheritDoc}
     */
    abstract public function has(string $serviceId): bool;

    /**
     * {@inheritDoc}
     */
    abstract public function get(string $serviceId): mixed;

    /**
     * Get all service IDs.
     *
     * @return array<int, string>
     */
    abstract public function getServiceIds(): array;
}
