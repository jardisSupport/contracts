<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Scheduling;

use DateTimeInterface;

interface ConstraintInterface
{
    public function __invoke(DateTimeInterface $now): bool;
}
