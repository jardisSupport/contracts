<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Auth;

/**
 * An authentication result that additionally carries the role of the authenticated subject.
 *
 * Additive extension of AuthResultInterface: existing implementers stay valid, consumers that need
 * the role check for this interface.
 */
interface RoleAwareAuthResultInterface extends AuthResultInterface
{
    /**
     * Role from the session claim `SessionClaim::ROLE`, null if the result is not successful or the
     * session carries no role.
     */
    public function getRole(): ?string;
}
