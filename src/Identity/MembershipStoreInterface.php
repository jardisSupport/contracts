<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Identity;

/**
 * Persistence port for memberships; a membership is identified by (userId, tenantId).
 *
 * @deprecated 2.11.0 Identity ist eine Generic Subdomain; ersetzt durch die Builder-Blaupause „Access“
 */
interface MembershipStoreInterface
{
    public function store(MembershipInterface $membership): void;

    public function updateRole(string $userId, string $tenantId, string $role): void;

    public function remove(string $userId, string $tenantId): void;

    public function find(string $userId, string $tenantId): ?MembershipInterface;

    /** @return list<MembershipInterface> */
    public function listForUser(string $userId): array;

    /** @return list<MembershipInterface> */
    public function listForTenant(string $tenantId): array;
}
