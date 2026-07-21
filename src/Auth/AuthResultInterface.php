<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Auth;

/**
 * Represents the result of an authentication attempt.
 */
interface AuthResultInterface
{
    public function isSuccess(): bool;

    public function getSubject(): ?string;

    public function getReason(): ?string;
}
