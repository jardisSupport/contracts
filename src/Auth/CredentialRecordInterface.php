<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Auth;

/**
 * Credential data of one account, as returned by a login lookup.
 *
 * Carries the stored password hash (never the plain password), the qualified
 * subject and the claims that are copied into the session at login.
 */
interface CredentialRecordInterface
{
    /**
     * @return string Stored password hash to verify the supplied password against
     */
    public function getPasswordHash(): string;

    /**
     * @return string Qualified subject in the format `type:id` (e.g. `user:42`)
     */
    public function getSubject(): string;

    /**
     * Claims copied into the session at login (e.g. SessionClaim::TENANT, SessionClaim::ROLE).
     *
     * @return array<string, mixed>
     */
    public function getClaims(): array;
}
