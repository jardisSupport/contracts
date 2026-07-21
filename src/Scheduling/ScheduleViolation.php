<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Scheduling;

/**
 * Describes a single validation issue found in a schedule definition.
 */
final readonly class ScheduleViolation
{
    public function __construct(
        public string $taskName,
        public string $message,
        public string $severity = 'error',
    ) {
    }
}
