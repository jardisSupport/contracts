<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Repository\Exception;

use Throwable;

/**
 * Thrown when a persist operation violates a unique constraint (duplicate key).
 * Subtype of PersistException so existing catch blocks keep working.
 *
 * Carries which columns (and table) were violated so callers can map the failure to a business field.
 * Both are populated on the failure path only; the original three constructor arguments keep their position.
 */
class UniqueViolationException extends PersistException
{
    /**
     * @param list<string> $columns Violated columns of the unique constraint. Empty = the driver/dialect did not
     *                              expose them (unknown); never a partial list.
     * @param string|null  $table   Table of the violated constraint, or null if unknown.
     */
    public function __construct(
        string $message = '',
        private readonly ?string $constraint = null,
        ?Throwable $previous = null,
        private readonly array $columns = [],
        private readonly ?string $table = null
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

    /**
     * Columns of the violated unique constraint. Empty = the driver/dialect delivers no columns (unknown);
     * never a partial list.
     *
     * @return list<string>
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * Table of the violated unique constraint, or null if the driver does not expose it.
     */
    public function getTable(): ?string
    {
        return $this->table;
    }
}
