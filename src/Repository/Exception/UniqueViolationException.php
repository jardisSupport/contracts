<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Repository\Exception;

use Throwable;

/**
 * Thrown when a persist operation violates a unique constraint (duplicate key).
 * Subtype of PersistException so existing catch blocks keep working.
 */
class UniqueViolationException extends PersistException
{
    public function __construct(
        string $message = '',
        private readonly ?string $constraint = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }

    /**
     * Name of the violated constraint/index, or null if the driver does not expose it.
     */
    public function getConstraint(): ?string
    {
        return $this->constraint;
    }
}
