<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Identity;

/**
 * Lifecycle status of a user account.
 */
enum UserStatus: string
{
    case Active = 'active';
    case Disabled = 'disabled';
}
