<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Identity;

use DateTimeImmutable;

/**
 * Membership of a user in a tenant with a role.
 */
interface MembershipInterface
{
    public function getUserId(): string;

    public function getTenantId(): string;

    public function getRole(): string;

    public function getCreatedAt(): DateTimeImmutable;
}
