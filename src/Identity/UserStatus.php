<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Identity;

/**
 * Lifecycle status of a user account.
 *
 * @deprecated 2.11.0 Identity ist eine Generic Subdomain; ersetzt durch die Builder-Blaupause „Access“
 */
enum UserStatus: string
{
    case Active = 'active';
    case Disabled = 'disabled';
}
