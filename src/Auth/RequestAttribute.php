<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Auth;

/**
 * Names of the request attributes under which a transport layer stores the
 * authenticated subject, subject id, tenant and session reference after resolving an access token.
 *
 * The names are defined exactly once here, so producers (authenticating
 * middleware) and consumers (request handlers, the domain bridge) never
 * invent their own. Pattern: the `jardisVersion` attribute of the HTTP layer.
 */
final class RequestAttribute
{
    /** Attribute holding the authenticated subject identifier (string). */
    public const SUBJECT = 'jardisSubject';

    /** Attribute holding the tenant of the authenticated session (string|null). */
    public const TENANT = 'jardisTenant';

    /** Attribute holding the bare subject identifier, without type prefix (string). */
    public const SUBJECT_ID = 'jardisSubjectId';

    /** Attribute holding the session reference used to revoke exactly this session (string). */
    public const SESSION = 'jardisSession';

    /**
     * Attribute holding the role of the authenticated subject (string). Set from the session claim
     * `SessionClaim::ROLE`, and only when the access token carries that claim.
     */
    public const ROLE = 'jardisRole';

    private function __construct()
    {
    }
}
