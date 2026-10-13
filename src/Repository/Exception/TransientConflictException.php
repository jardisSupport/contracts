<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Repository\Exception;

use Throwable;

/**
 * Thrown when a persist operation fails because of a transient concurrency conflict of the database
 * (snapshot/serialization conflict, deadlock): the statement was rejected, not wrong — repeating the whole
 * unit of work in a NEW transaction can succeed.
 * Subtype of PersistException so existing catch blocks keep working; catch it BEFORE PersistException to
 * tell a retryable conflict from a technical failure.
 *
 * The driver code is carried as text. Examples (not an exhaustive list): '1020' (MariaDB "record has changed
 * since last read"), '1213' (MySQL/MariaDB deadlock), '40001' (SQLSTATE serialization failure),
 * '40P01' (PostgreSQL deadlock detected).
 */
class TransientConflictException extends PersistException
{
    /**
     * @param string|null $driverCode Driver error code or SQLSTATE as text, or null if the driver does not expose it.
     */
    public function __construct(
        string $message = '',
        private readonly ?string $driverCode = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }

    /**
     * Driver error code or SQLSTATE of the conflict as text (e.g. '1020', '1213', '40001', '40P01'),
     * or null if the driver does not expose it.
     */
    public function getDriverCode(): ?string
    {
        return $this->driverCode;
    }
}
