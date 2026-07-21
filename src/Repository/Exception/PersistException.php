<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Repository\Exception;

use RuntimeException;

/**
 * Thrown when a persist operation (insert, update, delete) fails.
 */
class PersistException extends RuntimeException
{
}
