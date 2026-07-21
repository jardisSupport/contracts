<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Auth;

enum TokenType: string
{
    case Access = 'access';
    case Refresh = 'refresh';
    case ApiKey = 'api_key';
    case Verification = 'verification';
    case PasswordReset = 'password_reset';
}
