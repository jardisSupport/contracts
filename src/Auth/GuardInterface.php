<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Auth;

/**
 * Checks and enforces permissions for authenticated sessions.
 */
interface GuardInterface
{
    public function check(SessionInterface $session, string $permission): bool;

    public function authorize(SessionInterface $session, string $permission): void;
}
