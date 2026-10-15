<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Identity;

use DateTimeImmutable;

/**
 * Membership of a user in a tenant with a role.
 *
 * @deprecated 2.11.0 Identity ist eine Generic Subdomain; ersetzt durch die Builder-Blaupause „Access“
 */
interface MembershipInterface
{
    public function getUserId(): string;

    public function getTenantId(): string;

    public function getRole(): string;

    public function getCreatedAt(): DateTimeImmutable;
}
