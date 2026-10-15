<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Auth;

/**
 * Result of creating or refreshing a session: the session and its two token strings.
 *
 * Domain events of the session lifecycle stay internal to the implementing package and are
 * deliberately not part of this contract.
 */
interface SessionResultInterface
{
    /** Signed access token (hand it out to the client). */
    public function getAccessToken(): string;

    /** Opaque refresh token (hand it out to the client once). */
    public function getRefreshToken(): string;

    public function getSession(): SessionInterface;
}
