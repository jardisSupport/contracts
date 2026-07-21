<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Auth;

/**
 * Represents authentication credentials.
 */
interface CredentialInterface
{
    public function getType(): CredentialType;

    public function getValue(): string;

    public function getIdentifier(): string;
}
