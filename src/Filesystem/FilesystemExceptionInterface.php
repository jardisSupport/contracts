<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Filesystem;

use Throwable;

/**
 * Base exception interface for all filesystem errors.
 *
 * All filesystem exceptions implement this interface,
 * allowing callers to catch any filesystem error generically.
 */
interface FilesystemExceptionInterface extends Throwable
{
}
