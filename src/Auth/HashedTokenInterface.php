<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Auth;

use DateTimeImmutable;

/**
 * Represents a hashed authentication token.
 */
interface HashedTokenInterface
{
    public function getHash(): string;

    public function getType(): TokenType;

    public function getSubject(): ?string;

    /**
     * @return array<string, mixed>
     */
    public function getClaims(): array;

    public function getExpiresAt(): ?DateTimeImmutable;

    public function getCreatedAt(): DateTimeImmutable;

    public function isExpired(): bool;

    public function isRevoked(): bool;
}
