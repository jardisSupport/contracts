<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Identity;

use DateTimeImmutable;

/**
 * A named group of users within one tenant.
 */
interface GroupInterface
{
    public function getId(): string;

    public function getTenantId(): string;

    public function getName(): string;

    public function getCreatedAt(): DateTimeImmutable;
}
