<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Kernel;

/**
 * Classifies a collected event by its publication scope.
 *
 * Used as the write-gesture on ContextResponseInterface::addEvent() to
 * distinguish aggregate-internal events from events that are announced
 * outside the system.
 */
enum EventScope: string
{
    case Internal = 'internal';   // aggregate-level, stays within the system
    case Domain = 'domain';       // announced, leaves the system
}
