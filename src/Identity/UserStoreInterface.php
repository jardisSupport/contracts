<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Identity;

/**
 * Persistence port for users. The password hash is passed alongside the user, not part of it.
 *
 * @deprecated 2.11.0 Identity ist eine Generic Subdomain; ersetzt durch die Builder-Blaupause „Access“
 */
interface UserStoreInterface
{
    /** Persists a new user together with its password hash. */
    public function store(UserInterface $user, string $passwordHash): void;

    public function updateStatus(string $id, UserStatus $status): void;

    public function updatePasswordHash(string $id, string $hash): void;

    public function findById(string $id): ?UserInterface;

    /** @param string $email Normalised (trimmed, lower-case) email */
    public function findByEmail(string $email): ?UserInterface;

    /** @return string|null Stored password hash, null if the user does not exist */
    public function passwordHashOf(string $id): ?string;
}
