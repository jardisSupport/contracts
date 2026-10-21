<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Kernel;

use Throwable;

/**
 * Marker for a denied access to an endpoint.
 *
 * Generated rights checks throw an exception implementing this interface when the actor lacks the
 * required right or is of a kind that is not allowed. A delivery layer catches the interface, not the
 * generated class, and maps it to HTTP 403. The message key travels as data of the exception; this
 * package defines no key value.
 */
interface AccessDeniedExceptionInterface extends Throwable
{
    /**
     * Machine-readable message key of the denial, set by the throwing check.
     */
    public function getMessageKey(): string;

    /**
     * Additional context of the denial.
     *
     * @return array<string, mixed>
     */
    public function getContext(): array;
}
