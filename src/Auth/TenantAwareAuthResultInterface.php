<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Auth;

/**
 * An authentication result that additionally carries the tenant of the
 * authenticated subject.
 *
 * Additive extension of AuthResultInterface: existing implementers stay valid,
 * consumers that need the tenant check for this interface.
 */
interface TenantAwareAuthResultInterface extends AuthResultInterface
{
    /**
     * Tenant of the authenticated subject, null if the result is not
     * successful or the session carries no tenant.
     */
    public function getTenant(): ?string;
}
