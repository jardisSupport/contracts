<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Identity;

use DateTimeImmutable;

/**
 * A tenant (organisation) that owns memberships and groups.
 *
 * @deprecated 2.11.0 Identity ist eine Generic Subdomain; ersetzt durch die Builder-Blaupause „Access“
 */
interface TenantInterface
{
    public function getId(): string;

    public function getSlug(): string;

    public function getName(): string;

    public function getCreatedAt(): DateTimeImmutable;
}
