<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Identity;

/**
 * Persistence port for groups; group names are unique per tenant.
 *
 * @deprecated 2.11.0 Identity ist eine Generic Subdomain; ersetzt durch die Builder-Blaupause „Access“
 */
interface GroupStoreInterface
{
    public function store(GroupInterface $group): void;

    public function findById(string $id): ?GroupInterface;

    public function findByName(string $tenantId, string $name): ?GroupInterface;

    /** @return list<GroupInterface> */
    public function listForTenant(string $tenantId): array;
}
