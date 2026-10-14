<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Auth;

/**
 * An authentication result of a token session that additionally carries the bare
 * subject identifier and a reference to the session it was resolved from.
 *
 * Additive extension of TenantAwareAuthResultInterface: existing implementers stay valid,
 * consumers that need the identifier or want to revoke exactly this session check for this interface.
 */
interface SessionAwareAuthResultInterface extends TenantAwareAuthResultInterface
{
    /**
     * Bare identifier of the authenticated subject, i.e. the part of the qualified
     * subject (`type:id`) after the type prefix. Null if the result is not successful.
     */
    public function getSubjectId(): ?string;

    /**
     * Opaque reference to the session (hash of the presented access token) that a
     * session manager accepts to revoke exactly this session. Null if the result is not successful.
     */
    public function getSessionReference(): ?string;
}
