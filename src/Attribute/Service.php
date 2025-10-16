<?php

declare(strict_types=1);

namespace CloudCastle\DI\Attribute;

use Attribute;

/**
 * Marks a class as a service for autowiring.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Service
{
    /**
     * @param string|null $id Service identifier (defaults to class name)
     * @param array<int, string> $tags Service tags
     * @param bool $lazy Whether to create as lazy service
     * @param int $priority Priority for ordering (higher = first)
     */
    public function __construct(
        public ?string $id = null,
        public array $tags = [],
        public bool $lazy = false,
        public int $priority = 0
    ) {
    }
}

