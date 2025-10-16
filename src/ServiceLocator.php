<?php

declare(strict_types=1);

namespace CloudCastle\DI;

use CloudCastle\DI\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

/**
 * Service Locator pattern implementation.
 *
 * Provides a registry of services for dynamic discovery.
 */
class ServiceLocator implements ContainerInterface
{
    /**
     * @param ContainerInterface $container Underlying container
     * @param array<int, string> $serviceIds Allowed service IDs
     */
    public function __construct(
        private readonly ContainerInterface $container,
        private readonly array $serviceIds
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $id): mixed
    {
        if (!$this->has($id)) {
            throw new NotFoundException(sprintf("Service '%s' not available in this locator", $id));
        }

        return $this->container->get($id);
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $id): bool
    {
        return in_array($id, $this->serviceIds, true) && $this->container->has($id);
    }

    /**
     * Get all available service IDs.
     *
     * @return array<int, string>
     */
    public function getServiceIds(): array
    {
        return $this->serviceIds;
    }

    /**
     * Create service locator from tag.
     *
     * @param Container $container Container instance
     * @param string $tag Tag name
     * @return self
     */
    public static function fromTag(Container $container, string $tag): self
    {
        $serviceIds = $container->findTaggedServiceIds($tag);
        return new self($container, $serviceIds);
    }
}

