<?php

declare(strict_types=1);

namespace JardisSupport\Contract\DbSchema;

/**
 * Interface for database schema exporters.
 *
 * Defines methods for exporting database schema in various formats.
 */
interface DbSchemaExporterInterface
{
    /**
     * Exports schema as SQL DDL script.
     *
     * @param array<int, string> $tables List of table names to export
     * @return string Complete SQL DDL script
     */
    public function toSql(array $tables): string;

    /**
     * Exports schema as JSON.
     *
     * @param array<int, string> $tables List of table names to export
     * @param bool $prettyPrint Whether to format JSON with indentation (default: false)
     * @return string JSON representation of schema
     */
    public function toJson(array $tables, bool $prettyPrint = false): string;

    /**
     * Exports schema as array.
     *
     * @param array<int, string> $tables List of table names to export
     * @return array<string, mixed> Array representation of schema
     */
    public function toArray(array $tables): array;
}
