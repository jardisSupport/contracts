<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Secret;

use RuntimeException;

/**
 * Thrown when a secret value cannot be resolved.
 */
class SecretResolutionException extends RuntimeException
{
}
