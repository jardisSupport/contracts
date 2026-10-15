<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Identity;

use DateTimeImmutable;

/**
 * A user account. Does not expose the password hash (see UserStoreInterface::passwordHashOf()).
 *
 * @deprecated 2.11.0 Identity ist eine Generic Subdomain; ersetzt durch die Builder-Blaupause „Access“
 */
interface UserInterface
{
    public function getId(): string;

    public function getEmail(): string;

    public function getDisplayName(): string;

    public function getStatus(): UserStatus;

    public function getCreatedAt(): DateTimeImmutable;
}
