<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Auth;

/**
 * Session lifecycle: creation, token refresh and revocation.
 *
 * Subjects are qualified strings of the form `type:id` (see `CredentialRecordInterface::getSubject()`).
 * Domain events produced by an implementation are internal to the implementing package and are not
 * returned through this contract.
 */
interface SessionManagerInterface
{
    /**
     * Creates a session for the subject.
     *
     * @param string $subject qualified subject `type:id`
     * @param array<string, mixed> $claims session claims, e.g. `SessionClaim::TENANT`, `SessionClaim::ROLE`
     */
    public function create(string $subject, array $claims = []): SessionResultInterface;

    /**
     * Exchanges a refresh token for a new session (rotation).
     *
     * Failure cases (exceptions of jardissupport/auth, not declared in this package): `InvalidCredentialException`
     * for an unknown refresh token, `TokenExpiredException`, `TokenRevokedException`.
     */
    public function refresh(string $refreshToken): SessionResultInterface;

    /**
     * Revokes exactly the session behind a session reference, e.g. `getSessionReference()` of the
     * authentication result. Other sessions of the same subject stay valid.
     */
    public function invalidateByReference(string $sessionReference): void;

    /**
     * Revokes every session of the subject.
     *
     * @param string $subject qualified subject `type:id`
     */
    public function invalidateAll(string $subject): void;
}
