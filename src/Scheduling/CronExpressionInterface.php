<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Scheduling;

use DateTimeInterface;

interface CronExpressionInterface
{
    public function isDue(DateTimeInterface $now): bool;

    public function nextRun(DateTimeInterface $from): DateTimeInterface;

    /**
     * @return list<DateTimeInterface>
     */
    public function nextRuns(DateTimeInterface $from, int $count): array;

    public function previousRun(DateTimeInterface $from): DateTimeInterface;

    public function describe(): string;
}
