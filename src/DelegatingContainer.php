<?php

declare(strict_types=1);

namespace CloudCastle\DI;

use CloudCastle\DI\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

/**
 * Container that delegates to multiple containers.
 *
 * Tries to get services from delegates if not found in main container.
 */
class DelegatingContainer implements ContainerInterface
{
    /**
     * @param ContainerInterface $container Main container
     * @param array<int, ContainerInterface> $delegates Delegate containers
     */
    public function __construct(
        private readonly ContainerInterface $container,
        private array $delegates = []
    ) {
    }

    /**
     * Add a delegate container.
     *
     * @param ContainerInterface $delegate Delegate container
     */
    public function addDelegate(ContainerInterface $delegate): void
    {
        $this->delegates[] = $delegate;
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $id): mixed
    {
        // Try main container first
        if ($this->container->has($id)) {
            return $this->container->get($id);
        }

        // Try delegates
        foreach ($this->delegates as $delegate) {
            if ($delegate->has($id)) {
                return $delegate->get($id);
            }
        }

        throw new NotFoundException(sprintf("Service '%s' not found in any container", $id));
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $id): bool
    {
        if ($this->container->has($id)) {
            return true;
        }

        foreach ($this->delegates as $delegate) {
            if ($delegate->has($id)) {
                return true;
            }
        }

        return false;
    }
}

