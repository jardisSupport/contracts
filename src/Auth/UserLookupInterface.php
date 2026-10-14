<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Auth;

/**
 * Port for resolving a login identifier (e.g. an email address) to credential data.
 *
 * Implemented by the account store (e.g. jardissupport/identity), consumed by
 * the password authenticator.
 */
interface UserLookupInterface
{
    /**
     * @param string $identifier Login identifier as supplied by the user
     * @return CredentialRecordInterface|null Null if no account matches the identifier
     */
    public function lookup(string $identifier): ?CredentialRecordInterface;
}
