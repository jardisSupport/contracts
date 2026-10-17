<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Auth;

use DateTimeImmutable;

/**
 * Represents an authenticated session.
 */
interface SessionInterface
{
    public function getSubject(): string;

    public function getTokenHash(): string;

    /**
     * Point in time after which the session is no longer valid; `null` = the session does not expire.
     */
    public function getExpiresAt(): ?DateTimeImmutable;

    public function isExpired(): bool;

    /**
     * @return array<string, mixed>
     */
    public function getMetadata(): array;
}
