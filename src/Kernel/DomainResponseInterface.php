<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Kernel;

/**
 * Interface for Domain Response objects.
 *
 * Defines the contract for the final response from a domain operation.
 * Immutable result built by a transformer from ContextResults.
 */
interface DomainResponseInterface
{
    /**
     * Check if the response is successful (2xx status).
     */
    public function isSuccess(): bool;

    /**
     * Get the result status.
     */
    public function getStatus(): int;

    /**
     * Get the aggregated data from all context results.
     *
     * @return array<string, array<string, mixed>>
     */
    public function getData(): array;

    /**
     * Get all collected domain events, keyed by context name.
     *
     * Without a scope, returns all events (structurally unchanged). With a
     * scope, returns only the events of that scope.
     *
     * @return array<string, array<int, object>>
     */
    public function getEvents(?EventScope $scope = null): array;

    /**
     * Get all errors, keyed by context name.
     *
     * @return array<string, array<int, string>>
     */
    public function getErrors(): array;

    /**
     * Get response metadata (duration, contexts, timestamp, version).
     *
     * @return array<string, mixed>
     */
    public function getMetadata(): array;
}
