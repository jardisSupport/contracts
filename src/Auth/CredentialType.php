<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Auth;

enum CredentialType: string
{
    case Password = 'password';
    case ApiKey = 'api_key';
    case Token = 'token';
}
