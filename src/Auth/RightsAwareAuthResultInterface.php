<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Auth;

/**
 * An authentication result that additionally carries the rights of the authenticated subject.
 *
 * Additive extension of AuthResultInterface: existing implementers stay valid, consumers that need
 * the rights check for this interface.
 */
interface RightsAwareAuthResultInterface extends AuthResultInterface
{
    /**
     * Rights from the session claim `SessionClaim::RIGHTS`; each element is `resource:action`,
     * `resource:*` or `*`. Empty if the result is not successful or the session carries no rights.
     *
     * @return list<string>
     */
    public function getRights(): array;
}
