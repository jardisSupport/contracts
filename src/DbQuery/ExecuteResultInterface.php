<?php

declare(strict_types=1);

namespace JardisSupport\Contract\DbQuery;

/**
 * Result of a DML operation (INSERT, UPDATE, DELETE).
 *
 * Provides access to execution metadata from write operations.
 */
interface ExecuteResultInterface
{
    /**
     * Returns the number of rows affected by the operation.
     *
     * @return int Number of affected rows
     */
    public function affectedRows(): int;

    /**
     * Returns the last inserted auto-increment ID.
     *
     * Only meaningful after INSERT operations.
     *
     * @return string|false The last insert ID or false if not applicable
     */
    public function lastInsertId(): string|false;
}
