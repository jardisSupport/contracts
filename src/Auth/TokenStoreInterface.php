<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Auth;

/**
 * Stores and manages hashed authentication tokens.
 */
interface TokenStoreInterface
{
    public function store(HashedTokenInterface $token): void;

    public function find(string $hash): ?HashedTokenInterface;

    public function revoke(string $hash): void;

    public function revokeAllForSubject(string $subject): void;

    public function deleteExpired(): int;
}
