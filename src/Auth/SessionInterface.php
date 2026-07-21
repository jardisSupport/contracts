<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Auth;

/**
 * Represents an authenticated session.
 */
interface SessionInterface
{
    public function getSubject(): string;

    public function getTokenHash(): string;

    public function isExpired(): bool;

    /**
     * @return array<string, mixed>
     */
    public function getMetadata(): array;
}
