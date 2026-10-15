<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Auth;

/**
 * Issues one-time tokens (invitation, password reset, verification): the plain value is returned once,
 * only its hash is stored.
 */
interface TokenIssuerInterface
{
    /**
     * @param string $subject qualified subject `type:id`
     * @param positive-int $ttlSeconds
     * @param array<string, mixed> $claims
     */
    public function issue(TokenType $type, string $subject, int $ttlSeconds, array $claims = []): IssuedTokenInterface;
}
