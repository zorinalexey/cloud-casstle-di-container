<?php

declare(strict_types=1);

namespace CloudCastle\DI\Attribute;

use Attribute;

/**
 * Tags a service for grouping.
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Tag
{
    /**
     * @param string $name Tag name
     * @param array<string, mixed> $attributes Tag attributes
     */
    public function __construct(
        public string $name,
        public array $attributes = []
    ) {
    }
}

