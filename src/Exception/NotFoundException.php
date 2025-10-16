<?php

declare(strict_types=1);

namespace CloudCastle\DI\Exception;

use Psr\Container\NotFoundExceptionInterface;

/**
 * Exception thrown when a service is not found in the container.
 */
class NotFoundException extends ContainerException implements NotFoundExceptionInterface
{
}
