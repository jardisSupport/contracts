<?php

declare(strict_types=1);

namespace JardisSupport\Contract\DbQuery;

/**
 * Result of a SQL query (SELECT).
 *
 * Provides access to fetched rows from read operations.
 */
interface QueryResultInterface
{
    /**
     * Fetches all rows as associative arrays.
     *
     * @return array<int, array<string, mixed>>
     */
    public function fetchAll(): array;

    /**
     * Fetches a single row as associative array.
     *
     * @return array<string, mixed>|null The row or null if no result
     */
    public function fetchOne(): ?array;

    /**
     * Returns the number of rows in the result set.
     *
     * @return int Number of rows
     */
    public function rowCount(): int;
}
