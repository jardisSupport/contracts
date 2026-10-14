<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Identity;

use DateTimeImmutable;

/**
 * A user account. Does not expose the password hash (see UserStoreInterface::passwordHashOf()).
 */
interface UserInterface
{
    public function getId(): string;

    public function getEmail(): string;

    public function getDisplayName(): string;

    public function getStatus(): UserStatus;

    public function getCreatedAt(): DateTimeImmutable;
}
