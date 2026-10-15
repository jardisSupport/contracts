<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Identity;

/**
 * Persistence port for group membership (which users belong to which group).
 *
 * @deprecated 2.11.0 Identity ist eine Generic Subdomain; ersetzt durch die Builder-Blaupause „Access“
 */
interface GroupMemberStoreInterface
{
    public function add(string $groupId, string $userId): void;

    public function remove(string $groupId, string $userId): void;

    /** Removes the user from every group of the given tenant. */
    public function removeUserFromTenantGroups(string $userId, string $tenantId): void;

    /** @return list<string> User ids */
    public function listMembers(string $groupId): array;

    public function isMember(string $groupId, string $userId): bool;
}
