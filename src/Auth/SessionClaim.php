<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Auth;

/**
 * Names of the claims stored in a session token.
 *
 * Producer: the login lookup (identity) writes these claims into the
 * CredentialRecord. Consumers: `TokenAuthenticator` reads TENANT,
 * `Guard` (permission check) reads ROLE.
 *
 * The names are defined exactly once here, so producers and consumers never
 * invent their own. Pattern: `RequestAttribute`.
 */
final class SessionClaim
{
    /** Claim holding the tenant of the session (string). */
    public const TENANT = 'tenant';

    /** Claim holding the role of the subject within the tenant (string). */
    public const ROLE = 'role';

    /**
     * Claim holding the rights of the subject's role within the tenant of the session (list<string>).
     * Each element is `resource:action`, `resource:*` or `*`. An empty list and a missing claim mean the
     * same: no rights. Pattern: `ROLE`.
     */
    public const RIGHTS = 'rights';

    private function __construct()
    {
    }
}
