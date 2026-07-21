<?php

declare(strict_types=1);

namespace JardisSupport\Contract\DbConnection;

/**
 * Contract for database configuration objects.
 *
 * Each implementation knows how to build its own DSN and provide
 * the credentials PDO needs — no instanceof checks required.
 */
interface DatabaseConfigInterface
{
    /**
     * Returns the PDO Data Source Name.
     *
     * @return string The DSN (e.g., 'mysql:host=localhost;port=3306;dbname=mydb;charset=utf8mb4')
     */
    public function getDsn(): string;

    /**
     * Returns the username for the connection, or null if not applicable (e.g., SQLite).
     */
    public function getUser(): ?string;

    /**
     * Returns the password for the connection, or null if not applicable (e.g., SQLite).
     */
    public function getPassword(): ?string;

    /**
     * Returns additional PDO options for this connection.
     *
     * These will be merged with DbConnectionInterface::DEFAULT_OPTIONS.
     *
     * @return array<int, mixed>
     */
    public function getOptions(): array;

    /**
     * Returns the database name.
     */
    public function getDatabaseName(): string;

    /**
     * Returns the driver name (e.g., 'mysql', 'pgsql', 'sqlite').
     */
    public function getDriverName(): string;
}
