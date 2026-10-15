<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Auth;

/**
 * Looks up and redeems one-time tokens.
 */
interface TokenRedeemerInterface
{
    /**
     * Finds and validates a token WITHOUT revoking it (read-only check, e.g. to show an invitation
     * before it is accepted).
     *
     * Failure cases (exceptions of jardissupport/auth, not declared in this package): `InvalidCredentialException`
     * for an unknown token or wrong type, `TokenExpiredException`, `TokenRevokedException`.
     */
    public function peek(string $plain, TokenType $expected): HashedTokenInterface;

    /**
     * Redeems a token once: unknown, wrong type, expired or already redeemed tokens throw; a valid
     * token is revoked and returned with its subject and claims.
     *
     * Failure cases (exceptions of jardissupport/auth, not declared in this package): `InvalidCredentialException`
     * for an unknown token or wrong type, `TokenExpiredException`, `TokenRevokedException`.
     */
    public function redeem(string $plain, TokenType $expected): HashedTokenInterface;
}
