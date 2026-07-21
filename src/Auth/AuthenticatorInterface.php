<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Auth;

/**
 * Authenticates credentials and returns an authentication result.
 */
interface AuthenticatorInterface
{
    public function authenticate(CredentialInterface $credential): AuthResultInterface;
}
