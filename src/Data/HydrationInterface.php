<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Data;

use ReflectionException;

/**
 * Entity hydration, change tracking and snapshot management.
 *
 * Provides hydration from database rows, change detection via snapshots,
 * entity cloning, diffing and array conversion.
 */
interface HydrationInterface
{
    /**
     * Hydrates an entity with data from a database.
     *
     * Sets both the entity properties (via setters) and the __snapshot
     * for change tracking. Only processes DB-column values (scalar/null/DateTime/BackedEnum).
     *
     * @template T of object
     * @param T $entity
     * @param array<string, mixed> $data
     * @return T
     */
    public function hydrate(object $entity, array $data): object;

    /**
     * Applies data to an entity without updating the snapshot.
     *
     * Sets entity properties (via setters) like hydrate(), but does NOT update
     * the __snapshot. This means getChanges() will detect the applied values
     * as modifications. Use for programmatic changes (Set/Add operations).
     *
     * @template T of object
     * @param T $entity
     * @param array<string, mixed> $data
     * @return T
     */
    public function apply(object $entity, array $data): object;

    /**
     * Hydrates an aggregate with nested data structures.
     *
     * Recursively hydrates nested objects and arrays of objects.
     * Sets both the aggregate properties and snapshots for change tracking.
     *
     * @template T of object
     * @param T $aggregate
     * @param array<string, mixed> $data
     * @return T
     */
    public function hydrateAggregate(object $aggregate, array $data): object;

    /**
     * Detects changes between current entity state and snapshot.
     *
     * Compares current property values with the snapshot taken during hydration.
     *
     * @param object $entity The entity to check for changes
     * @return array<string, mixed> Map of changed field names to their new values
     */
    public function getChanges(object $entity): array;

    /**
     * Gets the snapshot from an entity.
     *
     * @param object $entity
     * @return array<string, mixed>
     */
    public function getSnapshot(object $entity): array;

    /**
     * Gets only the changed field names (without old/new values).
     *
     * @param object $entity
     * @return array<int, string>
     */
    public function getChangedFields(object $entity): array;

    /**
     * Creates a flat clone of an entity (DB-column properties only).
     *
     * @param object $entity The entity to clone
     * @return object The cloned entity
     * @throws ReflectionException
     */
    public function clone(object $entity): object;

    /**
     * Creates a deep clone of an aggregate (full object graph).
     *
     * @param object $entity The aggregate to clone
     * @return object The cloned aggregate
     * @throws ReflectionException
     */
    public function cloneAggregate(object $entity): object;

    /**
     * Compares two entities of the same class and returns differences.
     *
     * @param object $entity1 First entity (reference)
     * @param object $entity2 Second entity (compare against)
     * @return array<string, mixed> Map of column names to values from entity2 that differ
     */
    public function diff(object $entity1, object $entity2): array;

    /**
     * Converts an entity to a flat associative array (entity-level only).
     *
     * Only includes DB-column properties (scalar/null/DateTime/BackedEnum).
     * Formats DateTime properties as 'Y-m-d H:i:s', BackedEnum as ->value.
     *
     * @param object $entity The entity to convert
     * @return array<string, mixed> Associative array with column names as keys
     */
    public function toArray(object $entity): array;

    /**
     * Converts an aggregate to a nested associative array (full object graph).
     *
     * Includes all properties including relations.
     * Recursively converts nested objects and arrays of objects.
     *
     * @param object $entity The aggregate to convert
     * @return array<string, mixed> Nested associative array of the full object graph
     */
    public function aggregateToArray(object $entity): array;

    /**
     * Loads multiple database rows into an array of entities.
     *
     * @param object $template Template entity to clone for each row
     * @param array<int, array<string, mixed>> $rows Array of database rows
     * @return array<int, object> Array of hydrated entities
     */
    public function loadMultiple(object $template, array $rows): array;
}
