<?php

declare(strict_types=1);

namespace JardisSupport\Contract\DbSchema;

interface DbSchemaReaderInterface
{
    /**
     * Retrieves the list of tables.
     *
     * @return array<int, string>|null Returns an array of tables if available, or null if no tables are present.
     */
    public function tables(): ?array;

    /**
     * Retrieves the columns of a given database table.
     *
     * @param string $container The name of the database table to fetch columns for.
     * @param array<int, mixed>|null $fields A list of fields to include in the result.
     * @return array<int, mixed>|null An array containing the columns of the table, or null if the table does not exist
     * or an error occurs.
     */
    public function columns(string $container, ?array $fields = null): ?array;

    /**
     * Retrieves the indexes for the specified database table.
     *
     * @param string $container The name of the table for which to retrieve indexes.
     * @return array<int, mixed>|null An array of indexes if available, or null if no indexes are found.
     */
    public function indexes(string $container): ?array;

    /**
     * Retrieves the foreign keys associated with the given database table.
     *
     * @param string $container The name of the table for which to retrieve foreign keys.
     * @return array<int, mixed>|null An array of foreign keys if they exist, or null if none are found.
     */
    public function foreignKeys(string $container): ?array;

    /**
     * Maps a given database field type to its corresponding PHP data type.
     *
     * @param string $fieldType The database field type (e.g., 'varchar', 'int', 'datetime')
     * @return string|null The corresponding PHP data type (e.g., 'string', 'int', 'DateTime'), or null if unmapped
     */
    public function fieldType(string $fieldType): ?string;

    /**
     * Retrieves the name of the driver.
     *
     * @return string The name of the driver.
     */
    public function getDriverName(): string;
}
