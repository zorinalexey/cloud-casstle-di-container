<?php

declare(strict_types = 1);

namespace CloudCastle\DI\Exception;

use Psr\Container\ContainerExceptionInterface;

/**
 * General container exception.
 */
class ContainerException extends \Exception implements ContainerExceptionInterface
{
}
