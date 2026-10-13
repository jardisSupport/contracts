<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Auth;

/**
 * Names of the request attributes under which a transport layer stores the
 * authenticated subject and tenant after resolving an access token.
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

    private function __construct()
    {
    }
}
