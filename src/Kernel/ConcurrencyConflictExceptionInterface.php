<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Kernel;

use Throwable;

/**
 * Marker for an optimistic write conflict.
 *
 * Generated repositories throw an exception implementing this interface when a write
 * loses against a concurrent change (stale version, violated uniqueness). A delivery
 * layer catches the interface, not the generated class, and maps it to HTTP 409.
 */
interface ConcurrencyConflictExceptionInterface extends Throwable
{
    /**
     * Machine-readable reason of the conflict.
     */
    public function getReason(): string;

    /**
     * Name of the aggregate the conflict occurred on.
     */
    public function getAggregate(): string;

    /**
     * Additional conflict context.
     *
     * @return array<string, mixed>
     */
    public function getContext(): array;

    /**
     * Names of the violated constraints or fields.
     *
     * @return list<string>
     */
    public function getViolated(): array;
}
