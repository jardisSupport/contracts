<?php

declare(strict_types=1);

namespace JardisSupport\Contract\DbConnection;

use InvalidArgumentException;
use RuntimeException;

/**
 * Nested transaction brackets (savepoints) of a database connection.
 *
 * Additive capability next to DbConnectionInterface — a connection that can
 * nest brackets implements both. Consumers check `instanceof SavepointInterface`.
 *
 * SQL form (identical for MySQL/MariaDB, PostgreSQL and SQLite):
 *   SAVEPOINT <name> | RELEASE SAVEPOINT <name> | ROLLBACK TO SAVEPOINT <name>
 *
 * The name is interpolated into SQL and therefore validated by the
 * implementation: identifier form `[A-Za-z_][A-Za-z0-9_]*`, at most 63 characters.
 * Every method throws InvalidArgumentException for an invalid name and
 * RuntimeException when no transaction is active (PDOException is a RuntimeException).
 */
interface SavepointInterface
{
    /**
     * Longest accepted savepoint name (PostgreSQL identifier limit).
     */
    public const MAX_NAME_LENGTH = 63;

    /**
     * Opens a savepoint inside the running transaction.
     *
     * @throws InvalidArgumentException if the name is not a valid identifier
     * @throws RuntimeException if no transaction is active or the statement fails
     */
    public function savepoint(string $name): void;

    /**
     * Releases a savepoint: its work stays part of the enclosing transaction.
     *
     * @throws InvalidArgumentException if the name is not a valid identifier
     * @throws RuntimeException if no transaction is active or the statement fails
     */
    public function releaseSavepoint(string $name): void;

    /**
     * Rolls back to a savepoint: only the work since it was opened is discarded,
     * the enclosing transaction stays open.
     *
     * @throws InvalidArgumentException if the name is not a valid identifier
     * @throws RuntimeException if no transaction is active or the statement fails
     */
    public function rollbackToSavepoint(string $name): void;
}
