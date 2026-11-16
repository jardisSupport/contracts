<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Auth;

/**
 * Checks and enforces permissions for authenticated sessions.
 *
 * @deprecated 2.20.0 Role-based `Guard`/`Policy` was removed from `jardissupport/auth`; no implementation remains.
 *             Rights check: `HasRight` (`jardissupport/auth`); right per route: `RequireRight` in
 *             `jardiscore/app` 3.0.0; removal in contracts 3
 */
interface GuardInterface
{
    public function check(SessionInterface $session, string $permission): bool;

    public function authorize(SessionInterface $session, string $permission): void;
}
