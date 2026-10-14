<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Identity;

/**
 * Persistence port for tenants.
 */
interface TenantStoreInterface
{
    public function store(TenantInterface $tenant): void;

    public function findById(string $id): ?TenantInterface;

    public function findBySlug(string $slug): ?TenantInterface;
}
