<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Identity;

/**
 * Persistence port for tenants.
 *
 * @deprecated 2.11.0 Identity ist eine Generic Subdomain; ersetzt durch die Builder-Blaupause „Access“
 */
interface TenantStoreInterface
{
    public function store(TenantInterface $tenant): void;

    public function findById(string $id): ?TenantInterface;

    public function findBySlug(string $slug): ?TenantInterface;
}
