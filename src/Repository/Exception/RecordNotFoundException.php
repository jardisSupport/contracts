<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Repository\Exception;

use RuntimeException;

/**
 * Thrown when a requested record does not exist.
 */
class RecordNotFoundException extends RuntimeException
{
}
