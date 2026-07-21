<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Scheduling;

use DateTimeInterface;

interface ScheduledTaskInterface
{
    public function name(): string;

    public function description(): string;

    public function expression(): CronExpressionInterface;

    public function isDue(DateTimeInterface $now): bool;

    public function nextRun(DateTimeInterface $from): DateTimeInterface;

    /**
     * @return list<string>
     */
    public function tags(): array;

    public function priority(): int;

    public function allowsOverlapping(): bool;
}
