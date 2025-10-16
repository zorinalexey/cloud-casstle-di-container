<?php

declare(strict_types=1);

namespace CloudCastle\DI\Attribute;

use Attribute;

/**
 * Specifies which service to inject for a parameter.
 */
#[Attribute(Attribute::TARGET_PARAMETER)]
class Inject
{
    /**
     * @param string $serviceId Service identifier to inject
     */
    public function __construct(
        public string $serviceId
    ) {
    }
}

