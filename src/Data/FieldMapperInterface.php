<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Data;

/**
 * Bidirectional array-key mapping between domain field names and database column names.
 */
interface FieldMapperInterface
{
    /**
     * Renames array keys from domain field names to database column names.
     *
     * @param array<string, mixed> $data Input with domain field names as keys
     * @param array<string, string> $map Domain-to-column mapping
     * @return array<string, mixed> Output with column names as keys
     */
    public function toColumns(array $data, array $map): array;

    /**
     * Renames array keys from database column names to domain field names.
     *
     * Applies recursively to nested arrays (for aggregate query responses).
     *
     * @param array<string, mixed> $data Input with column names as keys
     * @param array<string, string> $map Domain-to-column mapping (same direction as toColumns)
     * @return array<string, mixed> Output with domain field names as keys
     */
    public function fromColumns(array $data, array $map): array;

    /**
     * Maps a hierarchical aggregate array using per-entity mappings from a provider.
     *
     * Each level gets its own mapping via $mapProvider. Scalar values not in the mapping
     * are omitted. Array values trigger recursive descent with the array key as entity name.
     *
     * @param array<string, mixed> $data Hierarchical array (e.g. from aggregateToArray)
     * @param callable(string): array<string, string> $mapProvider Returns [domainName => columnName] per entity
     * @param string $entityName Name of the current entity level
     * @return array<string, mixed> Mapped array with only mapped field names
     */
    public function fromAggregate(array $data, callable $mapProvider, string $entityName): array;
}
