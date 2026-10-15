<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Identity;

use DateTimeImmutable;

/**
 * A named group of users within one tenant.
 *
 * @deprecated 2.11.0 Identity ist eine Generic Subdomain; ersetzt durch die Builder-Blaupause „Access“
 */
interface GroupInterface
{
    public function getId(): string;

    public function getTenantId(): string;

    public function getName(): string;

    public function getCreatedAt(): DateTimeImmutable;
}
