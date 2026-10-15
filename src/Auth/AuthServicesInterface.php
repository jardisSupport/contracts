<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Auth;

/**
 * The auth services a generated domain reaches through `DomainKernelInterface::auth()`, without
 * knowing a concrete class.
 */
interface AuthServicesInterface
{
    public function sessions(): SessionManagerInterface;

    public function tokenIssuer(): TokenIssuerInterface;

    public function tokenRedeemer(): TokenRedeemerInterface;

    public function hasher(): PasswordHasherInterface;
}
