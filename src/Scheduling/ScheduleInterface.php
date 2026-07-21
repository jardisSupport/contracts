<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Scheduling;

use DateTimeInterface;

interface ScheduleInterface
{
    /**
     * @param list<string> $tags Filter by tags (empty = all)
     * @return list<ScheduledTaskInterface>
     */
    public function dueNow(DateTimeInterface $now, array $tags = []): array;

    /**
     * @param list<string> $tags Filter by tags (empty = all)
     * @return list<ScheduledTaskInterface>
     */
    public function allTasks(array $tags = []): array;

    /**
     * @return list<ScheduleViolation>
     */
    public function validate(): array;
}
