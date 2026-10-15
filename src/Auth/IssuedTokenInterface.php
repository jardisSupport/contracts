<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Auth;

/**
 * Result of issuing a one-time token: the plain value (hand it out once) and its hashed form as stored.
 */
interface IssuedTokenInterface
{
    /** The plain token value. Only available at issue time; the store keeps just its hash. */
    public function getPlain(): string;

    public function getHashed(): HashedTokenInterface;
}
